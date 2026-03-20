#!/usr/bin/env python3
"""WinCaseJobs logo v5 — bigger and bolder."""

from PIL import Image, ImageDraw, ImageFont
import os

OUT_DIR = "/Users/webwavedeveloper/Herd/wincase/wincasejobs-logo-final"
FONT_PATH = "/System/Library/Fonts/Supplemental/Bodoni 72.ttc"

BRAND_BLUE = (1, 94, 167)
DARK = (24, 24, 24)
PREFIX = "WinCase"
SUFFIX = "Jobs"

# Bodoni 72.ttc indices: 0=Regular, 1=Bold, 2=Book, 3=Book Italic
# Try bold index
FONT_IDX = 1  # Bold

W, H = 340, 80


def find_font_size(w, h, idx):
    for size in range(100, 10, -1):
        font = ImageFont.truetype(FONT_PATH, size, index=idx)
        tmp = Image.new('RGBA', (w, h))
        draw = ImageDraw.Draw(tmp)
        bbox = draw.textbbox((0, 0), PREFIX + SUFFIX, font=font)
        if bbox[2] - bbox[0] <= w - 6 and bbox[3] - bbox[1] <= h - 4:
            return size
    return 15


def render_split(w, h, fs, idx, pc, sc):
    img = Image.new('RGBA', (w, h), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)
    font = ImageFont.truetype(FONT_PATH, fs, index=idx)
    full = PREFIX + SUFFIX
    bf = draw.textbbox((0, 0), full, font=font)
    bp = draw.textbbox((0, 0), PREFIX, font=font)
    x = (w - (bf[2] - bf[0])) // 2
    y = (h - (bf[3] - bf[1])) // 2 - bf[1]
    draw.text((x, y), PREFIX, fill=pc + (255,), font=font)
    draw.text((x + bp[2] - bp[0], y), SUFFIX, fill=sc + (255,), font=font)
    return img


# Try all Bodoni indices to find boldest
for idx in range(4):
    try:
        font = ImageFont.truetype(FONT_PATH, 40, index=idx)
        name = font.getname()
        print(f"  Index {idx}: {name}")
    except:
        print(f"  Index {idx}: not available")

# Generate with bold (idx=1)
fs = find_font_size(W, H, 1)
logo = render_split(W, H, fs, 1, DARK, BRAND_BLUE)
logo.save(os.path.join(OUT_DIR, "wincasejobs-v5-bold.png"), "PNG")
print(f"\n  Bold: {W}x{H}, font={fs}px")

# Also try regular but bigger stroke for comparison
fs0 = find_font_size(W, H, 0)
logo0 = render_split(W, H, fs0, 0, DARK, BRAND_BLUE)
logo0.save(os.path.join(OUT_DIR, "wincasejobs-v5-regular.png"), "PNG")
print(f"  Regular: {W}x{H}, font={fs0}px")
