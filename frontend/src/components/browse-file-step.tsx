import { CloudUpload, FileCheck2, Paperclip } from "lucide-react";
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";

interface BrowseFileStepProps {
  selectedFiles: File[];
  setSelectedFiles: (files: File[]) => void;
}

export default function BrowseFileStep({ selectedFiles, setSelectedFiles }: BrowseFileStepProps) {
  function handleFileChange(event: React.ChangeEvent<HTMLInputElement>) {
    if (event.target.files) {
      setSelectedFiles(Array.from(event.target.files));
    }
  };

  return (
    <Card className="w-full bg-background">
      <CardHeader className="flex flex-row items-center gap-4 border-b">
        <Paperclip className="h-8 w-8 text-gray-100" />
        <div>
          <CardTitle className="text-xl text-gray-100">Browse file(s)</CardTitle>
          <CardDescription className="text-md text-gray-500">
            Select and upload the file(s) of your choice
          </CardDescription>
        </div>
      </CardHeader>

      <CardContent className="space-y-3">
        <Label
          htmlFor="canvaFile"
          className="flex h-72 cursor-pointer flex-col items-center justify-center rounded-lg border border-dashed p-6 text-center text-gray-500 hover:bg-gray-900/40"
        >
          <CloudUpload size={36} />
          <p className="mt-2 text-lg">Choose a file or drag & drop it here</p>
          <p className="text-lg text-gray-400">For now just accept PNG formats, up to 5MB.</p>
          <Input id="canvaFile" type="file" className="hidden" multiple onChange={handleFileChange} />
        </Label>
      </CardContent>

      <CardFooter>
        {selectedFiles.length > 0 ? (
          <div className="w-full flex flex-col gap-3">
            {selectedFiles.map((file, index) => (
              <div key={index} className="flex items-center gap-2 rounded-lg bg-gray-900/40 p-3">
                <div className="rounded-md bg-sky-700 px-2 py-1 text-gray-200">PNG</div>
                <span className="text-gray-500">{file.name}</span>
              </div>
            ))}
          </div>
        ) : (
          <div className="flex w-full items-center gap-2 rounded-lg bg-gray-900/40 p-4">
            <FileCheck2 className="text-gray-500" />
            <span className="text-gray-500 text-center">Your file(s) will appear right here.</span>
          </div>
        )}
      </CardFooter>
    </Card>
  );
}
