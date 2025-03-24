import { Scissors, Split } from "lucide-react";
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from "./ui/card";
import { Input } from "./ui/input";
import { Label } from "./ui/label";
import { Button } from "./ui/button";
import { toast } from "sonner";
import { useState } from "react";

const MIN_NUMBER_OF_PARTS = 2;
const MAX_NUMBER_OF_PARTS = 10;

interface SplitUploadStepProps {
  selectedFiles: File[];
}

export default function SplitUploadStep({ selectedFiles }: SplitUploadStepProps) {
  const [numberOfParts, setNumberOfParts] = useState(MIN_NUMBER_OF_PARTS);
  const [isLoading, setIsLoading] = useState(false);

  async function handleFileSplitting() {
    if (numberOfParts < MIN_NUMBER_OF_PARTS || numberOfParts > MAX_NUMBER_OF_PARTS) {
      toast.error('Invalid number of parts', {
        description: `Please enter a number between ${MIN_NUMBER_OF_PARTS} and ${MAX_NUMBER_OF_PARTS}.`
      });
      
      return;
    }
  
    try {
      const response = await fetch('http://localhost:8000/api/split_upload', {
        method: 'POST',
        body: JSON.stringify({
          carouselFiles: selectedFiles[0],
          numberOfParts: numberOfParts,
        }),
      });
  
      if (!response.ok) {
        throw new Error('Failed to split and upload file');
      }
  
      const result = await response.json();

      toast.success('File processed successfully!', {
        description: `${result.message['message']}`
      });
    } catch (error) {
      toast.error('Failed to process file', {
        description: 'There was an error while splitting and uploading your file. Please try again.'
      });

      throw error;
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
          <div className="flex flex-col gap-2">
            <Label htmlFor="parts" className="text-gray-100 pb-1">
              Number of parts
            </Label>
            
            <div className="flex items-center gap-3">
              <Input
                id="parts"
                type="number"
                min={MIN_NUMBER_OF_PARTS}
                max={MAX_NUMBER_OF_PARTS}
                value={numberOfParts}
                onChange={(e) => setNumberOfParts(Number(e.target.value))}
                className="text-gray-100 p-2 rounded-lg"
                placeholder="Max: 10"
              />

              <Button 
                type="button" 
                onClick={handleFileSplitting} 
                className="w-52" 
                variant="secondary"
                disabled={isLoading}
              >
                <Scissors className="ml-2" />
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