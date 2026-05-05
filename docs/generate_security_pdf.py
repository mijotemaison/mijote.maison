from pathlib import Path
import re
import textwrap

from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import cm
from reportlab.platypus import PageBreak, Paragraph, Preformatted, SimpleDocTemplate, Spacer


ROOT = Path(__file__).resolve().parents[1]
SOURCE = ROOT / "docs" / "rapport-securite.md"
TARGET = ROOT / "docs" / "rapport-securite-projet-final-greta92.pdf"


def clean(text: str) -> str:
    return (
        text.replace("&", "&amp;")
        .replace("<", "&lt;")
        .replace(">", "&gt;")
    )


def split_code_blocks(markdown: str):
    parts = re.split(r"```(?:php|bash|sql)?\n(.*?)```", markdown, flags=re.S)
    for index, part in enumerate(parts):
        yield index % 2 == 1, part


def build_pdf() -> None:
    styles = getSampleStyleSheet()
    styles.add(ParagraphStyle(name="CoverTitle", parent=styles["Title"], fontSize=26, leading=32, alignment=TA_CENTER, textColor=colors.HexColor("#0f172a"), spaceAfter=18))
    styles.add(ParagraphStyle(name="H1x", parent=styles["Heading1"], fontSize=18, leading=24, textColor=colors.HexColor("#0f172a"), spaceBefore=12, spaceAfter=8))
    styles.add(ParagraphStyle(name="H2x", parent=styles["Heading2"], fontSize=14, leading=19, textColor=colors.HexColor("#155e75"), spaceBefore=10, spaceAfter=6))
    styles.add(ParagraphStyle(name="Bodyx", parent=styles["BodyText"], fontSize=9.5, leading=13, spaceAfter=5))
    styles.add(ParagraphStyle(name="Bulletx", parent=styles["BodyText"], fontSize=9.5, leading=13, leftIndent=12, firstLineIndent=-8, spaceAfter=3))
    code_style = ParagraphStyle(name="CodexCode", fontName="Courier", fontSize=7.2, leading=9, backColor=colors.HexColor("#eef2ff"), borderColor=colors.HexColor("#c7d2fe"), borderWidth=0.5, borderPadding=5, spaceBefore=4, spaceAfter=8)

    doc = SimpleDocTemplate(str(TARGET), pagesize=A4, rightMargin=1.5 * cm, leftMargin=1.5 * cm, topMargin=1.4 * cm, bottomMargin=1.4 * cm)
    story = []
    markdown = SOURCE.read_text(encoding="utf-8")

    story.append(Spacer(1, 5 * cm))
    story.append(Paragraph("Secure Recipes GRETA 92", styles["CoverTitle"]))
    story.append(Paragraph("Projet final formation GRETA 92", styles["Title"]))
    story.append(Paragraph("Developpement securise d'un site de recettes de cuisine", styles["BodyText"]))
    story.append(Paragraph("Otmane Aiboud", styles["BodyText"]))
    story.append(Paragraph("PHP · HTML · JavaScript · Tailwind CSS · MySQL", styles["BodyText"]))
    story.append(PageBreak())

    for is_code, part in split_code_blocks(markdown):
        if is_code:
            wrapped = "\n".join(textwrap.fill(line, width=88, replace_whitespace=False) if len(line) > 88 else line for line in part.strip("\n").splitlines())
            story.append(Preformatted(wrapped, code_style))
            continue

        for raw_line in part.splitlines():
            line = raw_line.strip()
            if not line:
                story.append(Spacer(1, 4))
                continue
            if line.startswith("# "):
                continue
            if line.startswith("## "):
                story.append(Paragraph(clean(line[3:]), styles["H1x"]))
                continue
            if line.startswith("### "):
                story.append(Paragraph(clean(line[4:]), styles["H2x"]))
                continue
            if line.startswith("- "):
                story.append(Paragraph("• " + clean(line[2:]), styles["Bulletx"]))
                continue
            story.append(Paragraph(clean(line), styles["Bodyx"]))

    doc.build(story, onFirstPage=footer, onLaterPages=footer)


def footer(canvas, doc):
    canvas.saveState()
    canvas.setFont("Helvetica", 8)
    canvas.setFillColor(colors.HexColor("#64748b"))
    canvas.drawString(1.5 * cm, 0.8 * cm, "Secure Recipes GRETA 92 - Rapport securite")
    canvas.drawRightString(A4[0] - 1.5 * cm, 0.8 * cm, f"Page {doc.page}")
    canvas.restoreState()


if __name__ == "__main__":
    build_pdf()
