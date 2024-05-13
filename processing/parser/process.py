import operator
import re

from processing.parser.helpers.headings import is_heading


def process_initial_pages(script):
    total = []

    for page in script:
        existing_y = {}
        for content in page["content"]:
            if content["y"] not in existing_y:
                existing_y[content["y"]] = True

        total.append(len(existing_y))

    avg = sum(total) / len(total)
    first_pages = []
    i = 0
    while i < len(total):
        if total[i] > avg - 10:
            break
        first_pages.append({
            "page": i,
            "content": script[i]["content"]
        })
        i += 1

    first_pages = clean_page(first_pages, 0)
    first_pages = [x for x in first_pages]
    for page in first_pages:
        page["type"] = "FIRST_PAGES"
    return {
        "firstPages": first_pages,
        "pageStart": i
    }


def clean_page(script, page_start):
    dialogue_stitch = []
    for page in script:
        if page["page"] < page_start:
            continue
        dialogue_stitch.append({"page": page["page"], "content": []})

        first_round = []
        for content in page["content"]:
            text = re.sub("\s{2}", " ", content["text"].strip())
            if text == "" or text == "*" or text == "." or text == "\\." or text == "\\" or text == "'":
                continue
            if content["x"] < 65 or content["x"] > 500 or content["y"] <= 50:
                continue
            content["text"] = text
            first_round.append(content)

        for i, content in enumerate(first_round):
            text = content["text"]
            if "Okay, so how many trees are on tha" in text:
                x = 0
            if not is_heading(content) and content["y"] < 80 and content["x"] < 100:
                if "TV Calling - For educational purposes only" in text:
                    continue
                elif (re.search(' \d{1,3}[.]?', text) or re.search('\d{1,2}\/\d{1,2}\/\d{2,4}', text)) and (
                    i == 0 or i == len(content) - 1):
                    continue
                elif (re.match('(\d|l|i|I){1,3}[.]?(?![\w\d])', text)) and len(text.strip()) < 5:
                    continue
                elif re.search(r"^i{2,3}$", text) and (len(text) < 2 or i == 0 or i == len(content) - 1):
                    continue
                elif re.search(r"([(]?CONTINUED[:)]{1,2})", text):
                    continue
                elif re.match('i{2,3}', text):
                    continue

            dialogue_stitch[-1]["content"].append(content)
    remove_duplicates(dialogue_stitch)
    return dialogue_stitch


def remove_duplicates(script):
    for pageIndex, page in enumerate(script):
        for contentIndex, content in enumerate(page["content"]):
            if contentIndex + 1 < len(page["content"]) and content == page["content"][contentIndex + 1]:
                script[pageIndex]["content"].pop(contentIndex + 1)


def sort_lines(script, page_start):
    new_script = []
    for page in script:
        if page["page"] < page_start:
            continue
        new_script.append({
            "page": page["page"],
            "content": []
        })

        new_script[-1]["content"] = page["content"]

        new_script[-1]["content"].sort(
            key=lambda curr: (curr["y"], curr["x"]))

        # TODO: how to determine this?
        for i, content in enumerate(new_script[-1]["content"]):
            if abs(content["y"] - new_script[-1]["content"][i - 1]["y"]) < 5:
                new_script[-1]["content"][i - 1]["y"] = content["y"]
        # print(content)
        # print(newScript[-1]["content"][i-1])

        new_script[-1]["content"].sort(
            key=lambda curr: (curr["y"], curr["x"]))

    return new_script


def get_top_trends(script):
    trends = {}
    for page in script:
        for section in page["content"]:
            rounded_x = round(section["segment"][0]["x"])
            if rounded_x not in trends:
                trends[rounded_x] = 1
            else:
                trends[rounded_x] += 1
    trends = sorted(trends.items(), key=operator.itemgetter(0), reverse=False)

    while trends[0][1] < 10:
        trends.pop(0)

    return trends


def clean_script(script, include_page_number):
    for page in script:
        if not include_page_number:
            del page["page"]
        for i, section in enumerate(page["content"]):
            for j, scene in enumerate(section["scene"]):
                if type(scene["content"]) is list:
                    for line in scene["content"]:
                        if "x" in line:
                            del line["x"]
                            del line["y"]
                elif "x" in scene["content"]:
                    print(scene)
                    del scene["content"]["x"]
                    del scene["content"]["y"]
    return script
