<?php ob_start(); ?>

<div class="flex-1 flex flex-col min-h-0 w-full space-y-6">

    <!-- Header + Date Picker -->
    <div class="flex items-center justify-between flex-shrink-0">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Daily Report</h2>
            <p class="text-[10px] font-bold text-stone-400 dark:text-slate-500 uppercase tracking-widest mt-1">
                <?= date('l, d F Y', strtotime($date)) ?>
                <?php if ($date === date('Y-m-d')): ?>
                    <span class="ml-2 text-brand-500">· Today</span>
                <?php endif; ?>
            </p>
        </div>
        <form action="/reports/daily" method="GET" class="flex items-center gap-3">
            <input type="date" name="date" value="<?= $date ?>"
                class="bg-white dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all shadow-sm">
            <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white text-[11px] font-black px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-brand-500/20 uppercase tracking-widest">View Report</button>
        </form>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 flex-shrink-0">
        <!-- Total Sales -->
        <div class="bg-white dark:bg-[#151515] p-6 rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md group hover:-translate-y-0.5 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-2xl bg-brand-500/10 flex items-center justify-center text-brand-500 group-hover:bg-brand-500 group-hover:text-white transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m.599-1c.51-.598.811-1.357.811-2.188 0-1.657-1.343-3-3-3s-3 1.343-3 3c0 .831.302 1.59.811 2.188M12 16c1.11 0 2.08.402 2.599 1"/></svg>
                </div>
                <span class="text-[10px] font-black text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-500/10 px-2.5 py-1 rounded-lg uppercase tracking-widest border border-brand-100 dark:border-brand-500/20"><?= $sales['total_orders'] ?> Orders</span>
            </div>
            <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Sales</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter">LKR <?= number_format($sales['total'], 2) ?></p>
        </div>

        <!-- Estimated Profit -->
        <div class="bg-white dark:bg-[#151515] p-6 rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md group hover:-translate-y-0.5 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <?php $margin = $sales['total'] > 0 ? round(($profit / $sales['total']) * 100, 1) : 0; ?>
                <span class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 rounded-lg uppercase tracking-widest border border-emerald-100 dark:border-emerald-500/20">Margin: <?= $margin ?>%</span>
            </div>
            <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-1">Estimated Profit</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter">LKR <?= number_format($profit, 2) ?></p>
        </div>

        <!-- Purchases -->
        <div class="bg-white dark:bg-[#151515] p-6 rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md group hover:-translate-y-0.5 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <span class="text-[10px] font-black text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-2.5 py-1 rounded-lg uppercase tracking-widest border border-amber-100 dark:border-amber-500/20"><?= $purchases['total_orders'] ?> Orders</span>
            </div>
            <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Purchases</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter">LKR <?= number_format($purchases['total'], 2) ?></p>
        </div>
    </div>

    <!-- Two-column: Top Products + Category Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 flex-shrink-0">

        <!-- Top Selling Products -->
        <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden transition-all">
            <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-brand-500 rounded-full"></div>
                    <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Top Selling Products</h3>
                </div>
            </div>
            <?php if (empty($topItems)): ?>
                <div class="flex flex-col items-center justify-center py-16 opacity-30">
                    <svg class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <p class="text-[10px] font-black uppercase tracking-widest">No sales for this date</p>
                </div>
            <?php else: ?>
            <table class="w-full text-left">
                <thead class="bg-stone-50 dark:bg-white/5 border-b border-stone-200 dark:border-white/5">
                    <tr>
                        <th class="px-6 py-3 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Product</th>
                        <th class="px-6 py-3 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Qty</th>
                        <th class="px-6 py-3 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right pr-6">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-white/5">
                    <?php foreach ($topItems as $i => $item): ?>
                    <tr class="hover:bg-blue-50/20 dark:hover:bg-white/5 transition-all">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <span class="w-5 h-5 rounded-lg bg-brand-500/10 text-brand-500 text-[9px] font-black flex items-center justify-center flex-shrink-0"><?= $i + 1 ?></span>
                                <span class="text-[12px] font-black text-slate-800 dark:text-slate-200"><?= htmlspecialchars($item['name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-center"><span class="px-2.5 py-1 bg-stone-100 dark:bg-white/10 text-slate-600 dark:text-slate-300 rounded-lg text-[10px] font-black"><?= (int)$item['total_qty'] ?></span></td>
                        <td class="px-6 py-3.5 text-right pr-6 font-black text-[12px] text-slate-800 dark:text-slate-200">LKR <?= number_format($item['total_revenue'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- Sales by Category -->
        <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden transition-all">
            <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Sales by Category</h3>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <?php if (empty($catSales)): ?>
                    <div class="flex flex-col items-center justify-center py-12 opacity-30">
                        <svg class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <p class="text-[10px] font-black uppercase tracking-widest">No data available</p>
                    </div>
                <?php else: ?>
                    <?php $maxRev = max(array_column($catSales, 'total_revenue')) ?: 1; ?>
                    <?php $colors = ['bg-brand-500', 'bg-emerald-500', 'bg-amber-500', 'bg-purple-500', 'bg-rose-500', 'bg-sky-500']; ?>
                    <?php foreach ($catSales as $i => $cat): ?>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[12px] font-black text-slate-700 dark:text-slate-300"><?= htmlspecialchars($cat['name']) ?></span>
                                <span class="text-[11px] font-black text-slate-600 dark:text-slate-400">LKR <?= number_format($cat['total_revenue'], 2) ?></span>
                            </div>
                            <div class="w-full h-2 bg-stone-100 dark:bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full <?= $colors[$i % count($colors)] ?> rounded-full transition-all duration-700" style="width: <?= ($cat['total_revenue'] / $maxRev) * 100 ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Daily Cashflow -->
    <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden flex-shrink-0 transition-all">
        <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
            <div class="flex items-center gap-2">
                <div class="w-1 h-5 bg-amber-500 rounded-full"></div>
                <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Daily Cashflow Summary</h3>
            </div>
        </div>
        <div class="p-6">
            <?php
                $inflow = 0; $outflow = 0;
                foreach ($txns as $t) {
                    if ($t['direction'] === 'in')  $inflow  = $t['total'];
                    if ($t['direction'] === 'out') $outflow = $t['total'];
                }
                $net = $inflow - $outflow;
            ?>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Inflow -->
                <div class="flex items-center gap-4 p-4 bg-emerald-50 dark:bg-emerald-500/5 rounded-2xl border border-emerald-100 dark:border-emerald-500/10">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 17l-4 4m0 0l-4-4m4 4V3"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-widest">Total Inflow</p>
                        <p class="text-lg font-black text-emerald-700 dark:text-emerald-400 tracking-tighter">LKR <?= number_format($inflow, 2) ?></p>
                        <p class="text-[9px] text-emerald-500 font-bold mt-0.5">Sales, payments received</p>
                    </div>
                </div>
                <!-- Outflow -->
                <div class="flex items-center gap-4 p-4 bg-rose-50 dark:bg-rose-500/5 rounded-2xl border border-rose-100 dark:border-rose-500/10">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-rose-600 dark:text-rose-500 uppercase tracking-widest">Total Outflow</p>
                        <p class="text-lg font-black text-rose-700 dark:text-rose-400 tracking-tighter">LKR <?= number_format($outflow, 2) ?></p>
                        <p class="text-[9px] text-rose-400 font-bold mt-0.5">Purchases, expenses</p>
                    </div>
                </div>
                <!-- Net -->
                <div class="flex items-center gap-4 p-4 <?= $net >= 0 ? 'bg-brand-50 dark:bg-brand-500/5 border-brand-100 dark:border-brand-500/10' : 'bg-rose-50 dark:bg-rose-500/5 border-rose-100 dark:border-rose-500/10' ?> rounded-2xl border">
                    <div class="w-12 h-12 rounded-2xl <?= $net >= 0 ? 'bg-brand-500/20 text-brand-500' : 'bg-rose-500/20 text-rose-500' ?> flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black <?= $net >= 0 ? 'text-brand-600 dark:text-brand-400' : 'text-rose-600 dark:text-rose-400' ?> uppercase tracking-widest">Net Movement</p>
                        <p class="text-lg font-black <?= $net >= 0 ? 'text-brand-700 dark:text-brand-400' : 'text-rose-700 dark:text-rose-400' ?> tracking-tighter"><?= $net >= 0 ? '+' : '-' ?> LKR <?= number_format(abs($net), 2) ?></p>
                        <p class="text-[9px] <?= $net >= 0 ? 'text-brand-500' : 'text-rose-400' ?> font-bold mt-0.5"><?= $net >= 0 ? 'Positive day' : 'Net deficit' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';
