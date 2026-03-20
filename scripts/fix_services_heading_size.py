#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Fix: Make "Comprehensive immigration services in Poland" heading smaller
so it stays on the blue background and doesn't overlap onto the photo.
Changes font-size from 50px to 38px for the services-one section heading.
"""
import sys

FILE = '/home/wincase/public_html/index.html'

with open(FILE, 'r', encoding='utf-8') as f:
    c = f.read()

# Add inline style to the specific h2 in services section
old = '<h2 class="">Comprehensive immigration <br>\n                            services in Poland'
new = '<h2 class="" style="font-size: 38px; line-height: 48px;">Comprehensive immigration <br>\n                            services in Poland'

if old in c:
    c = c.replace(old, new, 1)
    print('[OK] Services heading font-size: 50px → 38px')
else:
    # Try without class=""
    old2 = '<h2>Comprehensive immigration'
    if old2 in c:
        c = c.replace(old2, '<h2 style="font-size: 38px; line-height: 48px;">Comprehensive immigration', 1)
        print('[OK] Services heading font-size: 50px → 38px (alt match)')
    else:
        print('[MISS] Could not find services heading h2')
        sys.exit(1)

with open(FILE, 'w', encoding='utf-8') as f:
    f.write(c)

print('Done! Heading now fits within the blue background.')
