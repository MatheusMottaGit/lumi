"use client";
import BrowseFileStep from "@/components/browse-file-step";
import CaptionCompletionStep from "@/components/caption-completion-step";
import PostInstagramStep from "@/components/post-instagram-step";
import SplitUploadStep from "@/components/split-upload-step";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { useAuth } from "@/contexts/auth-context";
import { useState } from "react";

const TOTAL_STEPS = 4;

export default function Home() {
  const [step, setStep] = useState<number>(1);
  const [selectedFiles, setSelectedFiles] = useState<File[]>([]);
  const [prompt, setPrompt] = useState<string>("");
  const [dirName, setDirName] = useState<string>("");
  const [caption, setCaption] = useState<string>("");

  const { loggedUser } = useAuth();

  function nextStep(): void {
    if (step < 4) {
      setStep((prev) => prev + 1);
    }
  }

  function prevStep(): void {
    if (step > 0) {
      setStep((prev) => prev - 1);
    }
  }

  return (
    <div className="flex flex-col w-3/4 gap-10">
      <div className="flex items-center gap-2">
        <Avatar>
          <AvatarImage src={loggedUser?.profile_picture_url} />
        </Avatar>

        <span className="font-medium">{}</span>
      </div>

      <form className="flex flex-col gap-3 items-center justify-center">
        {step === 1 && <BrowseFileStep selectedFiles={selectedFiles} setSelectedFiles={setSelectedFiles} />}
        {step === 2 && <SplitUploadStep selectedFiles={selectedFiles} dirName={dirName} setDirName={setDirName} />}
        {step === 3 && <CaptionCompletionStep prompt={prompt} setPrompt={setPrompt} caption={caption} setCaption={setCaption} />}
        {step === 4 && <PostInstagramStep dirName={dirName} caption={caption} /> }

        <div className="flex items-center justify-between w-full">
          <p className="text-gray-500 font-medium">
            Step <span className="font-bold">{step}</span> of <span className="font-bold">{TOTAL_STEPS}</span>
          </p>

          <div className="flex items-center gap-3">
            <Button disabled={step === 1} type="button" onClick={prevStep} variant="outline">Go back</Button>

            <Button disabled={step === 4} type="button" onClick={nextStep}>Next</Button>
          </div>
        </div>
      </form>
    </div>
  );
}