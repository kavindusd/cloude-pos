<?php ob_start(); ?>

<div class="flex-1 flex flex-col min-h-0 w-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8 flex-shrink-0">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Sales History</h2>
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-widest mt-1 flex items-center gap-2">
                Audit & review past transactions
                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                <span class="text-brand-500 font-black"><?= count($sales) ?> Records</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="/pos" class="w-10 h-10 bg-brand-500 hover:bg-brand-600 text-white flex items-center justify-center rounded-full transition-all shadow-lg shadow-brand-500/20" title="New Sale">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 p-5 mb-6 shadow-md flex-shrink-0 transition-all">
        <div class="flex flex-col md:flex-row items-center gap-4">
            <div class="relative flex-1 w-full">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" id="saleSearch" placeholder="Search by customer name or transaction ID..."
                    class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl pl-11 pr-4 py-2.5 text-[13px] text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-400 font-medium">
            </div>
            <div class="relative w-full md:w-56">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </div>
                <select id="methodFilter" class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl pl-11 pr-8 py-2.5 text-[13px] text-slate-600 dark:text-slate-400 focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer font-medium">
                    <option value="">All Payment Methods</option>
                    <option value="cash">Cash</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="credit">Store Credit</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="flex-1 min-h-0 bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden flex flex-col transition-all">
        <div class="flex-1 overflow-y-auto scrollbar-hide">
            <?php if (empty($sales)): ?>
                <div class="flex flex-col items-center justify-center py-24 opacity-30">
                    <svg class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-[10px] font-black uppercase tracking-widest">No Sales Found</p>
                </div>
            <?php else: ?>
            <table class="w-full text-left">
                <thead class="sticky top-0 bg-stone-50 dark:bg-[#0a0a0a] z-10 border-b border-stone-200 dark:border-white/5">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">ID</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Customer</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Date & Time</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Items</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Payment</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right">Total</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right pr-6">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-white/5">
                    <?php foreach ($sales as $s): ?>
                    <tr class="hover:bg-blue-50/20 dark:hover:bg-white/5 transition-all sale-row"
                        data-search="<?= strtolower(htmlspecialchars(($s['customer_name'] ?? '') . ' ' . $s['id'])) ?>"
                        data-method="<?= $s['payment_method'] ?>">
                        <td class="px-6 py-4 font-mono text-[11px] font-black text-stone-400">#<?= $s['id'] ?></td>
                        <td class="px-6 py-4">
                            <p class="text-[13px] font-black text-slate-800 dark:text-slate-200"><?= htmlspecialchars($s['customer_name'] ?? 'Walk-in Customer') ?></p>
                        </td>
                        <td class="px-6 py-4 text-[11px] font-bold text-stone-400 dark:text-slate-500 whitespace-nowrap">
                            <?= date('d M Y', strtotime($s['created_at'])) ?>
                            <span class="block opacity-60 mt-0.5"><?= date('h:i A', strtotime($s['created_at'])) ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 bg-stone-100 dark:bg-white/10 text-slate-600 dark:text-slate-300 rounded-lg text-[10px] font-black"><?= (int)$s['item_count'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?= match($s['payment_method']) { 'cash' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400', 'bank' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400', 'credit' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400', default => 'bg-stone-100 dark:bg-white/10 text-stone-600 dark:text-slate-400' } ?>"><?= $s['payment_method'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?= match($s['status']) { 'completed' => 'bg-emerald-500 text-white', 'credit' => 'bg-amber-500 text-white', 'voided' => 'bg-rose-500 text-white', default => 'bg-stone-200 dark:bg-white/10 text-stone-600 dark:text-slate-400' } ?>"><?= $s['status'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-[13px] text-slate-900 dark:text-white">LKR <?= number_format((float)$s['total'], 2) ?></td>
                        <td class="px-6 py-4 text-right pr-6">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="/sales/<?= $s['id'] ?>/print" target="_blank" title="Print Invoice" class="p-1.5 rounded-lg text-stone-400 hover:text-brand-500 hover:bg-brand-500/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>
                                <a href="/sales/<?= $s['id'] ?>" class="text-[11px] font-black text-brand-600 dark:text-brand-400 hover:underline">View</a>
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
(function(){
    const search  = document.getElementById('saleSearch');
    const method  = document.getElementById('methodFilter');
    const rows    = document.querySelectorAll('.sale-row');
    const counter = document.getElementById('saleCount');

    function filter() {
        const q = search.value.toLowerCase();
        const m = method.value;
        let v   = 0;
        rows.forEach(r => {
            const show = r.dataset.search.includes(q) && (m === '' || r.dataset.method === m);
            r.style.display = show ? '' : 'none';
            if (show) v++;
        });
        counter.textContent = v + ' Records';
    }
    search.addEventListener('input', filter);
    method.addEventListener('change', filter);
})();
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';