#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
WinCase SAFE Content Replacement Script
Only replaces TEXT inside existing tags. Never touches HTML structure.
"""
import os, sys

FILE = '/home/wincase/public_html/index.html'

with open(FILE, 'r', encoding='utf-8') as f:
    c = f.read()

ok = 0
miss = 0

def r(old, new, label=''):
    global c, ok, miss
    if old in c:
        n = c.count(old)
        c = c.replace(old, new)
        print(f'  [OK] ({n}x) {label}')
        ok += 1
    else:
        print(f'  [MISS] {label}')
        miss += 1

def r1(old, new, label=''):
    global c, ok, miss
    if old in c:
        c = c.replace(old, new, 1)
        print(f'  [OK] {label}')
        ok += 1
    else:
        print(f'  [MISS] {label}')
        miss += 1

# ============================================================
# LOGO COLOR: gold → blue
# ============================================================
r('color:#c8a96e', 'color:#2563eb', 'Logo gold→blue')

# ============================================================
# PHONE: +48 579 266 493 → +48 739 581 300
# ============================================================
r('+48 579 266', '+48 739 581', 'Phone prefix')
r('493</a>', '300</a>', 'Phone suffix')

# ============================================================
# NAV MENU TEXT (keep structure, change text only)
# ============================================================
# Main items
r1('>Home</a>', '>Home</a>', 'Nav: Home (keep)')
r('>About</a>', '>About</a>', 'Nav: About (keep)')
# Sub-menus text (rename existing items)
r('>Home One</a>', '>Main Page</a>', 'Sub: Home One')
r('>Home Two</a>', '>About Us</a>', 'Sub: Home Two')
r('>Home Three</a>', '>Our Team</a>', 'Sub: Home Three')
r('>One Page Style1</a>', '>Careers</a>', 'Sub: OnePage1')
r('>One Page Style2</a>', '>Partners</a>', 'Sub: OnePage2')
r('>One Page Style3</a>', '>News</a>', 'Sub: OnePage3')
r('>Services</a>', '>Services</a>', 'Nav: Services (keep)')
r('>Criminal Case</a>', '>Work Permits</a>', 'Sub: Criminal→WorkPermits')
r('>Family Violence</a>', '>Residence Cards</a>', 'Sub: Family→Residence')
r('>Business Law</a>', '>Visa Services</a>', 'Sub: Business→Visa')
r('>Health Care Law</a>', '>Business Setup</a>', 'Sub: Health→Business')
r('>Insurance Law</a>', '>Citizenship</a>', 'Sub: Insurance→Citizenship')
r('>Real Estate Law</a>', '>Family Reunification</a>', 'Sub: RealEstate→Family')
r('>Pages</a>', '>Results</a>', 'Nav: Pages→Results')
r('>Case</a>', '>Success Stories</a>', 'Sub: Case→Success')
r('>Case Details</a>', '>Client Reviews</a>', 'Sub: CaseDetails→Reviews')
r('>Team</a>', '>Our Process</a>', 'Sub: Team→Process')
r('>Price</a>', '>Pricing</a>', 'Sub: Price→Pricing')
r('>Faq</a>', '>FAQ</a>', 'Sub: Faq→FAQ')
r('>Blog</a>', '>News</a>', 'Nav: Blog→News')
r('>Blog Details</a>', '>Article</a>', 'Sub: BlogDetails→Article')
r('>Contact</a>', '>Contact</a>', 'Nav: Contact (keep)')

# ============================================================
# HEADER BUTTON
# ============================================================
# Careful: "Learn More" appears in header, banner, about section
# Header (inside thm-btn): change first occurrence
r1("""                                                Learn More
                                                <span class="thm-btn__icon">""",
    """                                                Free Consultation
                                                <span class="thm-btn__icon">""",
    'Header btn→Free Consultation')

# ============================================================
# BANNER
# ============================================================
r('BEST LAW FIRM <br>\n                                SINCE 1980',
  'IMMIGRATION <br>\n                                BUREAU WARSAW',
  'Banner headline')

# Banner paragraph (multi-line)
r('Fill unto likeness had shall is herb air set midst land in meat green had creepeth days\n                                bearing winged together malea moving also two replenish spirit set moving. All moving\n                                give\n                                form deep upon grass man it fread creepeth moveth.',
  'We help foreigners legalize their stay in Poland. Work permits, residence cards, visas, citizenship — over 10,000 people trusted WinCase with their future. Professional support at every stage of your immigration journey.',
  'Banner paragraph')

# Banner btn
r1('>Learn More <span class="icon-icon-8"></span></a>',
   '>Book Consultation <span class="icon-icon-8"></span></a>',
   'Banner btn→Book Consultation')

# ============================================================
# FEATURES (4 boxes)
# ============================================================
r('our best features', 'our services', 'Features heading')

r('Satisfied legal <br>\n                                        defense',
  'Work Permits <br>\n                                        &amp; Employment',
  'Feature 1')

r('Legal advice <br>\n                                        service',
  'Residence Cards <br>\n                                        &amp; Stay',
  'Feature 2')

r('high skilled <br>\n                                        lawyer',
  'Visa Services <br>\n                                        &amp; Travel',
  'Feature 3')

r('online client <br>\n                                        support',
  'Citizenship <br>\n                                        &amp; Naturalization',
  'Feature 4')

# ============================================================
# ABOUT SECTION
# ============================================================
r('Compassion for <br>\n                                    Representation <br>\n                                    Passion in Justice',
  'Win Your Case <br>\n                                    Immigration <br>\n                                    Bureau in Warsaw',
  'About headline')

r1('Fill unto likeness had shall is herb air set midst land in meat green had creepeth\n                                    days bearing winged together malea moving also two replenish spirit set moving. All\n                                    moving give form deep upon grass man it fread',
   'I quit my job and decided to create my own company. I wanted to build something strong and help people who struggle in a foreign country without knowing the language or laws. Because I was once in their situation and deeply understood their problems.',
   'About paragraph')

# About section btn
r1("""                                        Learn More
                                        <span class="thm-btn__icon">""",
   """                                        About Us
                                        <span class="thm-btn__icon">""",
   'About btn→About Us')

r('Hector Scudder, CEO', 'Founder, WinCase', 'CEO name')

# ============================================================
# COUNTERS
# ============================================================
r('data-count="245"', 'data-count="10000"', 'Counter 245→10000')
r('Global total Platform', 'People Helped', 'Counter label 1')
r('data-count="45"', 'data-count="7"', 'Counter 45→7')
r('<span\n                                            class="k">k</span>', '<span\n                                            class="k"></span>', 'Remove k')
r('Total Case Solved', 'Years Experience', 'Counter label 2')
r('data-count="552"', 'data-count="8"', 'Counter 552→8')
r('Global Award win', 'Languages We Speak', 'Counter label 3')

# ============================================================
# WHY CHOOSE US
# ============================================================
r('why choose us', 'why choose us', 'Why choose (keep)')
r('best lawyer make <br>\n                                        better justice',
  'trusted advisor for <br>\n                                        your immigration case',
  'Why headline')

r1('Likeness had fruit moved herb you\'re earth sea dry creature own man give It fish\n                                        cattle they are very could male give.',
   'With 7 years of experience and over 10,000 successful cases, WinCase is a leading immigration bureau in Warsaw. We provide comprehensive support at every stage.',
   'Why paragraph')

r('Highly skilled lawyer squad', 'Individual approach to every client', 'Why bullet 1')
r("Committed to bringing client's justice", 'Support available evenings &amp; weekends', 'Why bullet 2')

r1("""let's work""", """Get Started""", 'Why CTA')

# ============================================================
# SERVICES
# ============================================================
r('popular services', 'how we help', 'Services label')
r('Everyone deserves legal <br>\n                            best assistance',
  'Comprehensive immigration <br>\n                            services in Poland',
  'Services heading')

# Service list items (4)
r1('>family violence</a>', '>Work Permits</a>', 'Service 1 title')
r1('>criminal law</a>', '>Residence Cards</a>', 'Service 2 title')
r1('>insurance law</a>', '>Visa Services</a>', 'Service 3 title')
r1('>domestic law</a>', '>Business Setup</a>', 'Service 4 title')

# Service descriptions
r('Likeness had fruit moved herb earth sea dry',
  'Professional assistance at every stage',
  'Service descriptions')

# ============================================================
# CASE STUDIES
# ============================================================
r('latest case studies', 'our results', 'Cases label')
r('recent case studies <br>\n                                    showcase for <br>\n                                    our happy victim',
  'real success stories <br>\n                                    from our <br>\n                                    happy clients',
  'Cases heading')

r1('business / public', 'work permit', 'Case cat 1')
r1('>public company case</a>', '>residence card approved</a>', 'Case title 1')

# Family / domestic appears twice - handle carefully
r1('Family / domestic', 'student visa', 'Case cat 2')
r1('>Family violence case </a>', '>study visa approved fast</a>', 'Case title 2')

r1('corporate / tax', 'family reunification', 'Case cat 3')
r1('>business tax consultancy</a>', '>family residence cards</a>', 'Case title 3')
r1('>all project<', '>all cases<', 'Cases CTA')

# Second Family / domestic
r1('Family / domestic', 'citizenship', 'Case cat 4')
r1('>marriage agreement</a>', '>Polish citizenship granted</a>', 'Case title 4')

# ============================================================
# TESTIMONIALS
# ============================================================
r('free appointment', 'free consultation', 'Consult form title')
r('>YOUR QUERY*</option>', '>Select Service*</option>', 'Query label')
r('>Digital Marketing</option>', '>Work Permit</option>', 'Query opt 1')
r('>Digital Experience</option>', '>Residence Card</option>', 'Query opt 2')
r('>Web applications</option>', '>Visa Application</option>', 'Query opt 3')
r('>Web development</option>', '>Citizenship</option>', 'Query opt 4')

r('our testimonials', 'client reviews', 'Testimonials label')
r('clients feedback', 'what our clients say', 'Testimonials heading')

# 3 testimonial texts (all identical in template)
test_old = 'Have the of third divide foreign bring give void rise\n                                                        you\'ll grass ton fowl forth morning gathering main\n                                                        also evening were intro the yielding spirit called be\n                                                        form grass face into begger you is.'

r1(test_old,
   'WinCase helped me get my residence card in just 2 months. Professional team, clear communication, and they were available even on weekends. Highly recommend!',
   'Testimonial 1 text')

r1(test_old,
   'I was afraid I would lose my legal status, but WinCase submitted my application just in time. They handled everything professionally and kept me informed at every step.',
   'Testimonial 2 text')

r1(test_old,
   'Thanks to WinCase, my whole family now has residence cards in Poland. The process was smooth and stress-free. The best immigration bureau in Warsaw!',
   'Testimonial 3 text')

# Names and roles (3 each, all identical)
r1('>Richard Markram</h3>', '>Maria S.</h3>', 'Testimonial name 1')
r1('>Richard Markram</h3>', '>Raj P.</h3>', 'Testimonial name 2')
r1('>Richard Markram</h3>', '>Olena K.</h3>', 'Testimonial name 3')

r1('>family law case</p>', '>residence card client</p>', 'Testimonial role 1')
r1('>family law case</p>', '>work permit client</p>', 'Testimonial role 2')
r1('>family law case</p>', '>family reunification</p>', 'Testimonial role 3')

# ============================================================
# TEAM → OUR PROCESS
# ============================================================
r('meet our lawyer', 'how it works', 'Team label')
r('experienced attorneys', 'simple steps to success', 'Team heading')

r1('>Pamela Lasen</a>', '>Free Consultation</a>', 'Step 1 name')
r1('>Beverly Daniels</a>', '>Document Preparation</a>', 'Step 2 name')
r1('>Fred Vaughan</a>', '>Application Submission</a>', 'Step 3 name')

r1('>Senior partner</p>', '>Step 1</p>', 'Step 1 role')
r1('>Senior partner</p>', '>Step 2</p>', 'Step 2 role')
r1('>Senior partner</p>', '>Step 3</p>', 'Step 3 role')

# ============================================================
# CTA
# ============================================================
r('have any query <br>\n                        contact us',
  'ready to start? <br>\n                        book free consultation',
  'CTA heading')

r1("""                            contact with us
                            <span class="thm-btn__icon">""",
   """                            contact us now
                            <span class="thm-btn__icon">""",
   'CTA button')

# ============================================================
# FOOTER
# ============================================================
r('info@wincase.eu', 'biuro@wincase.eu', 'Footer email')

# ============================================================
# META
# ============================================================
r('<meta name="description" content="">',
  '<meta name="description" content="WinCase Immigration Bureau in Warsaw. Work permits, residence cards, visas, citizenship. Over 10,000 people helped. Free consultation.">',
  'Meta description')

# ============================================================
# DONE
# ============================================================
with open(FILE, 'w', encoding='utf-8') as f:
    f.write(c)

print(f'\nDone! OK: {ok}, MISS: {miss}')
print(f'File: {FILE}')
