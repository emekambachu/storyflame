import operator
import re

from processing.parser.helpers.headings import isHeading


def processInitialPages(script):
    total = []

    for page in script:
        existingY = {}
        for content in page["content"]:
            if content["y"] not in existingY:
                existingY[content["y"]] = True

        total.append(len(existingY))

    avg = sum(total) / len(total)
    firstPages = []
    i = 0
    while i < len(total):
        if total[i] > avg - 10:
            break
        firstPages.append({
            "page": i,
            "content": script[i]["content"]
        })
        i += 1

    firstPages = cleanPage(firstPages, 0)
    firstPages = [x for x in firstPages]
    for page in firstPages:
        page["type"] = "FIRST_PAGES"
    return {
        "firstPages": firstPages,
        "pageStart": i
    }


def cleanPage(script, pageStart):
    dialogueStitch = []
    for page in script:
        if page["page"] < pageStart:
            continue
        dialogueStitch.append({"page": page["page"], "content": []})

        firstRound = []
        for content in page["content"]:
            text = re.sub("\s{2}", " ", content["text"].strip())
            if text == "" or text == "*" or text == "." or text == "\\." or text == "\\" or text == "'":
                continue
            if content["x"] < 65 or content["x"] > 500 or content["y"] <= 50:
                continue
            content["text"] = text
            firstRound.append(content)

        for i, content in enumerate(firstRound):
            text = content["text"]
            if "Okay, so how many trees are on tha" in text:
                x = 0
            if not isHeading(content) and content["y"] < 80 and content["x"] < 100:
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

            dialogueStitch[-1]["content"].append(content)
    removeDuplicates(dialogueStitch)
    return dialogueStitch


def removeDuplicates(script):
    for pageIndex, page in enumerate(script):
        for contentIndex, content in enumerate(page["content"]):
            if contentIndex + 1 < len(page["content"]) and content == page["content"][contentIndex + 1]:
                script[pageIndex]["content"].pop(contentIndex + 1)


def sortLines(script, pageStart):
    newScript = []
    for page in script:
        if page["page"] < pageStart:
            continue
        newScript.append({
            "page": page["page"],
            "content": []
        })

        newScript[-1]["content"] = page["content"]

        newScript[-1]["content"].sort(
            key=lambda curr: (curr["y"], curr["x"]))

        # TODO: how to determine this?
        for i, content in enumerate(newScript[-1]["content"]):
            if abs(content["y"] - newScript[-1]["content"][i - 1]["y"]) < 5:
                newScript[-1]["content"][i - 1]["y"] = content["y"]
        # print(content)
        # print(newScript[-1]["content"][i-1])

        newScript[-1]["content"].sort(
            key=lambda curr: (curr["y"], curr["x"]))

    return newScript


def getTopTrends(script):
    trends = {}
    for page in script:
        for section in page["content"]:
            roundedX = round(section["segment"][0]["x"])
            if roundedX not in trends:
                trends[roundedX] = 1
            else:
                trends[roundedX] += 1
    trends = sorted(trends.items(), key=operator.itemgetter(0), reverse=False)

    while trends[0][1] < 10:
        trends.pop(0)

    return trends


def cleanScript(script, includePageNumber):
    for page in script:
        if not includePageNumber:
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
