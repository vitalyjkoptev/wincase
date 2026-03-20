#!/usr/bin/env python3
"""WinCaseJobs logo v6 — Bodoni 72 Bold + slight stroke for extra weight."""

from PIL import Image, ImageDraw, ImageFont
import os

OUT_DIR = "/Users/webwavedeveloper/Herd/wincase/wincasejobs-logo-final"
FONT_PATH = "/System/Library/Fonts/Supplemental/Bodoni 72.ttc"
FONT_IDX = 2  # Bold

BRAND_BLUE = (1, 94, 167)
DARK = (24, 24, 24)
PREFIX = "WinCase"
SUFFIX = "Jobs"

W, H = 380, 90


def find_font_size(w, h):
    for size in range(120, 10, -1):
        font = ImageFont.truetype(FONT_PATH, size, index=FONT_IDX)
        tmp = Image.new('RGBA', (w, h))
        draw = ImageDraw.Draw(tmp)
        bbox = draw.textbbox((0, 0), PREFIX + SUFFIX, font=font)
        if bbox[2] - bbox[0] <= w - 8 and bbox[3] - bbox[1] <= h - 6:
            return size
    return 15


def render_split(w, h, fs, pc, sc, stroke=1):
    img = Image.new('RGBA', (w, h), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)
    font = ImageFont.truetype(FONT_PATH, fs, index=FONT_IDX)
    full = PREFIX + SUFFIX
    bf = draw.textbbox((0, 0), full, font=font)
    bp = draw.textbbox((0, 0), PREFIX, font=font)
    x = (w - (bf[2] - bf[0])) // 2
    y = (h - (bf[3] - bf[1])) // 2 - bf[1]
    draw.text((x, y), PREFIX, fill=pc + (255,), font=font,
              stroke_width=stroke, stroke_fill=pc + (255,))
    draw.text((x + bp[2] - bp[0], y), SUFFIX, fill=sc + (255,), font=font,
              stroke_width=stroke, stroke_fill=sc + (255,))
    return img


fs = find_font_size(W, H)
logo = render_split(W, H, fs, DARK, BRAND_BLUE, stroke=1)
logo.save(os.path.join(OUT_DIR, "wincasejobs-v6.png"), "PNG")
print(f"  {W}x{H}, font={fs}px, stroke=1")
