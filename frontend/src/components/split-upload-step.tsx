import { Scissors, Split, Loader } from "lucide-react";
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from "./ui/card";
import { Input } from "./ui/input";
import { Label } from "./ui/label";
import { Button } from "./ui/button";
import { toast } from "sonner";
import { useState } from "react";
import { http } from "@/lib/axios";

const MIN_NUMBER_OF_PARTS = 2;
const MAX_NUMBER_OF_PARTS = 10;

interface SplitUploadStepProps {
  selectedFiles: File[];
}

export default function SplitUploadStep({ selectedFiles }: SplitUploadStepProps) {
  const [numberOfParts, setNumberOfParts] = useState(MIN_NUMBER_OF_PARTS);
  const [dirName, setDirName] = useState('');
  const [loading, setLoading] = useState(false);

  async function handleFileSplitting() {
    if (numberOfParts < MIN_NUMBER_OF_PARTS || numberOfParts > MAX_NUMBER_OF_PARTS) {
      toast.error(`Number of parts should be between ${MIN_NUMBER_OF_PARTS} and ${MAX_NUMBER_OF_PARTS}`);
      return;
    }
  
    setLoading(true);
  
    const formData = new FormData();
    formData.append('carouselFiles', selectedFiles[0]);
  
    try {
      const response = await http.post('/split_upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        params: { numberOfParts, dirName },
      });
  
      toast.success(response.data.message);
    } catch (error) {
      toast.error('Failed to split and upload image. Try again.');
    } finally {
      setLoading(false);
    }
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
            <div className="flex flex-col gap-2">
              <Label htmlFor="numberOfParts" className="text-gray-100 pb-1">
                Number of parts
              </Label>
              
              <Input
                id="numberOfParts"
                type="number"
                min={MIN_NUMBER_OF_PARTS}
                max={MAX_NUMBER_OF_PARTS}
                value={numberOfParts}
                onChange={(e) => setNumberOfParts(Number(e.target.value))}
                className="text-gray-100 p-2 rounded-lg"
                placeholder="Max: 10"
              />
            </div>

            <div className="flex flex-col gap-2 col-span-2">
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
              <Button 
                type="button" 
                onClick={handleFileSplitting} 
                variant="secondary"
                className="w-full"
              >
                {
                  loading ? (
                    <Loader className="animate-spin" />
                  ) : (
                    <>
                      Split <Scissors />
                    </>
                  )
                }
              </Button>
            </div>
          </div>

          <div className="flex flex-col gap-2">
            {selectedFiles && selectedFiles.map((file, i) => (
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