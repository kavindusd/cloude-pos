<?php ob_start(); ?>

<div class="flex-1 flex flex-col min-h-0 w-full">
    <!-- Header + Filter -->
    <div class="flex-shrink-0 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Transaction Ledger</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-widest mt-1 flex items-center gap-2">
                    Full account movement history
                    <?php 
                        $totalCount = 0;
                        foreach($grouped as $day => $items) $totalCount += count($items);
                    ?>
                    <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                    <span class="text-brand-500 font-black"><?= $totalCount ?> Transactions Recorded</span>
                </p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md p-5 transition-all">
            <form action="/transactions" method="GET" class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest whitespace-nowrap">Account</label>
                    <select name="account_id" class="bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                        <option value="">All Accounts</option>
                        <?php foreach ($accounts as $acc): ?>
                            <option value="<?= $acc['id'] ?>" <?= $accountId == $acc['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($acc['name']) ?> (<?= ucfirst($acc['type']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest whitespace-nowrap">From</label>
                    <input type="date" name="start_date" value="<?= $startDate ?>"
                        class="bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest whitespace-nowrap">To</label>
                    <input type="date" name="end_date" value="<?= $endDate ?>"
                        class="bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white text-[11px] font-black px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-brand-500/20 uppercase tracking-widest">Apply Filter</button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 min-h-0 overflow-y-auto scrollbar-hide space-y-6">
        <?php if (empty($grouped)): ?>
            <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm flex flex-col items-center justify-center py-24 opacity-30">
                <svg class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <p class="text-xs font-black uppercase tracking-widest">No transactions found</p>
            </div>
        <?php else: ?>
            <?php foreach ($grouped as $day => $txns): ?>
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest whitespace-nowrap">
                        <?= date('D, d M Y', strtotime($day)) ?>
                    </span>
                    <?php if ($day === date('Y-m-d')): ?>
                        <span class="text-[10px] font-black text-brand-500 bg-brand-50 dark:bg-brand-500/10 px-2.5 py-0.5 rounded-full border border-brand-100 dark:border-brand-500/20 uppercase tracking-widest">Today</span>
                    <?php endif; ?>
                    <div class="h-[1px] flex-1 bg-stone-200 dark:bg-white/5"></div>
                </div>
                <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden transition-all">
                    <table class="w-full text-left">
                        <thead class="bg-stone-50 dark:bg-white/5 border-b border-stone-200 dark:border-white/5">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Time</th>
                                <th class="px-6 py-3 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Account</th>
                                <th class="px-6 py-3 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Type / Ref</th>
                                <th class="px-6 py-3 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Note</th>
                                <th class="px-6 py-3 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right pr-6">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-50 dark:divide-white/5">
                            <?php foreach ($txns as $t): ?>
                            <tr class="hover:bg-blue-50/20 dark:hover:bg-white/5 transition-all">
                                <td class="px-6 py-3.5 text-[11px] font-black text-stone-400 dark:text-slate-500 whitespace-nowrap"><?= date('H:i', strtotime($t['created_at'])) ?></td>
                                <td class="px-6 py-3.5">
                                    <?php if ($t['account_name']): ?>
                                        <p class="text-[12px] font-black text-slate-700 dark:text-slate-300"><?= htmlspecialchars($t['account_name']) ?></p>
                                    <?php else: ?>
                                        <p class="text-[12px] font-black text-stone-400 dark:text-slate-500 italic">External / Cash</p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?= match($t['reference_type']) { 'sale' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400', 'purchase' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400', 'customer_credit' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400', 'supplier_credit' => 'bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400', default => 'bg-stone-100 dark:bg-white/10 text-stone-600 dark:text-slate-400' } ?>">
                                        <?= str_replace('_', ' ', $t['reference_type']) ?>
                                    </span>
                                    <span class="text-[10px] text-stone-400 dark:text-slate-500 ml-1.5 font-mono">#<?= $t['reference_id'] ?></span>
                                </td>
                                <td class="px-6 py-3.5 text-[11px] text-stone-400 dark:text-slate-500"><?= htmlspecialchars($t['note'] ?? '—') ?></td>
                                <td class="px-6 py-3.5 text-right pr-6 font-black text-[13px] <?= $t['direction'] === 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500 dark:text-rose-400' ?>">
                                    <?= $t['direction'] === 'in' ? '+' : '-' ?> LKR <?= number_format($t['amount'], 2) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';
