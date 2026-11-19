<?php ob_start(); ?>

<div class="flex-1 overflow-y-auto scrollbar-hide w-full min-w-0">
    <div class="max-w-[1600px] mx-auto space-y-10 pb-12 w-full">
        
        <!-- Welcome Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 w-full">
            <div class="min-w-0">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-1.5 h-6 bg-brand-500 rounded-full shadow-lg shadow-brand-500/40"></div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Dashboard</h2>
                </div>
                <p class="text-[11px] font-bold text-stone-400 dark:text-slate-500 mt-1 tracking-wider pl-4">Real-time Operations & Financial Overview</p>
            </div>
            <div class="flex items-center gap-4 bg-white/60 dark:bg-white/5 backdrop-blur-xl px-5 py-2.5 rounded-2xl border border-white/80 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                    </span>
                </div>
                <div class="h-4 w-[1px] bg-stone-200 dark:bg-white/10"></div>
                <span class="text-[10px] font-black text-stone-500 dark:text-slate-400 uppercase tracking-widest"><?= date('D, d M') ?></span>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Today's Sales -->
            <div class="relative bg-gradient-to-br from-[#0f172a] via-[#1e3a8a] to-[#1e40af] p-6 rounded-3xl border border-white/5 shadow-lg shadow-blue-900/20 hover:-translate-y-1 transition-all group overflow-hidden">
                <svg class="absolute -right-6 -bottom-6 w-40 h-40 text-white/5 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-700 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/10 group-hover:bg-white/20 transition-all shadow-sm">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <span class="text-[9px] font-black text-blue-100 bg-white/10 backdrop-blur-md px-2.5 py-1.5 rounded-lg uppercase tracking-widest border border-white/5">Daily Revenue</span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-black text-white tracking-tighter mb-1">LKR <?= number_format($todaySales['total']/1000, 1) ?>k</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-blue-200 uppercase tracking-widest"><?= $todaySales['count'] ?> Orders Today</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="relative bg-gradient-to-br from-[#0f172a] via-[#581c87] to-[#6b21a8] p-6 rounded-3xl border border-white/5 shadow-lg shadow-purple-900/20 hover:-translate-y-1 transition-all group overflow-hidden">
                <svg class="absolute -right-6 -bottom-6 w-40 h-40 text-white/5 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/10 group-hover:bg-white/20 transition-all shadow-sm">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <span class="text-[9px] font-black text-purple-100 bg-white/10 backdrop-blur-md px-2.5 py-1.5 rounded-lg uppercase tracking-widest border border-white/5">30-Day Yield</span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-black text-white tracking-tighter mb-1">LKR <?= number_format($monthSales['total']/1000, 1) ?>k</h3>
                        <p class="text-[11px] font-bold text-purple-200 uppercase tracking-widest">Current Month Gross</p>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="relative bg-gradient-to-br from-[#0f172a] via-[#7c2d12] to-[#9a3412] p-6 rounded-3xl border border-white/5 shadow-lg shadow-orange-900/20 hover:-translate-y-1 transition-all group overflow-hidden">
                <svg class="absolute -right-6 -bottom-6 w-40 h-40 text-white/5 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-700 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/10 group-hover:bg-white/20 transition-all shadow-sm">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        </div>
                        <?php if ($lowStock['count'] > 0): ?>
                            <span class="text-[9px] font-black text-rose-100 bg-rose-500/30 backdrop-blur-md px-2.5 py-1.5 rounded-lg uppercase tracking-widest border border-rose-500/20 animate-pulse">Critical</span>
                        <?php else: ?>
                            <span class="text-[9px] font-black text-emerald-100 bg-emerald-500/20 backdrop-blur-md px-2.5 py-1.5 rounded-lg uppercase tracking-widest border border-emerald-500/20">Healthy</span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h3 class="text-4xl font-black text-white tracking-tighter mb-1"><?= $lowStock['count'] ?> <span class="text-xl text-orange-200/60">items</span></h3>
                        <a href="/stock" class="text-[11px] font-bold text-orange-200 hover:text-white uppercase tracking-widest transition-colors flex items-center gap-1">Low Stock Alerts <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>
            </div>

            <!-- Inbound Purchases -->
            <div class="relative bg-gradient-to-br from-[#0f172a] via-[#083344] to-[#0f766e] p-6 rounded-3xl border border-white/5 shadow-lg shadow-teal-900/20 hover:-translate-y-1 transition-all group overflow-hidden">
                <svg class="absolute -right-6 -bottom-6 w-40 h-40 text-white/5 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/10 group-hover:bg-white/20 transition-all shadow-sm">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <span class="text-[9px] font-black text-teal-100 bg-white/10 backdrop-blur-md px-2.5 py-1.5 rounded-lg uppercase tracking-widest border border-white/5">Inbound</span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-black text-white tracking-tighter mb-1"><?= $pendingPurchases ?> <span class="text-xl text-teal-200/60">orders</span></h3>
                        <a href="/purchases" class="text-[11px] font-bold text-teal-200 hover:text-white uppercase tracking-widest transition-colors flex items-center gap-1">Pending Procurement <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Operations -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Recent Sales -->
                <div class="bg-white dark:bg-white/5 rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-5 border-b border-stone-100 dark:border-white/5 flex items-center justify-between bg-stone-50/50 dark:bg-transparent">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 dark:text-brand-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h2 class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Live Sales Feed</h2>
                        </div>
                        <a href="/sales" class="text-[10px] font-black bg-stone-100 dark:bg-white/10 hover:bg-stone-200 text-stone-600 dark:text-slate-300 px-3 py-1.5 rounded-lg transition-all uppercase tracking-widest">View Ledger</a>
                    </div>
                    <div class="overflow-x-auto scrollbar-hide p-2">
                        <table class="w-full text-left border-separate border-spacing-y-1">
                            <tbody class="">
                                <?php foreach ($recentSales as $sale): ?>
                                    <tr class="hover:bg-stone-50 dark:hover:bg-white/5 transition-all group rounded-2xl">
                                        <td class="px-4 py-3 rounded-l-2xl">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-stone-100 dark:bg-white/10 flex items-center justify-center text-[10px] font-black text-stone-500 dark:text-slate-400">
                                                    <?= substr($sale['customer_name'] ?? 'W', 0, 1) ?>
                                                </div>
                                                <div>
                                                    <p class="text-[13px] font-black text-slate-800 dark:text-slate-200 leading-none mb-1"><?= htmlspecialchars($sale['customer_name'] ?? 'Walking Customer') ?></p>
                                                    <p class="text-[10px] text-stone-400 dark:text-slate-500 font-bold uppercase tracking-widest">#<?= str_pad($sale['id'], 5, '0', STR_PAD_LEFT) ?> • <?= date('h:i A', strtotime($sale['created_at'])) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="text-[14px] font-black text-slate-900 dark:text-white">LKR <?= number_format($sale['total'], 2) ?></span>
                                        </td>
                                        <td class="px-4 py-3 rounded-r-2xl w-28 text-right">
                                            <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest inline-block <?= $sale['status'] === 'completed' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-stone-100 text-stone-600 dark:bg-white/10 dark:text-slate-400' ?>">
                                                <?= $sale['status'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Purchases (Horizontal Cards) -->
                <div>
                    <div class="flex items-center justify-between mb-4 px-2">
                        <h2 class="text-[11px] font-black text-stone-500 dark:text-slate-400 uppercase tracking-[0.2em]">Recent Procurement</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach (array_slice($recentPurchases, 0, 4) as $purchase): ?>
                            <div class="flex items-center justify-between p-5 bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-stone-50/50 dark:to-white/[0.02] opacity-0 group-hover:opacity-100 transition-all pointer-events-none"></div>
                                <div class="flex items-center gap-4 relative z-10">
                                    <div class="w-12 h-12 rounded-2xl bg-stone-100 dark:bg-white/5 flex items-center justify-center text-stone-500 dark:text-slate-400 group-hover:text-brand-500 group-hover:bg-brand-50 dark:group-hover:bg-brand-500/10 transition-all">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-black text-slate-800 dark:text-white leading-none mb-1.5"><?= htmlspecialchars($purchase['supplier_name']) ?></p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[9px] font-black text-stone-400 uppercase tracking-widest">#<?= $purchase['id'] ?></span>
                                            <span class="w-1 h-1 rounded-full bg-stone-300"></span>
                                            <span class="text-[9px] font-black uppercase tracking-widest <?= $purchase['status'] === 'received' ? 'text-emerald-500' : 'text-amber-500' ?>"><?= $purchase['status'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right relative z-10">
                                    <p class="text-[14px] font-black text-slate-900 dark:text-white leading-none mb-1.5"><?= number_format($purchase['total'], 2) ?></p>
                                    <p class="text-[9px] font-black text-stone-400 uppercase tracking-widest"><?= date('d M', strtotime($purchase['created_at'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- Right Column: Financials -->
            <div class="lg:col-span-1 space-y-8">
                
                <!-- Credit Exposure (Glassmorphic Premium Panel) -->
                <div class="relative bg-slate-900 dark:bg-[#0a0a0a] rounded-3xl p-8 text-white shadow-2xl overflow-hidden border border-slate-800 dark:border-white/5 group">
                    <!-- Dynamic glow -->
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-brand-500/30 rounded-full blur-[80px] group-hover:bg-brand-500/40 transition-all duration-700"></div>
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-emerald-500/20 rounded-full blur-[80px] group-hover:bg-emerald-500/30 transition-all duration-700"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em]">Credit Exposure</h2>
                            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-md">
                                <svg class="w-4 h-4 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Receivables -->
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-5 border border-white/10 hover:bg-white/10 transition-colors">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Receivables</p>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">Customers</span>
                                </div>
                                <h4 class="text-3xl font-black text-white tracking-tighter">LKR <?= number_format($customerCredits, 2) ?></h4>
                            </div>

                            <!-- Payables -->
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl p-5 border border-white/10 hover:bg-white/10 transition-colors">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-rose-400 shadow-[0_0_8px_rgba(251,113,133,0.8)]"></span>
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Payables</p>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">Suppliers</span>
                                </div>
                                <h4 class="text-3xl font-black text-white tracking-tighter">LKR <?= number_format($supplierCredits, 2) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Global Balances -->
                <div class="bg-white dark:bg-white/5 rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-stone-100 dark:border-white/5 flex items-center justify-between bg-stone-50/50 dark:bg-transparent">
                        <h2 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Global Balances</h2>
                        <a href="/accounts" class="w-7 h-7 rounded-lg bg-stone-100 dark:bg-white/10 flex items-center justify-center text-stone-500 hover:text-brand-500 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                    <div class="p-6 space-y-4">
                        <?php foreach ($accounts as $account): ?>
                            <div class="group">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-stone-100 dark:bg-white/5 flex items-center justify-center text-stone-400 group-hover:text-brand-500 group-hover:bg-brand-50 transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[13px] font-black text-slate-800 dark:text-slate-200 leading-none mb-1"><?= htmlspecialchars($account['name']) ?></p>
                                            <p class="text-[9px] font-black text-stone-400 uppercase tracking-widest"><?= $account['type'] ?></p>
                                        </div>
                                    </div>
                                    <span class="text-[14px] font-black text-slate-900 dark:text-white tracking-tight">LKR <?= number_format($account['balance'], 2) ?></span>
                                </div>
                                <div class="w-full h-1.5 bg-stone-100 dark:bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand-500 rounded-full" style="width: <?= min(100, max(5, ($account['balance'] / 500000) * 100)) ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';