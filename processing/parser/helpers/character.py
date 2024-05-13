def is_parenthetical(text):
    return "(" in text[0] and ")" in text[-1]


def extract_character(current_content):
    text = current_content["text"]
    split = text.split()
    modifier = text[text.find(
        "(") + 1:text.find(")")] if text.find("(") != -1 else None
    character = text.replace(
        "(" + modifier + ")", "") if modifier is not None else text
    return {
        "character": character,
        "modifier": modifier,
    }


def is_character(current_content):
    text = current_content["text"]
    character_name_enum = ["(V.O)", "(O.S)", "CONT'D"]

    if is_parenthetical(text):
        return False

    for heading in character_name_enum:
        if heading in text:
            return True

    if not text[0].isalpha() and "\"" not in text[0]:
        return False

    character_name = (text[0: text.index("(")] if "(" in text else text)
    if character_name != character_name.upper():
        return False

    # check if header?
    if any(x in text for x in ["--", "!", "?", "@", "%", "...", "THE END"]):
        return False

    if any(x in text[-1] for x in ["-"]):
        return False

    if current_content["x"] < 150:
        return False

    if any(x in text for x in [":"]):
        return False

    return True
