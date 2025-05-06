export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data: T;
  errors?: any;
}

// Auth Context
export type FacebookLinkedAccounts = {
  access_token: string;
  name: string;
  id: string;
  instagram_business_account: {
    id: string;
  };
};

export type InstagramAccount = {
  id: string;
  name: string;
  profile_picture_url: string;
};

export type CookiesUser = InstagramAccount & {
  sessionId: string;
  accessToken: string;
}

// Form Responses
export type CaptionCompletionResponse = ApiResponse<string>;
export type BucketPartsResponse = ApiResponse<string[]>;
export type InstagramPostResponse = ApiResponse<string>;
export type SplitUploadResponse = ApiResponse<string[]>;

