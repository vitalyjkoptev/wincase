#!/usr/bin/env python3
"""
Generate Warsaw Daily logo in PNG format (600x125, transparent background).
Requires: pip install Pillow
"""
from PIL import Image, ImageDraw, ImageFont
import os

WIDTH, HEIGHT = 600, 125
img = Image.new('RGBA', (WIDTH, HEIGHT), (0, 0, 0, 0))
draw = ImageDraw.Draw(img)

# Colors
RED = '#DC143C'
DARK = '#1a1a1a'
GRAY = '#666666'
LIGHT_GRAY = '#e0e0e0'

# Try system fonts
def get_font(size, bold=False):
    font_names = [
        '/System/Library/Fonts/Supplemental/Georgia Bold.ttf' if bold else '/System/Library/Fonts/Supplemental/Georgia.ttf',
        '/System/Library/Fonts/NewYork.ttf',
        '/System/Library/Fonts/Times.ttc',
    ]
    for fn in font_names:
        if os.path.exists(fn):
            try:
                return ImageFont.truetype(fn, size)
            except:
                continue
    return ImageFont.load_default()

def get_sans_font(size):
    sans_names = [
        '/System/Library/Fonts/Helvetica.ttc',
        '/System/Library/Fonts/SFCompact.ttf',
        '/System/Library/Fonts/Geneva.ttf',
    ]
    for fn in sans_names:
        if os.path.exists(fn):
            try:
                return ImageFont.truetype(fn, size)
            except:
                continue
    return ImageFont.load_default()

# Red accent bar
draw.rounded_rectangle([(0, 0), (8, HEIGHT)], radius=4, fill=RED)

# WARSAW text (bold)
font_main = get_font(52, bold=True)
draw.text((24, 12), "WARSAW", fill=DARK, font=font_main)

# Measure WARSAW width
bbox = draw.textbbox((24, 12), "WARSAW", font=font_main)
warsaw_end = bbox[2]

# DAILY text (regular, red)
font_daily = get_font(52, bold=False)
draw.text((warsaw_end + 10, 12), "DAILY", fill=RED, font=font_daily)

# Tagline
font_tag = get_sans_font(14)
draw.text((24, 78), "YOUR CITY · YOUR NEWS · YOUR VOICE", fill=GRAY, font=font_tag)

# Subtle divider
draw.line([(24, 105), (576, 105)], fill=LIGHT_GRAY, width=1)

# Save
output_dir = os.path.dirname(os.path.abspath(__file__))
project_dir = os.path.dirname(output_dir)
logo_path = os.path.join(project_dir, 'templates all', 'warsawdaily.org', 'logo-warsawdaily.png')
img.save(logo_path, 'PNG')
print(f"Logo saved to: {logo_path}")

# Also save white version for dark backgrounds
img_white = Image.new('RGBA', (WIDTH, HEIGHT), (0, 0, 0, 0))
draw_white = ImageDraw.Draw(img_white)
draw_white.rounded_rectangle([(0, 0), (8, HEIGHT)], radius=4, fill=RED)
draw_white.text((24, 12), "WARSAW", fill='#FFFFFF', font=font_main)
bbox2 = draw_white.textbbox((24, 12), "WARSAW", font=font_main)
draw_white.text((bbox2[2] + 10, 12), "DAILY", fill=RED, font=font_daily)
draw_white.text((24, 78), "YOUR CITY · YOUR NEWS · YOUR VOICE", fill='#aaaaaa', font=font_tag)
draw_white.line([(24, 105), (576, 105)], fill='#333333', width=1)

logo_white_path = os.path.join(project_dir, 'templates all', 'warsawdaily.org', 'logo-warsawdaily-white.png')
img_white.save(logo_white_path, 'PNG')
print(f"White logo saved to: {logo_white_path}")
