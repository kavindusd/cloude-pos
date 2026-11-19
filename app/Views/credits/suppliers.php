<?php ob_start(); ?>

<div class="flex-1 flex flex-col min-h-0 w-full">
    <!-- KPI Strip -->
    <div class="grid grid-cols-2 gap-6 mb-6 flex-shrink-0">
        <div class="bg-white dark:bg-white/5 p-5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm flex items-center justify-between group hover:-translate-y-0.5 hover:shadow-lg transition-all">
            <div>
                <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Owed to Suppliers</p>
                <p class="text-2xl font-black text-rose-600 dark:text-rose-400 tracking-tighter">LKR <?= number_format($totalOwed, 2) ?></p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-white/5 p-5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm flex items-center justify-between group hover:-translate-y-0.5 hover:shadow-lg transition-all">
            <div>
                <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-1">Unpaid Records</p>
                <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter"><?= count(array_filter($credits, fn($c) => $c['status'] !== 'settled')) ?></p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
    </div>

    <!-- Nav Tabs -->
    <div class="flex items-center gap-1 bg-white dark:bg-white/5 p-1 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm w-fit mb-6 flex-shrink-0">
        <a href="/credits/suppliers" class="px-5 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all <?= str_contains($currentPath, '/suppliers') ? 'bg-brand-500 text-white shadow-md shadow-brand-500/20' : 'text-stone-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-white' ?>">Supplier Credits</a>
        <a href="/credits/customers" class="px-5 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all <?= str_contains($currentPath, '/customers') ? 'bg-brand-500 text-white shadow-md shadow-brand-500/20' : 'text-stone-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-white' ?>">Customer Credits</a>
    </div>

    <!-- Table -->
    <div class="flex-1 min-h-0 bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5 flex-shrink-0">
            <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Supplier Credit Records</h3>
        </div>
        <div class="flex-1 overflow-y-auto scrollbar-hide">
            <?php if (empty($credits)): ?>
                <div class="flex flex-col items-center justify-center py-24 opacity-30">
                    <svg class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                    <p class="text-xs font-black uppercase tracking-widest">No supplier credits on record</p>
                </div>
            <?php else: ?>
            <table class="w-full text-left">
                <thead class="sticky top-0 bg-stone-50 dark:bg-[#0a0a0a] z-10 border-b border-stone-200 dark:border-white/5">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">#</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Supplier</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Purchase Ref</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right">Original</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right">Remaining</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right pr-6">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-white/5">
                    <?php foreach ($credits as $c): ?>
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-white/5 transition-all <?= $c['status'] === 'settled' ? 'opacity-50' : '' ?>">
                        <td class="px-6 py-4 font-mono text-[11px] font-black text-stone-400">#<?= $c['id'] ?></td>
                        <td class="px-6 py-4 text-[13px] font-black text-slate-800 dark:text-slate-200"><?= htmlspecialchars($c['supplier_name'] ?? '—') ?></td>
                        <td class="px-6 py-4"><a href="/purchases/<?= $c['purchase_id'] ?>" class="text-[11px] font-black text-brand-600 dark:text-brand-400 hover:underline">Purchase #<?= $c['purchase_id'] ?></a></td>
                        <td class="px-6 py-4 text-[11px] font-bold text-stone-400 dark:text-slate-500 whitespace-nowrap"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                        <td class="px-6 py-4 text-right text-[12px] font-black text-slate-700 dark:text-slate-300">LKR <?= number_format((float)$c['original_amount'], 2) ?></td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[12px] font-black <?= $c['status'] !== 'settled' ? 'text-rose-600 dark:text-rose-400' : 'text-stone-400' ?>">LKR <?= number_format((float)$c['amount_remaining'], 2) ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?= match($c['status']) { 'settled' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400', 'partial' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400', default => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400' } ?>"><?= ucfirst($c['status']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-right pr-6">
                            <?php if ($c['status'] !== 'settled'): ?>
                            <button type="button" onclick="openModal('supplier', <?= $c['id'] ?>, <?= $c['amount_remaining'] ?>, '<?= htmlspecialchars($c['supplier_name'], ENT_QUOTES) ?>')"
                                class="bg-brand-500 hover:bg-brand-600 text-white text-[10px] font-black px-4 py-2 rounded-xl transition-all shadow-sm shadow-brand-500/20 uppercase tracking-widest">Settle</button>
                            <?php else: ?>
                            <span class="text-[10px] font-black text-stone-300 dark:text-slate-600 uppercase tracking-widest">Paid</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/settle_modal.php'; ?>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';