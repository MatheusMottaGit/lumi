import { Scissors, Split } from "lucide-react";
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from "./ui/card";
import { Input } from "./ui/input";
import { Label } from "./ui/label";
import { Button } from "./ui/button";
import { useState } from "react";

const MIN_NUMBER_OF_PARTS = 2;
const MAX_NUMBER_OF_PARTS = 10;

interface SplitUploadStepProps {
  selectedFiles: File[];
}

export default function SplitUploadStep({ selectedFiles }: SplitUploadStepProps) {
  const [imagesParts, setImagesParts] = useState(MIN_NUMBER_OF_PARTS);

  async function handleFileSplitting() {
    
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
                min="2"
                max="10"
                className="text-gray-100 p-2 rounded-lg"
                placeholder="Max: 10"
                onChange={(e) => setImagesParts(parseInt(e.target.value))}
              />

              <Button type="button" onClick={handleFileSplitting} className="w-52" variant="secondary">
                Split <Scissors />
              </Button>
            </div>
          </div>

          <div className="flex flex-col gap-2">
            {selectedFiles && selectedFiles.map((file, index) => (
              <div className="bg-gray-900/40 p-3 rounded-xl border border-gray-800 shadow-md w-full h-full">
                <img key={index} src={URL.createObjectURL(file)} alt={file.name} className="w-full object-cover rounded-lg" />
              </div>
            ))}
          </div>
        </div>
      </CardContent>
    </Card>
  );
}