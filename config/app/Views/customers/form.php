<?php ob_start();
$editing = isset($customer);
$old     = $customer ?? $old ?? [];
?>

<div class="flex-1 flex flex-col min-h-0 w-full">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8 flex-shrink-0">
        <a href="/customers" class="p-2 rounded-xl bg-white dark:bg-white/5 border border-stone-200 dark:border-white/10 text-stone-400 hover:text-brand-500 hover:border-brand-500/30 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight"><?= $editing ? 'Edit Customer' : 'New Customer' ?></h2>
            <p class="text-[10px] font-bold text-stone-400 dark:text-slate-500 uppercase tracking-widest mt-1"><?= $editing ? 'Update customer details and credit settings' : 'Register a new customer account' ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <?php if (!empty($error)): ?>
                <div class="mb-5 px-4 py-3 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-600 dark:text-rose-400 rounded-xl text-[12px] font-bold"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-5 bg-brand-500 rounded-full"></div>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Customer Details</h3>
                    </div>
                </div>
                <form method="POST" action="<?= $editing ? '/customers/' . $customer['id'] . '/update' : '/customers/store' ?>" class="p-6 space-y-5">
                    <!-- Name -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($old['name'] ?? '') ?>" placeholder="e.g. John Doe"
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600">
                    </div>
                    <!-- Phone + Email -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Phone</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" placeholder="+94 77 123 4567"
                                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="customer@example.com"
                                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600">
                        </div>
                    </div>
                    <!-- Type + Credit Limit -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Customer Type</label>
                            <select name="type" class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer">
                                <option value="retail" <?= ($old['type'] ?? 'retail') === 'retail' ? 'selected' : '' ?>>Retail</option>
                                <option value="wholesale" <?= ($old['type'] ?? '') === 'wholesale' ? 'selected' : '' ?>>Wholesale</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Credit Limit (LKR)</label>
                            <input type="number" name="credit_limit" step="0.01" min="0" value="<?= htmlspecialchars($old['credit_limit'] ?? '0.00') ?>"
                                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                        </div>
                    </div>
                    <!-- Address -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Address</label>
                        <textarea name="address" rows="3" placeholder="Street, city, province…"
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600 resize-none"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                    </div>
                    <!-- Actions -->
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white text-[11px] font-black px-8 py-3 rounded-xl transition-all shadow-lg shadow-brand-500/20 uppercase tracking-widest">
                            <?= $editing ? 'Save Changes' : 'Add Customer' ?>
                        </button>
                        <a href="/customers" class="bg-stone-100 dark:bg-white/5 hover:bg-stone-200 dark:hover:bg-white/10 text-stone-600 dark:text-slate-400 text-[11px] font-black px-6 py-3 rounded-xl transition-all uppercase tracking-widest">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Panel -->
        <?php if ($editing): ?>
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                    <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Customer Info</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div><p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Customer ID</p><p class="text-[13px] font-black text-slate-900 dark:text-white mt-1 font-mono">#<?= $customer['id'] ?></p></div>
                    <div><p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Credit Used</p><p class="text-[13px] font-black text-amber-600 dark:text-amber-400 mt-1">LKR <?= number_format((float)$customer['credit_used'], 2) ?></p></div>
                    <div><p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Status</p>
                        <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase <?= $customer['active'] ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-stone-100 dark:bg-white/10 text-stone-400' ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $customer['active'] ? 'bg-emerald-500' : 'bg-stone-300' ?>"></span>
                            <?= $customer['active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                    <div><p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Registered</p><p class="text-[13px] font-black text-slate-900 dark:text-white mt-1"><?= date('d M Y', strtotime($customer['created_at'])) ?></p></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';
