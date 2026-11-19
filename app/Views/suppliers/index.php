<?php ob_start(); ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">

    <!-- Left: Form Panel -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden transition-all">
            <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-brand-500 rounded-full shadow-sm shadow-brand-500/30"></div>
                    <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">
                        <?= !empty($editing) ? 'Edit Supplier' : 'Add New Supplier' ?>
                    </h3>
                </div>
            </div>
            <div class="p-6">
                <?php if (!empty($editing)): ?>
                    <form method="POST" action="/suppliers/<?= $editing['id'] ?>/update">
                    <input type="hidden" name="id" value="<?= $editing['id'] ?>">
                <?php else: ?>
                    <form method="POST" action="/suppliers/store">
                <?php endif; ?>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($editing['name'] ?? '') ?>" placeholder="e.g. ABC Trading Co."
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Phone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($editing['phone'] ?? '') ?>" placeholder="+94 11 234 5678"
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($editing['email'] ?? '') ?>" placeholder="supplier@example.com"
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Address</label>
                        <textarea name="address" rows="2" placeholder="Optional address"
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600 resize-none"><?= htmlspecialchars($editing['address'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-brand-500 hover:bg-brand-600 text-white text-[11px] font-black py-3 rounded-xl transition-all shadow-lg shadow-brand-500/20 uppercase tracking-widest">
                        <?= !empty($editing) ? 'Update Supplier' : 'Add Supplier' ?>
                    </button>
                    <?php if (!empty($editing)): ?>
                        <a href="/suppliers" class="px-4 py-3 bg-stone-100 dark:bg-white/5 hover:bg-stone-200 dark:hover:bg-white/10 text-stone-600 dark:text-slate-400 text-[11px] font-black rounded-xl transition-all uppercase tracking-widest">Cancel</a>
                    <?php endif; ?>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right: Table -->
    <div class="lg:col-span-2 flex flex-col min-h-0">
        <div class="flex-1 min-h-0 bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden flex flex-col transition-all">
            <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5 flex items-center justify-between">
                <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">All Suppliers</h3>
                <span class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest"><?= count($suppliers) ?> total</span>
            </div>
            <div class="flex-1 overflow-y-auto scrollbar-hide">
                <?php if (empty($suppliers)): ?>
                    <div class="flex flex-col items-center justify-center py-24 opacity-30">
                        <svg class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                        <p class="text-xs font-black uppercase tracking-widest">No suppliers yet</p>
                    </div>
                <?php else: ?>
                <table class="w-full text-left">
                    <thead class="sticky top-0 bg-stone-50 dark:bg-[#0a0a0a] z-10 border-b border-stone-200 dark:border-white/5">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Supplier</th>
                            <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Phone</th>
                            <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Email</th>
                            <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right pr-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-white/5">
                        <?php foreach ($suppliers as $s): ?>
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-white/5 transition-all <?= (!empty($editing) && $editing['id'] == $s['id']) ? 'bg-brand-50 dark:bg-brand-500/5' : '' ?>">
                            <td class="px-6 py-4">
                                <p class="text-[13px] font-black text-slate-800 dark:text-slate-200"><?= htmlspecialchars($s['name']) ?></p>
                                <?php if (!empty($s['address'])): ?><p class="text-[10px] text-stone-400 mt-0.5 truncate max-w-[160px]"><?= htmlspecialchars($s['address']) ?></p><?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-[12px] font-bold text-slate-600 dark:text-slate-400"><?= htmlspecialchars($s['phone'] ?? '—') ?></td>
                            <td class="px-6 py-4 text-[12px] text-stone-400"><?= htmlspecialchars($s['email'] ?? '—') ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($s['active']): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Active</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-stone-100 dark:bg-white/10 text-stone-400"><span class="w-1.5 h-1.5 bg-stone-300 rounded-full"></span> Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right pr-6">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="/suppliers/<?= $s['id'] ?>/edit" class="p-1.5 rounded-lg text-stone-400 hover:text-brand-500 hover:bg-brand-500/10 transition-all" title="Edit"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                    <a href="/suppliers/<?= $s['id'] ?>/toggle" class="p-1.5 rounded-lg text-stone-400 hover:text-amber-500 hover:bg-amber-500/10 transition-all" title="Toggle"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';