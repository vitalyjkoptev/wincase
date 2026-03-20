#!/usr/bin/env python3
"""Generate WinCase internal logos for use inside the apps (login, splash, profile)."""

from PIL import Image, ImageDraw, ImageFont
import os

HOME = os.path.expanduser("~")
OUT_STAFF = f"{HOME}/Herd/wincase/mobile/assets/images"
OUT_CLIENT = f"{HOME}/Herd/wincase_client/assets/images"
os.makedirs(OUT_STAFF, exist_ok=True)
os.makedirs(OUT_CLIENT, exist_ok=True)

GOLD_LIGHT = (212, 185, 135)
GOLD_MID = (191, 163, 112)
GOLD_DARK = (160, 133, 85)

FONT_PATHS = [
    ("/System/Library/Fonts/Supplemental/Didot.ttc", 1),
    (f"{HOME}/Library/Fonts/PlayfairDisplay[wght].ttf", 0),
    ("/System/Library/Fonts/Supplemental/Bodoni 72.ttc", 0),
]

LABEL_FONT_PATH = "/System/Library/Fonts/Helvetica.ttc"


def get_font(size):
    for path, idx in FONT_PATHS:
        if os.path.exists(path):
            try:
                font = ImageFont.truetype(path, size, index=idx)
                if "[wght]" in path:
                    try: font.set_variation_by_axes([900])
                    except:
                        try: font.set_variation_by_axes([700])
                        except: pass
                return font
            except: continue
    return ImageFont.load_default()


def get_label_font(size, bold=True):
    try:
        return ImageFont.truetype(LABEL_FONT_PATH, size, index=1 if bold else 0)
    except:
        return ImageFont.load_default()


def create_gold_gradient(width, height):
    gradient = Image.new('RGBA', (width, height))
    draw = ImageDraw.Draw(gradient)
    for y in range(height):
        t = y / height
        if t < 0.4:
            s = t / 0.4
            r = int(GOLD_LIGHT[0] + (GOLD_MID[0] - GOLD_LIGHT[0]) * s)
            g = int(GOLD_LIGHT[1] + (GOLD_MID[1] - GOLD_LIGHT[1]) * s)
            b = int(GOLD_LIGHT[2] + (GOLD_MID[2] - GOLD_LIGHT[2]) * s)
        else:
            s = (t - 0.4) / 0.6
            r = int(GOLD_MID[0] + (GOLD_DARK[0] - GOLD_MID[0]) * s)
            g = int(GOLD_MID[1] + (GOLD_DARK[1] - GOLD_MID[1]) * s)
            b = int(GOLD_MID[2] + (GOLD_DARK[2] - GOLD_MID[2]) * s)
        draw.line([(0, y), (width, y)], fill=(r, g, b, 255))
    return gradient


def create_logo_w(size=256):
    """Gold W on transparent background."""
    img = Image.new('RGBA', (size, size), (0, 0, 0, 0))
    mask = Image.new('L', (size, size), 0)
    draw = ImageDraw.Draw(mask)
    font = get_font(int(size * 1.1))
    bbox = draw.textbbox((0, 0), "W", font=font)
    tw, th = bbox[2] - bbox[0], bbox[3] - bbox[1]
    x = (size - tw) // 2 - bbox[0]
    y = (size - th) // 2 - bbox[1]
    draw.text((x, y), "W", fill=255, font=font)
    gold = create_gold_gradient(size, size)
    gold_w = Image.new('RGBA', (size, size), (0, 0, 0, 0))
    gold_w.paste(gold, mask=mask)
    return gold_w


def create_logo_full(width=512, height=160, dark_bg=False):
    """Full logo: Gold W + WINCASE text."""
    bg_color = (15, 25, 40, 255) if dark_bg else (0, 0, 0, 0)
    img = Image.new('RGBA', (width, height), bg_color)

    # W icon
    w_size = int(height * 0.7)
    w_img = create_logo_w(w_size)
    w_x = int(width * 0.05)
    w_y = (height - w_size) // 2
    img.paste(w_img, (w_x, w_y), w_img)

    # Text
    draw = ImageDraw.Draw(img)
    text_font = get_label_font(int(height * 0.28))
    sub_font = get_label_font(int(height * 0.12), bold=False)

    text_x = w_x + w_size + int(width * 0.02)

    if dark_bg:
        text_color = (255, 255, 255, 255)
        sub_color = (255, 255, 255, 140)
    else:
        text_color = (26, 35, 50, 255)
        sub_color = (100, 110, 130, 200)

    # WINCASE
    text_y = height // 2 - int(height * 0.22)
    draw.text((text_x, text_y), "WINCASE", fill=text_color, font=text_font)

    # Immigration Bureau
    sub_y = height // 2 + int(height * 0.08)
    draw.text((text_x, sub_y), "Immigration Bureau", fill=sub_color, font=sub_font)

    return img


def create_logo_icon_dark(size=200):
    """Gold W in dark navy rounded square (for login screen)."""
    img = Image.new('RGBA', (size, size), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)

    radius = int(size * 0.22)
    draw.rounded_rectangle([0, 0, size - 1, size - 1], radius=radius, fill=(15, 25, 40, 255))

    w_img = create_logo_w(int(size * 0.7))
    offset = (size - int(size * 0.7)) // 2
    img.paste(w_img, (offset, offset), w_img)

    return img


def create_logo_icon_light(size=200):
    """Gold W in white rounded square (for client login)."""
    img = Image.new('RGBA', (size, size), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)

    radius = int(size * 0.22)
    draw.rounded_rectangle([0, 0, size - 1, size - 1], radius=radius, fill=(255, 255, 255, 255))
    draw.rounded_rectangle([0, 0, size - 1, size - 1], radius=radius, outline=(220, 220, 225, 255), width=2)

    w_img = create_logo_w(int(size * 0.7))
    offset = (size - int(size * 0.7)) // 2
    img.paste(w_img, (offset, offset), w_img)

    return img


def main():
    print("=== WinCase Internal Logos ===\n")

    # 1. Gold W icon (transparent bg) - multiple sizes
    for sz in [64, 128, 256]:
        w = create_logo_w(sz)
        for out in [OUT_STAFF, OUT_CLIENT]:
            path = os.path.join(out, f"logo_w_{sz}.png")
            w.save(path, "PNG")
        print(f"  logo_w_{sz}.png")

    # 2. Dark icon (navy bg + gold W) - for Staff login
    icon_dark = create_logo_icon_dark(200)
    icon_dark.save(os.path.join(OUT_STAFF, "logo_icon_dark.png"), "PNG")
    icon_dark.save(os.path.join(OUT_CLIENT, "logo_icon_dark.png"), "PNG")
    print("  logo_icon_dark.png")

    # 3. Light icon (white bg + gold W) - for Client login
    icon_light = create_logo_icon_light(200)
    icon_light.save(os.path.join(OUT_STAFF, "logo_icon_light.png"), "PNG")
    icon_light.save(os.path.join(OUT_CLIENT, "logo_icon_light.png"), "PNG")
    print("  logo_icon_light.png")

    # 4. Full logo (transparent bg) - for splash/about
    full = create_logo_full(512, 160, dark_bg=False)
    for out in [OUT_STAFF, OUT_CLIENT]:
        full.save(os.path.join(out, "logo_full.png"), "PNG")
    print("  logo_full.png")

    # 5. Full logo dark bg
    full_dark = create_logo_full(512, 160, dark_bg=True)
    for out in [OUT_STAFF, OUT_CLIENT]:
        full_dark.save(os.path.join(out, "logo_full_dark.png"), "PNG")
    print("  logo_full_dark.png")

    print(f"\nStaff: {OUT_STAFF}/")
    print(f"Client: {OUT_CLIENT}/")
    print("Done!")


if __name__ == "__main__":
    main()
