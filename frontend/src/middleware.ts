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
    const { id } = user;

    if(pathname === `/${id}`) {
      return NextResponse.next();
    }
  
    if (pathname.startsWith(`/${id}`)) {
      return NextResponse.redirect(new URL(`/${id}`, request.url));
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
