#!/usr/bin/env python3
"""Add 'JOBS' to the original WINCASE logo."""

from PIL import Image, ImageDraw, ImageFont
import os

SRC = "/Users/webwavedeveloper/Herd/wincase/wincasejobs-logo-final/wincase-original-logo.png"
OUT_DIR = "/Users/webwavedeveloper/Herd/wincase/wincasejobs-logo-final"

# Load original logo
orig = Image.open(SRC).convert("RGBA")
ow, oh = orig.size
print(f"Original: {ow}x{oh}")

# The WINCASE logo uses a bold sans font, blue #015EA7 / black
# "CASE" appears to be black, "WIN" is blue with the W having a figure
# We need to add "JOBS" in the same style

BLUE = (1, 94, 167)
BLACK = (24, 24, 24)

# Try to match the font — Impact or similar bold condensed
FONT_CANDIDATES = [
    "/System/Library/Fonts/Supplemental/Impact.ttf",
    "/System/Library/Fonts/Supplemental/Arial Bold.ttf",
]

font_path = None
for fp in FONT_CANDIDATES:
    if os.path.exists(fp):
        font_path = fp
        break

if not font_path:
    font_path = "/System/Library/Fonts/Helvetica.ttc"

# The main text "WINCASE" is roughly the top 75% of the image
# The slogan "Win with a trusted advisor" is the bottom part
# We need to figure out the height and position of "WINCASE" text

# Approach: extend canvas to the right and add "JOBS" in blue
# matching the approximate height and vertical position of "WINCASE"

# The main title area is roughly top 70% of image
title_h = int(oh * 0.68)  # approximate height of WINCASE text area

# Find font size for "JOBS" that matches title height
def find_size(text, target_h, path, idx=0):
    for s in range(200, 10, -1):
        f = ImageFont.truetype(path, s, index=idx)
        tmp = Image.new('RGBA', (500, 300))
        d = ImageDraw.Draw(tmp)
        bb = d.textbbox((0, 0), text, font=f)
        th = bb[3] - bb[1]
        if th <= target_h:
            return s, bb
    return 20, (0, 0, 100, 30)

# Use Impact for bold condensed look
fs, bbox = find_size("JOBS", title_h, font_path)
font = ImageFont.truetype(font_path, fs, index=0)

# Measure JOBS text
tmp = Image.new('RGBA', (600, 300))
d = ImageDraw.Draw(tmp)
bb = d.textbbox((0, 0), "JOBS", font=font)
jobs_w = bb[2] - bb[0]
jobs_h = bb[3] - bb[1]

print(f"JOBS: font={fs}px, w={jobs_w}, h={jobs_h}")

# Create new canvas: original + gap + JOBS
gap = 12
new_w = ow + gap + jobs_w + 10
new_h = oh

result = Image.new('RGBA', (new_w, new_h), (0, 0, 0, 0))
result.paste(orig, (0, 0))

# Draw JOBS aligned vertically with the WINCASE text
draw = ImageDraw.Draw(result)
jobs_x = ow + gap
# Vertically center JOBS in the title area (top part of logo)
jobs_y = (title_h - jobs_h) // 2 - bb[1]

draw.text((jobs_x, jobs_y), "JOBS", fill=BLUE + (255,), font=font)

# Save
out_path = os.path.join(OUT_DIR, "wincasejobs-original-combined.png")
result.save(out_path, "PNG")
print(f"Saved: {out_path}")

# Also save a smaller header version
header = result.copy()
# Scale to height ~50px
scale = 50 / new_h
hw = int(new_w * scale)
header_sm = result.resize((hw, 50), Image.LANCZOS)
header_sm.save(os.path.join(OUT_DIR, "wincasejobs-header-from-original.png"), "PNG")

# @2x
header_2x = result.resize((hw * 2, 100), Image.LANCZOS)
header_2x.save(os.path.join(OUT_DIR, "wincasejobs-header-from-original@2x.png"), "PNG")

print(f"Header: {hw}x50, @2x: {hw*2}x100")
