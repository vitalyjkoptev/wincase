#!/usr/bin/env python3
"""WinCaseJobs logo v3 — even smaller for header."""

from PIL import Image, ImageDraw, ImageFont
import os

OUT_DIR = "/Users/webwavedeveloper/Herd/wincase/wincasejobs-logo-final"
FONT_PATH = "/System/Library/Fonts/Supplemental/Bodoni 72.ttc"
FONT_IDX = 0

BRAND_BLUE = (1, 94, 167)
DARK = (24, 24, 24)
WHITE = (255, 255, 255)
PREFIX = "WinCase"
SUFFIX = "Jobs"

# Even smaller header logo
SIZES = [
    ("header-sm", 150, 38),
    ("header-sm@2x", 300, 76),
]


def find_font_size(w, h, max_size=80):
    for size in range(max_size, 10, -1):
        font = ImageFont.truetype(FONT_PATH, size, index=FONT_IDX)
        tmp = Image.new('RGBA', (w, h))
        draw = ImageDraw.Draw(tmp)
        bbox = draw.textbbox((0, 0), PREFIX + SUFFIX, font=font)
        tw = bbox[2] - bbox[0]
        th = bbox[3] - bbox[1]
        if tw <= w - 6 and th <= h - 4:
            return size
    return 15


def render_split(w, h, font_size, prefix_color, suffix_color):
    img = Image.new('RGBA', (w, h), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)
    font = ImageFont.truetype(FONT_PATH, font_size, index=FONT_IDX)
    full = PREFIX + SUFFIX
    bbox_full = draw.textbbox((0, 0), full, font=font)
    full_w = bbox_full[2] - bbox_full[0]
    full_h = bbox_full[3] - bbox_full[1]
    bbox_prefix = draw.textbbox((0, 0), PREFIX, font=font)
    prefix_w = bbox_prefix[2] - bbox_prefix[0]
    x = (w - full_w) // 2
    y = (h - full_h) // 2 - bbox_full[1]
    draw.text((x, y), PREFIX, fill=prefix_color + (255,), font=font)
    draw.text((x + prefix_w, y), SUFFIX, fill=suffix_color + (255,), font=font)
    return img


for name, w, h in SIZES:
    fs = find_font_size(w, h)
    logo = render_split(w, h, fs, DARK, BRAND_BLUE)
    logo.save(os.path.join(OUT_DIR, f"wincasejobs-{name}.png"), "PNG")
    print(f"  {name}: {w}x{h}, font={fs}px")

print("Done")
