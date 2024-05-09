from processing.parser.grouping.grouping import groupDualDialogues, stitchSeperateWordsIntoLines
from processing.parser.grouping.sections import groupSections
from processing.parser.pdfParse import parsePdf
from processing.parser.process import processInitialPages, cleanPage, sortLines, getTopTrends, cleanScript


def convert(file, page_offset=None):
    """
    Convert the pdf script into a structured json format.
    """

    # parse script based on pdfminer.six. Lacking documentation so gotta need some adjustments in our end :(
    new_script = parsePdf(file)["pdf"]

    first_pages_dict = processInitialPages(new_script)

    first_pages = first_pages_dict["firstPages"]
    skip_page = page_offset if page_offset else first_pages_dict["pageStart"]

    # remove any useless line (page number, empty line, special symbols)
    new_script = cleanPage(new_script, skip_page)

    # sort lines by y. If y is the same, then sort by x
    new_script = sortLines(new_script, skip_page)

    # group dual dialogues into the same segments
    new_script = groupDualDialogues(new_script, skip_page)

    # because of pdfminer's imperfections, we have to stitch words into what's supposed to be part of the same line
    new_script = stitchSeperateWordsIntoLines(new_script, skip_page)

    top_trends = getTopTrends(new_script)

    # group into sections based on type
    new_script = groupSections(top_trends, new_script, skip_page, False)

    new_script = cleanScript(new_script, False)

    new_script = first_pages + new_script
    file.close()
    return new_script
