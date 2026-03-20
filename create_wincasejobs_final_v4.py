#!/usr/bin/env python3
"""WinCaseJobs logo v4 — even smaller."""

from PIL import Image, ImageDraw, ImageFont
import os

OUT_DIR = "/Users/webwavedeveloper/Herd/wincase/wincasejobs-logo-final"
FONT_PATH = "/System/Library/Fonts/Supplemental/Bodoni 72.ttc"
FONT_IDX = 0

BRAND_BLUE = (1, 94, 167)
DARK = (24, 24, 24)
PREFIX = "WinCase"
SUFFIX = "Jobs"

# Tiny header logo
W, H = 220, 56
W2, H2 = 110, 28  # 1x


def find_font_size(w, h):
    for size in range(80, 10, -1):
        font = ImageFont.truetype(FONT_PATH, size, index=FONT_IDX)
        tmp = Image.new('RGBA', (w, h))
        draw = ImageDraw.Draw(tmp)
        bbox = draw.textbbox((0, 0), PREFIX + SUFFIX, font=font)
        if bbox[2] - bbox[0] <= w - 4 and bbox[3] - bbox[1] <= h - 2:
            return size
    return 12


def render_split(w, h, fs, pc, sc):
    img = Image.new('RGBA', (w, h), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)
    font = ImageFont.truetype(FONT_PATH, fs, index=FONT_IDX)
    full = PREFIX + SUFFIX
    bf = draw.textbbox((0, 0), full, font=font)
    bp = draw.textbbox((0, 0), PREFIX, font=font)
    x = (w - (bf[2] - bf[0])) // 2
    y = (h - (bf[3] - bf[1])) // 2 - bf[1]
    draw.text((x, y), PREFIX, fill=pc + (255,), font=font)
    draw.text((x + bp[2] - bp[0], y), SUFFIX, fill=sc + (255,), font=font)
    return img


fs = find_font_size(W, H)
logo = render_split(W, H, fs, DARK, BRAND_BLUE)
logo.save(os.path.join(OUT_DIR, "wincasejobs-header-tiny@2x.png"), "PNG")

fs1 = find_font_size(W2, H2)
logo1 = render_split(W2, H2, fs1, DARK, BRAND_BLUE)
logo1.save(os.path.join(OUT_DIR, "wincasejobs-header-tiny.png"), "PNG")

print(f"  @2x: {W}x{H}, font={fs}px")
print(f"  @1x: {W2}x{H2}, font={fs1}px")
