import { Instagram, Loader, Search, Send } from "lucide-react";
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from "./ui/card";
import { useState } from "react";
import { useRequest } from "@/hooks/useRequest";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "./ui/dialog";
import { Button } from "./ui/button";
import { Checkbox } from "./ui/checkbox";
import { BucketPartsResponse, InstagramPostResponse } from "@/app/types/main";

interface PostInstagramStepProps {
  dirName: string
  caption: string
}

export default function PostInstagramStep({ dirName, caption }: PostInstagramStepProps) {
  const [selectedImages, setSelectedImages] = useState<string[]>([]);

  const { data: bucketPartsData, loading: loadingImages, requestFn: getPartsFromBucket } = useRequest<BucketPartsResponse>("/bucket/parts");
  const { loading: loadingPost, requestFn: postToInstagram } = useRequest<InstagramPostResponse>("/post/carousel");

  function toggleImageSelection(url: string) {
    setSelectedImages((prev) =>
      prev.includes(url) ? prev.filter((img) => img !== url) : [...prev, url]
    );
  }

  async function handleGetPartsFromBucket() {
    await getPartsFromBucket({
      params: {
        dirName,
      }
    });
  }

  async function handlePostToInstagram() {
    await postToInstagram({
      method: "POST",
      data: { 
        imageOrder: selectedImages,
        chatCompletion: caption
      },
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
          <Dialog>
            <DialogTrigger asChild>
              <Button onClick={handleGetPartsFromBucket} className="flex items-center gap-2">
                <Search className="w-5 h-5" /> See images
              </Button>
            </DialogTrigger>
            <DialogContent>
              <DialogHeader>
                <DialogTitle>Select the order for your carousel</DialogTitle>
                <DialogDescription>
                  Click on the images to select or deselect them.
                </DialogDescription>
              </DialogHeader>

              {loadingImages ? (
                <div className="flex justify-center py-4">
                  <Loader className="animate-spin h-6 w-6 text-gray-500" />
                </div>
              ) : bucketPartsData && bucketPartsData.data.length > 0 ? (
                <div className="grid grid-cols-4 gap-2">
                  {bucketPartsData.data.map((url, index) => (
                    <div
                      key={index}
                      className={`relative w-full h-40 border-2 rounded-lg overflow-hidden cursor-pointer ${
                        selectedImages.includes(url) ? "border-blue-500" : ""
                      }`}
                      onClick={() => toggleImageSelection(url)}
                    >
                      <img src={url} className="w-full h-full object-cover" />
                      <div className="absolute top-1 left-1">
                        <Checkbox
                          checked={selectedImages.includes(url)}
                          onCheckedChange={() => toggleImageSelection(url)}
                        />
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <p className="text-center text-gray-500">No images found.</p>
              )}

              <Button disabled={loadingPost} variant="secondary" onClick={handlePostToInstagram} className="w-fit">
                {
                  loadingPost ? <Loader className="animate-spin w-5 h-5" /> : <Send className="w-5 h-5" />
                } 
                Post to Instagram
              </Button>
            </DialogContent>
          </Dialog>
        </div>
      </CardContent>
    </Card>
  );
}