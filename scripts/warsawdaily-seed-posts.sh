#!/bin/bash
# =====================================================
# WarsawDaily.org — Initial content seeding script
# Creates 16 high-quality articles about Warsaw
# Run on VPS-2 (65.109.108.82)
# ssh root@65.109.108.82 'bash -s' < scripts/warsawdaily-seed-posts.sh
# =====================================================

WP="wp --path=/home/wdaily/public_html --allow-root"
AUTHOR=1  # wincase_admin

echo "=== Warsaw Daily Content Seeding ==="
echo ""

# Get category IDs
echo "[1/2] Fetching category IDs..."
get_cat_id() {
    $WP term list category --slug="$1" --field=term_id 2>/dev/null
}

CAT_CITY=$(get_cat_id "city-news")
CAT_EVENTS=$(get_cat_id "events")
CAT_BIZ=$(get_cat_id "business")
CAT_CULTURE=$(get_cat_id "culture-art")
CAT_FOOD=$(get_cat_id "food")
CAT_EXPAT=$(get_cat_id "expat-life")
CAT_TRANSPORT=$(get_cat_id "transport")
CAT_REALESTATE=$(get_cat_id "real-estate")

echo "  Categories: city=$CAT_CITY events=$CAT_EVENTS biz=$CAT_BIZ culture=$CAT_CULTURE"
echo "  food=$CAT_FOOD expat=$CAT_EXPAT transport=$CAT_TRANSPORT realestate=$CAT_REALESTATE"
echo ""

# Create posts
echo "[2/2] Creating articles..."

create_post() {
    local title="$1"
    local slug="$2"
    local cats="$3"
    local tags="$4"
    local content="$5"
    local excerpt="$6"

    local post_id=$($WP post create \
        --post_title="$title" \
        --post_name="$slug" \
        --post_content="$content" \
        --post_excerpt="$excerpt" \
        --post_status=publish \
        --post_author=$AUTHOR \
        --post_category="$cats" \
        --tags_input="$tags" \
        --porcelain 2>/dev/null)

    if [ -n "$post_id" ]; then
        echo "  ✓ #$post_id: $title"
    else
        echo "  ✗ FAILED: $title"
    fi
}

# ============================================================
# CITY NEWS
# ============================================================

create_post \
    "Warsaw Metro Line M3 Plans Unveiled: Connecting Praga to Wilanów" \
    "warsaw-metro-m3-plans-unveiled" \
    "$CAT_CITY,$CAT_TRANSPORT" \
    "metro,warsaw,transport,m3,infrastructure" \
'<!-- wp:paragraph -->
<p>Warsaw city officials have presented the preliminary plans for the much-anticipated Metro Line M3, which aims to connect the eastern districts of Praga with the rapidly developing southern area of Wilanów. The new line is expected to significantly reduce commuting times across the capital.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>According to the Warsaw City Hall announcement, the M3 line will feature 15 stations spanning approximately 19 kilometers. Construction is projected to begin in 2028, with the first segment operational by 2033. The total investment is estimated at 18 billion PLN, partially funded by EU cohesion grants.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Mayor Rafał Trzaskowski emphasized that the M3 will be a game-changer for public transport in Warsaw, particularly for residents of Praga-Południe and Gocław who currently face long bus commutes to reach the existing metro network. Urban planners also note that property values along the proposed route have already started to increase in anticipation.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The environmental impact study is currently underway, and public consultations are scheduled for the coming months. Residents can submit their feedback through the official Warsaw city portal.</p>
<!-- /wp:paragraph -->' \
    "Warsaw announces Metro Line M3 plans connecting Praga to Wilanów with 15 stations across 19km. Construction set to begin in 2028."


create_post \
    "Nowy Świat Street to Become Fully Pedestrianized Starting Summer 2026" \
    "nowy-swiat-pedestrianized-summer-2026" \
    "$CAT_CITY" \
    "nowy swiat,pedestrian zone,warsaw center,urban planning" \
'<!-- wp:paragraph -->
<p>In a landmark decision for Warsaw urban development, the city council has approved a plan to fully pedestrianize Nowy Świat street starting from June 2026. The iconic boulevard, already partially car-free on weekends, will become a permanent pedestrian zone extending from Rondo de Gaulle to Krakowskie Przedmieście.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The transformation includes new green spaces, expanded outdoor dining areas, and dedicated cycling lanes on parallel streets. City planners drew inspiration from similar successful projects in Copenhagen and Barcelona, adapting them to Warsaw unique architectural heritage.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Local business owners have expressed mixed reactions. While restaurant and cafe proprietors are enthusiastic about the expected increase in foot traffic, some retail shops worry about delivery logistics. The city has proposed a dedicated morning delivery window between 5:00 and 9:00 AM to address these concerns.</p>
<!-- /wp:paragraph -->' \
    "Nowy Świat street to become fully pedestrianized from June 2026. The plan includes green spaces, cycling lanes, and morning delivery windows."


# ============================================================
# EVENTS
# ============================================================

create_post \
    "Warsaw Film Festival 2026: Record 300 Films from 60 Countries" \
    "warsaw-film-festival-2026-record-entries" \
    "$CAT_EVENTS,$CAT_CULTURE" \
    "film festival,cinema,warsaw events,culture" \
'<!-- wp:paragraph -->
<p>The 42nd Warsaw Film Festival has announced its most ambitious program to date, featuring over 300 films from 60 countries across all continents. The festival, running from October 10-19 at the Kinoteka and Multikino Złote Tarasy venues, will showcase everything from experimental shorts to major studio premieres.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>This year special focus section, titled "New Voices of Eastern Europe," highlights emerging filmmakers from Poland, Ukraine, Romania, and the Baltic states. Festival director Stefan Laudyn noted that the selection reflects a growing international recognition of Central European cinema as a creative powerhouse.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>International guests include several Academy Award nominees, with masterclasses and Q&A sessions planned throughout the festival week. Early bird passes are available at wff.pl, with student discounts of 40% on festival packages.</p>
<!-- /wp:paragraph -->' \
    "42nd Warsaw Film Festival announces record 300 films from 60 countries. Special focus on New Voices of Eastern Europe. October 10-19."


create_post \
    "Warsaw Restaurant Week Returns: 200 Restaurants Offering Tasting Menus" \
    "warsaw-restaurant-week-2026-spring" \
    "$CAT_EVENTS,$CAT_FOOD" \
    "restaurant week,dining,food,warsaw events" \
'<!-- wp:paragraph -->
<p>Warsaw Restaurant Week is back for its Spring 2026 edition, with a record 200 participating restaurants offering special three-course tasting menus at fixed prices. The event, running from March 15-29, gives diners the opportunity to experience some of the city finest culinary establishments at accessible price points.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>This year edition introduces a "Warsaw Fusion" category, celebrating restaurants that blend traditional Polish cuisine with international influences. From pierogi reimagined with Korean kimchi to bigos-inspired tacos, the creativity of Warsaw chefs continues to push boundaries.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Reservations can be made through the official restaurantweek.pl platform. Prices range from 59 to 99 PLN per person for the three-course experience. Vegetarian and vegan options are available at every participating venue for the first time this year.</p>
<!-- /wp:paragraph -->' \
    "200 restaurants join Warsaw Restaurant Week Spring 2026, March 15-29. New 'Warsaw Fusion' category and universal vegan options."


# ============================================================
# BUSINESS
# ============================================================

create_post \
    "Warsaw Tech Hub: City Attracts Record Foreign Tech Investment in 2025" \
    "warsaw-tech-hub-record-investment-2025" \
    "$CAT_BIZ" \
    "tech investment,startups,warsaw business,IT sector" \
'<!-- wp:paragraph -->
<p>Warsaw has cemented its position as Central Europe leading tech hub, with foreign direct investment in the technology sector reaching a record 4.2 billion EUR in 2025. The Polish capital attracted headquarters and R&D centers from major global tech companies, creating over 15,000 new high-skilled jobs.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Key drivers behind the surge include Warsaw competitive talent pool — the city graduates over 20,000 IT and engineering students annually — along with attractive tax incentives for R&D activities under the Polish IP Box regime. The average developer salary in Warsaw, while rising, remains significantly lower than in Western European capitals.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The newly opened Warsaw Innovation District in Wola has become the focal point, housing co-working spaces, accelerators, and corporate innovation labs. The district already hosts offices of Google, Samsung, and several major fintech companies, with Amazon and Microsoft expanding their Warsaw operations this year.</p>
<!-- /wp:paragraph -->' \
    "Warsaw attracts record 4.2 billion EUR in tech investment in 2025. 15,000 new IT jobs created. Wola Innovation District becoming the focal point."


# ============================================================
# CULTURE & ART
# ============================================================

create_post \
    "POLIN Museum Opens Major Exhibition on 100 Years of Jewish Culture in Warsaw" \
    "polin-museum-100-years-jewish-culture-exhibition" \
    "$CAT_CULTURE" \
    "polin museum,jewish culture,exhibition,warsaw history" \
'<!-- wp:paragraph -->
<p>The POLIN Museum of the History of Polish Jews has unveiled a landmark exhibition documenting 100 years of Jewish cultural life in Warsaw, from the vibrant interwar period through the tragedy of the Holocaust to the contemporary revival of Jewish heritage in the city.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The exhibition features over 500 artifacts, including personal letters, photographs, artwork, and everyday objects that paint an intimate portrait of Jewish Warsaw. Interactive installations allow visitors to experience the sounds and atmosphere of prewar Nalewki Street, once the heart of Jewish commerce and culture.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Museum director Zygmunt Stępiński described the exhibition as both a memorial and a celebration, highlighting the contributions of Warsaw Jewish community to Polish science, art, and commerce. The exhibition runs through December 2026, with guided tours available in Polish, English, Hebrew, and Yiddish.</p>
<!-- /wp:paragraph -->' \
    "POLIN Museum unveils major exhibition on 100 years of Jewish culture in Warsaw with 500+ artifacts. Open through December 2026."


create_post \
    "Chopin Point Warsaw: New Interactive Music Space Opens at Łazienki Park" \
    "chopin-point-interactive-music-space-lazienki" \
    "$CAT_CULTURE,$CAT_EVENTS" \
    "chopin,music,lazienki park,cultural space" \
'<!-- wp:paragraph -->
<p>A brand new interactive cultural space dedicated to Frederic Chopin has opened at the edge of Łazienki Park, offering visitors an immersive journey through the composer life and musical legacy. Chopin Point Warsaw combines cutting-edge technology with historical authenticity.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The space features holographic performances, AI-powered music composition stations where visitors can create their own Chopin-inspired pieces, and a 360-degree cinema showing the Warsaw that Chopin knew. The rooftop terrace offers panoramic views of Łazienki Park while Chopin music plays through directional speakers.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Tickets start at 45 PLN for adults and 25 PLN for students, with family packages available. The venue also hosts evening concert series featuring young pianists from the Chopin University of Music performing in an intimate 80-seat recital hall.</p>
<!-- /wp:paragraph -->' \
    "New Chopin Point Warsaw opens at Łazienki Park with holographic performances, AI music stations, and rooftop terrace concerts."


# ============================================================
# FOOD & RESTAURANTS
# ============================================================

create_post \
    "Best New Restaurants in Warsaw 2026: From Michelin Stars to Hidden Gems" \
    "best-new-restaurants-warsaw-2026" \
    "$CAT_FOOD" \
    "restaurants,dining,food guide,michelin,warsaw gastronomy" \
'<!-- wp:paragraph -->
<p>Warsaw dining scene continues to evolve at a remarkable pace, with several exciting new restaurants opening their doors in early 2026. From ambitious fine dining concepts to charming neighborhood bistros, the city culinary landscape has never been more diverse.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Leading the pack is Złota 44 Dining, a new restaurant on the 50th floor of the iconic residential tower, offering contemporary Polish cuisine with stunning city views. Chef Aleksandra Nowak, previously of Atelier Amaro, brings a philosophy of radical seasonality — the menu changes weekly based on what arrives from local farms that morning.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>In the Praga district, Bazar Różyckiego has become a food lover destination with three new openings: a Georgian wine bar, a contemporary Vietnamese kitchen, and an artisanal bakery specializing in sourdough and natural fermentation. These additions complement the existing market vendors and reflect Praga ongoing transformation.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>For budget-conscious diners, the Hala Koszyki food hall has expanded with five new vendors, including a Peruvian ceviche bar and a Polish-Japanese fusion concept. Most dishes are priced between 25-45 PLN, making quality dining accessible to all.</p>
<!-- /wp:paragraph -->' \
    "Guide to Warsaw best new restaurants in 2026: from Złota 44 Dining to Praga hidden gems and Hala Koszyki new vendors."


create_post \
    "Warsaw Craft Beer Revolution: 50 Breweries Now Call the Capital Home" \
    "warsaw-craft-beer-50-breweries" \
    "$CAT_FOOD" \
    "craft beer,breweries,nightlife,warsaw drinks" \
'<!-- wp:paragraph -->
<p>Warsaw craft beer scene has hit a major milestone, with the 50th independent brewery opening its doors in the Mokotów district. What started as a niche movement a decade ago has transformed into a thriving industry that puts Warsaw on the map alongside Brussels and Portland as a global craft beer destination.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The Warsaw Craft Beer Association reports that local breweries now produce over 200 unique beer styles, from traditional Polish wheat beers to experimental sour ales aged in oak barrels from local wineries. Annual production has reached 5 million liters, with exports to 12 European countries.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Popular tap rooms include PiwPaw in Foksal Street (40 rotating taps), Kufle i Kapsle in Praga, and the new Beer Garden District at Elektrownia Powiśle. The annual Warsaw Beer Festival, held in September at the Expo XXI center, has grown to attract 30,000 visitors from across Europe.</p>
<!-- /wp:paragraph -->' \
    "Warsaw craft beer scene reaches 50 breweries. 200+ unique styles, 5 million liters annual production. The capital is now a European beer destination."


# ============================================================
# EXPAT LIFE
# ============================================================

create_post \
    "Living in Warsaw as an Expat in 2026: Complete Guide to Costs, Districts, and Tips" \
    "expat-guide-warsaw-2026-costs-districts" \
    "$CAT_EXPAT" \
    "expat life,cost of living,relocation,warsaw guide" \
'<!-- wp:paragraph -->
<p>Warsaw continues to attract international professionals and digital nomads, with the expat community growing by 20% year-over-year. Whether you are relocating for work or choosing Warsaw as your European base, here is everything you need to know about life in the Polish capital in 2026.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Cost of Living:</strong> A comfortable single lifestyle in Warsaw costs approximately 5,000-7,000 PLN per month (excluding rent). Studio apartments range from 2,500 PLN in Praga to 4,500 PLN in Śródmieście. A meal at a mid-range restaurant costs 50-80 PLN, while monthly public transport passes are 110 PLN.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Best Districts for Expats:</strong> Mokotów offers a family-friendly environment with parks and international schools. Żoliborz provides a quiet, leafy atmosphere popular with creatives. Praga-Północ is the artistic, affordable choice. Wola is the new business district with modern apartments.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Essential Tips:</strong> Register for PESEL (personal ID number) at your local Urząd Dzielnicy within 30 days. Open a Polish bank account — mBank and ING offer English-language services. Join expat communities on Facebook and Meetup for social connections and practical advice.</p>
<!-- /wp:paragraph -->' \
    "Complete 2026 guide to living in Warsaw as an expat: costs, best districts, registration tips, and community resources."


create_post \
    "International Schools in Warsaw: 2026 Rankings and Enrollment Guide" \
    "international-schools-warsaw-2026-guide" \
    "$CAT_EXPAT" \
    "international schools,education,families,expat children" \
'<!-- wp:paragraph -->
<p>Choosing the right school is one of the most important decisions for expat families in Warsaw. The city now offers 28 international schools following IB, British, American, French, and German curricula, providing excellent options for families from around the world.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Top-rated schools include the American School of Warsaw (ASW) in Konstancin, the British School Warsaw in Sadyba, and the French Lycée René Descartes in Wilanów. Annual tuition ranges from 45,000 to 95,000 PLN depending on the school and grade level.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>For families on a budget, several Polish public schools now offer bilingual programs, particularly in the Mokotów and Śródmieście districts. The Montessori School of Warsaw and several Waldorf-inspired schools provide alternative educational approaches at more accessible price points.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Early enrollment is crucial — many popular schools have waiting lists that extend 12-18 months. Applications for the 2027/28 academic year typically open in October, with entrance assessments scheduled for January-March.</p>
<!-- /wp:paragraph -->' \
    "Guide to 28 international schools in Warsaw: IB, British, American curricula. Rankings, tuition costs, and enrollment timeline."


# ============================================================
# TRANSPORT
# ============================================================

create_post \
    "Warsaw Public Transport Guide 2026: Metro, Trams, Buses, and E-Scooters" \
    "warsaw-public-transport-guide-2026" \
    "$CAT_TRANSPORT" \
    "public transport,metro,tram,bus,e-scooter,ZTM" \
'<!-- wp:paragraph -->
<p>Warsaw public transport network has expanded significantly, making the city increasingly car-free friendly. Here is your complete guide to getting around the Polish capital using the ZTM (Zarząd Transportu Miejskiego) system and alternative mobility options in 2026.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Metro:</strong> Two lines serve the city — M1 (north-south, 23 stations) and M2 (east-west, 21 stations with the new Praga extension). Trains run every 2-3 minutes during rush hour and every 5-6 minutes off-peak. The metro operates from 5:00 to 1:00 daily.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Trams & Buses:</strong> Over 30 tram lines and 200 bus routes cover the city. The new low-floor electric buses have replaced most diesel fleet. Night buses (marked with "N") run from midnight to 5:00 AM on major routes.</p>
<!-- /wp:parameter -->

<!-- wp:paragraph -->
<p><strong>Tickets & Apps:</strong> A single 75-minute ticket costs 4.40 PLN. Monthly passes are 110 PLN for all zones. The Jakdojade app provides real-time routing, while tickets can be purchased via the mKarta or SkyCash apps. Warsaw City Card offers tourists unlimited travel for 1-3 days.</p>
<!-- /wp:paragraph -->' \
    "Complete 2026 guide to Warsaw public transport: metro, trams, buses, e-scooters. Tickets, apps, and monthly passes explained."


create_post \
    "Warsaw Chopin Airport: New Terminal and Record Passenger Numbers" \
    "warsaw-chopin-airport-new-terminal-2026" \
    "$CAT_TRANSPORT,$CAT_CITY" \
    "chopin airport,flights,aviation,travel" \
'<!-- wp:paragraph -->
<p>Warsaw Chopin Airport has unveiled plans for a major terminal expansion project that will increase annual capacity from 20 to 30 million passengers. The expansion comes as the airport reported record passenger numbers in 2025, serving 22.5 million travelers — a 15% increase over the previous year.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The new Terminal C, designed by renowned Polish architect Robert Konieczny, will feature sustainable architecture with a green roof, solar panels, and rainwater collection systems. Construction is set to begin in Q3 2026 with completion expected by 2029.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>New direct routes launched this year include connections to Seoul, Tokyo-Narita, and Cape Town. Budget carriers Ryanair and Wizz Air have expanded their Warsaw operations, while LOT Polish Airlines has added frequencies on transatlantic routes to New York and Chicago.</p>
<!-- /wp:paragraph -->' \
    "Warsaw Chopin Airport announces Terminal C expansion to 30M passengers. Record 22.5M travelers in 2025. New routes to Seoul and Tokyo."


# ============================================================
# REAL ESTATE
# ============================================================

create_post \
    "Warsaw Real Estate Market 2026: Prices, Trends, and Best Districts to Buy" \
    "warsaw-real-estate-market-2026-trends" \
    "$CAT_REALESTATE" \
    "real estate,apartments,property market,investment" \
'<!-- wp:paragraph -->
<p>Warsaw property market enters 2026 with cautious optimism following the stabilization of interest rates and the end of government subsidy programs. Average apartment prices in the capital currently stand at 15,800 PLN per square meter, with significant variation between districts.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Premium locations:</strong> Śródmieście leads at 22,000-28,000 PLN/m², followed by Mokotów (17,000-21,000) and Żoliborz (16,000-20,000). New luxury developments along the Vistula riverfront command premium prices, particularly in the Powiśle area.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Emerging areas:</strong> Praga-Północ (11,000-14,000 PLN/m²) continues its rapid gentrification and offers the best value for investment. Ursus and Białołęka (10,000-12,000) attract first-time buyers with new residential complexes and improving infrastructure.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Rental market:</strong> Yields average 5-6% gross in central locations. One-bedroom apartments in the center rent for 3,000-4,500 PLN monthly, while shared apartments remain popular among young professionals at 1,500-2,500 PLN per room.</p>
<!-- /wp:paragraph -->' \
    "Warsaw real estate 2026: average 15,800 PLN/m². Best districts, emerging areas, and rental yields analyzed."


create_post \
    "Smart City Warsaw: How Technology Is Transforming the Capital" \
    "smart-city-warsaw-technology-transformation" \
    "$CAT_CITY,$CAT_BIZ" \
    "smart city,technology,innovation,digital transformation" \
'<!-- wp:paragraph -->
<p>Warsaw has been recognized as the fastest-growing smart city in Central Europe, with digital infrastructure investments exceeding 500 million PLN in 2025. From AI-powered traffic management to IoT-enabled waste collection, technology is reshaping how the city operates and serves its residents.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The Warsaw Data Hub, launched last year, integrates data from over 10,000 IoT sensors across the city to optimize everything from public transport routing to air quality monitoring. Residents can access real-time city data through the free "Warsaw Smart" app, which also enables digital interactions with city services.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Notable smart city initiatives include: adaptive traffic light systems that have reduced average commute times by 12%, smart parking sensors that guide drivers to available spots via an app, and an AI-powered citizen helpline that handles 60% of routine inquiries without human intervention.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The city also launched a free public Wi-Fi network covering all major squares, parks, and public transport stops, serving over 500,000 daily connections. Plans for 2026 include expanding the network to residential areas and launching 5G-connected autonomous shuttle services in the Wola district.</p>
<!-- /wp:paragraph -->' \
    "Warsaw named fastest-growing smart city in CE. 500M PLN invested in IoT sensors, AI traffic, smart parking, and free public Wi-Fi."

echo ""
echo "=== Warsaw Daily Seeding Complete! ==="
echo "  16 articles created across all categories"
echo "  Run: $WP post list --post_status=publish --fields=ID,post_title,post_date"
