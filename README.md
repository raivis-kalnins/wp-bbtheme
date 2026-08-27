# WP BBTheme

WP BBTheme is a modular WordPress parent theme built to work with the WP BBTheme child theme and WP BBuilder plugin. Most functionality is optional and controlled from **Theme Settings -> WP Options**.

## Main Features

- Optional CPT controls with on/off switches
- Booking system with admin dashboard and cleanup tools
- Event CPT with custom fields and single template
- Custom login slug and logged-out `/wp-admin` redirect
- Main menu extras with conditional output
- Developer tools for visual QA and design inspection
- SMTP-ready form delivery through the builder plugin
- Compact Theme Settings control panel with shortcut buttons

## Theme Settings -> General

Everything is off by default unless noted otherwise.

### Optional CPTs
- Booking
- Event
- Products
- Case Study
- Testimonial
- Megamenu

When enabled, the matching CPT becomes available in admin. Megamenu is shown under **Appearance**.

### General Shortcuts
The General tab includes quick links to common admin areas such as Bookings, Events, Products, Case Studies, Testimonials, and Megamenu.

## Theme Settings -> Bookings

### Booking System
The theme can run a lightweight booking flow tied to events.

### Features
- Booking dashboard
- Calendar/table view helpers
- Reply tools
- Active and canceled booking statuses
- Manual delete actions
- Automatic cleanup options

### Cleanup Controls
- Delete canceled bookings
- Delete old bookings
- Old after X days
- Delete canceled now
- Delete old now

## Event CPT

When enabled, the theme registers an Event CPT and event category taxonomy.

### Event Fields
- Event Name
- Event Date
- Event Time
- Location
- Short Description
- Event Details

### Single Event Template
The single event template can output:
- Title or Event Name
- Date
- Time
- Location
- Short Description
- Event Details
- Featured image
- Main content

## Theme Settings -> Developer Tools

All developer tools are optional and off by default.

### Available Tools
- Show Borders
- Show Margins and Paddings
- Typography Inspector
- Color Codes
- Pixel Perfect Overlay
- Mockup image upload
- Mockup opacity

### Purpose
Use these tools during design QA to inspect spacing, typography, colors, and layout alignment against a mockup.

## Main Menu Extras

The theme supports conditional header extras, including:
- Search Bar
- Customer Account
- Mini Cart
- Wishlist
- Mega Menu
- Last Button
- Light/Dark switch
- Language Bar
- Sticky Header

These should only render when switched on in the available settings and matching functionality exists.

## Booking + Events Flow

Recommended setup:
1. Enable Booking
2. Enable Event
3. Create Events
4. Use the event and booking templates/blocks on the frontend
5. Manage requests from Theme Settings -> Bookings

## Builder Plugin Notes

WP BBuilder provides companion functionality for:
- form blocks
- SMTP delivery settings
- booking-related blocks
- events-related blocks

If using form submissions, configure the builder SMTP settings first.

## Layout Notes

The project prefers 12-column based layouts and avoids `col-md-6` defaults where possible.

Recommended classes:
- `col-12`
- `col-12 col-lg-4`
- `col-12 col-lg-3`
- `col-12 col-lg-5`

## Installation Order

1. Install parent theme
2. Install child theme
3. Activate child theme
4. Install builder plugin
5. Configure Theme Settings

## Best Practices

- Keep optional features off until needed
- Use Event CPT for event-driven booking flows
- Keep developer tools disabled on normal usage
- Save permalinks after enabling new CPTs

## Documentation in Admin

A Documentation screen is available from Theme Settings so project editors can review the current feature set without opening theme files.

## Sector demos

`Settings -> Theme Settings -> Demo Import` imports the profile supplied by the active child theme. The import is safe to run repeatedly and always updates the same `demo-homepage` page.

- business profiles create a Homepage, About, Services, Industries, Blog and Contact structure
- commerce profiles let `wp-theme-woo-support` add shop pages, sector products, filters and load more
- the importer creates only `Header Menu` and `Footer Menu` and assigns the two registered locations
- switching profiles replaces importer-owned demo products; it never deletes ordinary shop products
- child themes supply profiles with `wp_theme_demo_profile` and integrations extend imports through `wp_theme_before_demo_import`, `wp_theme_demo_extra_home_sections` and navigation filters

## Asset ownership

The parent owns PHP structure, settings and shared functionality only. It enqueues only its metadata stylesheet and does not ship a frontend build. Frontend presentation and JavaScript belong to each child theme. The default child owns the full Vite build; sector children own their standalone sector styles, navigation script and demo-import admin asset.

WooCommerce behaviour is supplied by the separate `wp-theme-woo-support` plugin.
