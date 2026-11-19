<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'POSS') ?> — POSS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#f0f5ff',
                            100: '#e0eaff',
                            500: '#1e3a8a',
                            600: '#172554',
                            700: '#111827',
                            800: '#0f172a',
                            900: '#020617',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>
    <script>
        // Apply sidebar and theme state immediately to prevent flash
        const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        const isDark = localStorage.getItem('theme') === 'dark';
        
        if (isCollapsed) document.documentElement.classList.add('sidebar-collapsed');
        if (isDark) document.documentElement.classList.add('dark');
    </script>
    <style>
        /* Global Select Box Styling */
        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNjQ3NDhiIiBzdHJva2Utd2lkdGg9IjIuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cG9seWxpbmUgcG9pbnRzPSI2IDkgMTIgMTUgMTggOSI+PC9wb2x5bGluZT48L3N2Zz4=") !important;
            background-repeat: no-repeat !important;
            background-position: right 1rem center !important;
            background-size: 1em !important;
            padding-right: 2.8rem !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            outline: none !important;
        }
        
        select:hover {
            border-color: #cbd5e1 !important;
        }

        select:focus {
            border-color: #1e3a8a !important;
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1) !important;
        }

        .dark select {
            background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjOTRhM2I4IiBzdHJva2Utd2lkdGg9IjIuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cG9seWxpbmUgcG9pbnRzPSI2IDkgMTIgMTUgMTggOSI+PC9wb2x5bGluZT48L3N2Zz4=") !important;
        }

        .dark select:hover {
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        .dark select:focus {
            border-color: rgba(30, 58, 138, 0.5) !important;
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.2) !important;
        }

        /* Standardize options for dark mode browsers that support it */
        select option {
            background-color: white !important;
            color: #1e293b !important;
            padding: 12px !important;
            font-family: inherit !important;
        }

        .dark select option {
            background-color: #1a1a1a !important;
            color: #f8fafc !important;
        }

        /* Premium Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
        ::-webkit-scrollbar-thumb:hover { background: #a8a29e; }
        .dark ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        /* Global Typography & Selection */
        ::selection { background-color: rgba(30, 58, 138, 0.3); color: #1e3a8a; }
        .dark ::selection { background-color: rgba(30, 58, 138, 0.5); color: #f0f5ff; }
        html { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; scroll-behavior: smooth; }
    </style>
    <style type="text/tailwindcss">
        @layer components {
            .sidebar-link {
                @apply flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-[#efece6] hover:text-brand-500 text-[13px] font-medium transition-all duration-200;
            }
            .sidebar-link-active {
                @apply flex items-center gap-3 px-4 py-2 rounded-lg bg-gradient-to-r from-[#0f172a] to-[#1e3a8a] text-white text-[13px] font-medium shadow-md shadow-brand-500/20;
            }
            .sidebar-collapsed .sidebar-link {
                @apply justify-center px-0 mx-4;
            }
            .sidebar-collapsed .sidebar-label, 
            .sidebar-collapsed .sidebar-header-text,
            .sidebar-collapsed .sidebar-logo-text {
                @apply hidden;
            }
            .sidebar-collapsed aside {
                @apply w-20;
            }
            .sidebar-collapsed .sidebar-section-header {
                @apply justify-center px-0;
            }
            /* Dark Mode Adjustments */
            .dark .sidebar-link {
                @apply text-slate-400 hover:bg-[#1a1a1a] hover:text-white;
            }
            .dark .sidebar-link-active {
                @apply bg-gradient-to-br from-[#2563eb] to-[#1e40af] text-white shadow-lg shadow-blue-900/30 border border-blue-400/20;
            }
            /* Prevent transition on page load */
            .no-transition * {
                transition: none !important;
            }
            /* Hide scrollbar utility */
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }
            /* Global Button Overrides for Dark Mode */
            .dark .bg-brand-500 {
                @apply bg-gradient-to-br from-[#2563eb] to-[#1e40af] border border-blue-400/20 shadow-lg shadow-blue-900/20;
            }
            .dark .hover\:bg-brand-600:hover {
                @apply from-[#3b82f6] to-[#2563eb];
            }

            /* Interactive Lift Effect for Main Cards */
            .rounded-3xl {
                @apply transition-all duration-300 ease-out;
            }
            .rounded-3xl:hover {
                @apply shadow-xl shadow-black/5 dark:shadow-brand-500/5 -translate-y-0.5;
            }

            /* Refined Form Controls */
            input, textarea {
                @apply outline-none transition-all duration-200;
            }
            input:focus, textarea:focus {
                @apply ring-2 ring-brand-500/10 border-brand-500/40;
            }

            /* Micro-interactions */
            button:active, a:active {
                @apply scale-[0.98] transition-transform;
            }

            /* Global Table Styling */
            table {
                @apply w-full border-collapse;
            }
            th {
                @apply text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest px-6 py-4 text-left border-b border-stone-200/50 dark:border-white/5 bg-stone-50/50 dark:bg-white/5;
            }
            td {
                @apply px-6 py-4 text-[13px] border-b border-stone-100 dark:border-white/5 transition-colors;
            }
            tr:hover td {
                @apply bg-stone-50/50 dark:bg-white/5;
            }

            /* Sidebar Glow */
            .sidebar-link-active {
                @apply relative overflow-hidden;
            }
            .sidebar-link-active::after {
                content: '';
                @apply absolute inset-0 bg-white/10 blur-xl opacity-50;
            }
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-[#f2efe9] dark:bg-[#0a0a0a] text-slate-800 dark:text-slate-200 font-sans h-screen overflow-hidden transition-colors duration-200">

<!-- ── Ambient Lighting Layer ─────────────────────────────────────────────── -->
<div class="fixed inset-0 pointer-events-none z-0 overflow-hidden" aria-hidden="true">
    <!-- Top-right warm glow: amber/orange in light, brand-blue in dark -->
    <div class="absolute -top-40 -right-40 w-[600px] h-[600px] rounded-full
        bg-amber-200/20 dark:bg-brand-500/10
        blur-[120px]">
    </div>
    <!-- Bottom-left cool glow: sky-blue/indigo in light, emerald in dark -->
    <div class="absolute -bottom-40 -left-24 w-[500px] h-[500px] rounded-full
        bg-sky-200/15 dark:bg-emerald-500/5
        blur-[100px]">
    </div>
    <!-- Centre subtle warmth: barely visible in light, invisible in dark -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] rounded-full
        bg-rose-100/5 dark:bg-transparent
        blur-[140px]">
    </div>
</div>
<!-- ─────────────────────────────────────────────────────────────────────────── -->

<?php
$currentUser     = Auth::user();
$userRole        = Auth::role();
$unreadCount     = Auth::check() ? Notification::unreadCount(Auth::id()) : 0;
$currentPath     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$roleLabels = [
    'super_admin'  => ['Super Admin', 'bg-purple-100 text-purple-700'],
    'admin'        => ['Admin',       'bg-blue-100 text-blue-700'],
    'manager'      => ['Manager',     'bg-green-100 text-green-700'],
    'stock_keeper' => ['Stock Keeper','bg-amber-100 text-amber-700'],
    'cashier'      => ['Cashier',     'bg-slate-100 text-slate-600'],
];
[$roleLabel, $roleCls] = $roleLabels[$userRole] ?? [$userRole, 'bg-slate-100 text-slate-600'];

function navLink(string $href, string $label, string $icon, string $currentPath): string {
    $active = str_starts_with($currentPath, $href);
    $cls = $active ? 'sidebar-link-active' : 'sidebar-link';
    return "
    <a href=\"{$href}\" class=\"{$cls}\" title=\"{$label}\">
        <span class=\"w-5 h-5 flex items-center justify-center flex-shrink-0\">{$icon}</span>
        <span class=\"sidebar-label truncate\">{$label}</span>
    </a>";
}
?>

<div class="flex h-screen overflow-hidden relative z-10">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#eae6de] dark:bg-[#111111] flex flex-col flex-shrink-0 shadow-[12px_0_35px_-10px_rgba(0,0,0,0.15)] dark:shadow-none z-20 border-r border-stone-200/50 dark:border-white/5 font-sans relative transition-all duration-300 ease-in-out">
        <!-- Logo & App Name -->
        <div class="px-5 pt-6 pb-4 flex items-center gap-2.5">
            <div class="w-7 h-7 bg-slate-900 dark:bg-brand-500 rounded-md flex items-center justify-center text-white font-black text-sm flex-shrink-0">P</div>
            <span class="text-lg font-bold text-slate-900 dark:text-white tracking-tight sidebar-logo-text transition-opacity duration-200">POSS</span>
        </div>

        <!-- Search -->
        <div class="px-4 mb-6 sidebar-logo-text">
            <div class="relative group">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 group-focus-within:text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search..." class="w-full bg-stone-50/50 dark:bg-white/5 border border-stone-200/60 dark:border-white/10 rounded-lg pl-9 pr-3 py-1.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-brand-500 focus:bg-white dark:focus:bg-white/10 transition-all placeholder-slate-400">
            </div>
        </div>

        <nav class="flex-1 px-2 space-y-0.5 overflow-y-auto scrollbar-hide">
            <div class="px-3 py-2 flex items-center justify-between group sidebar-section-header">
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest sidebar-header-text">Navigation</span>
            </div>
            <?php if (Auth::can('dashboard')): ?>
                <?= navLink('/dashboard', 'Dashboard', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>', $currentPath) ?>
            <?php endif; ?>
            <?php if (Auth::can('pos')): ?>
                <?= navLink('/pos', 'POS Terminal', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>', $currentPath) ?>
            <?php endif; ?>

            <!-- Inventory Section -->
            <div class="px-3 py-3 flex items-center justify-between group mt-2 sidebar-section-header">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest sidebar-header-text">Inventory</span>
            </div>
            <?php if (Auth::can('products')): ?>
                <?= navLink('/products', 'Products', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>', $currentPath) ?>
            <?php endif; ?>
            <?php if (Auth::can('categories')): ?>
                <?= navLink('/categories', 'Categories', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h.01M11 11h.01M11 15h.01M15 7h.01M15 11h.01M15 15h.01"/></svg>', $currentPath) ?>
            <?php endif; ?>
            <?php if (Auth::can('suppliers')): ?>
                <?= navLink('/suppliers', 'Suppliers', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>', $currentPath) ?>
            <?php endif; ?>
            <?php if (Auth::can('stock')): ?>
                <?= navLink('/stock', 'Stock Levels', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>', $currentPath) ?>
            <?php endif; ?>
            <?php if (Auth::can('purchases')): ?>
                <?= navLink('/purchases', 'Purchases', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>', $currentPath) ?>
            <?php endif; ?>

            <!-- Sales & CRM -->
            <div class="px-3 py-3 flex items-center justify-between group mt-2 sidebar-section-header">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest sidebar-header-text">Sales & CRM</span>
            </div>
            <?php if (Auth::can('customers')): ?>
                <?= navLink('/customers', 'Customers', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>', $currentPath) ?>
            <?php endif; ?>
            <?php if (Auth::can('sales')): ?>
                <?= navLink('/sales', 'Sales History', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>', $currentPath) ?>
            <?php endif; ?>

            <!-- Finance -->
            <div class="px-3 py-3 flex items-center justify-between group mt-2 sidebar-section-header">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest sidebar-header-text">Finance</span>
            </div>
            <?php if (Auth::can('transactions')): ?>
                <?= navLink('/transactions', 'Transactions', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>', $currentPath) ?>
            <?php endif; ?>
            <?php if (Auth::can('accounts')): ?>
                <?= navLink('/accounts', 'Manage Accounts', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>', $currentPath) ?>
            <?php endif; ?>
            <?php if (Auth::can('credits')): ?>
                <?= navLink('/credits/suppliers', 'Credits', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', $currentPath) ?>
            <?php endif; ?>
            <?php if (Auth::can('reports')): ?>
                <?= navLink('/reports/daily', 'Daily Reports', '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>', $currentPath) ?>
            <?php endif; ?>
        </nav>

    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        <!-- Top bar -->
        <header class="bg-[#f2efe9]/80 dark:bg-[#0a0a0a]/80 backdrop-blur-md border-b border-stone-200/60 dark:border-white/5 px-8 py-4 flex items-center justify-between z-10 shadow-sm shadow-stone-100/50 dark:shadow-none">
            <div class="flex items-center gap-4">
                <button id="sidebarToggle" class="p-1.5 rounded-lg hover:bg-stone-200/50 dark:hover:bg-white/5 text-slate-500 dark:text-slate-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-[15px] font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    <span class="w-1 h-4 bg-brand-500 rounded-full"></span>
                    <?= htmlspecialchars($title ?? '') ?>
                </h1>
            </div>

            <div class="flex items-center gap-6">
                <!-- Theme Toggle -->
                <button id="themeToggle" class="p-1.5 rounded-lg hover:bg-stone-200/50 dark:hover:bg-white/5 text-slate-500 dark:text-slate-400 transition-all duration-200">
                    <!-- Moon icon -->
                    <svg class="w-5 h-5 block dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                    <!-- Sun icon -->
                    <svg class="w-5 h-5 hidden dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                </button>

                <!-- Notification bell -->
                <?php if (Auth::can('notifications')): ?>
                <a href="/notifications" class="relative group text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z"/>
                    </svg>
                    <?php if ($unreadCount > 0): ?>
                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center font-bold ring-2 ring-white dark:ring-[#0a0a0a]">
                            <?= $unreadCount > 9 ? '9+' : $unreadCount ?>
                        </span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>

                <div class="h-6 w-[1px] bg-slate-200 dark:bg-white/10"></div>

                <!-- User Profile -->
                <div class="flex items-center gap-4">
                    <span class="text-xs font-medium text-slate-400 dark:text-slate-500"><?= date('D, d M Y') ?></span>
                    
                    <div class="flex items-center gap-2 pl-2">
                        <a href="/settings" class="flex items-center gap-2 group">
                            <div class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center text-white text-xs font-bold shadow-md shadow-brand-200 dark:shadow-blue-900/20 group-hover:bg-brand-600 transition-all">
                                <?= strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <div class="text-left hidden sm:block">
                                <div class="flex items-center gap-1">
                                    <p class="text-[13px] font-bold text-slate-800 dark:text-white leading-none"><?= htmlspecialchars($currentUser['name'] ?? '') ?></p>
                                    <svg class="w-3 h-3 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                <p class="text-[10px] font-medium text-slate-400 dark:text-slate-500 mt-1 uppercase tracking-wider"><?= $roleLabel ?></p>
                            </div>
                        </a>
                        <a href="/logout" class="ml-2 p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-md transition-all" title="Sign out">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash messages -->
        <?php if ($flash = Session::getFlash('success')): ?>
            <div class="mx-6 mt-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
                <?= htmlspecialchars($flash) ?>
            </div>
        <?php endif; ?>
        <?php if ($flash = Session::getFlash('error')): ?>
            <div class="mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
                <?= htmlspecialchars($flash) ?>
            </div>
        <?php endif; ?>

        <!-- Page content -->
        <main class="flex-1 flex flex-col min-h-0 p-6 overflow-y-auto">
            <?= $content ?? '' ?>
        </main>

    </div>
</div>

<script src="/assets/js/app.js"></script>
<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const themeToggle = document.getElementById('themeToggle');
    const html = document.documentElement;

    sidebarToggle.addEventListener('click', () => {
        html.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebar-collapsed', html.classList.contains('sidebar-collapsed'));
    });

    themeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    });
</script>
</body>
</html>
