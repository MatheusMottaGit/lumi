"use client";
import BrowseFileStep from "@/components/browse-file-step";
import CaptionCompletionStep from "@/components/caption-completion-step";
import PostInstagramStep from "@/components/post-instagram-step";
import SplitUploadStep from "@/components/split-upload-step";
import { Button } from "@/components/ui/button";
import { useState } from "react";

const TOTAL_STEPS = 4;

export default function Home() {
  const [step, setStep] = useState<number>(1);
  const [selectedFiles, setSelectedFiles] = useState<File[]>([]);
  const [prompt, setPrompt] = useState<string>("");
  const [dirName, setDirName] = useState<string>("");
  const [caption, setCaption] = useState<string>("");

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
  );
}

// Vou fazer uma postagem carousel sobre BUSCA BINÁRIA e você deve fazer uma legenda para COMPLEMENTAR o post. Siga as seguintes instruções:

// - NÃO seja informativo sobre o tema, você deve apenas relacionar o tema ao post;
// - Seja levemente descontraído, porém em tom profissional;
// - NÃO precisa de muito texto, pode ser breve;
// - No final peça curtida e compartilhamento;
// - Use emojis pontuais (NÃO EXAGERE, use NO MÁXIMO 2);
// - Monte um pequeno título para o post (não indique que é o titulo)