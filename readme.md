# Bootstrap Summary Cards

**Description:** Create and display responsive "Summary Cards" — styled intro blocks that link to internal or external pages — using a Bootstrap grid layout.
**Version:** 1.1
**Author:** D Kandekore
**Requires:** WordPress 5.8+, PHP 7.4+
**Optional:** Elementor (for widget support), Simple Custom Post Order (for drag-and-drop reordering)

---

## Overview

Bootstrap Summary Cards adds a dedicated **Summary Card** custom post type to WordPress. Each card has a title, description, featured image, icon, and a target URL. Cards are displayed in a responsive Bootstrap 5 grid and can be output via a shortcode or an Elementor widget.

This plugin is ideal for landing pages, service listings, resource directories, or any situation where you want a consistent set of clickable, visual blocks linking to other pages.

---

## Features

- **Custom Post Type** — A dedicated "Summary Cards" post type, separate from posts and pages, with its own admin menu.
- **Custom Taxonomy** — "Summary Categories" to group and filter cards by topic or section.
- **Per-card Meta Fields** — Each card stores a Target URL (where clicking the card leads) and a Font Awesome icon class displayed on the image divider.
- **Featured Image Support** — Cards display a featured image at the top. A global fallback image URL can be set for cards without one.
- **Bootstrap 5 Grid** — Cards are rendered in a fully responsive Bootstrap 5 grid. The number of columns is configurable.
- **Font Awesome Icons** — Icon support via Font Awesome 6. Specify any free icon class (e.g. `fa-solid fa-rocket`) per card.
- **Global Design Settings** — Centralised admin panel to control border colour, background colour, text colour, button colour, divider colour, button label text, description character limit, and default fallback image.
- **Shortcode** — Drop cards anywhere using `[bootstrap_summary_cards]` with optional parameters.
- **Elementor Widget** — A native Elementor widget with point-and-click controls for category, columns, and card limit — no shortcode required.
- **Custom Post Order support** — Cards respect the order set by Simple Custom Post Order (or any plugin that writes to `menu_order`).

---

## Installation

1. Upload the `bootstrap-summary-cards` folder to `/wp-content/plugins/`.
2. Activate the plugin via **Plugins > Installed Plugins** in WordPress.
3. Go to **Summary Cards > Display Settings** to configure colours and defaults.

---

## Creating Cards

1. Go to **Summary Cards > Add New**.
2. **Title** — The card heading displayed on the front end.
3. **Content** — The description text shown below the title. Long descriptions are automatically truncated to the character limit set in Display Settings.
4. **Featured Image** — The image shown at the top of the card. If not set, the global fallback image is used.
5. **Card Details (meta box):**
   - **Target URL** — The page or external URL the card links to. All three clickable elements (image, title, button) point here.
   - **Icon Class** — A Font Awesome class string (e.g. `fa-solid fa-bolt`). The icon appears centred on the divider between the image and the card body. Leave blank to hide the icon area.
6. **Summary Category (sidebar)** — Assign one or more categories to group this card for filtered display.

---

## Displaying Cards

### Method A: Shortcode

Paste the shortcode into any page, post, or widget area:

```
[bootstrap_summary_cards category="services" columns="3" limit="6"]
```

| Parameter  | Default | Description |
|------------|---------|-------------|
| `category` | _(all)_ | Comma-separated category slug(s) to filter by. Omit to show all cards. |
| `columns`  | `3`     | Number of cards per row (1–6). Maps to Bootstrap column widths. |
| `limit`    | `12`    | Maximum number of cards to display. |

**Examples:**

```
[bootstrap_summary_cards]
[bootstrap_summary_cards category="services" columns="4" limit="8"]
[bootstrap_summary_cards category="services,resources" columns="2"]
```

### Method B: Elementor Widget

1. Edit any page with Elementor.
2. Search for **Bootstrap Summary Cards** in the widget panel (under the General category).
3. Drag the widget onto the page.
4. In the **Settings** panel configure:
   - **Category** — Multi-select dropdown populated from your Summary Categories. Select one or more, or leave empty to show all.
   - **Columns per Row** — Number picker (1–6).
   - **Total Cards Limit** — Number picker (1–50, default 9).

Changes preview live in the Elementor editor. The widget uses the same render function as the shortcode, so styles from Display Settings apply equally.

---

## Design Settings

Go to **Summary Cards > Display Settings** to configure the global appearance:

| Setting | Description | Default |
|---------|-------------|---------|
| Border Color | Card border colour | `#cccccc` |
| Divider Color | Horizontal rule and image-divider colour | `#eeeeee` |
| Background Color | Card background | `#ffffff` |
| Text Color | Card body text | `#333333` |
| Button Color | "Read More" button background | `#007bff` |
| Description Limit | Max characters shown in the card description | `300` |
| Button Text | Label on the link button | `Read More` |
| Default Image URL | Fallback image for cards without a featured image | _(blank)_ |

All colours are free-text fields — enter any valid CSS colour value (hex, rgb, named colour, etc.).

---

## Custom Post Order

Cards support drag-and-drop reordering via the **Simple Custom Post Order** plugin (or any compatible plugin that stores order in the `menu_order` database field).

1. Install and activate Simple Custom Post Order.
2. Go to **Summary Cards** in the admin.
3. Drag cards into the desired order and save.
4. The shortcode and Elementor widget will both display cards in that order on the front end.

---

## Assets & Dependencies

The plugin loads the following external assets on pages where cards are displayed:

- **Bootstrap 5.3.2 CSS** — `cdn.jsdelivr.net`
- **Font Awesome 6.5.2 CSS** — `cdnjs.cloudflare.com`

These are only enqueued when cards are actually rendered, not on every page.

---

## Changelog

### 1.1
- Fixed: Cards now respect the custom order set by Simple Custom Post Order (and compatible plugins). The query now sorts by `menu_order ASC` instead of defaulting to date order.

### 1.0
- Initial release.
