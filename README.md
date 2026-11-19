# Panto Design: BSIT Capstone Team Portfolio (WordPress Theme)

A single-page, light and formal portfolio theme for the **BSIT capstone team of Opol Community College**. It introduces the team, shows school memories and projects, explains the development process, collects visitor feedback, and gives each member their own profile page.

> Theme name in `style.css`: **Panto Design** (v11.14)

---

## Table of Contents

1. [Features](#features)
2. [Design System](#design-system)
3. [Folder Structure](#folder-structure)
4. [Page Sections (Home)](#page-sections-home)
5. [Member Profile Page](#member-profile-page)
6. [Visitor Feedback System](#visitor-feedback-system)
7. [Setup and Installation](#setup-and-installation)
8. [How to Edit Content](#how-to-edit-content)
9. [Customization Cheat Sheet](#customization-cheat-sheet)
10. [Accessibility and Performance](#accessibility-and-performance)
11. [Troubleshooting](#troubleshooting)

---

## Features

### Home page
- **Hero** with a giant faded headline ("Meet the Minds Behind the Work") and the team photo anchored at the bottom.
- **Skills marquee**: two rows of auto-collected skills (from every member, no duplicates) that move in opposite directions.
- **Team carousel**: one member at a time with arrows, avatar thumbnails, keyboard arrows, swipe on phones, and autoplay (pauses on hover, focus and touch).
- **Social icons** (Facebook, Twitter, Instagram, TikTok): shown only when the member has a link.
- **Email button**: opens a Gmail compose window on desktop, or the phone's mail app on mobile, addressed to that member.
- **Memories gallery**: bento-style photo grid with a full-screen **lightbox** (previous/next, Esc to close, keyboard arrows).
- **Projects showcase**: two-column cards with number, category chip and a lightbox viewer. The category filter script and styles are included (the filter bar markup can be added back when needed).
- **Process section**: five-step development process with tabs, image panels, progress bar, autoplay and swipe.
- **Feedback section**: adviser testimonial slider, average star rating, visitor form, and a "View comments" modal.
- **Closing call to action** with smooth scroll to the team section and back to top.
- **Scroll in/out animations** on every section (elements fly in and out as you scroll).

### Member profile page (`?member=slug`)
- Hero with photo (tilt effect on desktop), name, role, short intro and "Read more".
- Buttons: **Send email**, **Resume** (if a PDF exists), **Copy profile link**.
- Animated counters for Skills, Education and Tasks.
- Cards: **About me**, **Capstone role** (with tasks), **Education** (timeline), **Skills** (colored pills).
- Sticky sidebar: **Contact** (email, phone, address, copy email) and **Find me online**.
- Previous / Next member navigation.
- Skills ticker that scrolls under the hero.

### Footer
- IT logo with transparent background effect.
- Quick links.
- **Contact Us** that changes with the selected member.
- **Team Members picker** (photo and name, arrows, counter, swipe).

### Header
- Logo on the far left, centered menu, "Get in Touch" button on the right.
- **Scroll spy**: the active menu item follows the section you are viewing.
- Mobile menu toggle.

### Visitor feedback (backend)
- Custom post type **Feedback** in the WordPress admin.
- AJAX submit, list (paged), and like/unlike.
- Spam protection: nonce, honeypot field, and a 45-second rate limit per visitor.
- Optional email notice to the site admin for each new feedback.
- Auto-publish or moderate-first switch (`OCC_FB_AUTO_PUBLISH`).

---

## Design System

The theme uses a **light, formal, animated** look. Every section follows the same pattern: kicker label, large heading, a lead paragraph with a blue left border, plus soft decorations.

### Colors

| Role | Value | Used for |
|---|---|---|
| Blue (primary) | `#1f4fd8` | Buttons, kickers, last name, active states |
| Dark blue (hover) | `#183fb0` | Button hover |
| Ink | `#15161a` | Headings, main text |
| Soft text | `#4a4e5a` | Paragraphs |
| Sage | `#a9b6a0` | Photo backgrounds, soft circles |
| Page light | `#eceeef` / `#f6f7f9` | Section backgrounds |
| Green | `#0f9d73` | Accent (skills, app category) |
| Purple | `#7c4dff` | Accent (education, UI/UX category) |
| Orange | `#d9822b` | Accent (system category, role) |
| Star yellow | `#f2a900` | Ratings |

### Typography
- Body and headings: system font stack (`--sans`): `system-ui, -apple-system, "Segoe UI", Roboto, ...`
- Hero ghost text: **Anton** (Google Fonts).
- Old code-editor font (**JetBrains Mono**) is still loaded by `style.css` for the legacy styles.

### Decorations (per section)
- Dotted grid corner, thin dashed rotating ring, soft sage circle, floating blue square, and a giant outlined section word (TEAM, MEMORIES, PROJECTS, PROCESS, FEEDBACK).

### Animation
- Scroll in/out via `IntersectionObserver` (each section has its own prefix: `tm-`, `mx-`, `pj-`, `pc-`, `fv-`, `ct-`, `mp-`).
- Carousels slide with CSS transitions; the progress bar fills during autoplay.
- All animation turns off when the visitor prefers **reduced motion**.

### Responsive breakpoints
- `960px`: tablet (menu toggle, single-column layouts)
- `900px`, `760px`, `700px`: section-specific stacking
- `600px` / `560px` / `640px`: phones (full-width buttons, bottom-sheet modal)

---

## Folder Structure

```
panto-design/
├── style.css               # Theme header + all styles (sections numbered 1–32)
├── functions.php           # Setup, assets, image helper, feedback backend (AJAX)
├── header.php              # <head>, logo, menu, scroll-spy script, SVG logo filter
├── front-page.php          # Home page: hero, team, memories, projects, process, feedback, CTA
├── member-profile.php      # Profile page (included by front-page.php when ?member=slug)
├── footer.php              # Footer: contact per member + member picker
├── team-data.php           # ★ Edit this: member info, links, education, skills
├── role-data.php           # Capstone roles: label, icon, description, tasks
├── resume/                 # (optional) PDF resumes, e.g. basadre-resume.pdf
└── assets/
    ├── js/
    │   └── main.js         # Theme script (marquee motion, header, background layer)
    └── images/
        ├── IT_logo.jpg             # Header and footer logo
        ├── Profile.bg.jpg          # Legacy background image
        ├── team.png                # Hero team photo (transparent PNG)
        ├── basadre.jpg             # Member photos
        ├── gwapo.jpg
        ├── campion.jpg
        ├── pangit.jpg
        ├── Rachel.jpg              # Adviser photo (feedback)
        ├── team2.jpg               # Memories gallery
        ├── presentation.jpg
        ├── sch_evnts.jpg
        ├── sch_evnts2.jpg
        ├── team6.jpg
        ├── project1.jpg            # Projects
        ├── project2.jpg
        ├── project3.jpg
        ├── project4.jpg
        ├── step-requirements.jpg   # Process steps
        ├── step-planning.jpg
        ├── step-development.jpg
        ├── step-reveal.jpg
        └── step-deployment.jpg
```

### What each file does

| File | Purpose |
|---|---|
| `style.css` | Theme info and global CSS. Newer light-theme rules (sections 22–31) override the older dark editor theme at the top. |
| `functions.php` | `panto_img()` helper, asset loading, body class for profile pages, page title, **Feedback** post type and AJAX handlers. |
| `header.php` | Opens `<html>`, shows the logo, menu and "Get in Touch", runs the scroll-spy. Defines the `#occ-knockout` SVG filter that removes the white box from the logo. |
| `front-page.php` | The whole home page. Each section has its own scoped `<style>` and `<script>`. |
| `member-profile.php` | Renders one member's profile. Has its own scoped styles and scripts (`#mp`). |
| `footer.php` | Footer layout and the member carousel (contact changes per member). |
| `team-data.php` | The **single source of truth** for members. |
| `role-data.php` | Role presets and `occ_team_with_roles()` which assigns each member's role. |

---

## Page Sections (Home)

| Order | Section | ID / class | Notes |
|---|---|---|---|
| 1 | Hero | `.hero` | Giant text and team photo |
| 2 | Skills marquee | `.marquee` | Skills collected from all members |
| 3 | Team | `#team` | Carousel with socials, email, profile link |
| 4 | Memories | `#collections` | Bento grid and lightbox |
| 5 | Projects | `#projects` | Cards and lightbox |
| 6 | Process | `#process` | 5 steps, autoplay |
| 7 | Feedback | `#feedback` | Slider, form, comments modal |
| 8 | Closing CTA | `#closing` | Meet the team / Back to top |
| 9 | Footer | `#contact` | Contact per member |

---

## Member Profile Page

Open a profile with a URL like:

```
https://your-site.com/?member=basadre
```

The slug is the **array key** in `team-data.php` (`basadre`, `cachero`, `campion`, `muego`).

The page layout is:

1. Hero (photo, name, role, intro, buttons, counters)
2. Skills ticker
3. Main column: About me, Capstone role, Education and Skills
4. Sidebar: Contact, Find me online (sticky on desktop)
5. Previous / Next member

---

## Visitor Feedback System

Everything is saved inside WordPress. No external service is needed.

| Item | Detail |
|---|---|
| Storage | Custom post type `occ_feedback` (WP Admin, **Feedback**) |
| Meta | `_fb_name`, `_fb_rating` (1–5), `_fb_likes` |
| Endpoints | `occ_fb_list`, `occ_fb_submit`, `occ_fb_like` (via `admin-ajax.php`) |
| Validation | Rating required, message 10–600 characters |
| Anti-spam | Nonce, hidden honeypot input, 45 s rate limit per IP |
| Notification | `wp_mail()` to the admin email on each submission |
| Likes | Remembered in the visitor's browser (`localStorage`) |

**Auto-publish or moderate:** in `functions.php`

```php
define('OCC_FB_AUTO_PUBLISH', true);   // true = appears right away
                                       // false = pending until you Publish it in WP Admin
```

The average rating shown in the Feedback header combines the adviser rating(s) in `front-page.php` (`$feedbacks`) with all published visitor ratings.

---

## Setup and Installation

1. Copy the theme folder to `wp-content/themes/panto-design/`.
2. In WordPress, go to **Appearance, Themes** and activate **Panto Design**.
3. Make sure these exist:
   - `assets/js/main.js`
   - the images listed under [Folder Structure](#folder-structure) (missing images show as broken placeholders)
4. Go to **Settings, Reading** and keep a static front page or latest posts; the theme's `front-page.php` is used for the home page either way.
5. (Optional) Create a **Primary Menu** under **Appearance, Menus**. If none is set, the default menu (Home, Profile, Memories, Projects, Process, Feedback) is used.
6. (Optional) Create a `resume/` folder in the theme and upload PDFs for members.

**Requirements:** WordPress 6.x, PHP 7.4+ (8.x recommended). A modern browser is needed for `color-mix()` and `IntersectionObserver`.

---

## How to Edit Content

### Add or edit a team member: `team-data.php`

```php
'basadre' => [
  'name'      => 'Jhon Christian Basadre',
  'image'     => 'basadre.jpg',                 // file in assets/images/
  'email'     => 'name@gmail.com',
  'phone'     => '+63 900 000 0000',
  'address'   => 'Poblacion, Opol, Misamis Oriental',
  'about'     => 'Short introduction...',
  'education' => [
    ['School name', 'Course or detail', '2022 - Present'],
  ],
  'skills'    => ['PHP', 'JavaScript', 'MySQL'],
  'resume'    => 'basadre-resume.pdf',          // file in /resume/ ('' = no button)
  'facebook'  => 'https://facebook.com/...',    // '' = icon is hidden
  'twitter'   => '',
  'instagram' => '',
  'tiktok'    => '',
],
```

- The **key** (`basadre`) becomes the profile URL: `?member=basadre`.
- The role comes from `role-data.php` (matched by surname, then by order).

### Change a role: `role-data.php`
Edit the label, color, icon, description or `tasks` of Programmer, System Analyst, Technical Writer or Project Manager. The tasks appear on the profile page.

### Memories, Projects, Process, Feedback: `front-page.php`

| Section | Variable | Format |
|---|---|---|
| Memories | `$memories` | `['Title', 'Description', 'image.jpg']` |
| Projects | `$projects` | `['category', 'Title', 'Subtitle', 'image.jpg']` (`web`, `app`, `uiux`, `system`) |
| Process | `$pc_steps` | `['01', 'Title', 'Text', 'image.jpg']` |
| Adviser feedback | `$feedbacks` | `quote`, `name`, `role`, `img`, `rating` |

Add more entries to `$feedbacks` and the slider arrows, dots and autoplay appear automatically.

---

## Customization Cheat Sheet

| I want to... | Where |
|---|---|
| Change the main blue | Replace `#1f4fd8` (and `#183fb0`) in `front-page.php`, `member-profile.php`, `footer.php`, `style.css` |
| Change autoplay speed (team) | `front-page.php`, team script, `8000` in `setTimeout` |
| Change autoplay speed (process) | `TIME = 6000` in the process script |
| Move the team photo left/right | `.tm-visual` `margin-right: clamp(40px, 9vw, 130px)` |
| Show/hide the member name on one line | `.tm-title { white-space: nowrap; }` |
| Move the hero team photo | `.hero-team { --team-h; --team-down; }` in `style.css` |
| Stop scroll animations | Remove the `data-*` attributes, or rely on `prefers-reduced-motion` |
| Remove the white box on the logo | Use a transparent PNG for `IT_logo.jpg`, or keep the SVG `#occ-knockout` filter |
| Moderate feedback first | `OCC_FB_AUTO_PUBLISH` to `false` in `functions.php` |
| Add a social network | Add it to `$socials` (front-page, footer) and `$mp_socials` (profile), then add the key in `team-data.php` |

---

## Accessibility and Performance

- Semantic landmarks (`main`, `header`, `footer`, `nav`, `article`, `aside`).
- ARIA roles and labels on carousels, tabs, dialogs and star ratings.
- Keyboard support: arrow keys on carousels and lightboxes, Esc to close dialogs, visible focus rings.
- `aria-hidden` is kept in sync on hidden slides, and hidden slides cannot catch clicks.
- Honors `prefers-reduced-motion`.
- Images use `loading="lazy"` and `decoding="async"` where appropriate.
- Autoplay only runs while a section is on screen.
- Lightboxes and modals are moved to `<body>` so nothing can clip them.

---

## Troubleshooting

| Problem | Fix |
|---|---|
| An image shows only its alt text | The file is missing or misspelled in `assets/images/`. File names are case-sensitive on most servers. |
| Hero or Memories photo is cropped at the top | Use `object-position: center top;` on that image (already applied to the first Memories card). |
| Layout breaks inside a team slide | Check that every `<div>` is closed (`tm-row`, `tm-info`) and that there is only one `tm-about` paragraph. |
| Logo shows a white box | Replace the JPG with a transparent PNG, or check that the `#occ-knockout` SVG in `header.php` is present. |
| Buttons are not clickable | A hidden layer may be on top. Keep the "FIX: nothing may block the buttons" rules in the team `<style>`. |
| "Post feedback" or "View comments" does nothing | `admin-ajax.php` is blocked, or a second `.fc-viewall` button exists. Keep exactly one inside `.fv-actions`. |
| Feedback does not appear | `OCC_FB_AUTO_PUBLISH` is `false`: publish it in WP Admin, Feedback. |
| Profile page shows the home page | The `?member=` slug does not match a key in `team-data.php`. |
| Old dark styles show through | Section order in `style.css` matters. Keep the light-theme sections (22–31) after the older ones. |

---

## Credits

- **Team:** BSIT Capstone Team, Opol Community College, Misamis Oriental
- **Adviser:** Prof. Rachel Ana Dantes
- **Theme base:** Panto Design (customized)
