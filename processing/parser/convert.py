from processing.parser.grouping.grouping import group_dual_dialogues, stitch_separate_words_into_lines
from processing.parser.grouping.sections import group_sections
from processing.parser.pdfParse import parse_pdf
from processing.parser.process import process_initial_pages, clean_page, sort_lines, get_top_trends, clean_script


def convert(file, page_offset=None):
    """
    Convert the pdf script into a structured json format.
    """

    # parse script based on pdfminer.six. Lacking documentation so gotta need some adjustments in our end :(
    new_script = parse_pdf(file)["pdf"]

    first_pages_dict = process_initial_pages(new_script)

    first_pages = first_pages_dict["firstPages"]
    skip_page = page_offset if page_offset else first_pages_dict["pageStart"]

    # remove any useless line (page number, empty line, special symbols)
    new_script = clean_page(new_script, skip_page)

    # sort lines by y. If y is the same, then sort by x
    new_script = sort_lines(new_script, skip_page)

    # group dual dialogues into the same segments
    new_script = group_dual_dialogues(new_script, skip_page)

    # because of pdfminer's imperfections, we have to stitch words into what's supposed to be part of the same line
    new_script = stitch_separate_words_into_lines(new_script, skip_page)

    top_trends = get_top_trends(new_script)

    # group into sections based on type
    new_script = group_sections(top_trends, new_script, skip_page, False)

    new_script = clean_script(new_script, False)

    new_script = first_pages + new_script
    file.close()
    return new_script
