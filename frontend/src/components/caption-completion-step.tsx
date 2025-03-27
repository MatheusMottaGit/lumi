import { useState } from "react";
import { AlignLeft, WandSparkles, Loader } from "lucide-react";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Input } from "./ui/input";
import { Label } from "./ui/label";
import { Textarea } from "./ui/textarea";
import { http } from "@/lib/axios";
import { toast } from "sonner";
import { Button } from "./ui/button";

export default function CaptionCompletionStep() {
  const [caption, setCaption] = useState("");
  const [prompt, setPrompt] = useState("");
  const [loading, setLoading] = useState(false);

  async function generateCaption() {
    setLoading(true);

    try {
      const response = await http.post("/caption/completion", { prompt });

      if (response.status === 200 && response.data.caption) {
        setCaption(response.data.caption);
      } else {
        toast.error("Failed to generate caption. Please try again later.");
      }
    } catch (error) {
      toast.error("An error occurred while generating the caption.");
    } finally {
      setLoading(false);
    }
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

      <CardContent className="space-y-4">
        <div className="flex flex-col gap-3">
          <Label htmlFor="prompt" className="text-gray-100">Prompt</Label>
          <Input
            id="prompt"
            type="text"
            value={prompt}
            onChange={(e) => setPrompt(e.target.value)}
            className="text-gray-100 p-2 rounded-lg"
            placeholder="Type how you want your caption to be..."
          />
        </div>

        <div className="flex flex-col gap-3">
          <Label htmlFor="response" className="text-gray-100">Response</Label>
          <Textarea
            id="response"
            value={caption}
            readOnly
            className="text-gray-100 p-2 rounded-lg h-60 resize-none bg-gray-800"
            placeholder="The AI-generated caption will appear here..."
          />
        </div>

        <Button onClick={generateCaption} variant="secondary" disabled={loading} className="flex items-center gap-2">
          {loading ? <Loader className="animate-spin w-5 h-5" /> : <WandSparkles />}
          Generate
        </Button>
      </CardContent>
    </Card>
  );
}