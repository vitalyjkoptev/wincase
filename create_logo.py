#!/usr/bin/env python3
"""Generate PolandPulse logo variants with all installed serif fonts.
Creates a comparison sheet + individual logo files."""

from PIL import Image, ImageDraw, ImageFont
import os

HOME = os.path.expanduser("~")
OUT_DIR = "/Users/webwavedeveloper/Herd/wincase/logo-variants"
os.makedirs(OUT_DIR, exist_ok=True)

TARGET_W = 767
TARGET_H = 128
TEXT = "PolandPulse"

# All candidate fonts: (name, path, index)
FONTS = [
    ("Didot Bold", "/System/Library/Fonts/Supplemental/Didot.ttc", 1),
    ("Playfair Display (900)", f"{HOME}/Library/Fonts/PlayfairDisplay[wght].ttf", 0),
    ("Libre Bodoni (700)", f"{HOME}/Library/Fonts/LibreBodoni[wght].ttf", 0),
    ("DM Serif Display", f"{HOME}/Library/Fonts/DMSerifDisplay-Regular.ttf", 0),
    ("Cormorant Bold", f"{HOME}/Library/Fonts/Cormorant-Bold.otf", 0),
    ("Cormorant SC Bold", f"{HOME}/Library/Fonts/CormorantSC-Bold.otf", 0),
    ("Old Standard Bold", f"{HOME}/Library/Fonts/OldStandard-Bold.ttf", 0),
    ("Spectral ExtraBold", f"{HOME}/Library/Fonts/Spectral-ExtraBold.ttf", 0),
    ("Lora (700)", f"{HOME}/Library/Fonts/Lora[wght].ttf", 0),
    ("EB Garamond 12", f"{HOME}/Library/Fonts/EBGaramond12-Regular.otf", 0),
    ("Bodoni 72 (system)", "/System/Library/Fonts/Supplemental/Bodoni 72.ttc", 0),
]

ROWS = len(FONTS)
ROW_H = TARGET_H + 50  # extra space for label
SHEET_W = TARGET_W + 40
SHEET_H = ROWS * ROW_H + 20

sheet = Image.new('RGB', (SHEET_W, SHEET_H), (255, 255, 255))
sheet_draw = ImageDraw.Draw(sheet)

# Label font
try:
    label_font = ImageFont.truetype("/System/Library/Fonts/Helvetica.ttc", 16, index=0)
except:
    label_font = ImageFont.load_default()

for i, (name, path, idx) in enumerate(FONTS):
    y_offset = i * ROW_H + 10

    # Draw label
    sheet_draw.text((20, y_offset), f"{i+1}. {name}", fill=(100, 100, 100), font=label_font)

    # Create individual logo
    img = Image.new('RGBA', (TARGET_W, TARGET_H), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)

    if not os.path.exists(path):
        sheet_draw.text((20, y_offset + 25), f"  [FONT NOT FOUND: {path}]", fill=(255, 0, 0), font=label_font)
        continue

    # For variable fonts, set weight via variation
    font_size = 120
    font = None
    while font_size > 30:
        try:
            font = ImageFont.truetype(path, font_size, index=idx)
            # Try to set weight for variable fonts
            if "[wght]" in path:
                try:
                    font.set_variation_by_axes([900])  # Black/ExtraBold weight
                except:
                    try:
                        font.set_variation_by_axes([700])  # Bold
                    except:
                        pass
        except Exception as e:
            sheet_draw.text((20, y_offset + 25), f"  [ERROR: {e}]", fill=(255, 0, 0), font=label_font)
            font = None
            break

        bbox = draw.textbbox((0, 0), TEXT, font=font)
        text_w = bbox[2] - bbox[0]
        text_h = bbox[3] - bbox[1]

        if text_w <= TARGET_W - 20 and text_h <= TARGET_H - 10:
            break
        font_size -= 1

    if font is None:
        continue

    # Center text
    x = (TARGET_W - text_w) // 2
    y = (TARGET_H - text_h) // 2 - bbox[1]

    draw.text((x, y), TEXT, fill=(24, 24, 24, 255), font=font)

    # Save individual logo
    safe_name = name.replace(" ", "_").replace("(", "").replace(")", "")
    individual_path = os.path.join(OUT_DIR, f"logo_{i+1:02d}_{safe_name}.png")
    img.save(individual_path, "PNG")

    # Paste onto comparison sheet
    # Create white bg version for sheet
    img_bg = Image.new('RGB', (TARGET_W, TARGET_H), (255, 255, 255))
    img_bg.paste(img, (0, 0), img)
    sheet.paste(img_bg, (20, y_offset + 22))

    print(f"  {i+1}. {name}: size={font_size}px, w={text_w}")

# Save comparison sheet
sheet_path = os.path.join(OUT_DIR, "comparison_sheet.png")
sheet.save(sheet_path, "PNG")
print(f"\nComparison sheet: {sheet_path}")
print(f"Individual logos: {OUT_DIR}/")
