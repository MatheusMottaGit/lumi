import { Button } from "@/components/ui/button";
import { Facebook, Fingerprint } from "lucide-react";

export default function LoginPage() {
  return (
    <body className="flex items-center justify-center min-h-screen">
      <div className="flex items-center flex-col gap-4 bg-gray-900/10 border border-dashed p-24 rounded-lg shadow-lg">
        <Fingerprint size={48} />

        <h1 className="text-4xl font-semibold">Join Lumi!</h1>

        <p className="text-gray-400 text-lg mt-3.5">
          Use your <span className="text-white font-mono">Business Account</span> to enter.
        </p>

        <Button className="w-80 text-base">
          <Facebook /> Sign in with Facebook
        </Button>

        <p className="text-center text-lg text-gray-500 mt-3.5">
          By clicking continue, you agree to our <br /> <a href="#" className="underline">Terms of Service</a> and <a href="#" className="underline">Privacy Policy</a>.
        </p>
      </div>
    </body>
  );
}