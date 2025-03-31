import { Instagram } from "lucide-react";
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from "./ui/card";
import { useState } from "react";
import BucketPartsModal from "./bucket-parts-modal";
import { useRequest } from "@/hooks/useRequest";
import { toast } from "sonner";

interface BucketResponse {
  imagesUrl: string[]
}

export default function PostInstagramStep() {
  const [imagesUrl, setImagesUrl] = useState<string[]>([]);
  const { error, requestFn, loading } = useRequest<BucketResponse>();
  
  async function getPartsFromBucket() {
    const response = await requestFn('/bucket/parts', {
      params: {
        dirName: 'random1'
      }
    });
  }

  return (
    <Card className="w-full bg-background">
      <CardHeader className="flex flex-row items-center gap-4 border-b">
        <Instagram className="h-8 w-8 text-gray-100" />
        <div>
          <CardTitle className="text-xl text-gray-100">Post to Instagram</CardTitle>
          <CardDescription className="text-md text-gray-500">
            Post the carousel to your Instagram account.
          </CardDescription>
        </div>
      </CardHeader>
      <CardContent className="space-y-3">
        <div className="flex items-center justify-center border border-gray-800 bg-gray-900/40 h-96 rounded-lg gap-3">
          <BucketPartsModal getPartsFromBucket={getPartsFromBucket} imagesUrl={imagesUrl} loading={loading} />
        </div>
      </CardContent>
    </Card>
  );
}