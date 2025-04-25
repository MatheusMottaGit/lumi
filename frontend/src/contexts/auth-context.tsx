"use client";
import axios from "axios";
import { createContext, useContext, useState, useEffect } from "react";
import { toast } from "sonner";

type InstagramAccount = {
  id: string;
  name: string;
  access_token: string;
  instagram_business_account?: {
    id: string;
  };
};

type AuthContextType = {
  accounts: InstagramAccount[];
  selectedAccount: InstagramAccount | null;
  setSelectedAccount: (account: InstagramAccount) => void;
  accessToken: string | null;
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
      try {
        const response = await axios.get<InstagramAccount[]>(`${process.env.NEXT_PUBLIC_API_URL}/session/${sessionId}`);
      
        if (response.data && Array.isArray(response.data)) {
          setAccounts(response.data);
      
          toast.success("Accounts fetched successfully", {
            description: "You can now select an account to continue.",
          });
        } else {
          throw new Error("Invalid data");
        }
      } catch (err) {
        console.error("Fetch accounts error:", err);
        toast.error("Failed to fetch your accounts", {
          description: "Please try again.",
        });
      }      
    }

    if (sessionId) {
      fetchSessionAccounts();
    }
  }, [sessionId]);

  const setSelectedAccount = (account: InstagramAccount) => {
    localStorage.setItem("selectedAccount", JSON.stringify(account));
    setSelectedAccountState(account);
  };

  return (
    <AuthContext.Provider value={{ accounts, selectedAccount, setSelectedAccount, accessToken }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  return context;
};