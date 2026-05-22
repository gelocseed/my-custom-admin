# ✨ My Custom Admin — Next-Gen WordPress Dashboard

[![WordPress Version](https://img.shields.io/badge/WordPress-7.0%2B-blue.svg)](https://wordpress.org)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-GPL%20v2%20or%20later-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

**My Custom Admin** is a modern, lightweight WordPress plugin designed to fully customize the administrative control panel (Dashboard). Serving as a high-performance alternative to **UiPress**, it replaces the default WordPress layout with a clean, containerized **App Shell Layout**, featuring flat panels, modern typography, and clean contrast controls.

---

## 🚀 Key Features

*   **📱 App Shell Sidebar**: A flat, border-right aligned navigation sidebar that merges seamlessly with the page background, using a refined font weight (`400`/`500`) and thinner outline-style icons.
*   **💳 Containerized Workspace**: The entire WordPress content area (`#wpbody-content`) is wrapped in a single, card-like tablet canvas featuring a `16px` border-radius, thin borders, soft shadow depth, and solid white/dark-graphite backings.
*   **🔮 Flat Admin Bar**: A top navigation bar flush with the screen edges that acts as a flat extension of the dashboard, carrying an integrated custom brand logo.
*   **🌗 On-the-Fly Theme Switcher (Light / Dark / System)**: Fully dynamic styling controlled by CSS custom properties and instant live-preview toggling before saving.
*   **🖼️ Custom Login Branding**: Replaces the default `wp-login.php` interface with a centered minimalist container, custom brand logo support, and adaptive coloring matching the active theme.
*   **🧱 Bricks Builder Compatibility**: Scoped styles using unique CSS prefixes (e.g. `.mcl-`) to guarantee 100% isolation from builder canvases (like Bricks, Bricksforge, and Bricksultimate).
*   **🧹 Declutter Console**: Easily disable standard WordPress widgets (Welcome panel, Quick draft, Activity, etc.) with a single click.

---

## 🛠️ System Requirements

*   **WordPress**: Version `7.0` or higher
*   **PHP**: Version `8.2` or higher (strictly enforced during runtime)
*   **Plugin Compatibility**: Verified with *Bricksforge, Bricksultimate, Clearfy Pro, WP Rocket, WPvivid Backup Pro*.

---

## 📦 Installation & Activation

1.  **Download the ZIP**: Pack the `my-custom-admin` folder into a ZIP archive.
2.  **Upload to WordPress**: In your WordPress dashboard, navigate to **Plugins ➔ Add New ➔ Upload Plugin** and select the ZIP file.
    *   *Manual method*: Extract the files and upload the `my-custom-admin` folder directly to `/wp-content/plugins/` via FTP.
3.  **Activate**: Go to the **Plugins** menu and click **Activate** next to **My Custom Admin**.
4.  **Configure**: Navigate to **Settings ➔ Кастомная админка** (Settings ➔ Custom Admin) to upload your logo and choose your interface theme.

---

## ⚙️ File Structure

```text
my-custom-admin/
├── my-custom-admin.php     # Main entry file, requirements checker & enqueue scripts
├── README.md               # English plugin documentation
├── includes/
│   └── settings.php        # WordPress Settings API screen and Media Uploader hooks
└── assets/
    ├── css/
    │   ├── admin-base.css  # Layout grid, sidebar rules, card wrappers, login styling
    │   └── themes.css      # Light, dark, and system theme variables
    └── js/
        └── admin-core.js   # Live theme preview class and media frame uploader events
```

---

## 🔄 Changelog

### Version 1.0.4
*   **📏 Sidebar Menu Spacing Refinement**: Adjusted padding for sidebar menu item labels (`.wp-menu-name`) to `8px 8px 8px 0px` for a perfectly balanced visual layout.
*   **🐛 Custom Plugin Icons Conflict Resolution**: Enhanced custom icon checks in JavaScript to prevent replacing icons enqueued by plugins (e.g. Bricks Builder). Implemented scoped CSS targeting to only hide `:before` icons when replaced by Lucide, preserving custom SVG/font icons.
*   **🌗 Robust Dark Mode Contrast**: Injected comprehensive dark theme CSS overrides to ensure labels, descriptions, links, table headers/cells, and tabs remain fully readable in dark and system-dark states.

### Version 1.0.3
*   **🎨 Integrates Lucide SVG Icons**: Replaced default WordPress Dashicons in the sidebar menu with modern, crisp vector Lucide SVG icons (similar to shadcn/ui style).
*   **🌗 Top Bar Theme Toggle Button**: Added a Sun/Moon theme switcher button directly in the WordPress Admin Bar (top-right side next to the user menu) that toggles themes instantly and saves choices persistently via AJAX.
*   **📏 Strict Icon Spacing and Alignment**: Enforced strict left-alignment for all menu links and set a consistent `12px` gap between icons and text.
*   **🌑 Dark Theme Table & Notice Overrides**: Eliminated hardcoded white row backgrounds in list tables (e.g. plugins page) and redesigned WordPress notice boxes (`.notice`) to adapt beautifully to dark mode.

### Version 1.0.2
*   **🐛 Fixed Submenu Flicker**: Resolved the rapid jumping of submenus on expanded sidebar hover by restricting absolute position flyouts to collapsed (`.folded`) or inactive menus.
*   **🎨 Flat Hover Refinements**: Removed all solid background fills on hover/active states of sidebar and admin bar navigation items, switching to a color-only font/stroke accentuation.
*   **🌀 Text Translation**: Added smooth 5px rightward shifts (`transform: translateX(5px)`) for parent menu items and padding transitions for submenu links on hover.
*   **🌐 Inter Font Integration**: Added global typography override to use the **Inter** font family imported from Google Fonts.
*   **📏 Tightened Layout Spacing**: Reduced spacing between icons and menu text to `5px` for a cleaner, unified alignment.
*   **🖋️ Outline-Only Icons**: Replaced solid-filled Dashicons with transparent fills and crisp `1px` outlines (`-webkit-text-stroke`), providing a cleaner, premium visual weight.

### Version 1.0.1
*   **🚀 Transitioned to App Shell Layout**: Replaced the floating macOS style with a flat container design. Both the top admin bar and the left sidebar now extend flush to the margins and merge with the background.
*   **💳 Containerized Workspace Card**: Encapsulated the WordPress workspace area (`#wpbody-content`) in a beautiful tablet card featuring a `16px` border-radius, thin border outlines, and distinct white/dark-graphite backings.
*   **🐛 Fixed Submenu Clipping**: Removed parent sidebar wrapper `overflow: hidden` rules that truncated native WordPress submenus.
*   **🎨 Improved Hover States**: Modified sidebar list item hovers to force active text and outline icon colors to white (`#ffffff !important`) against the solid blue/indigo accent backgrounds.
*   **👁️ Fly-out Dropdown Enhancements**: Added solid opaque backgrounds and clean shadow profiles to absolute fly-out menus to improve legibility against background tables.

---

## 📄 License

Distributed under the GPL v2 or later License.
