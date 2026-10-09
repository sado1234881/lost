# Clearance UI/UX Design System Specification
### Universal design system extracted from the University Clearance System

This specification defines a comprehensive, responsive, dashboard-centric design system featuring a clean dark-navy/royal-blue visual hierarchy, a robust dark/light mode engine, and unified UI patterns. Developed for high-density administrative, checklist, and audit workflows, it relies on Tailwind CSS v4 and Font Awesome icons.

---

## 1. Design Philosophy & Visual Identity

The **Clearance Design System** focuses on structural clarity, visual hierarchy, and reliable interactive feedback. It is designed to make complex administrative workflows feel clean, professional, and highly trustworthy.

### Core Visual Principles:
*   **High Contrast & Readability**: Content panels use crisp, bordered surfaces (`border-slate-200` in light mode; `border-zinc-800` in dark mode) on light-slate or deep-zinc backdrops.
*   **Aesthetic Blue Accents**: Primary accents leverage institutional dark navy blue (`#1E3A8A`) and secondary actions use royal blue (`#2563EB`) to establish professional authority.
*   **Intent-Driven Coloring**: Statuses are coded using explicit green (success), amber (warning), red/rose (danger), and blue/purple/indigo (info/reviews).
*   **Glassmorphism & Depth**: Overlay surfaces (modals, authentication blocks, maintenance screens) utilize backdrop blur filters (`blur(16px)` or `blur(20px)`) and semi-transparent backdrops for spatial layering.

---

## 2. Design Tokens

Design tokens represent the visual values of the design system. Since this project utilizes Tailwind CSS v4, these tokens are integrated into the CSS theme layer and utility classes.

### A. Color Palette
The system utilizes a structured light/dark palette. The base values are derived from Tailwind's default colors, alongside custom branding variables.

| Token Name | Light Mode Value | Dark Mode Value | Usage / Description |
| :--- | :--- | :--- | :--- |
| **Primary Navy** | `#1E3A8A` (`blue-900`) | `#3B82F6` (`blue-500` / `zinc-300`) | Main accent, primary buttons, branding icons, headings |
| **Secondary Royal** | `#2563EB` (`blue-600`) | `#60A5FA` (`blue-400`) | Interactive text, focus indicators, secondary badges |
| **Dark Navy (Deep)** | `#0F172A` (`slate-900`) | `#09090b` (`zinc-950`) | Sidebar background, body background in auth & maintenance |
| **Success** | `bg-emerald-50 text-emerald-800` | `dark:bg-emerald-950/20 dark:text-emerald-400` | Satisfied checklist item, active account, success alert |
| **Warning / Alert** | `bg-amber-50 text-amber-800` | `dark:bg-amber-950/20 dark:text-amber-400` | Awaiting review, outstanding checklist, maintenance |
| **Danger / Error** | `bg-red-50 text-red-800` | `dark:bg-red-950/20 dark:text-red-400` | Rejected items, errors, disabled accounts, delete actions |
| **Info / Status** | `bg-indigo-50 text-indigo-800` | `dark:bg-indigo-950/25 dark:text-indigo-400` | Review queue status, finalist status, category markers |
| **Base Background** | `#F8FAFC` (`slate-50`) | `#09090B` (`zinc-950`) | Global page background |
| **Surface Background** | `#FFFFFF` (`white`) | `#18181B` (`zinc-900`) | Cards, tables, modals, dropdowns |
| **Borders** | `#E2E8F0` (`slate-200`) | `#27272A` (`zinc-800`) | Card boundaries, input outlines, table rows |
| **Divider Line** | `#F1F5F9` (`slate-100`) | `#18181B` (`zinc-800`) | Inside cards, profile dropdown lines |

### B. Typography
The system enforces **Inter** as the primary font face for UI copy, overriding the default tailwind configuration inside the global page context.

*   **Primary System Font**: `'Inter', sans-serif`
*   **Scale and Hierarchies**:
    *   **Page Title**: `text-2xl font-bold tracking-tight text-slate-900 dark:text-white` (24px, 700 weight, tight tracking)
    *   **Card Title / Sub-section**: `font-bold text-slate-800 dark:text-white text-base` (16px, 700 weight)
    *   **Body Copy**: `text-sm` (14px) and globally scaled to `15px` (`body { font-size: 15px; }`)
    *   **Small Captions**: `text-xs` (12px, 500/600 weight) or `text-[11px]` / `text-[10px]` (for metadata, badges, timestamps)
*   **Font Weights**:
    *   `font-bold` (700): Page titles, card headers, table cells of significance.
    *   `font-semibold` (600): Secondary headings, navigation item links, button labels.
    *   `font-medium` (500): Secondary texts, helper copies.
    *   `font-normal` (400): Standard labels, descriptions.

### C. Spacing & Grid System
The spacing rules dictate padding, margin, layout grids, and containers:
*   **Core Layout Grid**: Grid columns utilize `gap-6` (24px) for consistency.
*   **Standard Card Padding**: `p-6` (24px) padding is applied to standard cards.
*   **Layout Spacers**: Sections are spaced using vertical margins of `mb-6` (24px).
*   **Grid Breakpoints**:
    *   `sm`: `40rem` (640px)
    *   `md`: `48rem` (768px)
    *   `lg`: `64rem` (1024px) — *Trigger point for showing static sidebar*
    *   `xl`: `80rem` (1280px)
*   **Interactive Radius Rules**:
    *   **Modals & Large Blocks**: `rounded-3xl` (24px)
    *   **Dashboard Cards & Tables**: `rounded-2xl` (16px)
    *   **Inputs & Buttons**: `rounded-xl` (12px)
    *   **Navigation Links & Small Action buttons**: `rounded-lg` (8px)

### D. CSS Animations & Transitions
Smooth micro-interactions distinguish the design.

*   **Universal Smooth Transition**: `.transition-all-custom`
    ```css
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    ```
*   **Theme Color Duration**: `transition-colors duration-200`
*   **Entrance Fade In (Up)**: `.animate-fade-in-up`
    ```css
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    ```
*   **Slow Pulsing (Decorative Background Blobs)**: `.animate-pulse-slow`
    ```css
    @keyframes pulse-slow {
        0%, 100% { transform: scale(1); opacity: 0.2; }
        50% { transform: scale(1.1); opacity: 0.35; }
    }
    .animate-pulse-slow {
        animation: pulse-slow 8s ease-in-out infinite;
    }
    ```
*   **Slow Spinning Gears (Maintenance Page)**: `.spin-slow`
    ```css
    @keyframes gear-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .spin-slow {
        animation: gear-spin 12s linear infinite;
    }
    ```

---

## 3. Layout Architecture Specification

The layout employs a fixed-header, sticky-sidebar content frame. On mobile viewports, the sidebar collapses into a slide-out overlay controlled by JavaScript.

```
+-------------------------------------------------------------------+
|                           TOP NAVBAR (Fixed, h-[70px])            |
+---------------------+---------------------------------------------+
| SIDEBAR             | BREADCRUMBS & PAGE HEADER                   |
| (w-[260px], static  |                                             |
| on desktop, absolute| FLASH ALERTS (SUCCESS/ERROR)                |
| overlay on mobile)  |                                             |
|                     | MAIN CONTENT PANEL                          |
|                     |                                             |
|                     |                                             |
|                     |                                             |
|                     | FOOTER                                      |
+---------------------+---------------------------------------------+
```

### A. Top Navbar (Header)
*   **Height**: `70px` (`h-[70px]`)
*   **Positioning**: `fixed top-0 left-0 right-0 z-40`
*   **Styles**: `bg-white dark:bg-zinc-900 border-b border-slate-200 dark:border-zinc-800 shadow-sm px-6 flex items-center justify-between`
*   **Left Section**: Contains a hamburger trigger for mobile toggle (`lg:hidden`), alongside a logo badge and the application brand title.
*   **Center Section**: Hosts the global search input block.
*   **Right Section**: Contains theme selectors, notifications toggle, and the profile menu dropdown.

### B. Sidebar
*   **Width**: `260px` (`w-[260px]`)
*   **Positioning**: `fixed inset-y-0 left-0 lg:static z-30 pt-[70px] lg:pt-0`
*   **Transition**: `transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out`
*   **Background**: Deep Navy (`#0F172A` / `.bg-navy-dark`) with light grey slate text (`text-slate-300`).
*   **Menu Groups**: Organized by uppercase light-grey labels (`text-slate-500 text-[11px] uppercase tracking-wider font-semibold px-3`).
*   **Active Item State**: Marked by `.bg-slate-800.text-white`.
*   **Footer Block**: Contains a dedicated Sign Out element anchored to the bottom.

### C. Page Header
*   **Layout**: `flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6`
*   **Breadcrumbs**: Positioned top-left (`text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide`).
*   **Page Title**: Main `<h1>` block (`text-2xl font-bold tracking-tight text-slate-900 dark:text-white`).
*   **Quick Actions**: Aligned to the right, enabling action buttons to inject smoothly into the view.

---

## 4. Reusable HTML/CSS Component Catalogue

The following code snippets are optimized for Tailwind CSS v4 and contain exact structures, icons, and state styles.

### A. Dashboard Card (Stats Card)
```html
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm flex items-center justify-between">
    <div class="space-y-1">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Items</span>
        <span class="text-2xl font-bold text-slate-900 dark:text-white">1,248</span>
        <span class="text-[11px] text-emerald-500 font-semibold block">
            <i class="fa-solid fa-circle text-[6px] mr-1 align-middle animate-pulse"></i>Active Indicators
        </span>
    </div>
    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-zinc-800 text-blue-900 dark:text-blue-400 flex items-center justify-center text-xl">
        <i class="fa-solid fa-user-graduate"></i>
    </div>
</div>
```

### B. Standard Data Table
```html
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-slate-100 dark:border-zinc-800 text-xs text-slate-400 dark:text-zinc-500 uppercase tracking-wider font-semibold">
                    <th class="pb-3 w-8">
                        <input type="checkbox" class="rounded border-slate-200 dark:border-zinc-700 text-blue-600 focus:ring-blue-500">
                    </th>
                    <th class="pb-3">Registration & Name</th>
                    <th class="pb-3">Department Review</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-sm">
                <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                    <td class="py-4">
                        <input type="checkbox" class="rounded border-slate-200 dark:border-zinc-700 text-blue-600 focus:ring-blue-500">
                    </td>
                    <td class="py-4">
                        <span class="font-bold text-slate-900 dark:text-white block">2023/0123</span>
                        <span class="text-xs text-slate-400">Erick Peter</span>
                    </td>
                    <td class="py-4 text-xs text-slate-500">Finance & Student Accounts</td>
                    <td class="py-4 text-xs">
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900 uppercase">
                            Cleared
                        </span>
                    </td>
                    <td class="py-4 text-right space-x-1.5 whitespace-nowrap">
                        <button class="bg-blue-50 hover:bg-blue-100 dark:bg-zinc-850 dark:hover:bg-zinc-800 text-blue-900 dark:text-blue-400 text-xs px-2.5 py-1 rounded font-semibold transition-colors">
                            Edit
                        </button>
                        <button class="bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/40 text-red-600 dark:text-red-400 text-xs px-2 py-1 rounded font-semibold transition-colors">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
```

### C. Forms and Form Controls
```html
<form class="space-y-4">
    <!-- Text Input field -->
    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Registration Number</label>
        <input type="text" required placeholder="e.g. 2023/0123" class="w-full px-4 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white transition-all">
    </div>

    <!-- Select Dropdown -->
    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Select Category</label>
        <select class="w-full px-4 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
            <option value="regular">Regular Student</option>
            <option value="resident">Hostel Resident</option>
        </select>
    </div>

    <!-- Textarea -->
    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Comments / Reason</label>
        <textarea rows="3" placeholder="Provide extra feedback..." class="w-full px-4 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white"></textarea>
    </div>
</form>
```

### D. Buttons
```html
<!-- Primary Action Button (Navy Blue) -->
<button class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2.5 px-4 rounded-xl text-sm shadow-md transition-all">
    Save Checklist Changes
</button>

<!-- Secondary Neutral Button (Gray/Slate) -->
<button class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 rounded-xl text-sm font-semibold transition-all">
    Cancel Action
</button>

<!-- Danger Action Button -->
<button class="bg-red-650 hover:bg-red-600 text-white font-semibold py-2.5 px-4 rounded-xl text-sm shadow-md transition-all">
    Delete Selected
</button>
```

### E. Alert Panels
```html
<!-- Success Alert -->
<div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm flex items-center gap-3">
    <i class="fa-solid fa-circle-check text-lg"></i>
    <span>Clearance application has been approved successfully!</span>
</div>

<!-- Error Alert -->
<div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 text-sm flex items-center gap-3">
    <i class="fa-solid fa-circle-exclamation text-lg"></i>
    <span>Validation failed: registration number could not be recognized.</span>
</div>
```

### F. Badges & Pills
```html
<!-- Active / Approved Pill -->
<span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900 uppercase">
    Active
</span>

<!-- Inactive / Danger Pill -->
<span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-900 uppercase">
    Disabled
</span>

<!-- Information Status Pill (Purple style) -->
<span class="px-2.5 py-1 rounded-full font-bold text-[11px] uppercase tracking-wider bg-purple-50 dark:bg-purple-950/25 text-purple-700 dark:text-purple-400 border border-purple-100 dark:border-purple-900">
    Finalist
</span>
```

### G. Modal Dialog
```html
<div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-xl w-full max-w-md mx-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100 dark:border-zinc-800">
            <h3 class="font-bold text-slate-800 dark:text-white text-sm">Action Confirmed</h3>
            <button class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>
        <!-- Modal body copy -->
        <p class="text-xs text-slate-500 dark:text-zinc-400 mb-6">Are you sure you want to perform this operation? This step cannot be reverted.</p>
        <!-- Action Buttons -->
        <div class="flex justify-end gap-2">
            <button class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 rounded-xl text-sm font-semibold">Cancel</button>
            <button class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-xl text-sm font-semibold">Confirm Action</button>
        </div>
    </div>
</div>
```

### H. Context Dropdown Menu
```html
<div class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl shadow-lg z-50 py-2">
    <a href="#" class="flex items-center gap-2 px-4 py-2 text-xs hover:bg-slate-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300">
        <i class="fa-solid fa-key w-4 text-center"></i>
        <span>Change Password</span>
    </a>
    <hr class="border-slate-100 dark:border-zinc-800 my-1">
    <button class="w-full flex items-center gap-2 px-4 py-2 text-xs hover:bg-red-50 dark:hover:bg-red-950/20 text-red-600 dark:text-red-400 text-left font-semibold">
        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
        <span>Logout Session</span>
    </button>
</div>
```

### I. Navigation Breadcrumbs
```html
<nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
    <a href="#" class="hover:underline">Home Directory</a>
    <i class="fa-solid fa-angle-right text-[10px]"></i>
    <span class="text-slate-600 dark:text-zinc-400">Current Dashboard</span>
</nav>
```

### J. Empty State Area
```html
<div class="text-center py-12">
    <div class="w-16 h-16 bg-blue-50 dark:bg-zinc-800 text-blue-900 dark:text-blue-400 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
        <i class="fa-solid fa-folder-open"></i>
    </div>
    <h4 class="font-bold text-slate-700 dark:text-white text-base">No active records found</h4>
    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">There are no records currently registered in this database view.</p>
</div>
```

### K. Compliance Radial Progress Circle
```html
<div class="flex flex-col items-center text-center">
    <div class="relative w-40 h-40 flex items-center justify-center">
        <!-- SVG Container -->
        <svg class="w-full h-full transform -rotate-90">
            <!-- Background circle track -->
            <circle cx="80" cy="80" r="70" stroke-width="10" class="stroke-slate-100 dark:stroke-zinc-800" fill="transparent"/>
            <!-- Active Fill Circle (Formula: stroke-dashoffset = 440 - (440 * percentage) / 100) -->
            <circle cx="80" cy="80" r="70" stroke-width="10" class="stroke-blue-900 dark:stroke-blue-500 transition-all duration-500" fill="transparent"
                stroke-dasharray="440" stroke-dashoffset="110" stroke-linecap="round"/>
        </svg>
        <div class="absolute flex flex-col items-center">
            <span class="text-3xl font-extrabold text-slate-900 dark:text-white">75%</span>
            <span class="text-[11px] text-slate-400 uppercase font-bold tracking-wider mt-1">Cleared</span>
        </div>
    </div>
</div>
```

### L. Event Timeline
```html
<div class="relative pl-6 border-l-2 border-slate-100 dark:border-zinc-800 space-y-6">
    <!-- Step item -->
    <div class="relative">
        <!-- Bullet Marker -->
        <span class="absolute -left-[31px] top-0 w-4 h-4 rounded-full bg-white dark:bg-zinc-900 border-2 border-blue-900 flex items-center justify-center">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-900"></span>
        </span>
        <div class="flex justify-between items-center">
            <h4 class="font-bold text-xs text-slate-900 dark:text-white">Application Submitted</h4>
            <span class="text-[10px] text-slate-400">10 mins ago</span>
        </div>
        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Exit clearance workflow initialized by academic office verification.</p>
    </div>
</div>
```

---

## 5. CSS & Javascript Architecture

### CSS Architecture
*   **Tailwind CSS v4 Integration**: Utilizes `@import "tailwindcss";` inside `app.css`. The tailwind directives map custom sources to track template updates.
*   **Early Theme Engine**: Solves the flickering issue by placing a theme checker script at the very top of `<head>` tag.
    ```javascript
    const theme = localStorage.getItem('theme') || 'system';
    if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    ```
*   **Custom Global Rules**:
    *   Set font size globally to `15px` inside custom css.
    *   Define `.bg-navy-dark` (`#0F172A`) explicitly inside inline stylesheet as backup color tokens.

### Javascript Conventions
*   **Interactive Controllers**: Toggles components (Sidebar, notifications, user settings) by adding and removing classes directly via inline vanilla JS click listeners.
*   **State Listeners**: Binds custom listeners to window click to handle closing absolute elements (such as notification panels, theme lists) when clicking outside.
*   **Form Helpers**: Inlines short utility handlers to set checkboxes on table bulk selectors dynamically (e.g. `toggleSelectAll(master)`).

---

## 6. Migration Guide: Rebuilding the Design Language in a New Laravel Project

Follow this guide to replicate the exact UI/UX design language on a completely different Laravel project while maintaining different business models and controllers.

### Step 1: Initialize Tailwind CSS v4 in the New Project
In your new Laravel codebase, ensure you've installed Vite, Tailwind CSS v4, and the Laravel plugin:
```bash
npm install tailwindcss @tailwindcss/vite laravel-vite-plugin --save-dev
```

### Step 2: Configure `vite.config.js`
In the root directory, replace the contents of `vite.config.js` to enable Tailwind v4 compiling and load the font stack:
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
});
```

### Step 3: Set Up `resources/css/app.css`
Replace the contents of `resources/css/app.css` with the Tailwind v4 imports and custom font configurations:
```css
@import 'tailwindcss';

@custom-variant dark (&:where(.dark, .dark *));

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';

@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
}
```

### Step 4: Replicate the Master Layout Template
Create a new file `resources/views/layouts/app.blade.php`. This master structure contains the top bar, sidebar logic, notification container, light/dark mode engine, and script hooks.

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Application')</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            font-size: 15px;
        }
        .bg-navy-dark {
            background-color: #0F172A;
        }
        .theme-primary {
            color: #1E3A8A;
        }
        .bg-theme-primary {
            background-color: #1E3A8A;
        }
        .theme-secondary {
            color: #2563EB;
        }
        .bg-theme-secondary {
            background-color: #2563EB;
        }
        .transition-all-custom {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .radial-progress-custom {
            transform: rotate(-90deg);
        }
    </style>
    
    <script>
        function applyTheme() {
            const theme = localStorage.getItem('theme') || 'system';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (theme === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        }
        applyTheme();
        
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if ((localStorage.getItem('theme') || 'system') === 'system') {
                applyTheme();
                if (typeof updateActiveThemeIcon === 'function') {
                    updateActiveThemeIcon();
                }
            }
        });
    </script>
</head>
<body class="h-full bg-slate-50 dark:bg-zinc-950 text-slate-800 dark:text-zinc-200 transition-colors duration-200">
    <div class="min-h-full flex flex-col">
        
        <!-- TOP NAVBAR -->
        <header class="fixed top-0 left-0 right-0 z-40 bg-white dark:bg-zinc-900 border-b border-slate-200 dark:border-zinc-800 shadow-sm h-[70px] flex items-center justify-between px-6">
            <div class="flex items-center gap-4">
                <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-lg">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-900 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        <i class="fa-solid fa-folder"></i>
                    </div>
                    <span class="font-bold text-lg tracking-tight text-blue-950 dark:text-white hidden sm:inline-block">System Portal</span>
                </div>
            </div>

            <!-- Options -->
            <div class="flex items-center gap-4">
                <!-- Theme Dropdown -->
                <div class="relative inline-block text-left" id="theme-dropdown-container">
                    <button onclick="toggleThemeDropdown(event)" class="p-2 text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-lg focus:outline-none flex items-center justify-center" id="theme-menu-btn">
                        <i id="active-theme-icon" class="fa-solid fa-desktop text-lg"></i>
                    </button>
                    <div id="theme-menu-dropdown" class="hidden absolute right-0 mt-2 w-40 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl shadow-lg z-50 py-1">
                        <button onclick="setTheme('light')" class="w-full flex items-center gap-2 px-4 py-2.5 text-xs text-left hover:bg-slate-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-medium">
                            <i class="fa-solid fa-sun w-4 text-amber-500"></i><span>Light</span>
                        </button>
                        <button onclick="setTheme('dark')" class="w-full flex items-center gap-2 px-4 py-2.5 text-xs text-left hover:bg-slate-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-medium">
                            <i class="fa-solid fa-moon w-4 text-blue-500"></i><span>Dark</span>
                        </button>
                        <button onclick="setTheme('system')" class="w-full flex items-center gap-2 px-4 py-2.5 text-xs text-left hover:bg-slate-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-medium">
                            <i class="fa-solid fa-desktop w-4 text-slate-500"></i><span>System Default</span>
                        </button>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button onclick="toggleProfileDropdown()" class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-zinc-800 focus:outline-none">
                        <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-900 flex items-center justify-center font-bold text-sm">
                            AD
                        </div>
                        <div class="hidden lg:flex flex-col text-left mr-1">
                            <span class="font-semibold text-sm leading-none text-slate-900 dark:text-white block">User Name</span>
                            <span class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1 block">Administrator</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl shadow-lg z-50 py-2">
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-xs hover:bg-slate-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300">
                            <i class="fa-solid fa-user w-4 text-center"></i><span>Profile Settings</span>
                        </a>
                        <hr class="border-slate-100 dark:border-zinc-800 my-1">
                        <button class="w-full flex items-center gap-2 px-4 py-2 text-xs hover:bg-red-50 dark:hover:bg-red-950/20 text-red-600 dark:text-red-400 text-left font-semibold">
                            <i class="fa-solid fa-right-from-bracket w-4 text-center"></i><span>Logout</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- SIDEBAR & CONTAINER -->
        <div class="flex flex-1 pt-[70px]">
            <aside id="sidebar-panel" class="fixed inset-y-0 left-0 lg:static z-30 w-[260px] bg-navy-dark text-slate-300 flex flex-col justify-between pt-[70px] lg:pt-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">
                <div class="flex-1 py-6 overflow-y-auto px-4 space-y-7">
                    <div class="space-y-2">
                        <span class="text-slate-500 text-[11px] uppercase tracking-wider font-semibold px-3">System Menu</span>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors bg-slate-800 text-white">
                            <i class="fa-solid fa-chart-line w-5 text-center"></i><span>Overview</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
                            <i class="fa-solid fa-sliders w-5 text-center"></i><span>Settings</span>
                        </a>
                    </div>
                </div>
                <div class="p-4 border-t border-slate-800">
                    <button class="w-full flex items-center justify-center gap-3 bg-red-600/10 hover:bg-red-600 hover:text-white text-red-500 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all">
                        <i class="fa-solid fa-right-from-bracket"></i><span>Sign Out</span>
                    </button>
                </div>
            </aside>
            
            <main class="flex-1 flex flex-col p-6 overflow-hidden">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
                            <a href="#" class="hover:underline">System</a>
                            <i class="fa-solid fa-angle-right text-[10px]"></i>
                            <span class="text-slate-600 dark:text-zinc-400">@yield('breadcrumb', 'Dashboard')</span>
                        </nav>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">@yield('page_title', 'Overview')</h1>
                    </div>
                    <div class="flex items-center gap-2">
                        @yield('quick_actions')
                    </div>
                </div>

                <div class="flex-1">
                    @yield('content')
                </div>

                <footer class="mt-12 py-6 border-t border-slate-200 dark:border-zinc-800 text-center text-xs text-slate-400 dark:text-zinc-500 flex flex-col sm:flex-row justify-between items-center gap-2">
                    <span>&copy; {{ date('Y') }} Organization | Version 1.0</span>
                    <span>All Rights Reserved</span>
                </footer>
            </main>
        </div>
    </div>

    <!-- Toggle scripts for responsive layout -->
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar-panel');
            sidebar.classList.toggle('-translate-x-full');
        }
        
        function toggleThemeDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('theme-menu-dropdown');
            dropdown.classList.toggle('hidden');
        }

        function setTheme(mode) {
            localStorage.setItem('theme', mode);
            applyTheme();
            updateActiveThemeIcon();
            document.getElementById('theme-menu-dropdown').classList.add('hidden');
        }

        function updateActiveThemeIcon() {
            const theme = localStorage.getItem('theme') || 'system';
            const icon = document.getElementById('active-theme-icon');
            if (!icon) return;
            icon.className = 'fa-solid text-lg';
            if (theme === 'light') {
                icon.classList.add('fa-sun', 'text-amber-500');
            } else if (theme === 'dark') {
                icon.classList.add('fa-moon', 'text-blue-500');
            } else {
                icon.classList.add('fa-desktop', 'text-slate-500');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateActiveThemeIcon();
        });

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.toggle('hidden');
        }

        window.addEventListener('click', function(e) {
            const profileDropdown = document.getElementById('profile-dropdown');
            if (profileDropdown && !profileDropdown.contains(e.target) && !e.target.closest('button')) {
                profileDropdown.classList.add('hidden');
            }
            const themeDropdown = document.getElementById('theme-menu-dropdown');
            if (themeDropdown && !themeDropdown.contains(e.target) && !e.target.closest('#theme-menu-btn')) {
                themeDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
```

### Step 5: Implement Content Views
Use the layout block inside your child views (`@extends('layouts.app')`), and construct components by utilizing the HTML blocks in the catalogue section above. Build custom views using the clean card-based grid blocks and bordered table lists to preserve visual coherence.
