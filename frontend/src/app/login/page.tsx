"use client";
import { Button } from "@/components/ui/button";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Facebook, Fingerprint, Instagram, User2 } from "lucide-react";
import { useEffect, useState } from "react";

export default function LoginPage() {
  const [accounts, setAccounts] = useState([]);

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const encoded = params.get("accounts");

    if (encoded) {
      const decoded = JSON.parse(decodeURIComponent(encoded));
      setAccounts(decoded);
    }
  }, []);

  async function handleLogin() {
    window.location.href = `${process.env.NEXT_PUBLIC_API_URL}/facebook/redirect`;
  }

  return (
    <div className="flex items-center justify-center min-h-screen">
      <div className="flex items-center flex-col gap-4 bg-gray-900/10 border border-dashed p-24 rounded-lg shadow-lg">
        <Fingerprint size={48} />

        <h1 className="text-4xl font-semibold">Join Lumi!</h1>

        <p className="text-gray-400 text-lg mt-3.5">
          Use your <span className="text-white font-mono">Business Account</span> to enter.
        </p>

        {
          accounts.length > 0 ? (
            <Select>
              <SelectTrigger>
                <div className="flex items-center gap-2">
                  <User2 />
                  <SelectValue placeholder="Select an account" />
                </div>
              </SelectTrigger>
              <SelectContent>
                {
                  accounts.map((account: any) => (
                    <SelectItem key={account.id} value={account.id}>
                      <Instagram /> {account.name}
                    </SelectItem>
                  ))
                }
              </SelectContent>
            </Select>
          ) : (
            <Button onClick={handleLogin} className="w-80 text-base">
              <Facebook className="mr-2" /> Sign in with Facebook
            </Button>
          )
        }

        <p className="text-center text-lg text-gray-500">
          By clicking continue, you agree to our <br /> <a href="#" className="underline">Terms of Service</a> and <a href="#" className="underline">Privacy Policy</a>.
        </p>
      </div>
    </div>
  );
}