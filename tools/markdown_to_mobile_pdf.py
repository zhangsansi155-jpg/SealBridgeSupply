#!/usr/bin/env python3
"""Convert a simple Markdown document into a mobile-readable PDF."""

from __future__ import annotations

import re
import sys
from pathlib import Path

from reportlab.lib import colors
from reportlab.lib.enums import TA_LEFT
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import mm
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.platypus import PageBreak, Paragraph, SimpleDocTemplate, Spacer


ROOT = Path(__file__).resolve().parents[1]
FONT_PATHS = [
    Path("/System/Library/Fonts/STHeiti Medium.ttc"),
    Path("/System/Library/Fonts/STHeiti Light.ttc"),
    Path("/System/Library/Fonts/Supplemental/Songti.ttc"),
]


def register_font() -> str:
    for font_path in FONT_PATHS:
        if font_path.exists():
            pdfmetrics.registerFont(TTFont("SealBridgeCJK", str(font_path)))
            return "SealBridgeCJK"
    return "Helvetica"


def esc(text: str) -> str:
    return (
        text.replace("&", "&amp;")
        .replace("<", "&lt;")
        .replace(">", "&gt;")
        .replace("  ", "&nbsp;&nbsp;")
    )


def inline_markup(text: str) -> str:
    text = esc(text.strip())
    text = re.sub(r"`([^`]+)`", r"<font color='#0f3d38'>\1</font>", text)
    text = re.sub(r"\*\*([^*]+)\*\*", r"<b>\1</b>", text)
    return text


def build_styles(font_name: str):
    styles = getSampleStyleSheet()
    base = {
        "fontName": font_name,
        "alignment": TA_LEFT,
        "leading": 17,
        "textColor": colors.HexColor("#182622"),
        "spaceAfter": 7,
    }
    return {
        "title": ParagraphStyle(
            "TitleMobile",
            parent=styles["Title"],
            fontName=font_name,
            fontSize=22,
            leading=28,
            textColor=colors.HexColor("#0f3d38"),
            spaceAfter=14,
        ),
        "h2": ParagraphStyle(
            "HeadingMobile",
            parent=styles["Heading2"],
            fontName=font_name,
            fontSize=15,
            leading=20,
            textColor=colors.HexColor("#0f3d38"),
            spaceBefore=10,
            spaceAfter=8,
        ),
        "body": ParagraphStyle("BodyMobile", parent=styles["BodyText"], fontSize=10.5, **base),
        "quote": ParagraphStyle(
            "QuoteMobile",
            parent=styles["BodyText"],
            fontName=font_name,
            fontSize=10.5,
            leading=17,
            leftIndent=8,
            borderColor=colors.HexColor("#d9a441"),
            borderWidth=1.4,
            borderPadding=7,
            backColor=colors.HexColor("#fbfaf5"),
            textColor=colors.HexColor("#182622"),
            spaceBefore=4,
            spaceAfter=9,
        ),
        "code": ParagraphStyle(
            "CodeMobile",
            parent=styles["Code"],
            fontName=font_name,
            fontSize=9.6,
            leading=14,
            leftIndent=0,
            borderColor=colors.HexColor("#dfe6df"),
            borderWidth=0.7,
            borderPadding=7,
            backColor=colors.HexColor("#f7f8f3"),
            textColor=colors.HexColor("#142622"),
            spaceBefore=4,
            spaceAfter=9,
        ),
        "bullet": ParagraphStyle(
            "BulletMobile",
            parent=styles["BodyText"],
            fontName=font_name,
            fontSize=10.5,
            leading=17,
            leftIndent=13,
            firstLineIndent=-8,
            textColor=colors.HexColor("#182622"),
            spaceAfter=4,
        ),
    }


def parse_markdown(md: str, style) -> list:
    story = []
    in_code = False
    code_lines: list[str] = []

    def flush_code() -> None:
        nonlocal code_lines
        if code_lines:
            story.append(Paragraph("<br/>".join(esc(line) for line in code_lines), style["code"]))
            code_lines = []

    for raw in md.splitlines():
        line = raw.rstrip()
        stripped = line.strip()

        if stripped.startswith("```"):
            if in_code:
                flush_code()
                in_code = False
            else:
                in_code = True
                code_lines = []
            continue

        if in_code:
            code_lines.append(line)
            continue

        if stripped == "---":
            story.append(Spacer(1, 4))
            continue

        if stripped == "":
            story.append(Spacer(1, 2.5))
            continue

        if stripped.startswith("# "):
            story.append(Paragraph(inline_markup(stripped[2:]), style["title"]))
            continue

        if stripped.startswith("## "):
            story.append(Paragraph(inline_markup(stripped[3:]), style["h2"]))
            continue

        if stripped.startswith("> "):
            story.append(Paragraph(inline_markup(stripped[2:]), style["quote"]))
            continue

        if stripped.startswith("- "):
            story.append(Paragraph("• " + inline_markup(stripped[2:]), style["bullet"]))
            continue

        story.append(Paragraph(inline_markup(stripped), style["body"]))

    flush_code()
    return story


def add_footer(canvas, doc):
    canvas.saveState()
    canvas.setFont("SealBridgeCJK" if "SealBridgeCJK" in pdfmetrics.getRegisteredFontNames() else "Helvetica", 8)
    canvas.setFillColor(colors.HexColor("#6a7771"))
    canvas.drawRightString(A4[0] - 14 * mm, 9 * mm, f"Page {doc.page}")
    canvas.restoreState()


def main() -> int:
    if len(sys.argv) != 3:
        print("Usage: markdown_to_mobile_pdf.py input.md output.pdf", file=sys.stderr)
        return 2

    src = Path(sys.argv[1])
    out = Path(sys.argv[2])
    out.parent.mkdir(parents=True, exist_ok=True)

    font_name = register_font()
    styles = build_styles(font_name)
    md = src.read_text(encoding="utf-8")
    story = parse_markdown(md, styles)

    doc = SimpleDocTemplate(
        str(out),
        pagesize=A4,
        rightMargin=14 * mm,
        leftMargin=14 * mm,
        topMargin=14 * mm,
        bottomMargin=14 * mm,
        title=src.stem,
        author="SealBridge Supply",
    )
    doc.build(story, onFirstPage=add_footer, onLaterPages=add_footer)
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
