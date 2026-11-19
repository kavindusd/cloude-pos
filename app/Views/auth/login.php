<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — POSS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: { brand: { 50:'#f0f5ff',100:'#e0eaff',500:'#1e3a8a',600:'#172554',700:'#111827' } }, fontFamily: { sans: ['"Plus Jakarta Sans"','ui-sans-serif','system-ui'] } } }
        }
        // Apply saved theme
        if (localStorage.getItem('theme') === 'dark') document.documentElement.classList.add('dark');
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style type="text/tailwindcss">
        @layer components {
            .dark .bg-brand-500 {
                @apply bg-gradient-to-br from-[#2563eb] to-[#1e40af] border border-blue-400/20 shadow-lg shadow-blue-900/20;
            }
            .dark .hover\:bg-brand-600:hover {
                @apply from-[#3b82f6] to-[#2563eb];
            }
        }
    </style>
</head>
<body class="bg-[#fcfaf7] dark:bg-[#0a0a0a] font-sans min-h-screen flex items-center justify-center p-4 transition-colors duration-200 overflow-hidden">

    <!-- Ambient Lighting -->
    <div class="fixed inset-0 pointer-events-none z-0" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-amber-200/30 dark:bg-brand-500/10 blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-20 w-[400px] h-[400px] rounded-full bg-sky-200/25 dark:bg-emerald-500/5 blur-[100px]"></div>
    </div>

    <!-- Theme Toggle -->
    <button id="themeToggle" class="fixed top-5 right-5 z-50 p-2 rounded-xl bg-white dark:bg-white/10 border border-stone-200 dark:border-white/10 text-slate-500 dark:text-slate-400 hover:text-brand-500 transition-all shadow-sm">
        <svg class="w-4 h-4 block dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        <svg class="w-4 h-4 hidden dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
    </button>

    <div class="relative z-10 w-full max-w-sm">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-brand-500 rounded-2xl flex items-center justify-center text-white font-black text-2xl mx-auto mb-4 shadow-xl shadow-brand-500/30">P</div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight uppercase">POSS</h1>
            <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-[0.3em] mt-1">Point of Sale System</p>
        </div>

        <!-- Card -->
        <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/10 shadow-xl shadow-stone-200/50 dark:shadow-none p-8">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-1 h-5 bg-brand-500 rounded-full shadow-sm shadow-brand-500/30"></div>
                <h2 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Authenticate Access</h2>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-5 px-4 py-3 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-600 dark:text-rose-400 rounded-xl text-[12px] font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Email Address</label>
                    <input type="email" name="email" required autocomplete="email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        placeholder="admin@pos.lk"
                        class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-3 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Password</label>
                    <input type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-3 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600">
                </div>
                <button type="submit"
                    class="w-full bg-brand-500 hover:bg-brand-600 active:scale-[0.98] text-white text-[12px] font-black py-3.5 rounded-xl transition-all shadow-lg shadow-brand-500/20 uppercase tracking-widest mt-2 flex items-center justify-center gap-2">
                    <span>Sign In</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>
            </form>
        </div>

        <p class="text-center text-[10px] text-stone-400 dark:text-slate-600 mt-6 font-bold uppercase tracking-widest">&copy; <?= date('Y') ?> POSS. All rights reserved.</p>
    </div>

<script>
    document.getElementById('themeToggle').addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    });
</script>
</body>
</html>