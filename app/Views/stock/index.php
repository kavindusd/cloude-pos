<?php ob_start(); ?>

<div class="flex-1 flex flex-col min-h-0 w-full space-y-6">

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 flex-shrink-0">
        <div class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm flex items-center gap-4 group hover:shadow-lg hover:-translate-y-0.5 transition-all">
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Inventory Value</p>
                <p class="text-xl font-black text-slate-900 dark:text-white tracking-tighter mt-0.5">LKR <?= number_format($totalValue, 2) ?></p>
            </div>
        </div>
        <div class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm flex items-center gap-4 group hover:shadow-lg hover:-translate-y-0.5 transition-all">
            <div class="w-12 h-12 rounded-2xl <?= $lowStockCount > 0 ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400 group-hover:bg-rose-600' : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-600' ?> flex items-center justify-center group-hover:text-white transition-all shadow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Low Stock Items</p>
                <p class="text-xl font-black <?= $lowStockCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white' ?> tracking-tighter mt-0.5"><?= $lowStockCount ?></p>
            </div>
        </div>
        <div class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm flex items-center gap-4 group hover:shadow-lg hover:-translate-y-0.5 transition-all">
            <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:bg-purple-600 group-hover:text-white transition-all shadow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Active Batches</p>
                <p class="text-xl font-black text-slate-900 dark:text-white tracking-tighter mt-0.5"><?= count($batches) ?></p>
            </div>
        </div>
    </div>

    <!-- Tab Panel -->
    <div class="flex-1 min-h-0 bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden flex flex-col">
        <div class="px-4 py-3 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-1 bg-stone-100 dark:bg-white/5 p-1 rounded-xl border border-stone-200 dark:border-white/5">
                <button onclick="switchTab('products')" id="tabBtn-products" class="px-5 py-2 text-[10px] font-black tracking-widest rounded-lg transition-all bg-white dark:bg-white/10 text-brand-500 dark:text-white shadow-sm">Product Levels</button>
                <button onclick="switchTab('batches')" id="tabBtn-batches" class="px-5 py-2 text-[10px] font-black tracking-widest rounded-lg transition-all text-stone-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-white">Batch Details</button>
            </div>
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                <input type="text" id="stockSearch" placeholder="Search..." class="bg-white dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl pl-9 pr-4 py-2 text-[12px] text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-400 font-medium w-48">
            </div>
        </div>

        <!-- Products Tab -->
        <div id="tab-products" class="tab-content flex-1 overflow-y-auto scrollbar-hide">
            <table class="w-full text-left">
                <thead class="sticky top-0 bg-stone-50 dark:bg-[#0a0a0a] z-10 border-b border-stone-200 dark:border-white/5">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 tracking-widest">Product</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 tracking-widest">Category</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 tracking-widest text-center">Buying Price</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 tracking-widest text-center">Stock Qty</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 tracking-widest text-center">State</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-white/5">
                    <?php foreach ($products as $p): ?>
                    <?php $low = (int)$p['qty_total'] <= (int)$p['low_threshold']; ?>
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-white/5 transition-all stock-row" data-search="<?= strtolower($p['name'] . ' ' . ($p['barcode'] ?? '')) ?>">
                        <td class="px-6 py-4">
                            <p class="text-[13px] font-black text-slate-800 dark:text-slate-200"><?= htmlspecialchars($p['name']) ?></p>
                            <p class="text-[10px] text-stone-400 font-mono mt-0.5"><?= htmlspecialchars($p['barcode'] ?? '—') ?></p>
                        </td>
                        <td class="px-6 py-4 text-[12px] font-bold text-stone-400 dark:text-slate-500"><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
                        <td class="px-6 py-4 text-center text-[12px] font-black text-slate-700 dark:text-slate-300">LKR <?= number_format($p['buying_price'], 2) ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-lg text-[11px] font-black <?= $low ? 'bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400' : 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' ?>"><?= (int)$p['qty_total'] ?></span>
                            <?php if ($low): ?><p class="text-[9px] text-rose-500 font-black uppercase mt-1">Low Stock</p><?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center"><span class="w-2.5 h-2.5 rounded-full inline-block <?= $p['active'] ? 'bg-emerald-500' : 'bg-stone-300' ?>"></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Batches Tab -->
        <div id="tab-batches" class="tab-content hidden flex-1 overflow-y-auto scrollbar-hide">
            <table class="w-full text-left">
                <thead class="sticky top-0 bg-stone-50 dark:bg-[#0a0a0a] z-10 border-b border-stone-200 dark:border-white/5">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Batch</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Product</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Supplier</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Cost</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Remaining</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right pr-6">Received</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-white/5">
                    <?php foreach ($batches as $b): ?>
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-white/5 transition-all stock-row" data-search="<?= strtolower($b['batch_number'] . ' ' . $b['product_name']) ?>">
                        <td class="px-6 py-4"><span class="px-2.5 py-1 bg-stone-100 dark:bg-white/10 text-stone-600 dark:text-slate-400 rounded-lg text-[10px] font-mono font-black"><?= htmlspecialchars($b['batch_number']) ?></span></td>
                        <td class="px-6 py-4 text-[13px] font-black text-slate-800 dark:text-slate-200"><?= htmlspecialchars($b['product_name']) ?></td>
                        <td class="px-6 py-4 text-[12px] text-stone-400"><?= htmlspecialchars($b['supplier_name'] ?? '—') ?></td>
                        <td class="px-6 py-4 text-center text-[12px] font-black text-slate-700 dark:text-slate-300">LKR <?= number_format($b['cost_price'], 2) ?></td>
                        <td class="px-6 py-4 text-center"><span class="px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-[11px] font-black"><?= (int)$b['qty_remaining'] ?> / <?= (int)$b['qty_received'] ?></span></td>
                        <td class="px-6 py-4 text-right pr-6 text-[11px] font-bold text-stone-400"><?= date('d M Y', strtotime($b['purchase_date'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    document.getElementById('tab-' + tab).classList.remove('hidden');
    const active   = 'bg-white dark:bg-white/10 text-brand-500 dark:text-white shadow-sm';
    const inactive = 'text-stone-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-white';
    document.querySelectorAll('button[id^="tabBtn-"]').forEach(b => { 
        b.className = b.className.replace(/bg-white[^\s]*/g,''); 
        b.classList.remove('bg-white', 'dark:bg-white/10', 'text-brand-500', 'dark:text-white', 'shadow-sm'); 
        b.classList.add(...inactive.split(' ')); 
    });
    const activeBtn = document.getElementById('tabBtn-' + tab);
    activeBtn.classList.remove(...inactive.split(' '));
    activeBtn.classList.add('bg-white','dark:bg-white/10','text-brand-500','dark:text-white','shadow-sm');
}
document.getElementById('stockSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.stock-row').forEach(r => { r.style.display = r.dataset.search.includes(q) ? '' : 'none'; });
});
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';
