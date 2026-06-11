from pathlib import Path

from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import cm
from reportlab.platypus import PageBreak, Paragraph, SimpleDocTemplate, Spacer, Table, TableStyle


ROOT = Path(__file__).resolve().parents[1]
TARGET = ROOT / "output" / "pdf" / "plan-soutenance-mijote-maison.pdf"


PARTS = [
    {
        "speaker": "Intervenant 1",
        "slides": "Pages 1 à 6",
        "duration": "10 min",
        "title": "Sujet, architecture et partie publique",
        "subject": "PDF pages 1-2, contraintes techniques page 4, compléments front-office page 6.",
        "messages": [
            "Le projet répond au besoin : un site public de recettes avec administration sécurisée.",
            "La stack respecte le sujet : PHP, MySQL, Bootstrap, JavaScript, PDO.",
            "L'architecture repose sur un point d'entrée unique, des routes propres et une séparation MVC.",
            "La partie publique couvre accueil, liste, detail recette, recherche, filtres, pagination, notes, commentaires et impression.",
        ],
        "timing": "Pages 1-2 : 2 min ; pages 3-4 : 2 min ; page 5 : 3 min ; page 6 : 3 min.",
        "transition": "Maintenant que la partie publique et l'architecture sont posées, on passe à la partie admin et aux protections prioritaires.",
    },
    {
        "speaker": "Intervenant 2",
        "slides": "Pages 7 à 11",
        "duration": "10 min",
        "title": "Back-office et protections centrales",
        "subject": "PDF page 3 et grille page 5 : CRUD, authentification, SQLi, XSS, CSP et CSRF.",
        "messages": [
            "Le back-office est strictement réservé aux administrateurs connectés.",
            "Les mots de passe sont hashés, les sessions sont régénérées et l'accès admin est protégé.",
            "Les accès SQL passent par PDO et des requêtes préparées.",
            "Les affichages sont échappés, la CSP limite les scripts et le CSRF est vérifié avant les actions sensibles.",
        ],
        "timing": "Page 7 : 2 min ; page 8 : 2 min ; page 9 : 2 min ; page 10 : 2 min ; page 11 : 2 min.",
        "transition": "On a couvert les protections centrales. Il reste à montrer le durcissement, la validation, les tests et les livrables.",
    },
    {
        "speaker": "Intervenant 3",
        "slides": "Pages 12 à 16",
        "duration": "10 min",
        "title": "Durcissement, preuves et conclusion",
        "subject": "PDF pages 4-7 : force brute, upload, validation, livrables, tests et fonctionnalités bonus.",
        "messages": [
            "Le login limite les essais répétés et journalise les tentatives.",
            "L'upload vérifie taille, extension, MIME et empêche les fichiers dangereux.",
            "La validation serveur refuse les données incohérentes même si le navigateur est contourné.",
            "Les tests, la documentation et la page conformité prouvent que le projet est défendable.",
        ],
        "timing": "Page 12 : 2 min ; page 13 : 2 min ; page 14 : 2 min ; page 15 : 2 min ; page 16 : 2 min.",
        "transition": "Le projet est fonctionnel, structuré, sécurisé, documenté et relié à la grille des 40 points.",
    },
]


def clean(text: str) -> str:
    return text.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;")


def paragraph(text: str, style):
    return Paragraph(clean(text), style)


def build_pdf() -> None:
    TARGET.parent.mkdir(parents=True, exist_ok=True)

    styles = getSampleStyleSheet()
    styles.add(ParagraphStyle(name="CoverTitle", parent=styles["Title"], fontSize=25, leading=31, alignment=TA_CENTER, textColor=colors.HexColor("#0f172a"), spaceAfter=16))
    styles.add(ParagraphStyle(name="CoverSub", parent=styles["BodyText"], fontSize=12, leading=17, alignment=TA_CENTER, textColor=colors.HexColor("#475569"), spaceAfter=8))
    styles.add(ParagraphStyle(name="H1x", parent=styles["Heading1"], fontSize=17, leading=22, textColor=colors.HexColor("#0f172a"), spaceBefore=12, spaceAfter=8))
    styles.add(ParagraphStyle(name="H2x", parent=styles["Heading2"], fontSize=13, leading=17, textColor=colors.HexColor("#155e75"), spaceBefore=8, spaceAfter=4))
    styles.add(ParagraphStyle(name="Bodyx", parent=styles["BodyText"], fontSize=9.2, leading=12.5, textColor=colors.HexColor("#1f2937"), spaceAfter=5))
    styles.add(ParagraphStyle(name="Smallx", parent=styles["BodyText"], fontSize=8.2, leading=11, textColor=colors.HexColor("#475569"), spaceAfter=3))
    styles.add(ParagraphStyle(name="Bulletx", parent=styles["BodyText"], fontSize=9.2, leading=12.5, leftIndent=12, firstLineIndent=-8, textColor=colors.HexColor("#1f2937"), spaceAfter=3))

    doc = SimpleDocTemplate(str(TARGET), pagesize=A4, rightMargin=1.45 * cm, leftMargin=1.45 * cm, topMargin=1.35 * cm, bottomMargin=1.35 * cm)
    story = []

    story.append(Spacer(1, 4.8 * cm))
    story.append(paragraph("Plan de soutenance Mijote Maison", styles["CoverTitle"]))
    story.append(paragraph("Répartition en 3 parties de 10 minutes", styles["CoverSub"]))
    story.append(paragraph("Présentation de 30 minutes - Projet final Analyste Cybersécurité GRETA 92", styles["CoverSub"]))
    story.append(Spacer(1, 1.2 * cm))
    story.append(paragraph("Objectif : chaque intervenant couvre une partie cohérente du sujet PDF et des pages du support `/presentation`.", styles["CoverSub"]))
    story.append(PageBreak())

    story.append(paragraph("Vue d'ensemble", styles["H1x"]))
    table_data = [[paragraph("Intervenant", styles["Smallx"]), paragraph("Pages", styles["Smallx"]), paragraph("Durée", styles["Smallx"]), paragraph("Rôle", styles["Smallx"])]]
    for part in PARTS:
        table_data.append([
            paragraph(part["speaker"], styles["Smallx"]),
            paragraph(part["slides"], styles["Smallx"]),
            paragraph(part["duration"], styles["Smallx"]),
            paragraph(part["title"], styles["Smallx"]),
        ])
    table = Table(table_data, colWidths=[3.1 * cm, 3.1 * cm, 2.1 * cm, 8.3 * cm])
    table.setStyle(TableStyle([
        ("BACKGROUND", (0, 0), (-1, 0), colors.HexColor("#e0f2fe")),
        ("TEXTCOLOR", (0, 0), (-1, 0), colors.HexColor("#0f172a")),
        ("GRID", (0, 0), (-1, -1), 0.5, colors.HexColor("#cbd5e1")),
        ("VALIGN", (0, 0), (-1, -1), "TOP"),
        ("ROWBACKGROUNDS", (0, 1), (-1, -1), [colors.white, colors.HexColor("#f8fafc")]),
        ("LEFTPADDING", (0, 0), (-1, -1), 6),
        ("RIGHTPADDING", (0, 0), (-1, -1), 6),
        ("TOPPADDING", (0, 0), (-1, -1), 6),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 6),
    ]))
    story.append(table)
    story.append(Spacer(1, 10))
    story.append(paragraph("Logique du découpage", styles["H2x"]))
    story.append(paragraph("Le découpage 1-6, 7-11, 12-16 garde ensemble les blocs qui doivent être expliqués ensemble : la partie publique reste avec l'architecture, le back-office reste avec les protections centrales, puis le durcissement et les preuves terminent la démonstration.", styles["Bodyx"]))

    for part in PARTS:
        story.append(PageBreak())
        story.append(paragraph(f"{part['speaker']} - {part['title']} ({part['slides']}, {part['duration']})", styles["H1x"]))
        story.append(paragraph("Sujet PDF couvert", styles["H2x"]))
        story.append(paragraph(part["subject"], styles["Bodyx"]))
        story.append(paragraph("Messages à faire passer", styles["H2x"]))
        for message in part["messages"]:
            story.append(paragraph("• " + message, styles["Bulletx"]))
        story.append(paragraph("Timing conseillé", styles["H2x"]))
        story.append(paragraph(part["timing"], styles["Bodyx"]))
        story.append(paragraph("Transition", styles["H2x"]))
        story.append(paragraph(part["transition"], styles["Bodyx"]))

    story.append(Spacer(1, 8))
    story.append(paragraph("A garder pour les questions", styles["H1x"]))
    for item in [
        "La page /conformite sert de grille de justification face au jury.",
        "La page /stack sert à expliquer l'organisation technique.",
        "Le rapport de sécurité détaille les vulnérabilités et les protections mises en place.",
        "Les fonctionnalites bonus renforcent la demonstration, mais ne remplacent pas les exigences principales.",
    ]:
        story.append(paragraph("• " + item, styles["Bulletx"]))

    doc.build(story, onFirstPage=footer, onLaterPages=footer)


def footer(canvas, doc):
    canvas.saveState()
    canvas.setFont("Helvetica", 8)
    canvas.setFillColor(colors.HexColor("#64748b"))
    canvas.drawString(1.45 * cm, 0.75 * cm, "Mijote Maison - Plan de soutenance")
    canvas.drawRightString(A4[0] - 1.45 * cm, 0.75 * cm, f"Page {doc.page}")
    canvas.restoreState()


if __name__ == "__main__":
    build_pdf()
