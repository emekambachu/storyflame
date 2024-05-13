import re

from processing.parser.helpers.character import is_character, extract_character
from processing.parser.helpers.headings import is_heading, extract_heading

LAST_SCENE = -2


def group_sections(top_trends, script, page_start, include_scene_number):
    """group types into the same sections"""
    new_script = categorize_sections(top_trends, script, page_start, include_scene_number)
    new_script = combine_categories(new_script, page_start)
    new_script = divide_parentheticals(new_script)
    return new_script


def divide_parentheticals(new_script):
    """separates parentheticals from dialogues"""

    for page in new_script:
        for i, section in enumerate(page["content"]):
            for j, scene in enumerate(section["scene"]):
                if scene["type"] == "CHARACTER":
                    scene["content"]["dialogue"] = get_parenthetical(
                        scene["content"]["dialogue"])
                elif scene["type"] == "DUAL_DIALOGUE":
                    scene["content"]["character1"]["dialogue"] = get_parenthetical(
                        scene["content"]["character1"]["dialogue"])
                    scene["content"]["character2"]["dialogue"] = get_parenthetical(
                        scene["content"]["character2"]["dialogue"])
    return new_script


def get_parenthetical(text):
    """splits dialogue string into list, separating any containing parenthetical(s)"""

    return list(
        filter(lambda x: len(x.strip()) > 0, re.split(r'(\([^)]+\))', text))
    )


def combine_categories(new_script, page_start):
    """combines consecutive sections with the same type"""

    final_sections = []
    for page in new_script:
        if page["page"] < page_start:
            continue
        final_sections.append({"page": page["page"], "content": []})

        for i, content in enumerate(page["content"]):
            final_sections[-1]["content"].append({
                # "scene_number": content["scene_number"],
                "scene_info": content["scene_info"],
                "scene": []
            })
            j = 0
            while j < len(content["scene"]):
                scene = content["scene"][j]

                section_same_type_as_previous = j > 0 and scene["type"] == content["scene"][j - 1]["type"]
                if scene["type"] == "CHARACTER":
                    final_sections[-1]["content"][-1]["scene"].append({
                        "type": "CHARACTER",
                        "content": {
                            "character": scene["text"]["character"],
                            "modifier": scene["text"]["modifier"],
                            "dialogue": ""
                        }
                    })
                elif scene["type"] == "DUAL_DIALOGUE":
                    section_is_character = lambda curr_scene, character: len(
                        curr_scene["content"][character]) == 1 and is_character(curr_scene["content"][character][0])
                    if section_is_character(scene, "character1") and section_is_character(scene, "character2"):
                        final_sections[-1]["content"][-1]["scene"].append({
                            "type": "DUAL_DIALOGUE",
                            "content": {
                                "character1": extract_character(scene["content"]["character1"][0]),
                                "character2": extract_character(scene["content"]["character2"][0]),
                            }
                        })
                    else:
                        final_sections[-1]["content"][-1]["scene"][-1]["content"]["character1"][
                            "dialogue"] = get_joined_text(
                            scene["content"]["character1"])
                        final_sections[-1]["content"][-1]["scene"][-1]["content"]["character2"][
                            "dialogue"] = get_joined_text(
                            scene["content"]["character2"])

                elif scene["type"] == "DIALOGUE" and content["scene"][j - 1]["type"] == "CHARACTER":
                    final_sections[-1]["content"][-1]["scene"][-1]["content"]["dialogue"] += scene["text"].strip()
                elif section_same_type_as_previous and scene["type"] == "DIALOGUE":
                    final_sections[-1]["content"][-1]["scene"][-1]["content"]["dialogue"] += " " + scene["text"].strip()
                elif section_same_type_as_previous and scene["type"] == "ACTION":
                    # if part of same paragraph, concat text
                    if (scene["content"][0]["y"] - final_sections[-1]["content"][-1]["scene"][-1]["content"][-1][
                        "y"] <= 16):
                        final_sections[-1]["content"][-1]["scene"][-1]["content"][-1]["text"] += " " + \
                                                                                                 scene["content"][0][
                                                                                                     "text"]
                        final_sections[-1]["content"][-1]["scene"][-1]["content"][-1]["y"] = scene["content"][0]["y"]
                    # else, just append entire text
                    else:
                        final_sections[-1]["content"][-1]["scene"][-1]["content"].append(
                            scene["content"][0])
                else:
                    final_sections[-1]["content"][-1]["scene"].append(scene)

                j += 1
    return final_sections


def get_joined_text(text_arr):
    return " ".join([arr["text"]
                     for arr in text_arr])


def categorize_sections(top_trends, script, page_start, include_scene_number):
    """categorize lines into types"""

    final_sections = []
    scene_number = 0
    for page in script:
        if page["page"] < page_start:
            continue
        final_sections.append({"page": page["page"], "content": []})

        final_sections[-1]["content"].append({
            # "scene_number": scene_number,
            "scene_info": final_sections[LAST_SCENE]["content"][-1]["scene_info"] if len(final_sections) >= 2 else None,
            "scene": []
        })

        character_occurred = False
        for i, content in enumerate(page["content"]):
            if "character2" in content:
                final_sections[-1]["content"][-1]["scene"].append({
                    "type": "DUAL_DIALOGUE",
                    "content": {
                        "character1": content["segment"],
                        "character2": content["character2"],
                    }
                })
                character_occurred = False
                continue

            previous_y = page["content"][i - 1]["segment"][-1]["y"] if i > 0 else 0
            x = content["segment"][0]["x"]
            y = content["segment"][0]["y"]
            text = content["segment"][0]["text"]

            # booleans
            is_action = abs(x - top_trends[0][0]) <= 15
            is_transition = content["segment"][0]["x"] >= 420 or "FADE" in text or (
                "CUT" in text and not is_action) or "TO:" in text

            if is_heading(content["segment"][0]):
                scene_number += 1
                if len(final_sections[-1]["content"][-1]["scene"]) == 0:
                    final_sections[-1]["content"][-1] = {
                        # "scene_number": scene_number,
                        "scene_info": extract_heading(content["segment"][0]["text"]),
                        "scene": []
                    }
                else:
                    final_sections[-1]["content"].append({
                        # "scene_number": scene_number,
                        "scene_info": extract_heading(content["segment"][0]["text"]),
                        "scene": []
                    })
                character_occurred = False
            elif is_transition:
                final_sections[-1]["content"][-1]["scene"].append({
                    "type": "TRANSITION",
                    "content": {
                        "text": text,
                        "metadata": {
                            "x": x,
                            "y": y
                        }
                    }
                })
                character_occurred = False
            elif is_action:
                # if Heading is multi-line
                if i > 0 and len(final_sections[-1]["content"][-1]["scene"]) == 0 and y - \
                    page["content"][i - 1]["segment"][-1]["y"] < 24:
                    final_sections[-1]["content"][-1]["scene_info"]["location"] += " " + text
                else:
                    final_sections[-1]["content"][-1]["scene"].append({
                        "type": "ACTION",
                        "content": [{"text": text, "x": x, "y": y}]
                    })
                    character_occurred = False

            elif is_character(content["segment"][0]):
                final_sections[-1]["content"][-1]["scene"].append({
                    "type": "CHARACTER",
                    "text": extract_character(content["segment"][0]),
                    "metadata": {
                        "x": x,
                        "y": y
                    }
                })
                character_occurred = True
            else:
                current_scene = final_sections[-1]["content"][-1]["scene"]

                # first line of page is never a dialogue
                if len(current_scene) == 0 or not character_occurred:
                    final_sections[-1]["content"][-1]["scene"].append({
                        "type": "ACTION",
                        "content": [{"text": text, "x": x, "y": y}]
                    })

                else:
                    final_sections[-1]["content"][-1]["scene"].append({
                        "type": "DIALOGUE",
                        "text": text,
                        "metadata": {
                            "x": x,
                            "y": y
                        }
                    })

    return final_sections
