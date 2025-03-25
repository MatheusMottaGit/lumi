import { useState } from "react";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { Loader, Search } from "lucide-react";
import { Button } from "./ui/button";
import { Checkbox } from "@/components/ui/checkbox";

interface BucketPartsModalProps {
  getPartsFromBucket: () => Promise<void>;
  imagesUrl: string[];
}

export default function BucketPartsModal({
  getPartsFromBucket,
  imagesUrl,
}: BucketPartsModalProps) {
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
                  alt={`Part ${index + 1}`}
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
      </DialogContent>
    </Dialog>
  );
}
