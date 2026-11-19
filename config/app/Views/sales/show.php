<?php ob_start(); ?>

<div class="flex-1 flex flex-col min-h-0 w-full space-y-6">

    <!-- Header & Back -->
    <div class="flex items-center gap-4 flex-shrink-0">
        <a href="/sales" class="p-2 rounded-xl bg-white dark:bg-white/5 border border-stone-200 dark:border-white/10 text-stone-400 hover:text-brand-500 hover:border-brand-500/30 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Sale #<?= $sale['id'] ?></h2>
            <p class="text-[10px] font-bold text-stone-400 dark:text-slate-500 uppercase tracking-widest mt-1">Transaction Details</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-1 min-h-0">

        <!-- Left: Info & Totals -->
        <div class="lg:col-span-1 flex flex-col gap-6">
            <!-- Sale Info -->
            <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                    <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Sale Info</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Customer</span>
                        <span class="text-[12px] font-black text-slate-900 dark:text-white"><?= htmlspecialchars($sale['customer_name'] ?? 'Walk-in') ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Date</span>
                        <span class="text-[12px] font-black text-slate-900 dark:text-white"><?= date('d M Y, H:i', strtotime($sale['created_at'])) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Payment</span>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?= match($sale['payment_method']) { 'cash' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400', 'bank' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400', 'credit' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400', default => 'bg-stone-100 dark:bg-white/10 text-stone-600 dark:text-slate-400' } ?>"><?= ucfirst($sale['payment_method']) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Status</span>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?= match($sale['status']) { 'completed' => 'bg-emerald-500 text-white', 'credit' => 'bg-amber-500 text-white', 'voided' => 'bg-rose-500 text-white', default => 'bg-stone-200 dark:bg-white/10 text-stone-600 dark:text-slate-400' } ?>"><?= ucfirst($sale['status']) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Served by</span>
                        <span class="text-[12px] font-black text-slate-900 dark:text-white"><?= htmlspecialchars($sale['created_by_name'] ?? '—') ?></span>
                    </div>

                    <?php if ($sale['note']): ?>
                    <div class="pt-4 border-t border-stone-200 dark:border-white/5">
                        <span class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Note</span>
                        <p class="text-[12px] font-medium text-slate-700 dark:text-slate-300"><?= htmlspecialchars($sale['note']) ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="pt-4 border-t border-stone-200 dark:border-white/5 mt-2">
                        <a href="/sales/<?= $sale['id'] ?>/print" target="_blank"
                           class="flex items-center justify-center gap-2 w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[11px] font-black py-3 rounded-xl transition-all shadow-md hover:bg-slate-800 dark:hover:bg-stone-100 uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Print Invoice
                        </a>
                    </div>
                </div>
            </div>

            <!-- Totals -->
            <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm p-6 space-y-3">
                <div class="flex justify-between items-center text-stone-500 dark:text-slate-400">
                    <span class="text-[11px] font-black uppercase tracking-widest">Subtotal</span>
                    <span class="text-[12px] font-black">LKR <?= number_format((float)$sale['subtotal'], 2) ?></span>
                </div>
                <?php if ((float)$sale['discount'] > 0): ?>
                <div class="flex justify-between items-center text-rose-500 dark:text-rose-400">
                    <span class="text-[11px] font-black uppercase tracking-widest">Discount</span>
                    <span class="text-[12px] font-black">− LKR <?= number_format((float)$sale['discount'], 2) ?></span>
                </div>
                <?php endif; ?>
                <div class="flex justify-between items-center pt-3 border-t border-stone-200 dark:border-white/5">
                    <span class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Total</span>
                    <span class="text-xl font-black text-brand-600 dark:text-brand-400 tracking-tighter">LKR <?= number_format((float)$sale['total'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Right: Items -->
        <div class="lg:col-span-2 flex flex-col min-h-0">
            <div class="flex-1 min-h-0 bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5 flex-shrink-0">
                    <h2 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Items Purchased</h2>
                </div>
                <div class="flex-1 overflow-y-auto scrollbar-hide">
                    <table class="w-full text-left">
                        <thead class="sticky top-0 bg-stone-50 dark:bg-[#0a0a0a] z-10 border-b border-stone-200 dark:border-white/5">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Product</th>
                                <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Qty</th>
                                <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right">Unit Price</th>
                                <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right pr-6">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-white/5">
                            <?php foreach ($items as $item): ?>
                            <tr class="hover:bg-blue-50/20 dark:hover:bg-white/5 transition-all">
                                <td class="px-6 py-4 text-[13px] font-black text-slate-800 dark:text-slate-200"><?= htmlspecialchars($item['product_name'] ?? '—') ?></td>
                                <td class="px-6 py-4 text-center"><span class="px-2.5 py-1 bg-stone-100 dark:bg-white/10 text-slate-600 dark:text-slate-300 rounded-lg text-[10px] font-black"><?= (int)$item['qty'] ?></span></td>
                                <td class="px-6 py-4 text-right text-[12px] font-black text-stone-500 dark:text-slate-400">LKR <?= number_format((float)$item['unit_price'], 2) ?></td>
                                <td class="px-6 py-4 text-right pr-6 font-black text-[13px] text-slate-900 dark:text-white">LKR <?= number_format((float)$item['subtotal'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';