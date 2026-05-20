import re
import sys

from g2p_en import G2p

if hasattr(sys.stdout, "reconfigure"):
    sys.stdout.reconfigure(encoding="utf-8")


PHONEMES = {
    "AA": "ɑ",
    "AE": "æ",
    "AH": "ʌ",
    "AO": "ɔː",
    "AW": "aʊ",
    "AY": "aɪ",
    "EH": "e",
    "ER": "ɜːr",
    "EY": "eɪ",
    "IH": "ɪ",
    "IY": "iː",
    "OW": "oʊ",
    "OY": "ɔɪ",
    "UH": "ʊ",
    "UW": "uː",
    "B": "b",
    "CH": "tʃ",
    "D": "d",
    "DH": "ð",
    "F": "f",
    "G": "ɡ",
    "HH": "h",
    "JH": "dʒ",
    "K": "k",
    "L": "l",
    "M": "m",
    "N": "n",
    "NG": "ŋ",
    "P": "p",
    "R": "r",
    "S": "s",
    "SH": "ʃ",
    "T": "t",
    "TH": "θ",
    "V": "v",
    "W": "w",
    "Y": "j",
    "Z": "z",
    "ZH": "ʒ",
}

VOWELS = {"AA", "AE", "AH", "AO", "AW", "AY", "EH", "ER", "EY", "IH", "IY", "OW", "OY", "UH", "UW"}


def arpabet_to_ipa(tokens, keep_single_syllable_stress=False):
    result = []
    use_stress = sum(
        1
        for token in tokens
        if (match := re.fullmatch(r"([A-Z]+)([012])?", token)) and match.group(1) in VOWELS
    ) > 1 or keep_single_syllable_stress

    for token in tokens:
        if token.isspace():
            if result and result[-1] != " ":
                result.append(" ")
            continue

        match = re.fullmatch(r"([A-Z]+)([012])?", token)
        if not match:
            continue

        phoneme, stress = match.groups()
        ipa = PHONEMES.get(phoneme)
        if not ipa:
            continue

        if phoneme == "AH" and stress == "0":
            ipa = "ə"
        elif phoneme == "ER" and stress == "0":
            ipa = "ər"

        if use_stress and phoneme in VOWELS and stress in {"1", "2"}:
            cluster = []

            while result and result[-1] != " " and result[-1][0] not in "ɑæʌɔaeɜɪioʊuə":
                cluster.insert(0, result.pop())

            result.append("ˈ" if stress == "1" else "ˌ")
            result.extend(cluster)

        result.append(ipa)

    text = "".join(result)
    text = re.sub(r"\s+", " ", text).strip()

    return f"[{text}]" if text else ""


def main():
    keep_single_syllable_stress = "--keep-single-syllable-stress" in sys.argv
    args = [arg for arg in sys.argv[1:] if arg != "--keep-single-syllable-stress"]
    word = " ".join(args).strip()
    if not word:
        return 1

    transcription = arpabet_to_ipa(G2p()(word), keep_single_syllable_stress)
    if not transcription:
        return 1

    print(transcription, end="")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
