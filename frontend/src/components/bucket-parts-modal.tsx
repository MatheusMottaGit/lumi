import { useState } from "react";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { Loader, Search, Send } from "lucide-react";
import { Button } from "./ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { http } from "@/lib/axios";
import { toast } from "sonner";

interface BucketPartsModalProps {
  getPartsFromBucket: () => Promise<void>;
  imagesUrl: string[];
}

export default function BucketPartsModal({ getPartsFromBucket, imagesUrl }: BucketPartsModalProps) {
  const [loading, setLoading] = useState(false);
  const [selectedImages, setSelectedImages] = useState<string[]>([]);

  async function handleOpen() {
    setLoading(true);
    await getPartsFromBucket();
    setLoading(false);
  }

  function toggleImageSelection(url: string) {
    setSelectedImages((prev) =>
      prev.includes(url) ? prev.filter((img) => img !== url) : [...prev, url]
    );
  }

  async function postToInstagram() {
    if (imagesUrl.length === 0) {
      toast.error("Please select images before posting.");
      return;
    }

    setLoading(true);
    try {
      const response = await http.post("/api/post/carousel", {
        imageOrder: imagesUrl,
      });

      toast.success(response.data.message);
    } catch (error) {
      toast.error("Failed to post on Instagram.");
    } finally {
      setLoading(false);
    }
  }

  return (
    <Dialog>
      <DialogTrigger asChild>
        <Button onClick={handleOpen} className="flex items-center gap-2">
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

        {loading ? (
          <div className="flex justify-center py-4">
            <Loader className="animate-spin h-6 w-6 text-gray-500" />
          </div>
        ) : imagesUrl.length > 0 ? (
          <div className="grid grid-cols-3 gap-3">
            {imagesUrl.map((url, index) => (
              <div
                key={index}
                className={`relative border-2 rounded-lg overflow-hidden cursor-pointer ${
                  selectedImages.includes(url) ? "border-blue-500" : ""
                }`}
                onClick={() => toggleImageSelection(url)}
              >
                <img
                  src={url}
                  className="w-full h-full object-cover"
                />
                <div className="absolute top-2 left-2 rounded">
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

        <Button disabled={loading} variant="secondary" onClick={postToInstagram} className="w-fit">
          {loading ? <Loader className="animate-spin w-5 h-5" /> : <Send className="w-5 h-5" />} Post to Instagram
        </Button>
      </DialogContent>
    </Dialog>
  );
}
