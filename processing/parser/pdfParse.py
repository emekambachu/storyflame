from pdfminer.converter import PDFPageAggregator
from pdfminer.layout import LAParams, LTTextLine, LTTextBoxHorizontal
from pdfminer.pdfdevice import PDFDevice
from pdfminer.pdfdocument import PDFDocument
from pdfminer.pdfinterp import PDFResourceManager, PDFPageInterpreter
from pdfminer.pdfpage import PDFPage
from pdfminer.pdfparser import PDFParser


def parse_pdf(script_file):
    new_script = {
        "pdf": []
    }

    # Create a PDF parser object associated with the file object.
    parser = PDFParser(script_file)

    # Create a PDF document object that stores the document structure.
    document = PDFDocument(parser)

    # Check if the document allows text extraction. If not, abort.
    if not document.is_extractable:
        return print("wrong format")

    # Create a PDF resource manager object that stores shared resources.
    rsrcmgr = PDFResourceManager()

    # Create a PDF device object.
    device = PDFDevice(rsrcmgr)

    # BEGIN LAYOUT ANALYSIS
    # Set parameters for analysis.
    laparams = LAParams()

    # Create a PDF page aggregator object.
    device = PDFPageAggregator(rsrcmgr, laparams=laparams)

    # Create a PDF interpreter object.
    interpreter = PDFPageInterpreter(rsrcmgr, device)

    i = 0
    # loop over all pages in the document
    for page in PDFPage.create_pages(document):
        new_script["pdf"].append({
            "page": i,
            "content": []
        })

        # read the page into a layout object
        interpreter.process_page(page)
        layout = device.get_result()

        # extract text from this object
        parse_obj(new_script, layout._objs, page.mediabox[3])
        i += 1
    return new_script


def parse_obj(new_script, lt_objs, page_height):
    # loop over the object list
    for obj in lt_objs:

        if isinstance(obj, LTTextLine):
            # text = obj.get_text()
            # if text.strip():
            #     for c in obj._objs:
            #         if isinstance(c, pdfminer.layout.LTChar):
            #             print("fontname %s" % c)

            new_script["pdf"][-1]["content"].append({
                "x": round(obj.bbox[0]),
                "y": round(page_height - obj.bbox[1]),
                "text": obj.get_text().replace('\n', '').strip()
            })
        # if it's a textbox, also recurse
        if isinstance(obj, LTTextBoxHorizontal):
            parse_obj(new_script, obj._objs, page_height)
