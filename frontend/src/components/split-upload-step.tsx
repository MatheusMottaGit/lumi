import { Scissors, Split, Loader } from "lucide-react";
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from "./ui/card";
import { Input } from "./ui/input";
import { Label } from "./ui/label";
import { Button } from "./ui/button";
import { toast } from "sonner";
import { useEffect } from "react";
import { useRequest } from "@/hooks/useRequest";

interface SplitUploadStepProps {
  selectedFiles: File[];
  dirName: string;
  setDirName: (dirName: string) => void
}

interface SplitUploadResponse {
  message: string;
}

export default function SplitUploadStep({ selectedFiles, dirName, setDirName }: SplitUploadStepProps) {
  const { data, error, loading, requestFn } = useRequest<SplitUploadResponse>("/split_upload", { method: "POST" });

  async function handleFileSplitting(): Promise<void> {
    const formData: FormData = new FormData();
    selectedFiles.forEach((file) => formData.append("carouselFiles[]", file));

    await requestFn({
      data: formData,
      headers: {
        "Content-Type": "multipart/form-data",
      },
      params: { 
        dirName 
      }
    });
  }

  if (data) {
    toast.success(data.message, {
      description: "You'll be able to see the parts on the next steps!",
    });
  }

  return (
    <Card className="w-full bg-background">
      <CardHeader className="flex flex-row items-center gap-4 border-b">
        <Split className="h-8 w-8 text-gray-100" />
        <div>
          <CardTitle className="text-xl text-gray-100">Split and upload</CardTitle>
          <CardDescription className="text-md text-gray-500">
            Split the file into multiple parts and upload them
          </CardDescription>
        </div>
      </CardHeader>
      <CardContent className="space-y-3">
        <div className="flex flex-col gap-3">
          <div className="grid grid-cols-4 gap-3">
            <div className="flex flex-col gap-2 col-span-3">
              <Label htmlFor="folderName" className="text-gray-100 pb-1">
                Folder name
              </Label>
              <Input
                id="folderName"
                type="text"
                value={dirName}
                onChange={(e) => setDirName(e.target.value)}
                className="text-gray-100 p-2 rounded-lg w-full"
                placeholder="Type the folder these parts will be saved..."
              />
            </div>

            <div className="flex flex-col gap-2 justify-end">
              <Button disabled={loading} type="button" onClick={handleFileSplitting} variant="secondary" className="w-full">
                {loading ? <Loader className="animate-spin w-5 h-5" /> : <Scissors />} Split
              </Button>
            </div>
          </div>

          <div className="flex flex-col gap-2">
            {selectedFiles.map((file, i) => (
              <div key={i} className="bg-gray-900/40 p-3 rounded-xl border border-gray-800 shadow-md w-full h-full">
                <img src={URL.createObjectURL(file)} alt={file.name} className="w-full object-cover rounded-lg" />
              </div>
            ))}
          </div>
        </div>
      </CardContent>
    </Card>
  );
}