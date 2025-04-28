"use client";
import axios from "axios";
import { createContext, useContext, useState, useEffect } from "react";
import { toast } from "sonner";
import { setCookie } from "cookies-next";

type InstagramAccount = {
  id: string;
  name: string;
  access_token: string;
  instagram_business_account: {
    id: string;
  };
};

type AuthContextType = {
  accounts: InstagramAccount[];
  selectedAccount: InstagramAccount | null;
  setSelectedAccount: (account: InstagramAccount) => void;
  accessToken: string | null;
  loginSelectedAccount: () => void;
};

const AuthContext = createContext({} as AuthContextType);

export const AuthProvider = ({ children }: { children: React.ReactNode }) => {
  const [accounts, setAccounts] = useState<InstagramAccount[]>([]);
  const [selectedAccount, setSelectedAccountState] = useState<InstagramAccount | null>(null);
  const [accessToken, setAccessToken] = useState<string | null>(null);
  const [sessionId, setSessionId] = useState<string | null>(null);

  useEffect(() => {
    if (typeof window !== "undefined") {
      const params = new URLSearchParams(window.location.search);
      setSessionId(params.get("session_id"));
    }
  }, []);

  useEffect(() => {
    async function fetchSessionAccounts() {
      const response = await axios.get<InstagramAccount[]>(`${process.env.NEXT_PUBLIC_API_URL}/session/${sessionId}`);
      // console.log(response.data);
      if(!response.data) {
        toast.error("Failed to fetch your accounts", {
          description: "Please try again.",
        });
      }

      setAccounts(response.data);
    }

    if (sessionId) {
      fetchSessionAccounts();
    }
  }, [sessionId]);

  function setSelectedAccount(account: InstagramAccount) {
    setSelectedAccountState(account);
  };

  function loginSelectedAccount() {
    if (selectedAccount) {
      const userData = {
        id: selectedAccount.id,
        name: selectedAccount.name
      };

      setCookie("lumi:user", JSON.stringify(userData), {
        maxAge: 60 * 60 * 24 * 30,
      });

      setAccessToken(selectedAccount.access_token);

      window.location.href = `${process.env.NEXT_PUBLIC_MAIN_URL}/${selectedAccount.instagram_business_account.id}?sessionId=${sessionId}`;
    } else {
      toast.error("Please select an account to login.");
      return;
    }
  }

  return (
    <AuthContext.Provider value={{ accounts, selectedAccount, setSelectedAccount, accessToken, loginSelectedAccount }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  return context;
};