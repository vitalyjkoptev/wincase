#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
WinCase Content Replacement Script
Replaces text content in /home/wincase/public_html/index.html
Only text replacements — no HTML structure changes.

Run on the server:
    python3 /path/to/replace_content.py
"""

import os
import sys
import shutil
from datetime import datetime

FILE_PATH = '/home/wincase/public_html/index.html'
BACKUP_PATH = FILE_PATH + '.bak.' + datetime.now().strftime('%Y%m%d_%H%M%S')


def replace_once(content, old, new, label=''):
    """Replace exactly the first occurrence of old with new. Warn if not found."""
    if old in content:
        result = content.replace(old, new, 1)
        print(f'  [OK] {label or repr(old[:70])}')
        return result
    else:
        print(f'  [MISS] Not found: {label or repr(old[:70])}')
        return content


def replace_all(content, old, new, label=''):
    """Replace all occurrences of old with new. Warn if not found."""
    if old in content:
        count = content.count(old)
        result = content.replace(old, new)
        print(f'  [OK] ({count}x) {label or repr(old[:70])}')
        return result
    else:
        print(f'  [MISS] Not found: {label or repr(old[:70])}')
        return content


def main():
    if not os.path.exists(FILE_PATH):
        print(f'ERROR: File not found: {FILE_PATH}')
        sys.exit(1)

    shutil.copy2(FILE_PATH, BACKUP_PATH)
    print(f'Backup created: {BACKUP_PATH}\n')

    with open(FILE_PATH, 'r', encoding='utf-8') as f:
        content = f.read()

    original = content

    # =========================================================================
    # NAVIGATION MENU
    # Replace the entire <ul class="navigation"> ... </ul> block
    # (Use first </ul> after the opening tag — works for flat nav lists)
    # =========================================================================
    nav_open = '<ul class="navigation">'
    if nav_open in content:
        start = content.index(nav_open)
        end = content.index('</ul>', start) + len('</ul>')
        new_nav = (
            '<ul class="navigation">\n'
            '                        <li><a href="index.html">Home</a></li>\n'
            '                        <li><a href="about.html">About</a></li>\n'
            '                        <li class="dropdown"><a href="#">Services</a>\n'
            '                            <ul>\n'
            '                                <li><a href="work-permits.html">Work Permits</a></li>\n'
            '                                <li><a href="residence-cards.html">Residence Cards</a></li>\n'
            '                                <li><a href="visa-services.html">Visa Services</a></li>\n'
            '                                <li><a href="business-setup.html">Business Setup</a></li>\n'
            '                                <li><a href="citizenship.html">Citizenship</a></li>\n'
            '                            </ul>\n'
            '                        </li>\n'
            '                        <li><a href="faq.html">FAQ</a></li>\n'
            '                        <li><a href="contact.html">Contact</a></li>\n'
            '                    </ul>'
        )
        content = content[:start] + new_nav + content[end:]
        print('  [OK] Navigation menu block replaced')
    else:
        print('  [MISS] <ul class="navigation"> not found')

    # =========================================================================
    # LOGO COLOR (inline style — all occurrences)
    # =========================================================================
    content = replace_all(content, 'color:#c8a96e', 'color:#2563eb', 'Logo color gold→blue')

    # =========================================================================
    # HEADER PHONE
    # =========================================================================
    content = replace_all(content, '+48 579 266', '+48 739 581', 'Phone prefix')
    content = replace_all(content, '493</a>', '300</a>', 'Phone suffix')

    # =========================================================================
    # HEADER BUTTON
    # The header CTA button says "Learn More" — replace it before the banner
    # "Learn More" so we can handle them differently.
    # The header button is typically wrapped like: >Learn More</a> or >Learn More</span>
    # We do NOT want to change the banner "Learn More" here — we do it separately.
    # Strategy: replace the first occurrence of ">Learn More<" → Free Consultation,
    # then replace the remaining one in the banner → Book Consultation.
    # =========================================================================
    learn_more_tag = '>Learn More<'
    occ = content.count(learn_more_tag)
    if occ >= 2:
        content = replace_once(content, learn_more_tag, '>Free Consultation<', 'Header "Learn More" → Free Consultation')
        content = replace_once(content, learn_more_tag, '>Book Consultation<', 'Banner "Learn More" → Book Consultation')
    elif occ == 1:
        content = replace_once(content, learn_more_tag, '>Free Consultation<', '"Learn More" (only one found) → Free Consultation')
    else:
        print('  [MISS] Not found: ">Learn More<"')

    # =========================================================================
    # BANNER SECTION
    # =========================================================================
    content = replace_all(
        content,
        'BEST LAW FIRM <br>\n                                SINCE 1980',
        'IMMIGRATION <br>\n                                BUREAU WARSAW',
        'Banner headline'
    )
    content = replace_all(
        content,
        'Fill unto likeness had shall',
        'We help foreigners legalize their stay in Poland.'
        ' Work permits, residence cards, visas, citizenship'
        ' \u2014 over 10,000 people trusted WinCase with their future.'
        ' 7 years of experience.',
        'Banner paragraph'
    )

    # =========================================================================
    # FEATURES SECTION (4 boxes)
    # =========================================================================
    content = replace_all(content, 'our best features', 'our services', 'Features section heading')
    content = replace_all(
        content,
        'Satisfied legal <br>\n                                        defense',
        'Work Permits <br>\n                                        &amp; Employment',
        'Feature box 1'
    )
    content = replace_all(
        content,
        'Legal advice <br>\n                                        service',
        'Residence Cards <br>\n                                        &amp; Stay',
        'Feature box 2'
    )
    content = replace_all(
        content,
        'high skilled <br>\n                                        lawyer',
        'Visa Services <br>\n                                        &amp; Travel',
        'Feature box 3'
    )
    content = replace_all(
        content,
        'online client <br>\n                                        support',
        'Citizenship <br>\n                                        &amp; Naturalization',
        'Feature box 4'
    )

    # =========================================================================
    # ABOUT SECTION
    # =========================================================================
    content = replace_all(
        content,
        'Compassion for <br>\n                                    Representation <br>\n                                    Passion in Justice',
        'Win Your Case <br>\n                                    Immigration <br>\n                                    Bureau in Warsaw',
        'About headline'
    )
    # About paragraph — try both common Lavale lorem starters
    about_new = (
        'I quit my job and decided to create my own company.'
        ' I wanted to build something strong and ambitious'
        ' \u2014 and most importantly, help people who struggle in a foreign country'
        ' without knowing the language or laws.'
        ' Because I was once in their situation myself and deeply understood their problems.'
    )
    for variant in [
        'Fill unto likeness had fruit',
        'Likeness had fruit moved god',
        'Had fruit moved god',
    ]:
        if variant in content:
            content = replace_all(content, variant, about_new, f'About paragraph ({repr(variant[:40])})')
            break

    content = replace_all(content, 'Hector Scudder, CEO', 'Founder, WinCase', 'CEO name')

    # =========================================================================
    # COUNTERS
    # =========================================================================
    content = replace_all(content, 'data-count="245"', 'data-count="10000"', 'Counter 245→10000')
    content = replace_all(content, 'Global total Platform', 'People Helped', 'Counter label 1')
    content = replace_all(content, 'data-count="45"', 'data-count="7"', 'Counter 45→7')
    content = replace_all(content, '<span class="k">k</span>', '<span class="k"></span>', 'Remove "k" after counter')
    content = replace_all(content, 'Total Case Solved', 'Years Experience', 'Counter label 2')
    content = replace_all(content, 'data-count="552"', 'data-count="8"', 'Counter 552→8')
    content = replace_all(content, 'Global Award win', 'Languages We Speak', 'Counter label 3')

    # =========================================================================
    # WHY CHOOSE US
    # =========================================================================
    content = replace_all(
        content,
        'best lawyer make <br>\n                                        better justice',
        'trusted advisor <br>\n                                        for your case',
        'Why choose us headline'
    )
    why_new = (
        'With 7 years of experience and over 10,000 successful cases,'
        ' WinCase is a leading immigration bureau in Warsaw.'
        ' We provide comprehensive support at every stage.'
    )
    for variant in [
        'Likeness had fruit moved',
        'Likeness had fruit god',
        'Had the of third divide',
    ]:
        if variant in content:
            content = replace_all(content, variant, why_new, f'Why choose us paragraph ({repr(variant[:40])})')
            break

    content = replace_all(content, 'Highly skilled lawyer squad', 'Individual approach to every client', 'Why bullet 1')
    content = replace_all(content, "Committed to bringing client's justice", 'Support available evenings &amp; weekends', 'Why bullet 2')
    content = replace_all(content, "let's work", 'Get Started', 'Why CTA button')

    # =========================================================================
    # SERVICES SECTION
    # IMPORTANT: Replace "Family violence case" BEFORE "family violence"
    # to avoid "family violence" being consumed first.
    # =========================================================================
    content = replace_all(content, 'popular services', 'how we help', 'Services section label')
    content = replace_all(
        content,
        'Everyone deserves legal <br>\n                            best assistance',
        'Comprehensive immigration <br>\n                            services in Poland',
        'Services section heading'
    )

    # Replace longer/more-specific strings first
    content = replace_all(content, 'Family violence case', 'study permit in 30 days', 'Case: Family violence case')
    content = replace_all(content, 'family violence', 'Work Permits', 'Service: family violence')
    content = replace_all(content, 'criminal law', 'Residence Cards', 'Service: criminal law')
    content = replace_all(content, 'insurance law', 'Visa Services', 'Service: insurance law')
    content = replace_all(content, 'domestic law', 'Business Setup', 'Service: domestic law')

    # Service paragraphs — Lavale repeats the same lorem for all 4 services.
    # Replace all 4 occurrences with distinct texts using indexed approach.
    svc_lorem = 'There are many variation of passages of Lorem Ipsum available'
    svc_texts = [
        'Employment legalization for foreigners in Poland',
        'Temporary and permanent residence permits',
        'National and Schengen visa applications',
        'Company registration and entrepreneur visas',
    ]
    if svc_lorem in content:
        for svc_text in svc_texts:
            content = replace_once(content, svc_lorem, svc_text, f'Service paragraph → {svc_text}')
    else:
        print(f'  [MISS] Service lorem paragraph not found: {repr(svc_lorem[:60])}')

    # =========================================================================
    # CASE STUDIES → SUCCESS STORIES
    # =========================================================================
    content = replace_all(content, 'latest case studies', 'our results', 'Case studies label')
    content = replace_all(
        content,
        'recent case studies <br>\n                                    showcase for <br>\n                                    our happy victim',
        'real success stories <br>\n                                    from our <br>\n                                    happy clients',
        'Case studies heading'
    )
    content = replace_all(content, 'business / public', 'work permit', 'Case category 1')
    content = replace_all(content, 'public company case', 'residence card approved', 'Case title 1')
    # "Family / domestic" appears twice: first → student visa, second → citizenship
    old_fd = 'Family / domestic'
    cnt_fd = content.count(old_fd)
    if cnt_fd >= 2:
        content = replace_once(content, old_fd, 'student visa', '"Family / domestic" 1st → student visa')
        content = replace_once(content, old_fd, 'citizenship', '"Family / domestic" 2nd → citizenship')
    elif cnt_fd == 1:
        content = replace_once(content, old_fd, 'student visa', '"Family / domestic" (only 1 found) → student visa')
    else:
        print(f'  [MISS] Not found: {repr(old_fd)}')
    content = replace_all(content, 'corporate / tax', 'family reunification', 'Case category 3')
    content = replace_all(content, 'business tax consultancy', 'family residence cards', 'Case title 3')
    content = replace_all(content, 'all project', 'all cases', 'Cases CTA')

    # =========================================================================
    # TESTIMONIALS
    # =========================================================================
    content = replace_all(content, 'free appointment', 'free consultation', 'Testimonials CTA')
    content = replace_all(content, 'Digital Marketing', 'Work Permit', 'Testimonial tag 1')
    content = replace_all(content, 'Digital Experience', 'Residence Card', 'Testimonial tag 2')
    content = replace_all(content, 'Web applications', 'Visa', 'Testimonial tag 3')
    content = replace_all(content, 'Web development', 'Citizenship', 'Testimonial tag 4')
    content = replace_all(content, 'our testimonials', 'client reviews', 'Testimonials section label')
    content = replace_all(content, 'clients feedback', 'what our clients say', 'Testimonials section heading')

    # Testimonial body text — 3 identical lorem paragraphs, replace in order
    lorem_test = 'Have the of third divide foreign'
    t_texts = [
        ('WinCase helped me get my residence card in just 2 months.'
         ' Professional team, clear communication, and they were available even on weekends.'
         ' Highly recommend!'),
        ('I was afraid I would lose my legal status, but WinCase submitted my application'
         ' just in time. They handled everything professionally and kept me informed at every step.'),
        ('Thanks to WinCase, my whole family now has residence cards in Poland.'
         ' The process was smooth and stress-free. The best immigration bureau in Warsaw!'),
    ]
    if lorem_test in content:
        for t in t_texts:
            content = replace_once(content, lorem_test, t, f'Testimonial body → {t[:50]}...')
    else:
        print(f'  [MISS] Testimonial lorem not found: {repr(lorem_test)}')

    # Testimonial names: 3 occurrences → Maria S., Raj P., Olena K.
    name_old = 'Richard Markram'
    name_new = ['Maria S.', 'Raj P.', 'Olena K.']
    for nn in name_new:
        content = replace_once(content, name_old, nn, f'Testimonial name → {nn}')

    # Testimonial roles: 3 occurrences
    role_old = 'family law case'
    role_new = ['residence card client', 'work permit client', 'family reunification']
    for rn in role_new:
        content = replace_once(content, role_old, rn, f'Testimonial role → {rn}')

    # =========================================================================
    # TEAM → OUR PROCESS
    # =========================================================================
    content = replace_all(content, 'meet our lawyer', 'how it works', 'Team section label')
    content = replace_all(content, 'experienced attorneys', '7 simple steps', 'Team section heading')
    content = replace_all(content, 'Pamela Lasen', 'Free Consultation', 'Team member 1 name')
    content = replace_all(content, 'Beverly Daniels', 'Document Preparation', 'Team member 2 name')
    content = replace_all(content, 'Fred Vaughan', 'Application Submission', 'Team member 3 name')
    # "Senior partner" x3 → Step 1, Step 2, Step 3
    for step in ['Step 1', 'Step 2', 'Step 3']:
        content = replace_once(content, 'Senior partner', step, f'Team role → {step}')

    # =========================================================================
    # CTA SECTION
    # =========================================================================
    content = replace_all(
        content,
        'have any query <br>\n                        contact us',
        'ready to start? <br>\n                        book free consultation',
        'CTA heading'
    )
    content = replace_all(content, 'contact with us', 'contact us now', 'CTA button')

    # =========================================================================
    # FOOTER
    # =========================================================================
    content = replace_all(content, 'info@wincase.eu', 'biuro@wincase.eu', 'Footer email')

    # =========================================================================
    # Final report
    # =========================================================================
    print()
    if content == original:
        print('WARNING: No changes were made. Check that strings match exactly.')
    else:
        with open(FILE_PATH, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Done. File written:  {FILE_PATH}')
        print(f'Backup at:           {BACKUP_PATH}')


if __name__ == '__main__':
    main()
