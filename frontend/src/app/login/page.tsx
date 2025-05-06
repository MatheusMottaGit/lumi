"use client";
import { useAuth } from "@/contexts/auth-context";
import { Button } from "@/components/ui/button";
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Facebook, Fingerprint, Pointer, User2 } from "lucide-react";

export default function LoginPage() {
  const { facebookLinkedAccounts, handleSelectFacebookPage, loginSelectedAccount, selectedFacebookPageId } = useAuth();

  async function handleOAuthRedirect() {
    window.location.href = `${process.env.NEXT_PUBLIC_API_URL}/facebook/redirect`;
  }

  return (
    <div className="flex items-center justify-center min-h-screen">
      <div className="flex items-center flex-col gap-4 p-24">
        <Fingerprint size={48} />

        <h1 className="text-4xl font-semibold">Join Lumi!</h1>

        <p className="text-gray-400 text-lg mt-3.5">
          Use your <span className="text-white font-mono">Business Account</span> to enter.
        </p>

        {
          facebookLinkedAccounts && facebookLinkedAccounts.length > 0 ? (
            <>
              <Select
                onValueChange={handleSelectFacebookPage}
                value={selectedFacebookPageId ? selectedFacebookPageId : ""}
              >
                <SelectTrigger>
                  <div className="flex items-center gap-2">
                    <User2 />
                    <SelectValue placeholder="Select an account" />
                  </div>
                </SelectTrigger>
                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>Your linked accounts</SelectLabel>
                    {facebookLinkedAccounts.map((account) => (
                      <SelectItem key={account.id} value={account.id}>
                        <div className="flex items-center gap-2">
                          <span>{account.name}</span>
                        </div>
                      </SelectItem>
                    ))}
                  </SelectGroup>
                </SelectContent>
              </Select>
              
              {
                selectedFacebookPageId && (
                  <Button type="button" onClick={loginSelectedAccount}>
                    Enter with this account <Pointer />
                  </Button>
                )
              }
            </>
          ) : (
            <Button type="button" onClick={handleOAuthRedirect} className="w-80 text-base">
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
