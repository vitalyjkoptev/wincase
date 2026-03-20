#!/usr/bin/env python3
"""
Generate WinCase app icons for Boss, Staff, and Client apps.
Style: Gold "W" monogram on different backgrounds.
"""

from PIL import Image, ImageDraw, ImageFont, ImageFilter
import os
import math

# === Config ===
OUT_DIR = "/Users/webwavedeveloper/Herd/wincase/mobile/assets/icons"
ICON_SIZE = 1024
CORNER_RADIUS = 220  # iOS-style rounded corners

# Colors
GOLD_LIGHT = (212, 185, 135)      # #D4B987
GOLD_MID = (191, 163, 112)        # #BFA370
GOLD_DARK = (160, 133, 85)        # #A08555
GOLD_DEEP = (140, 113, 65)        # #8C7141

# Background colors per app
APPS = {
    "boss": {
        "bg": (15, 25, 40),        # #0F1928 - deep dark navy
        "label": "B",              # small role indicator
        "accent": (212, 175, 55),  # brighter gold accent
        "name": "WinCase Boss",
    },
    "staff": {
        "bg": (31, 56, 100),       # #1F3864 - navy blue
        "label": "S",
        "accent": (200, 170, 100),
        "name": "WinCase Staff",
    },
    "client": {
        "bg": (255, 255, 255),     # white
        "label": "C",
        "accent": (160, 133, 85),
        "name": "WinCase Client",
    },
}

os.makedirs(OUT_DIR, exist_ok=True)

# Font selection
HOME = os.path.expanduser("~")
FONT_PATHS = [
    ("/System/Library/Fonts/Supplemental/Didot.ttc", 1),  # Didot Bold
    (f"{HOME}/Library/Fonts/PlayfairDisplay[wght].ttf", 0),
    ("/System/Library/Fonts/Supplemental/Bodoni 72.ttc", 0),
]

LABEL_FONT_PATH = "/System/Library/Fonts/Helvetica.ttc"


def get_font(size):
    """Get best available serif font at given size."""
    for path, idx in FONT_PATHS:
        if os.path.exists(path):
            try:
                font = ImageFont.truetype(path, size, index=idx)
                if "[wght]" in path:
                    try:
                        font.set_variation_by_axes([900])
                    except:
                        try:
                            font.set_variation_by_axes([700])
                        except:
                            pass
                return font
            except:
                continue
    return ImageFont.load_default()


def get_label_font(size):
    """Get Helvetica for small labels."""
    try:
        return ImageFont.truetype(LABEL_FONT_PATH, size, index=1)  # Bold
    except:
        return ImageFont.load_default()


def create_gold_gradient(width, height, direction="vertical"):
    """Create a gold gradient image."""
    gradient = Image.new('RGBA', (width, height))
    draw = ImageDraw.Draw(gradient)

    for y in range(height):
        t = y / height
        # Gold gradient: light top -> dark bottom with warm midtones
        if t < 0.3:
            s = t / 0.3
            r = int(GOLD_LIGHT[0] + (GOLD_MID[0] - GOLD_LIGHT[0]) * s)
            g = int(GOLD_LIGHT[1] + (GOLD_MID[1] - GOLD_LIGHT[1]) * s)
            b = int(GOLD_LIGHT[2] + (GOLD_MID[2] - GOLD_LIGHT[2]) * s)
        elif t < 0.7:
            s = (t - 0.3) / 0.4
            r = int(GOLD_MID[0] + (GOLD_DARK[0] - GOLD_MID[0]) * s)
            g = int(GOLD_MID[1] + (GOLD_DARK[1] - GOLD_MID[1]) * s)
            b = int(GOLD_MID[2] + (GOLD_DARK[2] - GOLD_MID[2]) * s)
        else:
            s = (t - 0.7) / 0.3
            r = int(GOLD_DARK[0] + (GOLD_DEEP[0] - GOLD_DARK[0]) * s)
            g = int(GOLD_DARK[1] + (GOLD_DEEP[1] - GOLD_DARK[1]) * s)
            b = int(GOLD_DARK[2] + (GOLD_DEEP[2] - GOLD_DARK[2]) * s)

        draw.line([(0, y), (width, y)], fill=(r, g, b, 255))

    return gradient


def draw_geometric_w(draw, cx, cy, size, color=(255, 255, 255)):
    """
    Draw a stylized geometric W mark.
    Inspired by the reference: interleaved V-shapes creating a W.
    """
    # W dimensions
    w = size
    h = int(size * 0.75)

    top = cy - h // 2
    bottom = cy + h // 2
    left = cx - w // 2
    right = cx + w // 2

    stroke_w = int(size * 0.09)  # Line thickness

    # The W is made of 3 downward strokes meeting at the bottom
    # Left leg: from top-left down to bottom-center-left
    # Middle: from top slightly right, down to bottom center
    # Right leg: from top-right down to bottom-center-right

    # Points for stylized W shape using thick lines
    mid_x = cx

    # Key x-positions
    x1 = left                    # far left
    x2 = left + w * 0.2          # inner left
    x3 = left + w * 0.35         # left valley bottom
    x4 = mid_x                   # center peak
    x5 = right - w * 0.35        # right valley bottom
    x6 = right - w * 0.2         # inner right
    x7 = right                   # far right

    # Draw as filled polygon - outer W shape
    points_outer = [
        (x1, top),                         # top-left outer
        (x1 + stroke_w, top),              # top-left inner
        (x3, bottom - stroke_w * 1.5),     # left valley inner
        (x4, top + stroke_w * 2),          # center peak inner
        (x5, bottom - stroke_w * 1.5),     # right valley inner
        (x7 - stroke_w, top),              # top-right inner
        (x7, top),                         # top-right outer
        (x5, bottom),                      # right valley outer
        (x4, top + stroke_w * 0.3),        # center peak outer
        (x3, bottom),                      # left valley outer
    ]

    draw.polygon(points_outer, fill=color)


def draw_w_strokes(img, cx, cy, size):
    """
    Draw a premium W lettermark using thick geometric strokes.
    Returns a mask for gold gradient application.
    """
    mask = Image.new('L', img.size, 0)
    draw = ImageDraw.Draw(mask)

    w = size
    h = int(size * 0.72)
    stroke = int(size * 0.088)

    top_y = cy - h // 2
    bot_y = cy + h // 2
    left_x = cx - w // 2
    right_x = cx + w // 2
    mid_x = cx

    # Valley positions (two bottom V-points)
    v_left_x = cx - w * 0.175
    v_right_x = cx + w * 0.175

    # W is drawn as 4 diagonal strokes forming the W
    # Each stroke is a parallelogram

    def draw_thick_line(p1, p2, thickness):
        """Draw a thick line as a polygon."""
        dx = p2[0] - p1[0]
        dy = p2[1] - p1[1]
        length = math.sqrt(dx*dx + dy*dy)
        if length == 0:
            return
        nx = -dy / length * thickness / 2
        ny = dx / length * thickness / 2

        points = [
            (p1[0] + nx, p1[1] + ny),
            (p1[0] - nx, p1[1] - ny),
            (p2[0] - nx, p2[1] - ny),
            (p2[0] + nx, p2[1] + ny),
        ]
        draw.polygon(points, fill=255)

    # Stroke 1: Top-left to left valley bottom
    draw_thick_line(
        (left_x + stroke * 0.3, top_y),
        (v_left_x, bot_y),
        stroke
    )

    # Stroke 2: Left valley bottom to center peak
    draw_thick_line(
        (v_left_x, bot_y),
        (mid_x, top_y + h * 0.32),
        stroke
    )

    # Stroke 3: Center peak to right valley bottom
    draw_thick_line(
        (mid_x, top_y + h * 0.32),
        (v_right_x, bot_y),
        stroke
    )

    # Stroke 4: Right valley bottom to top-right
    draw_thick_line(
        (v_right_x, bot_y),
        (right_x - stroke * 0.3, top_y),
        stroke
    )

    # Add small serifs at top ends
    serif_h = stroke * 0.5
    serif_w = stroke * 1.5

    # Left serif
    draw.rectangle([
        left_x - serif_w * 0.2, top_y - serif_h * 0.3,
        left_x + stroke + serif_w * 0.3, top_y + serif_h * 0.5
    ], fill=255)

    # Right serif
    draw.rectangle([
        right_x - stroke - serif_w * 0.3, top_y - serif_h * 0.3,
        right_x + serif_w * 0.2, top_y + serif_h * 0.5
    ], fill=255)

    return mask


def draw_w_with_font(img, cx, cy, size, font):
    """Draw W letter using font and return mask."""
    mask = Image.new('L', img.size, 0)
    draw = ImageDraw.Draw(mask)

    bbox = draw.textbbox((0, 0), "W", font=font)
    tw = bbox[2] - bbox[0]
    th = bbox[3] - bbox[1]

    x = cx - tw // 2 - bbox[0]
    y = cy - th // 2 - bbox[1]

    draw.text((x, y), "W", fill=255, font=font)
    return mask


def create_rounded_rect_mask(size, radius):
    """Create mask with rounded corners."""
    mask = Image.new('L', (size, size), 0)
    draw = ImageDraw.Draw(mask)
    draw.rounded_rectangle([0, 0, size - 1, size - 1], radius=radius, fill=255)
    return mask


def add_subtle_glow(img, mask, color, blur_radius=15):
    """Add subtle glow effect behind the W."""
    glow = Image.new('RGBA', img.size, (0, 0, 0, 0))
    glow_color = Image.new('RGBA', img.size, (*color, 60))

    # Expand mask slightly for glow
    glow_mask = mask.copy()
    glow_mask = glow_mask.filter(ImageFilter.GaussianBlur(blur_radius))

    glow.paste(glow_color, mask=glow_mask)
    return Image.alpha_composite(img, glow)


def create_icon(app_key, config):
    """Create a single app icon."""
    size = ICON_SIZE
    img = Image.new('RGBA', (size, size), (*config["bg"], 255))
    draw = ImageDraw.Draw(img)

    is_light_bg = sum(config["bg"]) > 500

    # Subtle gradient overlay on background
    if not is_light_bg:
        for y in range(size):
            t = y / size
            alpha = int(20 * t)  # subtle darkening at bottom
            draw.line([(0, y), (size, y)], fill=(0, 0, 0, alpha))

    # Create W lettermark
    w_size = int(size * 0.52)
    cx, cy = size // 2, int(size * 0.44)

    # Use font-based W for cleaner look
    w_font = get_font(int(w_size * 1.25))
    w_mask = draw_w_with_font(img, cx, cy, w_size, w_font)

    # Apply gold gradient through mask
    gold = create_gold_gradient(size, size)

    # Add glow for dark backgrounds
    if not is_light_bg:
        img = add_subtle_glow(img, w_mask, GOLD_LIGHT, blur_radius=25)

    # Composite gold W onto image
    gold_w = Image.new('RGBA', (size, size), (0, 0, 0, 0))
    gold_w.paste(gold, mask=w_mask)
    img = Image.alpha_composite(img, gold_w)

    # Add role label bar at bottom
    draw = ImageDraw.Draw(img)

    bar_h = int(size * 0.13)
    bar_y = size - bar_h - int(size * 0.08)
    bar_margin = int(size * 0.2)

    # Accent line / role text
    label_font = get_label_font(int(size * 0.055))
    role_text = config["name"].upper().replace("WINCASE ", "")

    bbox = draw.textbbox((0, 0), role_text, font=label_font)
    tw = bbox[2] - bbox[0]
    th = bbox[3] - bbox[1]

    text_x = (size - tw) // 2
    text_y = int(size * 0.78)

    # Role text color
    if is_light_bg:
        text_color = (80, 80, 80, 200)
    else:
        text_color = (*GOLD_LIGHT, 180)

    # Small accent line above text
    line_w = int(size * 0.15)
    line_y = text_y - int(size * 0.03)
    accent_color = (*GOLD_MID, 120) if not is_light_bg else (*GOLD_DARK, 100)
    draw.line(
        [(size // 2 - line_w // 2, line_y), (size // 2 + line_w // 2, line_y)],
        fill=accent_color,
        width=2
    )

    draw.text((text_x, text_y), role_text, fill=text_color, font=label_font)

    # Apply rounded corners for iOS
    rounded_mask = create_rounded_rect_mask(size, CORNER_RADIUS)

    # Save full size with rounded corners (iOS)
    ios_icon = Image.new('RGBA', (size, size), (0, 0, 0, 0))
    ios_icon.paste(img, mask=rounded_mask)

    # Save both versions
    # Full square (Android uses adaptive icons)
    android_path = os.path.join(OUT_DIR, f"ic_{app_key}_square.png")
    img_rgb = Image.new('RGB', (size, size), config["bg"])
    img_rgb.paste(img, mask=img.split()[3])
    img_rgb.save(android_path, "PNG", quality=95)

    # Rounded (iOS / preview)
    ios_path = os.path.join(OUT_DIR, f"ic_{app_key}_rounded.png")
    ios_icon.save(ios_path, "PNG", quality=95)

    # Foreground only for Android adaptive icon (transparent bg with W)
    fg = Image.new('RGBA', (size, size), (0, 0, 0, 0))
    fg.paste(gold, mask=w_mask)
    # Add role text to foreground
    fg_draw = ImageDraw.Draw(fg)
    fg_draw.line(
        [(size // 2 - line_w // 2, line_y), (size // 2 + line_w // 2, line_y)],
        fill=(*GOLD_MID, 120),
        width=2
    )
    fg_draw.text((text_x, text_y), role_text, fill=(*GOLD_LIGHT, 200), font=label_font)
    fg_path = os.path.join(OUT_DIR, f"ic_{app_key}_foreground.png")
    fg.save(fg_path, "PNG")

    print(f"  Created: {app_key}")
    print(f"    Android: {android_path}")
    print(f"    iOS:     {ios_path}")
    print(f"    FG:      {fg_path}")

    return img_rgb


def generate_android_sizes(source_img, app_key):
    """Generate all Android mipmap sizes."""
    sizes = {
        "mipmap-mdpi": 48,
        "mipmap-hdpi": 72,
        "mipmap-xhdpi": 96,
        "mipmap-xxhdpi": 144,
        "mipmap-xxxhdpi": 192,
    }

    android_dir = os.path.join(OUT_DIR, f"android_{app_key}")
    os.makedirs(android_dir, exist_ok=True)

    for folder, px in sizes.items():
        folder_path = os.path.join(android_dir, folder)
        os.makedirs(folder_path, exist_ok=True)
        resized = source_img.resize((px, px), Image.LANCZOS)
        resized.save(os.path.join(folder_path, "ic_launcher.png"), "PNG")

    print(f"    Android mipmaps: {android_dir}/")


def generate_ios_sizes(source_img, app_key):
    """Generate all iOS icon sizes."""
    ios_sizes = [20, 29, 40, 58, 60, 76, 80, 87, 120, 152, 167, 180, 1024]

    ios_dir = os.path.join(OUT_DIR, f"ios_{app_key}")
    os.makedirs(ios_dir, exist_ok=True)

    for px in ios_sizes:
        resized = source_img.resize((px, px), Image.LANCZOS)
        resized.save(os.path.join(ios_dir, f"Icon-App-{px}x{px}.png"), "PNG")

    print(f"    iOS icons: {ios_dir}/")


def main():
    print("=== WinCase App Icon Generator ===\n")

    for app_key, config in APPS.items():
        print(f"\n[{config['name']}]")
        source = create_icon(app_key, config)
        generate_android_sizes(source, app_key)
        generate_ios_sizes(source, app_key)

    # Create a comparison sheet
    sheet_w = ICON_SIZE * 3 + 80
    sheet_h = ICON_SIZE + 120
    sheet = Image.new('RGB', (sheet_w, sheet_h), (240, 240, 240))
    sheet_draw = ImageDraw.Draw(sheet)

    label_font = get_label_font(36)

    for i, (key, config) in enumerate(APPS.items()):
        x = 20 + i * (ICON_SIZE + 20)

        icon_path = os.path.join(OUT_DIR, f"ic_{key}_square.png")
        icon = Image.open(icon_path)
        sheet.paste(icon, (x, 20))

        sheet_draw.text(
            (x + ICON_SIZE // 2 - 80, ICON_SIZE + 40),
            config["name"],
            fill=(60, 60, 60),
            font=label_font
        )

    sheet_path = os.path.join(OUT_DIR, "comparison_sheet.png")
    sheet.save(sheet_path, "PNG")
    print(f"\nComparison sheet: {sheet_path}")
    print("\nDone!")


if __name__ == "__main__":
    main()
