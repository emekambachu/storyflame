from copy import copy

from processing.parser.helpers.character import is_character

LATEST_PAGE = -1
EPSILON = 3


def group_dual_dialogues(script, page_start):
    """detects and groups dual dialogues"""

    new_script = []
    for page in script:
        if page["page"] < page_start:
            continue
        new_script.append({"page": page["page"], "content": []})

        i = 0
        is_dual_dialogue = 0

        while i < len(page["content"]):
            content = page["content"][i]
            current_y = round(content["y"])
            segment_to_add = [{
                "x": content["x"],
                "y": content["y"],
                "text": content["text"]
            }]

            next_content = page["content"][i +
                                           1] if i + 1 < len(page["content"]) else False

            prev_content = page["content"][i - 1] if i - 1 >= 0 else False

            previous_is_character = prev_content and is_character(prev_content)

            next_content_is_character = next_content and is_character(next_content)

            # if current line and next line is character, and previous line is not character (3 characters in a row
            # is impossible)
            if not previous_is_character and is_character(content) and next_content_is_character:
                is_dual_dialogue = 1

            # if next content is the same line, and is_dual_dialogue > 0, then it's a dual dialogue
            if next_content and current_y == next_content["y"] and is_dual_dialogue > 0:
                character2_to_add = {
                    "x": next_content["x"],
                    "y": next_content["y"],
                    "text": next_content["text"]
                }
                left = segment_to_add[0]
                right = character2_to_add
                if left["x"] > next_content["x"]:
                    right = left
                    left = copy(right)

                if is_dual_dialogue <= 2:
                    new_script[-1]["content"].append({
                        "segment": [left],
                        "character2": [right]
                    })
                else:
                    new_script[-1]["content"][-1]["segment"].append(left)
                    new_script[-1]["content"][-1]["character2"].append(right)
                i += 1
                is_dual_dialogue += 1

            # if content resides in a different y-axis, we know it's not part of a dual dialogue
            else:
                is_dual_dialogue = 0
                # add content's y-axis as key and the content array index position as value
                new_script[-1]["content"].append({
                    "segment": segment_to_add
                })
            i += 1

    new_script = stitch_last_dialogue(new_script, page_start)
    return new_script


def stitch_last_dialogue(script, page_start):
    """
    detect last line of a dual dialogue. This isn't detected by detectDualDialogue since
    a dialogue may be longer than the other, and therefore take up a different y value
    """
    curr_script = []
    for page in script:
        if page["page"] < page_start:
            continue
        curr_script.append({"page": page["page"], "content": []})
        margin = -1
        for i, content in enumerate(page["content"]):
            # if margin > 0, then content is potentially a dual dialogue
            if margin > 0:
                curr_script_len = len(curr_script[LATEST_PAGE]["content"]) - 1

                # content might be the last line of dual dialogue, or not
                if "character2" not in content and i > 0:
                    # last line of a dual dialogue
                    if abs(content["segment"][0]["y"] - page["content"][i - 1]["segment"][LATEST_PAGE][
                        "y"]) <= margin + EPSILON:
                        def get_diff(content_x, curr_x):
                            return abs(
                                content_x - curr_x)

                        diff_between_content_and_segment = get_diff(
                            content["segment"][0]["x"],
                            curr_script[LATEST_PAGE]["content"][curr_script_len]["segment"][0]["x"])
                        diff_between_content_and_character2 = get_diff(
                            content["segment"][0]["x"],
                            curr_script[LATEST_PAGE]["content"][curr_script_len]["character2"][0][
                                "x"]) if "character2" in \
                                         curr_script[
                                             LATEST_PAGE][
                                             "content"][
                                             curr_script_len] else -1

                        if diff_between_content_and_segment < diff_between_content_and_character2:
                            curr_script[LATEST_PAGE]["content"][curr_script_len]["segment"] += content["segment"]
                        else:
                            curr_script[LATEST_PAGE]["content"][curr_script_len]["character2"] += content["segment"]

                    # not a dual dialogue. fuk outta here!
                    else:
                        curr_script[LATEST_PAGE]['content'].append(content)
                        margin = 0

                # still a dual dialogue
                else:
                    curr_script[LATEST_PAGE]["content"].append(content)

            # if no dual
            else:
                if "character2" in content:
                    # margin between character head and FIRST line of dialogue
                    margin = abs(page["content"][i + 1]["segment"][0]["y"] -
                                 content["segment"][LATEST_PAGE]["y"])
                    curr_script[LATEST_PAGE]['content'].append(content)
                else:
                    curr_script[LATEST_PAGE]['content'].append(content)

    return curr_script


def stitch_separate_words_into_lines(script, page_start):
    dialogue_stitch = []

    def get_joined_text(text_arr):
        return " ".join([x["text"]
                         for x in text_arr])

    def segment_text_exists(x):
        return len(x) > 0 and len(
            x[-1]["text"]) > 0

    for page in script:
        if page["page"] < page_start:
            continue
        dialogue_stitch.append({"page": page["page"], "content": []})

        content_stitch = {
            "segment": []
        }

        for i, content in enumerate(page["content"]):
            if "character2" in content:
                if segment_text_exists(content_stitch["segment"]):
                    dialogue_stitch[-1]["content"].append(copy(content_stitch))
                content_stitch = {
                    "segment": []
                }
                dialogue_stitch[-1]["content"].append(content)
            elif i > 0 and content["segment"][0]["y"] == page["content"][i - 1]["segment"][0]["y"]:
                content_stitch["segment"][-1]["text"] += " " + \
                                                         get_joined_text(content["segment"])
            else:
                if segment_text_exists(content_stitch["segment"]):
                    dialogue_stitch[-1]["content"].append(copy(content_stitch))
                content_stitch = copy(content)

        if len(content_stitch["segment"]) > 0:
            dialogue_stitch[-1]["content"].append(copy(content_stitch))

    return dialogue_stitch
