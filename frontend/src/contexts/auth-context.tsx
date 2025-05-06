"use client";
import axios from "axios";
import { createContext, useContext, useState, useEffect } from "react";
import { toast } from "sonner";
import { getCookie, setCookie } from "cookies-next";
import { FacebookLinkedAccounts, InstagramAccount, CookiesUser, ApiResponse } from "@/app/types/main";
import { useRouter } from "next/navigation";

type AuthContextType = {
  loginSelectedAccount: () => Promise<void>;
  loggedUser: CookiesUser | null;
  facebookLinkedAccounts: FacebookLinkedAccounts[];
  accessToken: string | null;
  handleSelectFacebookPage(pageId: string): void;
  selectedFacebookPageId: string | null;
  setSelectedFacebookPageId: (id: string | null) => void;
};

const AuthContext = createContext({} as AuthContextType);

export const AuthProvider = ({ children }: { children: React.ReactNode }) => {
  const router = useRouter();

  const [sessionId, setSessionId] = useState<string | null>(null);
  const [accessToken, setAccessToken] = useState<string | null>(null);
  const [facebookLinkedAccounts, setFacebookLinkedAccounts] = useState<FacebookLinkedAccounts[]>([]);
  const [loggedUser, setLoggedUser] = useState<CookiesUser | null>(null);
  const [selectedFacebookPageId, setSelectedFacebookPageId] = useState<string | null>(null);

  useEffect(() => {
    if (typeof window !== "undefined" && window.location.search) {
      const params = new URLSearchParams(window.location.search);
      const id = params.get("session_id");
      if (id) setSessionId(id);
    }
  }, []);

  useEffect(() => {
    async function getSessionSavedAccounts() {
      const response = await axios.get<ApiResponse<FacebookLinkedAccounts[]>>(`${process.env.NEXT_PUBLIC_API_URL}/session/${sessionId}`);
      
      if (!response.data.success) {
        toast.error(response.data.message, {
          description: "Please try again later.",
        });
        return;
      }

      setFacebookLinkedAccounts(response.data.data);
    }
  
    if (sessionId) {
      getSessionSavedAccounts();
    }
  }, [sessionId]);

  useEffect(() => {
    getLoggedUser();
  }, []);

  function handleSelectFacebookPage(pageId: string) {
    const selectedFacebookPage = facebookLinkedAccounts.find((account) => account.id === pageId);
    // console.log(facebookLinkedAccounts);

    if (selectedFacebookPage) {
      setSelectedFacebookPageId(selectedFacebookPage.id);
      setAccessToken(selectedFacebookPage.access_token);
    }
  }

  async function loginSelectedAccount() {
    const linkedAccount = facebookLinkedAccounts.find((account) => account.id === selectedFacebookPageId);

    if (!linkedAccount) {
      toast.error("There is no business account linked to your Facebook page.", {
        description: "You need to select a Facebook page to log in.",
      });
      return;
    }

    const instagramAccountResponse = await axios.post<ApiResponse<InstagramAccount>>(`${process.env.NEXT_PUBLIC_API_URL}/instagram/${linkedAccount.instagram_business_account.id}`, 
    { 
      session_id: sessionId 
    },
    { 
      params: { 
        access_token: accessToken 
      } 
    });

    if (!instagramAccountResponse.data.success) {
      toast.error(instagramAccountResponse.data.message, {
        description: "Please try again later.",
      });
      return;
    }

    const { data } = instagramAccountResponse.data;

    const userData = {
      id: data.id,
      name: data.name,
      profile_picture: data.profile_picture_url,
      sessionId,
      accessToken
    };

    setCookie(`${process.env.COOKIE_ID}`, JSON.stringify(userData), { maxAge: 60 * 60 });

    router.push(`${process.env.NEXT_PUBLIC_MAIN_URL}/${data.id}`);
  }

  async function getLoggedUser() {
    const cookie = getCookie(`${process.env.COOKIE_ID}`);
    
    if (cookie) {
      const user: CookiesUser = JSON.parse(cookie as string);
      setLoggedUser(user);
    }
  }

  return (
    <AuthContext.Provider value={{ loginSelectedAccount, loggedUser, facebookLinkedAccounts, accessToken, handleSelectFacebookPage, selectedFacebookPageId, setSelectedFacebookPageId }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  return useContext(AuthContext);
};
