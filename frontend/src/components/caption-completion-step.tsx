import { AlignLeft } from "lucide-react";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Input } from "./ui/input";
import { Label } from "./ui/label";
import { Textarea } from "./ui/textarea";
import { http } from "@/lib/axios";
import { useState } from "react";
import { toast } from "sonner";

export default function CaptionCompletionStep() {
  const [caption, setCaption] = useState("");
  const [prompt, setPrompt] = useState("");

  async function generateCaption() {
    const response = await http.post("/caption/completion", {
      prompt,
    });

    if (!(response.data.success)) {
      toast.error(response.data.message);
      return;
    }

    setCaption(response.data.data);
    setPrompt("");

    toast.success(response.data.message);
  }

  return (
    <Card className="w-full bg-background">
      <CardHeader className="flex flex-row items-center gap-4 border-b">
        <AlignLeft className="h-8 w-8 text-gray-100" />
        <div>
          <CardTitle className="text-xl text-gray-100">Generate caption</CardTitle>
          <CardDescription className="text-md text-gray-500">
            Generate a caption for your carousel post.
          </CardDescription>
        </div>
      </CardHeader>
      <CardContent className="space-y-3">
        <div className="flex flex-col gap-3">
          <Label htmlFor="prompt" className="text-gray-100 pb-1">
            Prompt
          </Label>
          <Input
            id="prompt"
            type="text"
            className="text-gray-100 p-2 rounded-lg"
            placeholder="Write a prompt for the AI..."
          />
        </div>
        <div className="flex flex-col gap-3">
          <Label htmlFor="tags" className="text-gray-100 pb-1">
            Response
          </Label>
          <Textarea 
            id="response"
            className="text-gray-100 p-2 rounded-lg h-60 resize-none"
            placeholder="The AI-generated caption will appear here..."
            value={caption}
          />
        </div>
      </CardContent>
    </Card>
  );
}