"use client";
import { getCookie } from "cookies-next";
import { useEffect } from "react";
import { usePathname, useRouter } from "next/navigation";
import { CookiesInstagramAccount } from "../types/main";
import { useAuth } from "@/contexts/auth-context";

export default function ProtectedProvider({ children }: { children: React.ReactNode }) {
  const router = useRouter();
  const pathname = usePathname();

  useEffect(() => {
    const user = getCookie("lumi:user");

    if (user) {
      try {
        const parsedUser: CookiesInstagramAccount = JSON.parse(user as string);
        const { sessionId, id } = parsedUser;

        if (!sessionId) {
          if (pathname !== "/login") router.replace("/login");
        } else {
          const expectedPath = `/${id}`;
          const expectedUrl = `${expectedPath}?session_id=${sessionId}`;

          if (!pathname.startsWith(expectedPath)) {
            router.replace(`${process.env.NEXT_PUBLIC_MAIN_URL}${expectedUrl}`);
          }
        }
      } catch (err) {
        router.replace("/login");
      }
    } else {
      if (pathname !== "/login") router.replace("/login");
    }
  }, [pathname, router]);

  return <>{children}</>;
}
