"use client";
import BrowseFileStep from "@/components/browse-file-step";
import CaptionCompletionStep from "@/components/caption-completion-step";
import PostInstagramStep from "@/components/post-instagram-step";
import SplitUploadStep from "@/components/split-upload-step";
import { Button } from "@/components/ui/button";
import { useState } from "react";

export default function Home() {
  const [step, setStep] = useState<number>(4);
  const [selectedFiles, setSelectedFiles] = useState<File[]>([]);

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
    <form className="flex flex-col gap-3 items-center justify-center w-3/4">
      {step === 1 && <BrowseFileStep selectedFiles={selectedFiles} setSelectedFiles={setSelectedFiles} />}
      {step === 2 && <SplitUploadStep selectedFiles={selectedFiles} />}
      {step === 3 && <CaptionCompletionStep />}
      {step === 4 && <PostInstagramStep />}

      <div className="flex items-center gap-3 self-end">
        <Button type="button" onClick={prevStep} variant="outline">Go back</Button>
        <Button type="button" onClick={nextStep}>Next</Button>
      </div>
    </form>
  );
}
