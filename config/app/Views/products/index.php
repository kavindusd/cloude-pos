<?php ob_start(); ?>

<div class="flex-1 flex flex-col min-h-0 w-full">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Inventory Catalog</h2>
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-widest mt-1 flex items-center gap-2">
                Manage products, pricing, and stock levels
                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                <span class="text-brand-500 font-black"><?= count($products) ?> Total Items</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <?php if (Auth::can('products')): ?>
            <a href="/products/create" class="w-10 h-10 bg-brand-500 hover:bg-brand-600 text-white flex items-center justify-center rounded-full transition-all shadow-lg shadow-brand-500/20" title="Add Product">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 p-5 mb-6 shadow-md transition-all">
        <div class="flex flex-col md:flex-row items-center gap-4">
            <div class="relative flex-1 group w-full">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" id="productSearch" placeholder="Search by name, barcode, or category..."
                    class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl pl-11 pr-4 py-2.5 text-[13px] text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-slate-400 font-medium">
            </div>
            <div class="relative w-full md:w-56">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </div>
                <select id="statusFilter" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl pl-11 pr-8 py-2.5 text-[13px] text-slate-600 dark:text-slate-400 focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer font-medium">
                    <option value="">All Statuses</option>
                    <option value="1">Active Only</option>
                    <option value="0">Inactive Only</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="flex-1 min-h-0 bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden flex flex-col transition-all">
        <div class="flex-1 overflow-y-auto scrollbar-hide px-2">
            <?php if (empty($products)): ?>
                <div class="flex flex-col items-center justify-center py-20 opacity-20">
                    <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <p class="text-xs font-bold uppercase tracking-widest leading-relaxed text-center">No Products Found<br><a href="/products/create" class="text-brand-500 hover:underline">Add First Product</a></p>
                </div>
            <?php else: ?>
            <table class="w-full text-left" id="productsTable">
                <thead class="sticky top-0 bg-white dark:bg-[#0a0a0a] z-10 border-b border-slate-100 dark:border-white/5">
                    <tr>
                        <th class="px-5 py-5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Product Details</th>
                        <th class="px-5 py-5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Classification</th>
                        <th class="px-5 py-5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">Pricing (LKR)</th>
                        <th class="px-5 py-5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-center">Availability</th>
                        <th class="px-5 py-5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-center">State</th>
                        <th class="px-5 py-5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right pr-6">Manage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    <?php foreach ($products as $p): ?>
                    <?php $low = (int)$p['qty_total'] <= (int)$p['low_threshold']; ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-all product-row group"
                        data-name="<?= strtolower(htmlspecialchars($p['name'])) ?>"
                        data-barcode="<?= strtolower(htmlspecialchars($p['barcode'] ?? '')) ?>"
                        data-active="<?= (int)$p['active'] ?>">
                        <td class="px-5 py-4">
                            <p class="text-[13px] font-bold text-slate-800 dark:text-slate-200 leading-none"><?= htmlspecialchars($p['name']) ?></p>
                            <p class="text-[10px] font-mono text-slate-400 dark:text-slate-500 mt-1.5"><?= htmlspecialchars($p['barcode'] ?? 'NO-BARCODE') ?></p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider"><?= htmlspecialchars($p['category_name'] ?? 'General') ?></p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5"><?= htmlspecialchars($p['supplier_name'] ?? 'Internal') ?></p>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="space-y-1">
                                <p class="text-[12px] font-black text-slate-900 dark:text-white">R: <?= number_format((float)$p['price_retail'], 2) ?></p>
                                <p class="text-[10px] font-bold text-slate-400">W: <?= number_format((float)$p['price_wholesale'], 2) ?></p>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="px-3 py-1 rounded-lg text-[11px] font-black <?= $low ? 'bg-rose-100 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400' : 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' ?>">
                                    <?= (int)$p['qty_total'] ?> in stock
                                </span>
                                <?php if ($low && (int)$p['qty_total'] > 0): ?>
                                    <span class="text-[9px] font-bold text-rose-500 mt-1 uppercase tracking-tighter">Low Stock Alert</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <?php if ($p['active']): ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Active
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-slate-100 dark:bg-white/10 text-slate-400">
                                    <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span> Disabled
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-4 text-right pr-6">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="/products/<?= $p['id'] ?>/edit" class="p-2 rounded-lg text-slate-400 hover:text-brand-500 hover:bg-brand-500/10 transition-all" title="Edit Product">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <a href="/products/<?= $p['id'] ?>/toggle" class="p-2 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-amber-500/10 transition-all" title="<?= $p['active'] ? 'Deactivate' : 'Activate' ?>">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </a>
                                <?php if ((int)$p['qty_total'] === 0): ?>
                                    <a href="/products/<?= $p['id'] ?>/delete" class="p-2 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 transition-all" 
                                        onclick="return confirm('Delete \'<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>\'?')" title="Delete Product">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </a>
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

<script>
(function () {
    const search  = document.getElementById('productSearch');
    const filter  = document.getElementById('statusFilter');
    const rows    = document.querySelectorAll('.product-row');
    const counter = document.getElementById('productCount');

    function applyFilters() {
        const q      = search.value.toLowerCase();
        const status = filter.value;
        let visible  = 0;
        rows.forEach(row => {
            const nameMatch    = row.dataset.name.includes(q) || row.dataset.barcode.includes(q);
            const statusMatch  = status === '' || row.dataset.active === status;
            const show         = nameMatch && statusMatch;
            row.style.display  = show ? '' : 'none';
            if (show) visible++;
        });
        counter.textContent = visible + ' shown';
    }

    search.addEventListener('input', applyFilters);
    filter.addEventListener('change', applyFilters);
})();
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';