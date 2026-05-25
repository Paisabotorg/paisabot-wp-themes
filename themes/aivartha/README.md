# AI Vartha Editorial — WordPress Theme

**v3.0.0 · Broadsheet Editorial Edition**

A production-grade WordPress theme for an Indian regional-language economic news portal. Designed in the **FT × Bloomberg × CNN** tradition, tuned for Malayalam, Hindi, Telugu, and English. v3 replaces the old hero-and-cards homepage with an **asymmetric Front Page broadsheet hero**, a live **sector heatmap**, the numbered **Briefing** block, **Voices** opinion grid, **Global** four-up, and integrated **ad zones**.

---

## Install

1. Zip this folder (`wordpress_theme/`) and rename to `aivartha-editorial.zip`.
2. WP Admin → **Appearance → Themes → Add New → Upload Theme** → choose the zip → **Install Now** → **Activate**.
3. Watch for the green setup nudge in your dashboard — it links to widgets + category setup.

## Required setup (5 minutes)

### 1. Create these categories

Posts → Categories → add (slugs must match exactly):

| Slug | Display name |
|---|---|
| `markets` | Markets |
| `policy` | Policy |
| `banking` | Banking |
| `economy` | Economy |
| `global` | Global |
| `foreign-policy` | Foreign Policy |
| `technology` | Technology |
| `opinion` | Opinion |
| `breaking` | Breaking |

### 2. Add the `briefing` tag

Tag the 5 articles that should appear in the homepage **Briefing** block with the tag `briefing`. Tags are ordered newest-first.

### 3. Mark today's top story as Sticky

The **Front Page lead** uses the latest sticky post. **Sensex breaches 74,000** (or whatever's leading) → Edit → **Stick to top of blog**.

### 4. Set up ad zones (optional but encouraged)

Appearance → **Widgets**. Six widget areas, each takes one or more **Custom HTML** widgets:

| Widget area | Placement | Recommended size |
|---|---|---|
| Ad — Leaderboard | Below front page hero | 1240×100 |
| Ad — MPU | In briefing sidebar | 300×250 |
| Ad — Native | After Banking band | Custom (sponsored) |
| Ad — Skyscraper | Article sidebar | 160×600 |
| Ad — Sticky | Bottom of every page | Responsive |
| Ad — In-article | After 3rd paragraph | 728×90 or 300×250 |

Paste your GAM/AdSense code as a Custom HTML widget in each zone. Zones with no widget render nothing.

### 5. Configure script (for Indic editions)

Settings → General → add option `aivartha_script` with one of: `latin`, `malayalam`, `hindi`, `telugu`. The theme will automatically:
- Swap the body font to Noto Sans for the chosen script
- Set `lang="ml"` / `hi` / `te` on `<html>`
- Loosen line-heights for Indic readability
- Adjust reading-time WPM (170 vs 220)

---

## What renders where

**Homepage** (`index.php`, when `is_home() && !is_paged()`):

1. **Dateline strip** — broadsheet "Vol. IV · No. 137 · Saturday · National Edition · indices"
2. **Front Page** — asymmetric 3-col hero (lead | mid | 4 right stories)
3. **Ad zone: Leaderboard**
4. **Markets Pulse** — sector heatmap (15 sectors, color-coded) + 4 key indices
5. **The Briefing** — numbered 01–05 editorial list + "By the Numbers" stat card + Ad zone: MPU
6. **Banking band** — feature + 3 secondary stories (full-bleed cream)
7. **Ad zone: Native** (sponsored content card)
8. **Index Stories** — 5 cells pairing market data with stories
9. **Voices** — 4 opinion-column cards with author avatars
10. **Global** — 4 region cells with flag accents
11. **Newsletter band** — full-bleed navy signup form
12. **Footer** + **Ad zone: Sticky** (dismissible)

**Article pages** (`single.php`) use the v2 article layout (drop cap, serif body, blockquote, share, author box, related grid, sidebar with skyscraper).

**Category / search / tag archives** (`archive.php` + fallback in `index.php`) use the news-row layout with full sidebar.

---

## Customize via filters

The theme reads market data, dateline values, and stat numbers through **filters** so you can wire real data without touching templates. In a child theme or small companion plugin:

```php
// Live market ticker
add_filter('aiv_market_items', function() {
    return get_transient('my_live_indices') ?: [];
});

// Sector heatmap (15 sectors, 1 marked 'large')
add_filter('aiv_heatmap', function() {
    return wp_remote_retrieve_body(wp_remote_get('https://my-feed/heatmap.json'));
});

// "By the Numbers" stat card
add_filter('aiv_numbers', fn() => my_numbers_of_the_day());

// Dateline values
add_filter('aiv_dateline', function($d) {
    $d['weather'] = get_my_weather('Mumbai');
    return $d;
});

// 4 key indices in the pulse band sidebar
add_filter('aiv_pulse_indices',  fn() => my_indices());

// Index-led story strip
add_filter('aiv_index_stories',  fn() => my_index_stories_with_post_links());

// Global cells (paired automatically with latest Global posts)
add_filter('aiv_global_cells',   fn() => my_global_data());
```

---

## File map

```
wordpress_theme/
├── style.css                # Theme metadata + tokens + all component CSS
├── functions.php            # Setup, widgets, helpers, filters
├── header.php               # Topbar + ticker + masthead + breaking + dateline
├── footer.php               # Newsletter band + footer cols + sticky ad
├── index.php                # Homepage composer + archive fallback
├── single.php               # Article page
├── archive.php              # Category / search / tag listings
├── page.php                 # Static page
├── 404.php                  # Not found
├── sidebar.php              # Article sidebar (trending + market + newsletter)
│
├── template-parts/
│   ├── dateline.php
│   ├── front-page.php
│   ├── markets-pulse.php
│   ├── briefing.php
│   ├── banking-band.php
│   ├── voices-band.php
│   ├── global-band.php
│   ├── index-stories.php
│   ├── newsletter-band.php
│   └── ads/
│       ├── leaderboard.php
│       ├── mpu.php
│       ├── native.php
│       ├── skyscraper.php
│       └── in-article.php
│
└── assets/
    ├── js/main.js           # Sticky header, search, reading progress, ticker pause
    └── svg/sprite.php       # 20-icon SVG sprite
```

---

## Author meta

For **Voices** cards, add a custom user meta field `aiv_role` per columnist with their title (e.g. "Chief Economist", "Markets Columnist"). The theme falls back to the user's bio (`description`) if `aiv_role` is empty.

A small mu-plugin to expose this field in user profiles:

```php
add_action('show_user_profile',  'aiv_role_field');
add_action('edit_user_profile',  'aiv_role_field');
function aiv_role_field($user) { ?>
    <table class="form-table">
        <tr><th><label>Editorial role</label></th>
        <td><input type="text" name="aiv_role" value="<?php echo esc_attr(get_user_meta($user->ID, 'aiv_role', true)); ?>" class="regular-text"></td></tr>
    </table>
<?php }
add_action('personal_options_update', 'aiv_role_save');
add_action('edit_user_profile_update','aiv_role_save');
function aiv_role_save($id) {
    if (isset($_POST['aiv_role'])) update_user_meta($id, 'aiv_role', sanitize_text_field($_POST['aiv_role']));
}
```

---

## Changelog

- **3.0.0** — Editorial broadsheet redesign: Front Page hero, sector heatmap, Briefing block, Voices, Global cells, Newsletter band, 6 ad zones. New nav with Technology + Foreign Policy. Type direction shift to Source Serif 4 for editorial headlines.
- **2.0.0** — Original FT-cream homepage with hero + card strip.

## License

GNU GPL v2 or later.
