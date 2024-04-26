import typer
from faster_whisper import WhisperModel


def transcribe(
    path: str
):
    # print("Transcribe")
    # print("This is the main function of the transcribe module")
    # print(f"Transcribing the audio file at: {path}")

    # model = whisper.load_model('tiny', 'cpu')

    # audio = whisper.load_audio(path)
    # audio = whisper.pad_or_trim(audio)

    # mel = whisper.log_mel_spectrogram(audio).to(model.device)
    # _, probs = model.detect_language(mel)

    # options = whisper.DecodingOptions()
    # result = whisper.decode(model, mel, options)

    model = WhisperModel("tiny", device="cpu", compute_type="float32")

    segments, info = model.transcribe(path, beam_size=5)

    # print("Detected language '%s' with probability %f" % (info.language, info.language_probability))
    
    return "\n".join([
        "[%.2fs -> %.2fs] %s" %
        (segment.start, segment.end, segment.text)
        for segment in segments
    ])
