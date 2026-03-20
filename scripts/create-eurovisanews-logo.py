#!/usr/bin/env python3
"""Generate EuroVisaNews logo in SendPulse style — clean, modern, minimal"""

import subprocess
import os

# SVG Logo - Globe icon + clean text (SendPulse style)
logo_svg = '''<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 420 80" width="420" height="80">
  <defs>
    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#1a73e8;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#0d47a1;stop-opacity:1" />
    </linearGradient>
  </defs>

  <!-- Globe Icon -->
  <g transform="translate(8, 8)">
    <!-- Circle -->
    <circle cx="32" cy="32" r="30" fill="url(#grad1)" />

    <!-- Globe lines -->
    <ellipse cx="32" cy="32" rx="18" ry="30" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5"/>
    <ellipse cx="32" cy="32" rx="30" ry="18" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" transform="rotate(90, 32, 32)"/>
    <line x1="2" y1="32" x2="62" y2="32" stroke="rgba(255,255,255,0.35)" stroke-width="1.2"/>
    <line x1="32" y1="2" x2="32" y2="62" stroke="rgba(255,255,255,0.35)" stroke-width="1.2"/>

    <!-- Document/visa overlay -->
    <g transform="translate(20, 18)">
      <rect x="0" y="0" width="24" height="30" rx="2" fill="white" opacity="0.95"/>
      <rect x="4" y="5" width="16" height="2" rx="1" fill="#1a73e8" opacity="0.6"/>
      <rect x="4" y="10" width="12" height="2" rx="1" fill="#1a73e8" opacity="0.4"/>
      <rect x="4" y="15" width="14" height="2" rx="1" fill="#1a73e8" opacity="0.4"/>
      <circle cx="17" cy="23" r="4" fill="none" stroke="#1a73e8" stroke-width="1.2" opacity="0.5"/>
      <polyline points="15,23 16.5,25 19.5,21" fill="none" stroke="#0d47a1" stroke-width="1.3" opacity="0.7"/>
    </g>
  </g>

  <!-- Text: EuroVisa -->
  <text x="82" y="42" font-family="'Inter', 'Segoe UI', 'Helvetica Neue', Arial, sans-serif" font-size="32" font-weight="700" fill="#1a237e" letter-spacing="-0.5">
    EuroVisa
  </text>

  <!-- Text: News -->
  <text x="280" y="42" font-family="'Inter', 'Segoe UI', 'Helvetica Neue', Arial, sans-serif" font-size="32" font-weight="700" fill="#1a73e8" letter-spacing="-0.5">
    News
  </text>

  <!-- Tagline -->
  <text x="83" y="62" font-family="'Inter', 'Segoe UI', 'Helvetica Neue', Arial, sans-serif" font-size="12" font-weight="400" fill="#78909c" letter-spacing="2">
    EU VISA &amp; IMMIGRATION PORTAL
  </text>
</svg>'''

# Favicon SVG - just the globe icon
favicon_svg = '''<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64">
  <defs>
    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#1a73e8;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#0d47a1;stop-opacity:1" />
    </linearGradient>
  </defs>
  <circle cx="32" cy="32" r="30" fill="url(#grad1)" />
  <ellipse cx="32" cy="32" rx="18" ry="30" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5"/>
  <line x1="2" y1="32" x2="62" y2="32" stroke="rgba(255,255,255,0.35)" stroke-width="1.2"/>
  <line x1="32" y1="2" x2="32" y2="62" stroke="rgba(255,255,255,0.35)" stroke-width="1.2"/>
  <g transform="translate(20, 18)">
    <rect x="0" y="0" width="24" height="30" rx="2" fill="white" opacity="0.95"/>
    <rect x="4" y="5" width="16" height="2" rx="1" fill="#1a73e8" opacity="0.6"/>
    <rect x="4" y="10" width="12" height="2" rx="1" fill="#1a73e8" opacity="0.4"/>
    <rect x="4" y="15" width="14" height="2" rx="1" fill="#1a73e8" opacity="0.4"/>
    <circle cx="17" cy="23" r="4" fill="none" stroke="#1a73e8" stroke-width="1.2" opacity="0.5"/>
    <polyline points="15,23 16.5,25 19.5,21" fill="none" stroke="#0d47a1" stroke-width="1.3" opacity="0.7"/>
  </g>
</svg>'''

# Save SVGs
out_dir = "/tmp/eurovisanews-logos"
os.makedirs(out_dir, exist_ok=True)

with open(f"{out_dir}/logo.svg", "w") as f:
    f.write(logo_svg)

with open(f"{out_dir}/favicon.svg", "w") as f:
    f.write(favicon_svg)

print(f"SVG files saved to {out_dir}")

# Try to convert to PNG using different tools
for tool in ["rsvg-convert", "convert", "inkscape"]:
    try:
        if tool == "rsvg-convert":
            # Logo 840x160 (2x for retina)
            subprocess.run([
                "rsvg-convert", "-w", "840", "-h", "160",
                f"{out_dir}/logo.svg", "-o", f"{out_dir}/logo.png"
            ], check=True)
            # Favicon 512x512
            subprocess.run([
                "rsvg-convert", "-w", "512", "-h", "512",
                f"{out_dir}/favicon.svg", "-o", f"{out_dir}/favicon.png"
            ], check=True)
            print(f"✓ Converted to PNG using {tool}")
            break
        elif tool == "convert":
            subprocess.run([
                "convert", "-background", "none", "-density", "300",
                f"{out_dir}/logo.svg", "-resize", "840x160",
                f"{out_dir}/logo.png"
            ], check=True)
            subprocess.run([
                "convert", "-background", "none", "-density", "300",
                f"{out_dir}/favicon.svg", "-resize", "512x512",
                f"{out_dir}/favicon.png"
            ], check=True)
            print(f"✓ Converted to PNG using ImageMagick")
            break
        elif tool == "inkscape":
            subprocess.run([
                "inkscape", f"{out_dir}/logo.svg",
                "--export-type=png", f"--export-filename={out_dir}/logo.png",
                "-w", "840", "-h", "160"
            ], check=True)
            subprocess.run([
                "inkscape", f"{out_dir}/favicon.svg",
                "--export-type=png", f"--export-filename={out_dir}/favicon.png",
                "-w", "512", "-h", "512"
            ], check=True)
            print(f"✓ Converted to PNG using Inkscape")
            break
    except (subprocess.CalledProcessError, FileNotFoundError):
        continue
else:
    print("⚠ No SVG-to-PNG converter found locally. Will convert on server.")

print("Done!")
