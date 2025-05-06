import { NextResponse } from "next/server";
import type { NextRequest } from "next/server";

export function middleware(request: NextRequest) {
  const pathname = request.nextUrl.pathname;
  const cookie = request.cookies.get("lumi:user");

  try {
    if(pathname.startsWith("/login")) {
      return NextResponse.next();
    }
  
    if (!cookie) {
      return NextResponse.redirect(new URL("/login", request.url));
    }
  
    const user = JSON.parse(cookie.value);
    const { sessionId, id } = user;
  
    const currentSearchParams = request.nextUrl.searchParams;
    const currentSessionId = currentSearchParams.get("session_id");
  
    if (pathname.startsWith(`/${id}`) || currentSessionId !== sessionId) {
      return NextResponse.redirect(new URL(`/${id}?session_id=${sessionId}`, request.url));
    }
  
    return NextResponse.next();
  } catch {
    return NextResponse.redirect(new URL("/login", request.url));
  }
}

export const config = {
  matcher: [
    "/((?!api|_next/static|_next/image|favicon.ico).*)",
    "/login",
  ],
};
