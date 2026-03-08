# Bootstrap Summary Cards

**Description:** Create and display responsive "Summary Cards" or "Intro Blocks" that link to other internal or external pages.  
**Version:** 1.1
**Author:** D Kandekore  

## Features
* **Custom Post Type:** "Summary Cards" for managing content separately from posts/pages.
* **Custom Fields:** Easily add a "Target URL" and "Font Awesome Icon" to each card.
* **Global Styling:** Centralized settings for colors, borders, and buttons.
* **Shortcode & Elementor:** Flexible display options.

## Installation
1.  Upload the `bootstrap-summary-cards` folder to `/wp-content/plugins/`.
2.  Activate the plugin in WordPress.
3.  Go to **Summary Cards > Display Settings** to configure colors.

## Usage

### 1. Creating Content
1.  Go to **Summary Cards > Add New**.
2.  **Title:** Enter the card title.
3.  **Content:** Enter the description text.
4.  **Featured Image:** Upload the image for the top of the card.
5.  **Card Details (Meta Box):**
    * **Target URL:** The link where the user goes when clicking the card.
    * **Icon Class:** A Font Awesome class (e.g., `fa-solid fa-bolt`) to display on the divider.
6.  **Categories:** Assign a "Summary Category" (right sidebar) to group this card.

### 2. Displaying Cards

#### Method A: Shortcode
Use the shortcode in any text area:
`[bootstrap_summary_cards category="services" columns="3" limit="6"]`

* `category`: (Optional) The slug of the category to show.
* `columns`: Number of cards per row (default: 3).
* `limit`: Max number of cards to show.

#### Method B: Elementor Widget
1.  Edit a page with Elementor.
2.  Drag the **Bootstrap Summary Cards** widget to the page.
3.  Select your category and column layout from the settings panel.

## Styles & Configuration
Go to **Summary Cards > Display Settings** in the dashboard menu to change:
* Border, Background, and Text colors.
* Button Text (e.g., "Read More", "Go", "View").
* Default Fallback Image.

## Custom Post Order
Cards can be reordered using the **Simple Custom Post Order** plugin. Drag cards into the desired order and the front-end display will reflect it.

## Changelog

### 1.1
* Fixed: Cards now respect the custom order set by Simple Custom Post Order (and compatible plugins) by querying posts ordered by `menu_order ASC`.

### 1.0
* Initial release.