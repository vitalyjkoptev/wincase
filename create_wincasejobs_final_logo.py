#!/usr/bin/env python3
"""Generate final WinCaseJobs logo — Bodoni 72, split colors. Multiple sizes."""

from PIL import Image, ImageDraw, ImageFont
import os

OUT_DIR = "/Users/webwavedeveloper/Herd/wincase/wincasejobs-logo-final"
os.makedirs(OUT_DIR, exist_ok=True)

FONT_PATH = "/System/Library/Fonts/Supplemental/Bodoni 72.ttc"
FONT_IDX = 0

BRAND_GREEN = (22, 163, 74)
DARK = (24, 24, 24)
WHITE = (255, 255, 255)

PREFIX = "WinCase"
SUFFIX = "Jobs"

SIZES = [
    ("header", 300, 80),       # site header
    ("header@2x", 600, 160),   # retina header
    ("favicon-text", 200, 60), # small
    ("og", 800, 200),          # Open Graph / social
]


def render_split(w, h, font_size, prefix_color, suffix_color, bg=(0, 0, 0, 0)):
    img = Image.new('RGBA', (w, h), bg)
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


def find_font_size(w, h, max_size=120):
    """Find largest font size that fits."""
    for size in range(max_size, 15, -1):
        font = ImageFont.truetype(FONT_PATH, size, index=FONT_IDX)
        tmp = Image.new('RGBA', (w, h))
        draw = ImageDraw.Draw(tmp)
        bbox = draw.textbbox((0, 0), PREFIX + SUFFIX, font=font)
        tw = bbox[2] - bbox[0]
        th = bbox[3] - bbox[1]
        if tw <= w - 10 and th <= h - 6:
            return size
    return 20


for name, w, h in SIZES:
    fs = find_font_size(w, h)

    # Dark on transparent (for light backgrounds)
    logo = render_split(w, h, fs, DARK, BRAND_GREEN)
    logo.save(os.path.join(OUT_DIR, f"wincasejobs-{name}.png"), "PNG")

    # White on transparent (for dark backgrounds)
    logo_white = render_split(w, h, fs, WHITE, BRAND_GREEN)
    logo_white.save(os.path.join(OUT_DIR, f"wincasejobs-{name}-white.png"), "PNG")

    print(f"  {name}: {w}x{h}, font={fs}px")

print(f"\nAll logos saved to: {OUT_DIR}/")
