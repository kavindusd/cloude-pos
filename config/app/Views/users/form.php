<?php ob_start(); ?>

<div class="max-w-2xl">
    <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden transition-all">
        <div class="px-8 py-6 border-b border-stone-200/60 dark:border-white/5 bg-stone-50 dark:bg-white/5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-brand-500/10 flex items-center justify-center text-brand-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-black text-slate-900 dark:text-white tracking-tight">Provision New Access</h3>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">Create a new system administrator or staff account</p>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mx-8 mt-6 px-4 py-3 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 rounded-xl text-[11px] font-bold uppercase tracking-widest">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/users/store" class="p-8">
            <div class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2.5">Full Legal Name</label>
                        <input type="text" name="name" required placeholder="e.g. John Doe"
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2.5">Email Address</label>
                        <input type="email" name="email" required placeholder="john@example.com"
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2.5">Access Password</label>
                    <input type="password" name="password" required minlength="8" placeholder="Minimum 8 characters"
                        class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2.5">System Role & Permissions</label>
                    <select name="role" required
                        class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer">
                        <option value="">Select an access level</option>
                        <option value="super_admin">Super Admin (Full Root Access)</option>
                        <option value="admin">Administrator (Module Control)</option>
                        <option value="manager">Manager (Operations & Reports)</option>
                        <option value="stock_keeper">Stock Keeper (Inventory Only)</option>
                        <option value="cashier">Cashier (Point of Sale Only)</option>
                    </select>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-brand-500 hover:bg-brand-600 text-white text-[11px] font-black py-4 rounded-xl transition-all shadow-lg shadow-brand-500/20 uppercase tracking-[0.1em]">
                        Create Account
                    </button>
                    <a href="/settings"
                        class="px-8 bg-stone-100 dark:bg-white/5 hover:bg-stone-200 dark:hover:bg-white/10 text-slate-600 dark:text-slate-400 text-[11px] font-black py-4 rounded-xl transition-all uppercase tracking-widest flex items-center justify-center">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';
?>