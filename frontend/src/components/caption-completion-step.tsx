import { AlignLeft, WandSparkles, Loader } from "lucide-react";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Label } from "./ui/label";
import { Textarea } from "./ui/textarea";
import { Button } from "./ui/button";
import { useRequest } from "@/hooks/useRequest";
import { CaptionCompletionResponse } from "@/app/types/main";

interface CaptionCompletionStepProps {
  prompt: string;
  setPrompt: (prompt: string) => void;
  caption: string;
  setCaption: (prompt: string) => void;
}

export default function CaptionCompletionStep({ prompt, setPrompt, caption, setCaption }: CaptionCompletionStepProps) {
  const { loading, requestFn } = useRequest<CaptionCompletionResponse>("/caption/completion", { method: "POST" });

  async function generateCaption() {
    await requestFn({
      data: { 
        prompt
      }
    });
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

      <CardContent className="grid grid-cols-2 gap-3">
        <div className="flex flex-col gap-3">
          <Label htmlFor="prompt" className="text-gray-100">Prompt</Label>
          <Textarea
            id="prompt"
            value={prompt}
            onChange={(e) => setPrompt(e.target.value)}
            className="text-gray-100 p-2 rounded-lg h-80 resize-none bg-gray-800"
            placeholder="Type how you want your caption to be (tip: be specific)..."
          />
        </div>

        <div className="flex flex-col gap-3">
          <Label htmlFor="response" className="text-gray-100">Response</Label>
          <Textarea
            id="response"
            value={caption}
            readOnly
            onChange={(e) => setCaption(e.target.value)}
            className="text-gray-100 p-2 rounded-lg h-80 resize-none bg-gray-800"
            placeholder="The AI-generated caption will appear here..."
          />
        </div>

        <Button onClick={generateCaption} variant="secondary" disabled={loading} className="flex items-center gap-2 col-span-2">
          {
            loading ? <Loader className="animate-spin w-5 h-5" /> : <WandSparkles />
          }
          Generate
        </Button>
      </CardContent>
    </Card>
  );
}