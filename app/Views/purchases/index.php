<?php ob_start(); ?>

<div class="flex-1 flex flex-col min-h-0 w-full">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Purchase Orders</h2>
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-widest mt-1 flex items-center gap-2">
                Supplier procurement & stock intake
                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                <span class="text-brand-500 font-black"><?= count($purchases) ?> Records Found</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <?php if (Auth::can('purchases')): ?>
            <a href="/purchases/create" class="w-10 h-10 bg-brand-500 hover:bg-brand-600 text-white flex items-center justify-center rounded-full transition-all shadow-lg shadow-brand-500/20" title="New Purchase"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg></a>
            <?php endif; ?>
        </div>
    </div>

    <div class="flex-1 min-h-0 bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden flex flex-col transition-all">
        <div class="px-6 py-4 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5 flex items-center justify-between">
            <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Procurement Ledger</h3>
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                <input type="text" id="purchaseSearch" placeholder="Search supplier..." class="bg-white dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl pl-9 pr-4 py-2 text-[12px] text-slate-700 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-400 font-medium w-52">
            </div>
        </div>
        <div class="flex-1 overflow-y-auto scrollbar-hide">
            <?php if (empty($purchases)): ?>
                <div class="flex flex-col items-center justify-center py-24 opacity-30">
                    <svg class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <p class="text-xs font-black uppercase tracking-widest">No purchases yet</p>
                </div>
            <?php else: ?>
            <table class="w-full text-left">
                <thead class="sticky top-0 bg-stone-50 dark:bg-[#0a0a0a] z-10 border-b border-stone-200 dark:border-white/5">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Order</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Supplier</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Items</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Payment</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right">Total</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">By</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right pr-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-white/5">
                    <?php foreach ($purchases as $p): ?>
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-white/5 transition-all purchase-row group" data-search="<?= strtolower(htmlspecialchars($p['supplier_name'] ?? '')) ?>">
                        <td class="px-6 py-4 font-mono text-[11px] font-black text-stone-400 dark:text-slate-500">#<?= str_pad($p['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-6 py-4">
                            <p class="text-[13px] font-black text-slate-800 dark:text-slate-200"><?= htmlspecialchars($p['supplier_name'] ?? '—') ?></p>
                        </td>
                        <td class="px-6 py-4 text-[11px] text-stone-400 dark:text-slate-500 font-bold whitespace-nowrap"><?= date('d M Y', strtotime($p['created_at'])) ?><span class="block opacity-60 mt-0.5"><?= date('H:i', strtotime($p['created_at'])) ?></span></td>
                        <td class="px-6 py-4 text-center"><span class="px-2.5 py-1 bg-stone-100 dark:bg-white/10 text-slate-600 dark:text-slate-300 rounded-lg text-[10px] font-black"><?= (int)$p['item_count'] ?></span></td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?= match($p['payment_method']) { 'cash' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400', 'bank' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400', 'credit' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400', default => 'bg-stone-100 dark:bg-white/10 text-stone-600' } ?>"><?= ucfirst($p['payment_method']) ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?= match($p['status']) { 'pending' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400', 'received' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400', 'cancelled' => 'bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400', default => 'bg-stone-100 dark:bg-white/10 text-stone-600' } ?>"><?= ucfirst($p['status']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-slate-900 dark:text-white text-[13px]">LKR <?= number_format((float)$p['total'], 2) ?></td>
                        <td class="px-6 py-4 text-[11px] text-stone-400 dark:text-slate-500"><?= htmlspecialchars($p['created_by_name'] ?? '—') ?></td>
                        <td class="px-6 py-4 text-right pr-6">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="/purchases/<?= $p['id'] ?>/print" target="_blank" class="p-1.5 rounded-lg text-stone-400 hover:text-brand-500 hover:bg-brand-500/10 transition-all" title="Print"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg></a>
                                <a href="/purchases/<?= $p['id'] ?>" class="text-[11px] font-black text-brand-600 dark:text-brand-400 hover:underline">View</a>
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
document.getElementById('purchaseSearch')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.purchase-row').forEach(r => {
        r.style.display = r.dataset.search.includes(q) ? '' : 'none';
    });
});
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';