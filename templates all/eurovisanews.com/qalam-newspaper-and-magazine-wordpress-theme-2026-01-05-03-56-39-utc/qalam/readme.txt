=== Qalam ===
Author: Saurabh Sharma
Requires at least: WordPress 5.0
Tested up to: WordPress 6.9
Version: 2.4.0
Tags: one-column, two-columns, right-sidebar, flexible-header, accessibility-ready, custom-colors, custom-header, custom-menu, custom-logo, editor-style, featured-images, footer-widgets, post-formats, rtl-language-support, sticky-post, theme-options, threaded-comments, translation-ready


== Description ==

Qalam is an all purpose WordPress theme designed for online magazine, newspaper, blog and editorial websites. With a focus on magazine sites, it features comprehensive tools and options for creating post modules in pages. You can personalize the theme with multi-columnar layouts, custom colors, unlimited post modules and customizable font options. The theme works great in many languages, for any abilities, and on any device.


== Installation ==

1. In your admin panel, go to Appearance -> Themes and click the 'Add New' button.
2. Click on 'Upload Theme' button. Next, click on the 'Choose File' button and browse qalam.zip file from your computer. (Make sure you have downloaded installable WordPress file from your themeforest account's downloads section).
3. Click on 'Install Now'.
4. Click on the 'Activate' button to use your new theme right away.
5. Navigate to Appearance > Customize in your admin panel and customize to taste.

== Theme Documentation ==
https://intothedesign.com/docs/qalam/index.html

== How to update the Theme =

1. For automatic updates, install the Envato Market Plugin:
https://envato.com/market-plugin/ . See plugin page for installation instructions.

2. For manual update, download latest installable theme archive from your themeforest account's downloads page. Next, upload the 'qalam' folder to /wp-content/themes/ directory of your website using FTP or File Manager, replacing the old one.

3. If there is any update in WP Post Modules Plugin, you will see a dashboard notice prompting to update the plugin to latest version. Click on "Begin updating plugins". If you do not see the dashboard link, then you can manually update the plugin by uploading it from your theme folder qalam/plugins/wp-post-modules-el.zip file.


== Changelog ==

= 2.4.0 =
* Updated bundled WP Post Modules (Elementor) plugin to v2.7.0
* Updated Fontawesome free icons to v7.1.0
* Elementor tested upto 3.34.0
* Ensured compatibility upto WordPress 6.9 and php 8.3.28
* Fixed: Deprecated HTML5 Shiv support
* Fixed: Kirki config causing "translation was triggered too early" warning

= 2.3.0 =
* Updated bundled WP Post Modules (Elementor) plugin to v2.6.2
* Updated Fontawesome free icons to v6.7.2
* Elementor tested upto 3.29.2
* Ensured compatibility upto WordPress 6.8.1 and php 8.2.0

= 2.2.0 =
* Added support for post labels
    - For usage instructions, please see the documentation's 'FAQ section at
      https://intothedesign.com/docs/qalam/index.html#frequently_asked_questions

* Updated bundled WP Post Modules (Elementor) plugin to v2.6.0
    - See WP Post Modules > Style > Title > Post Labels
    - Post labels are custom fields added in a post which can be displayed along with the title
      E.g. Live News, Breaking, Offer, Promoted, etc.
    - For usage instructions, please see the documentation/index.html file's FAQ section.
* Elementor tested upto 3.24.7
* Ensured compatibility upto WordPress 6.6.2  

= 2.1.1 =
* Updated bundled WP Post Modules plugin to v2.5.1
    - Fixed: Elementor inline styling not working
    Note: Edit and update the pages on which WP Post Modules is used. This shall fix the broken style issue.
* Ensured compatibility with WordPress 6.6.1 and Elementor 3.23.x  

= 2.1.0 =
* Added Threads social sharing in the following sections
    - Header social links
    - Archive social sharing
    - Single post social sharing
    - Single post author bio
    - WP Post Modules social sharing
* Updated Fontawesome free icons to v6.5.1
* Ensured compatibility with Kirki Customizer 5.1.0
* Ensured compatibility with WordPress 6.4.3 and Elementor 3.20.x
* Updated bundled WP Post Modules plugin to v2.5.0
    - See plugin's readme.txt file for update details

= 2.0.1 =
* Fixed: Twitter logo not updated in top bar social icons

= 2.0.0 =
* Replaced twitter social sharing icons with the new "X" icon
* Updated Fontawesome free icons to v6.4.2
* Ensured compatibility with Kirki Customizer 5.0.0
* Ensured compatibility with WordPress 6.4.1 and Elementor 3.17.x
* Updated bundled WP Post Modules plugin to v2.4.0
    - See plugin's readme.txt file for update details


= 1.9.0 =
* Ensured compatibility with Kirki Customizer 4.1.1
* Ensured compatibility with WordPress 6.2 and Elementor 3.12.x
* Tested and ensured compatibility with php 8.1.13
* WP Post Modules update
    - Updated deprecated Elementor hooks to the latest ones
    - Moved image align and margin/padding options into the "Images" section
    - Toggle switches with Fontawesome icons updated to the Elementor icons
        - E.g. Image align toggle, title align toggle.
    - Fixed: Category links "more" dropdown showing 1 extra count

= 1.8.0 =
* Ensured compatibility with Kirki Customizer 4.0.24
    - Fixed select dropdown fields showing multi select option
    - Fixed php warning caused by multi select option
* Ensured compatibility with WordPress 6.1.1 and Elementor 3.8.x
* Ensured compatibility with php 8.0.0

= 1.7.0 =
* Added wp_body_open hook with backward compatibility
* WP Post Module updates
    - Added option for specifying "No posts found" text
        - See WP Post Modules > Content > Query > No posts found text
    - Minified and reduced file size of frontend JavaScript file
    - Added Elementor specific keywords for the widget
        - Typing any of the following keywords will show the widget in the editor:
      posts, post grid, post list, query loop, blog posts, portfolio
    - Ensured compatibility with WordPress 5.9.2 and Elementor 3.6.x  

= 1.6.0 =
* Fixed: Single post social buttons not showing on mobile
* WP Post Modules Plugin Updates
    - Added "Filter by time period" option
        - Filter posts by today, yesterday, last 7 days, current month, previous month, current year and previous year.
        - WP Post Modules > Content > Query > Filter by time period
    - Fixed: Deprecated functions of Elementor widget replaced by latest ones
    - Ensured compatibility with WordPress 5.8.2 and Elementor 3.4.x

= 1.5.1 =
* WP Post Modules Plugin Updates
* Fixed: Video embeds should show when "Show Embed" is enabled
    - Earlier it required to set post format as "Video"
    - Now it works for "Standard" post format too
* Fixed: Added default width and height for images if no dimensions are provided in module settings
* Tweak: The "BFI Thumb" and "Hard Crop" now set to 'false' by default

= 1.5.0 =
* WP Post Modules Plugin Updates
    - Added option for 'Filter by Author' on Author archives page
        - See WP Post Modules > Content > Query > Author archive filtering
        (This option is useful when module shortcode is placed in widget areas on author archive page. Module shortcode can be generated using Elementor Pro or Anywhere Elementor plugin ).
    - Added option for left/center/right alignment of Ajax Navigation buttons
        - See WP Post Modules > Style > Ajax Options > Navigation Align
    - Added absolute position option for Author Avatar
        - See WP Post Modules > Style > Post Meta > Avatar Position Absolute
    - Fixed: php notice for undefined publisher logo
    - Tweak: Removed overflow: hidden from the wppm-grid container
        - Using a box shadow on the container causes shadow to clip. Now it is fixed.
    * Important: Please clear browser caches after updating the plugin

= 1.4.0 =
* WP Post Modules Plugin Updates
    - Added option for equal height columns
      - See WP Post Modules > Style > Display > Equal Height Columns
    - Added options for controlling author avatar size, margin and border radius
      - See WP Post Modules > Style > Post Meta > Author Avatar (Enable this toggle to see more options below)
    - Fixed: Content box shadow hiding within parent containers
    - Added text shadow option for post titles
      - See WP Post Modules > Style > Title > Text Shadow
    - Ensured compatibility with WordPress 5.5

= 1.3.0 =
* Added sticky navbar support for tablet and mobile
* Fixed: Flex property for site branding logo set to 1 0 auto;

= 1.2.8 =
* Fixed: List style archive posts overlapping sidebar content

= 1.2.7 =
* Added vendor specific CSS Flexbox properties for cross-browser compatibility

= 1.2.6 =
* Fixed: Site title color not honoring customizer setting
* Fixed: Twitter share code updated as per latest Twitter API

= 1.2.5 =
* Fixed: Breadcrumb schema error on category archive pages

= 1.2.4 =
* Fixed: CSS "word-break: break-all" property causing unwanted word breaks
    - Changed to "word-break: normal"

= 1.2.3 =
* Fixed: Validation errors in breadcrumbs schema markup
* Fixed: Post Modules Ticker not working in RTL layout
    - Update the WP Post Modules Plugin (included)
* Fixed: Scroll to top button position in RTL layout
* Fixed: Replaced "break-word" deprecated property with "break-all" for word-break

= 1.2.2 =
* Fixed: Main menu not showing in iPad pro
* Fixed: Search form misaligned in header style 3

= 1.2.1 =
* Added option for hiding post meta on single posts
    - See Appearance > Customize > Single Posts > Show post meta

= 1.2.0 =
* WP Post Modules Plugin update
* Added custom field option for image source
    - See WP Post Modules > Style > Images > Image Source
* Added link options for post image
    - See WP Post Modules > Style > Images > Image Link
    - Choose as permalink, media file or none
* Added lightbox support for post images
    - See WP Post Modules > Style > Images > Image Link (Choose Media File) > Lightbox
* Added custom field option for post title
    See WP Post Modules > Style > Title > Title Source
* Ensured compatibility with Advanced Custom Fields Plugin
    - Custom fields generated via ACF supported on post image, title and excerpt

= 1.1.0 =
* Added 2 new Home page layouts
    - Import using /dummy_data/demos/demo_11.json and demo_12.json
* Added responsive controls in WP Post Modules plugin for the following settings:
    - Category links padding and margin
    - Ajax Load More button padding and margin
    - Ajax loadmore button border radius
    - Ajax navigation buttons padding and margin
    - Post title margin
    - Excerpt Margin
    - Category links row margin
    - Post meta row margin
    - Social links outer margin (inline style)
* Added span and sup tag style support inside navigation links
    - Example usage: Services <sup>New</sup>
* Fixed: WooCommerce registration page columns not aligned in one row
* Ensured compatibility with WooCommerce 3.7.0

= 1.0.0 =
Initial release


== Credits ==

Qalam bundles the following third-party resources:

HTML5 Shiv, Copyright 2014 Alexander Farkas
Licenses: MIT/GPL2
Source: https://github.com/aFarkas/html5shiv

normalize.css, Copyright 2012-2016 Nicolas Gallagher and Jonathan Neal
License: MIT
Source: https://necolas.github.io/normalize.css/

Font Awesome icons, Copyright Dave Gandy
License: SIL Open Font License, version 1.1.
Source: http://fontawesome.io/
