<?php ob_start(); ?>

<div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
    <div class="w-16 h-16 bg-brand-50 text-brand-500 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z"/></svg>
    </div>
    <h2 class="text-xl font-bold text-slate-800 mb-2"><?= $title ?? 'Notifications' ?></h2>
    <p class="text-slate-500 max-w-sm mx-auto">The notifications page is coming soon. You'll be able to manage alerts and system messages here.</p>
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';
