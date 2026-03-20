#!/usr/bin/env python3
"""Generate WinCaseJobs logo variants — same style as PolandPulse."""

from PIL import Image, ImageDraw, ImageFont
import os

HOME = os.path.expanduser("~")
OUT_DIR = "/Users/webwavedeveloper/Herd/wincase/wincasejobs-logo-variants"
os.makedirs(OUT_DIR, exist_ok=True)

# Logo dimensions for job site header
TARGET_W = 300
TARGET_H = 80
TEXT = "WinCaseJobs"

# Green brand color from the Khuj template
BRAND_GREEN = (22, 163, 74)  # #16A34A
DARK = (24, 24, 24)
WHITE_BG = (255, 255, 255)

# Candidate serif fonts (same pool as PolandPulse)
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

# Also generate modern sans variants for a job portal
SANS_FONTS = [
    ("SF Pro Bold", "/System/Library/Fonts/SFNS.ttf", 0),
    ("Helvetica Bold", "/System/Library/Fonts/Helvetica.ttc", 1),
    ("Inter (700)", f"{HOME}/Library/Fonts/Inter[opsz,wght].ttf", 0),
    ("Poppins Bold", f"{HOME}/Library/Fonts/Poppins-Bold.ttf", 0),
    ("Montserrat Bold", f"{HOME}/Library/Fonts/Montserrat-Bold.otf", 0),
]

ALL_FONTS = FONTS + SANS_FONTS

ROWS = len(ALL_FONTS)
ROW_H = TARGET_H + 50
SHEET_W = TARGET_W * 2 + 80  # dark + green side by side
SHEET_H = ROWS * ROW_H + 20

sheet = Image.new('RGB', (SHEET_W, SHEET_H), WHITE_BG)
sheet_draw = ImageDraw.Draw(sheet)

try:
    label_font = ImageFont.truetype("/System/Library/Fonts/Helvetica.ttc", 14, index=0)
except:
    label_font = ImageFont.load_default()


def render_logo(text, font, color, bg_color=(0, 0, 0, 0)):
    """Render text logo centered in TARGET_W x TARGET_H."""
    img = Image.new('RGBA', (TARGET_W, TARGET_H), bg_color)
    draw = ImageDraw.Draw(img)
    bbox = draw.textbbox((0, 0), text, font=font)
    text_w = bbox[2] - bbox[0]
    text_h = bbox[3] - bbox[1]
    x = (TARGET_W - text_w) // 2
    y = (TARGET_H - text_h) // 2 - bbox[1]
    draw.text((x, y), text, fill=color + (255,), font=font)
    return img


def render_split_logo(prefix, suffix, font, prefix_color, suffix_color):
    """Render 'WinCase' + 'Jobs' with two colors."""
    img = Image.new('RGBA', (TARGET_W, TARGET_H), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)

    bbox_full = draw.textbbox((0, 0), prefix + suffix, font=font)
    full_w = bbox_full[2] - bbox_full[0]
    full_h = bbox_full[3] - bbox_full[1]

    bbox_prefix = draw.textbbox((0, 0), prefix, font=font)
    prefix_w = bbox_prefix[2] - bbox_prefix[0]

    x_start = (TARGET_W - full_w) // 2
    y = (TARGET_H - full_h) // 2 - bbox_full[1]

    draw.text((x_start, y), prefix, fill=prefix_color + (255,), font=font)
    draw.text((x_start + prefix_w, y), suffix, fill=suffix_color + (255,), font=font)
    return img


for i, (name, path, idx) in enumerate(ALL_FONTS):
    y_offset = i * ROW_H + 10

    sheet_draw.text((20, y_offset), f"{i+1}. {name}", fill=(100, 100, 100), font=label_font)

    if not os.path.exists(path):
        sheet_draw.text((20, y_offset + 20), "  [FONT NOT FOUND]", fill=(255, 0, 0), font=label_font)
        continue

    # Find best font size
    font_size = 60
    font = None
    while font_size > 20:
        try:
            font = ImageFont.truetype(path, font_size, index=idx)
            if "[wght]" in path:
                try:
                    font.set_variation_by_axes([700])
                except:
                    pass
        except:
            font = None
            break

        tmp = Image.new('RGBA', (TARGET_W, TARGET_H))
        tmp_draw = ImageDraw.Draw(tmp)
        bbox = tmp_draw.textbbox((0, 0), TEXT, font=font)
        text_w = bbox[2] - bbox[0]
        text_h = bbox[3] - bbox[1]

        if text_w <= TARGET_W - 10 and text_h <= TARGET_H - 6:
            break
        font_size -= 1

    if font is None:
        continue

    # Version 1: Dark text
    logo_dark = render_logo(TEXT, font, DARK)
    safe_name = name.replace(" ", "_").replace("(", "").replace(")", "")
    logo_dark.save(os.path.join(OUT_DIR, f"logo_{i+1:02d}_{safe_name}_dark.png"), "PNG")

    # Version 2: Two-tone (WinCase=dark, Jobs=green)
    logo_split = render_split_logo("WinCase", "Jobs", font, DARK, BRAND_GREEN)
    logo_split.save(os.path.join(OUT_DIR, f"logo_{i+1:02d}_{safe_name}_split.png"), "PNG")

    # Version 3: Full green
    logo_green = render_logo(TEXT, font, BRAND_GREEN)
    logo_green.save(os.path.join(OUT_DIR, f"logo_{i+1:02d}_{safe_name}_green.png"), "PNG")

    # Paste on sheet: dark left, split right
    bg1 = Image.new('RGB', (TARGET_W, TARGET_H), WHITE_BG)
    bg1.paste(logo_dark, (0, 0), logo_dark)
    sheet.paste(bg1, (20, y_offset + 22))

    bg2 = Image.new('RGB', (TARGET_W, TARGET_H), WHITE_BG)
    bg2.paste(logo_split, (0, 0), logo_split)
    sheet.paste(bg2, (TARGET_W + 50, y_offset + 22))

    print(f"  {i+1}. {name}: size={font_size}px, w={text_w}")

sheet_path = os.path.join(OUT_DIR, "comparison_sheet.png")
sheet.save(sheet_path, "PNG")
print(f"\nComparison sheet: {sheet_path}")
print(f"Individual logos: {OUT_DIR}/")
