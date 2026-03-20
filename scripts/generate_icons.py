#!/usr/bin/env python3
"""
Generate WinCase app icons for both mobile apps.
- Staff/Boss app: Shield with "W" on navy background
- Client app: "WC" monogram on navy background
"""

from PIL import Image, ImageDraw, ImageFont
import os

NAVY = (31, 56, 100)       # #1F3864
NAVY_LIGHT = (43, 74, 138) # #2B4A8A
WHITE = (255, 255, 255)
BLUE_ACCENT = (59, 130, 246) # #3B82F6

# Android mipmap sizes
SIZES = {
    'mipmap-mdpi': 48,
    'mipmap-hdpi': 72,
    'mipmap-xhdpi': 96,
    'mipmap-xxhdpi': 144,
    'mipmap-xxxhdpi': 192,
}

# High-res source size
SRC_SIZE = 1024


def draw_rounded_rect(draw, xy, radius, fill):
    """Draw a rounded rectangle."""
    x0, y0, x1, y1 = xy
    draw.rectangle([x0 + radius, y0, x1 - radius, y1], fill=fill)
    draw.rectangle([x0, y0 + radius, x1, y1 - radius], fill=fill)
    draw.pieslice([x0, y0, x0 + 2*radius, y0 + 2*radius], 180, 270, fill=fill)
    draw.pieslice([x1 - 2*radius, y0, x1, y0 + 2*radius], 270, 360, fill=fill)
    draw.pieslice([x0, y1 - 2*radius, x0 + 2*radius, y1], 90, 180, fill=fill)
    draw.pieslice([x1 - 2*radius, y1 - 2*radius, x1, y1], 0, 90, fill=fill)


def create_staff_icon(size=SRC_SIZE):
    """Staff/Boss app icon: Navy background with stylized 'W' and subtle shield."""
    img = Image.new('RGBA', (size, size), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)

    # Rounded square background
    margin = int(size * 0.02)
    radius = int(size * 0.18)
    draw_rounded_rect(draw, (margin, margin, size - margin, size - margin), radius, NAVY)

    # Subtle gradient overlay (lighter at top)
    for y in range(margin, size // 3):
        alpha = int(25 * (1 - (y - margin) / (size // 3 - margin)))
        draw.line([(margin + radius // 2, y), (size - margin - radius // 2, y)],
                  fill=(255, 255, 255, alpha))

    # Draw shield shape
    shield_margin = int(size * 0.15)
    shield_top = int(size * 0.12)
    shield_bottom = int(size * 0.88)
    shield_mid_y = int(size * 0.55)
    center_x = size // 2

    # Shield outline (subtle)
    shield_points = [
        (shield_margin, shield_top),                    # top-left
        (size - shield_margin, shield_top),             # top-right
        (size - shield_margin, shield_mid_y),           # right
        (center_x, shield_bottom),                       # bottom point
        (shield_margin, shield_mid_y),                   # left
    ]
    draw.polygon(shield_points, fill=NAVY_LIGHT, outline=None)

    # Inner shield (slightly smaller)
    inner_m = int(size * 0.04)
    inner_shield = [
        (shield_margin + inner_m, shield_top + inner_m),
        (size - shield_margin - inner_m, shield_top + inner_m),
        (size - shield_margin - inner_m, shield_mid_y - inner_m // 2),
        (center_x, shield_bottom - inner_m * 2),
        (shield_margin + inner_m, shield_mid_y - inner_m // 2),
    ]
    draw.polygon(inner_shield, fill=NAVY, outline=None)

    # Draw "W" letter
    try:
        # Try system fonts
        for font_name in [
            '/System/Library/Fonts/Supplemental/Arial Bold.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
            '/Library/Fonts/Arial Bold.ttf',
        ]:
            if os.path.exists(font_name):
                font = ImageFont.truetype(font_name, int(size * 0.48))
                break
        else:
            font = ImageFont.load_default()
    except:
        font = ImageFont.load_default()

    # Center the W
    text = "W"
    bbox = draw.textbbox((0, 0), text, font=font)
    tw = bbox[2] - bbox[0]
    th = bbox[3] - bbox[1]
    tx = (size - tw) // 2
    ty = int(size * 0.22)

    # White W with slight shadow
    draw.text((tx + 2, ty + 2), text, font=font, fill=(0, 0, 0, 60))
    draw.text((tx, ty), text, font=font, fill=WHITE)

    # Blue accent line at bottom of shield
    line_y = int(size * 0.75)
    line_w = int(size * 0.25)
    draw.rectangle([center_x - line_w, line_y, center_x + line_w, line_y + int(size * 0.025)],
                    fill=BLUE_ACCENT)

    return img


def create_client_icon(size=SRC_SIZE):
    """Client app icon: Navy background with 'WC' monogram."""
    img = Image.new('RGBA', (size, size), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)

    # Rounded square background
    margin = int(size * 0.02)
    radius = int(size * 0.18)
    draw_rounded_rect(draw, (margin, margin, size - margin, size - margin), radius, NAVY)

    # Subtle gradient overlay
    for y in range(margin, size // 3):
        alpha = int(20 * (1 - (y - margin) / (size // 3 - margin)))
        draw.line([(margin + radius // 2, y), (size - margin - radius // 2, y)],
                  fill=(255, 255, 255, alpha))

    # Circle border (accent)
    circle_margin = int(size * 0.12)
    circle_width = int(size * 0.025)
    draw.ellipse(
        [circle_margin, circle_margin, size - circle_margin, size - circle_margin],
        outline=BLUE_ACCENT, width=circle_width
    )

    # Inner filled circle (slightly darker navy)
    inner_circle = int(size * 0.17)
    draw.ellipse(
        [inner_circle, inner_circle, size - inner_circle, size - inner_circle],
        fill=NAVY_LIGHT
    )

    # Draw "WC" text
    try:
        for font_name in [
            '/System/Library/Fonts/Supplemental/Arial Bold.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
            '/Library/Fonts/Arial Bold.ttf',
        ]:
            if os.path.exists(font_name):
                font = ImageFont.truetype(font_name, int(size * 0.32))
                break
        else:
            font = ImageFont.load_default()
    except:
        font = ImageFont.load_default()

    text = "WC"
    bbox = draw.textbbox((0, 0), text, font=font)
    tw = bbox[2] - bbox[0]
    th = bbox[3] - bbox[1]
    tx = (size - tw) // 2
    ty = (size - th) // 2 - int(size * 0.03)

    # Shadow + white text
    draw.text((tx + 2, ty + 2), text, font=font, fill=(0, 0, 0, 50))
    draw.text((tx, ty), text, font=font, fill=WHITE)

    # Small "client" label
    try:
        for font_name in [
            '/System/Library/Fonts/Supplemental/Arial.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
        ]:
            if os.path.exists(font_name):
                small_font = ImageFont.truetype(font_name, int(size * 0.08))
                break
        else:
            small_font = ImageFont.load_default()
    except:
        small_font = ImageFont.load_default()

    label = "CLIENT"
    lbbox = draw.textbbox((0, 0), label, font=small_font)
    lw = lbbox[2] - lbbox[0]
    lx = (size - lw) // 2
    ly = ty + th + int(size * 0.04)
    draw.text((lx, ly), label, font=small_font, fill=BLUE_ACCENT)

    return img


def save_android_icons(img, base_path):
    """Save icon in all Android mipmap sizes."""
    for folder, px in SIZES.items():
        dir_path = os.path.join(base_path, folder)
        os.makedirs(dir_path, exist_ok=True)
        resized = img.resize((px, px), Image.LANCZOS)
        # Convert to RGB for Android (no alpha in launcher icons)
        rgb = Image.new('RGB', (px, px), NAVY)
        rgb.paste(resized, mask=resized.split()[3] if resized.mode == 'RGBA' else None)
        rgb.save(os.path.join(dir_path, 'ic_launcher.png'), 'PNG')
    print(f"  Saved to {base_path}")


def main():
    # Staff/Boss app
    print("Generating Staff/Boss app icon...")
    staff_icon = create_staff_icon()
    staff_base = '/Users/webwavedeveloper/Herd/wincase/mobile/android/app/src/main/res'
    save_android_icons(staff_icon, staff_base)
    # Save high-res
    staff_icon.save(os.path.join(staff_base, 'ic_launcher_1024.png'), 'PNG')

    # Client app
    print("Generating Client app icon...")
    client_icon = create_client_icon()
    client_base = '/Users/webwavedeveloper/Herd/wincase_client/android/app/src/main/res'
    save_android_icons(client_icon, client_base)
    client_icon.save(os.path.join(client_base, 'ic_launcher_1024.png'), 'PNG')

    print("Done!")


if __name__ == '__main__':
    main()
