<?php ob_start(); ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">

    <!-- Form Panel -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-brand-500 rounded-full shadow-sm shadow-brand-500/30"></div>
                    <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">
                        <?= !empty($editing) ? 'Edit Category' : 'Add New Category' ?>
                    </h3>
                </div>
            </div>
            <div class="p-6">
                <?php if (!empty($editing)): ?>
                    <form method="POST" action="/categories/<?= $editing['id'] ?>/update">
                <?php else: ?>
                    <form method="POST" action="/categories/store">
                <?php endif; ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($editing['name'] ?? '') ?>" placeholder="e.g. Beverages"
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Description</label>
                        <textarea name="description" rows="3" placeholder="Optional description"
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600 resize-none"><?= htmlspecialchars($editing['description'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-brand-500 hover:bg-brand-600 text-white text-[11px] font-black py-3 rounded-xl transition-all shadow-lg shadow-brand-500/20 uppercase tracking-widest">
                        <?= !empty($editing) ? 'Update' : 'Add Category' ?>
                    </button>
                    <?php if (!empty($editing)): ?>
                        <a href="/categories" class="px-4 py-3 bg-stone-100 dark:bg-white/5 hover:bg-stone-200 dark:hover:bg-white/10 text-stone-600 dark:text-slate-400 text-[11px] font-black rounded-xl transition-all uppercase tracking-widest">Cancel</a>
                    <?php endif; ?>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="lg:col-span-2 flex flex-col min-h-0">
        <div class="flex-1 min-h-0 bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5 flex items-center justify-between flex-shrink-0">
                <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">All Categories</h3>
                <span class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest"><?= count($categories) ?> total</span>
            </div>
            <div class="flex-1 overflow-y-auto scrollbar-hide">
                <?php if (empty($categories)): ?>
                    <div class="flex flex-col items-center justify-center py-24 opacity-30">
                        <svg class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 11h.01M7 15h.01M11 7h.01M11 11h.01M11 15h.01M15 7h.01M15 11h.01M15 15h.01"/></svg>
                        <p class="text-xs font-black uppercase tracking-widest">No categories yet</p>
                    </div>
                <?php else: ?>
                <table class="w-full text-left">
                    <thead class="sticky top-0 bg-stone-50 dark:bg-[#0a0a0a] z-10 border-b border-stone-200 dark:border-white/5">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Name</th>
                            <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Description</th>
                            <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Products</th>
                            <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right pr-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-white/5">
                        <?php foreach ($categories as $cat): ?>
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-white/5 transition-all <?= (!empty($editing) && $editing['id'] == $cat['id']) ? 'bg-brand-50 dark:bg-brand-500/5' : '' ?>">
                            <td class="px-6 py-4 text-[13px] font-black text-slate-800 dark:text-slate-200"><?= htmlspecialchars($cat['name']) ?></td>
                            <td class="px-6 py-4 text-[11px] text-stone-400 dark:text-slate-500"><?= htmlspecialchars($cat['description'] ?? '—') ?></td>
                            <td class="px-6 py-4 text-center"><span class="px-2.5 py-1 bg-stone-100 dark:bg-white/10 text-slate-600 dark:text-slate-300 rounded-lg text-[10px] font-black"><?= (int)$cat['product_count'] ?></span></td>
                            <td class="px-6 py-4 text-right pr-6">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="/categories/<?= $cat['id'] ?>/edit" class="p-1.5 rounded-lg text-stone-400 hover:text-brand-500 hover:bg-brand-500/10 transition-all" title="Edit"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                    <?php if ((int)$cat['product_count'] === 0): ?>
                                        <a href="/categories/<?= $cat['id'] ?>/delete" onclick="return confirm('Delete this category?')" class="p-1.5 rounded-lg text-stone-400 hover:text-rose-500 hover:bg-rose-500/10 transition-all" title="Delete"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></a>
                                    <?php else: ?>
                                        <span class="p-1.5 rounded-lg text-stone-200 dark:text-white/10 cursor-not-allowed" title="Cannot delete — has products"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></span>
                                    <?php endif; ?>
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