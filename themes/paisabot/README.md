# PaisaBot Editorial — WordPress Theme

**v4.0.0 · paisa-coin lockup edition**

A production-grade WordPress theme for **paisabot.com** — a global fintech
publisher running news, markets data, and product surfaces in English (this
theme) plus Hindi, Malayalam and Telugu siblings (the `arthanama` theme).

v4 introduces:

1. **New PaisaBot brand** — paisa-coin cushion-shape mark with a tall serif
   "1" inside, paired with a `Paisa**Bot**` wordmark (sans + italic-amber serif).
2. **Three new full-bleed sponsor leaderboard ad zones**:
   - between **Markets Pulse** and **The Briefing**
   - above **Voices**
   - below **Voices**
3. **Mobile-first responsive overhaul** — breakpoints at 480/768/1024/1280px,
   ≥44×44px touch targets on coarse pointers, hamburger-driven mobile nav.
4. Theme metadata + enqueue version bumped to **4.0.0** (cache-busts the CSS/JS).

Everything from v3 (Front Page asymmetric hero, sector heatmap, numbered
Briefing, Voices, Global, Newsletter band, 6 ad widget areas) is preserved.

---

## Install

1. Zip this folder (`paisabot-theme/`) and rename to `paisabot-theme.zip`.
2. WP Admin → **Appearance → Themes → Add New → Upload Theme** → choose the
   zip → **Install Now** → **Activate**.
3. Watch for the green setup nudge — it links to widgets + category setup.

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

### 2. Tag the 5 Briefing articles with `briefing`

The homepage **Briefing** block pulls posts tagged `briefing`, newest first.

### 3. Mark today's top story as Sticky

Front Page lead = latest sticky post.

### 4. Ad zones — **9 widget areas** in v4

Appearance → **Widgets**:

| Widget area | Placement | Size |
|---|---|---|
| Ad — Leaderboard | Below front-page hero | 970×120 / 1240×100 |
| Ad — Wide sponsor (between Markets Pulse & Briefing) **NEW** | Full-bleed sponsor band | 970×120 (responsive) |
| Ad — Wide sponsor (above Voices) **NEW** | Full-bleed house ad | 970×120 (responsive) |
| Ad — Wide sponsor (below Voices) **NEW** | Full-bleed sponsor band | 970×120 (responsive) |
| Ad — MPU | Briefing sidebar | 300×250 |
| Ad — Native | After Banking band | Custom (sponsored card) |
| Ad — Skyscraper | Article sidebar | 160×600 / 300×600 |
| Ad — Sticky | Bottom of every page | Responsive |
| Ad — In-article | After 3rd paragraph | 728×90 / 300×250 |

**Each leaderboard zone has a sensible default fallback** that renders if no
widget is assigned — useful so the layout still looks complete on a brand-new
install. Override by adding a Custom HTML widget to the zone.

### 5. Configure script for Indic editions (optional)

Settings → General → add option `aivartha_script` (kept for back-compat) with
one of: `latin`, `malayalam`, `hindi`, `telugu`. The theme will:

- Swap the body font to Noto Sans for that script
- Set `lang="ml"` / `hi` / `te` on `<html>`
- Loosen line-heights for Indic readability
- Adjust reading-time WPM (170 vs 220)

---

## What's new in v4 — homepage structure

```
─ dateline ─────────────────────────────────────
─ FRONT PAGE  (3-col asymmetric: lead | mid | right) ─
─ ad: leaderboard ──────────────────────────────
─ MARKETS PULSE  (heatmap + indices + stocks) ─
─ ad: wide sponsor (HDFC Securities default) ◀ NEW
─ BRIEFING  (numbered list + By the Numbers) ──
─ BANKING BAND ────────────────────────────────
─ ad: native ──────────────────────────────────
─ INDEX STORIES ───────────────────────────────
─ ad: wide sponsor (Daily Brief house ad) ◀ NEW
─ VOICES  (4 columnists) ─────────────────────
─ ad: wide sponsor (Bajaj Allianz default) ◀ NEW
─ GLOBAL BAND ─────────────────────────────────
─ NEWSLETTER ──────────────────────────────────
─ FOOTER ──────────────────────────────────────
```

Edit the call order or remove ad zones in `index.php`.

---

## Brand assets

- **Logo mark** — inline SVG in `header.php` (`<svg class="logo-mark" …>`).
  Cushion shape via `<path d="M 14 5 Q 30 3 46 5 …">` with a navy gradient,
  amber inner rim at 35% opacity, and a hand-drawn serif "1" path.
- **Wordmark** — `Paisa<em>Bot</em>` with "Bot" in `var(--font-serif)`
  italic amber (`var(--amber)`).
- **Tagline** — uses `bloginfo('description')` — set this in
  Settings → General. Default: "Economic Intelligence".

To replace the mark, edit the SVG block in `header.php` only; the wordmark
text is plain HTML next to it.

---

## File map (v4)

```
paisabot-theme/
├── style.css                # v4.0.0 — tokens + components + responsive
├── functions.php            # Setup, widget areas (9 ad zones), helpers
├── header.php               # PaisaBot logo + topbar + ticker + masthead + breaking
├── footer.php               # Newsletter band + footer (PaisaBot wordmark) + sticky ad
├── index.php                # Homepage composer w/ 3 new sponsor zones
├── single.php               # Article page (drop cap, share, related)
├── archive.php              # Category / search / tag listings
├── page.php                 # Static page
├── 404.php                  # Not found
├── sidebar.php              # Article sidebar
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
│       ├── leaderboard.php          # Below hero (widget-driven)
│       ├── sponsor-leaderboard.php  # NEW — full-bleed sponsor band w/ defaults
│       ├── mpu.php
│       ├── native.php
│       ├── skyscraper.php
│       └── in-article.php
│
└── assets/
    ├── js/main.js           # Sticky header, search, reading progress, ticker pause
    └── svg/sprite.php       # 20-icon SVG sprite (unchanged)
```

---

## Changelog

- **4.0.0** — PaisaBot rebrand: new paisa-coin logo lockup, full-bleed
  sponsor leaderboards at 3 new positions, mobile-first responsive
  (480/768/1024/1280 + 44px touch targets + hamburger nav), 3 new widget
  areas, theme metadata bump.
- **3.3.0** — Article header fix, icon-only share buttons, no comments.
- **3.0.0** — Editorial broadsheet redesign (Front Page, heatmap, Briefing,
  Voices, Global, Newsletter band, 6 ad zones).
- **2.0.0** — Original FT-cream homepage.

## License

GNU GPL v2 or later.
