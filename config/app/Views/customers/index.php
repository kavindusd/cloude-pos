<?php ob_start(); ?>

<div class="flex-1 flex flex-col min-h-0 w-full">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Customer Registry</h2>
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-widest mt-1 flex items-center gap-2">
                Manage accounts, credit limits & activity
                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                <span class="text-brand-500 font-black"><?= count($customers) ?> Total Customers</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="/customers/create" class="w-10 h-10 bg-brand-500 hover:bg-brand-600 text-white flex items-center justify-center rounded-full transition-all shadow-lg shadow-brand-500/20" title="Add Customer"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg></a>
        </div>
    </div>

    <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 p-5 mb-6 shadow-md transition-all">
        <div class="flex flex-col md:flex-row items-center gap-3">
            <div class="relative flex-1 group w-full">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-brand-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" id="customerSearch" placeholder="Search customers..." class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl pl-11 pr-4 py-2.5 text-[13px] text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-400 font-medium">
            </div>
            <select id="typeFilter" class="w-full md:w-44 bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-600 dark:text-slate-400 focus:ring-2 focus:ring-brand-500/20 appearance-none cursor-pointer font-medium">
                <option value="">All Types</option>
                <option value="retail">Retail</option>
                <option value="wholesale">Wholesale</option>
            </select>
            <select id="statusFilter" class="w-full md:w-44 bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-600 dark:text-slate-400 focus:ring-2 focus:ring-brand-500/20 appearance-none cursor-pointer font-medium">
                <option value="">All Statuses</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>

    <div class="flex-1 min-h-0 bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden flex flex-col transition-all">
        <div class="flex-1 overflow-y-auto scrollbar-hide">
            <?php if (empty($customers)): ?>
                <div class="flex flex-col items-center justify-center py-24 opacity-30">
                    <svg class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                    <p class="text-xs font-black uppercase tracking-widest">No customers yet</p>
                </div>
            <?php else: ?>
            <table class="w-full text-left" id="customersTable">
                <thead class="sticky top-0 bg-stone-50 dark:bg-[#0a0a0a] z-10 border-b border-stone-200 dark:border-white/5">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Customer</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Contact</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Type</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right">Credit Limit</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right">Outstanding</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right pr-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-white/5">
                    <?php foreach ($customers as $c): ?>
                    <?php $outstanding = (float)$c['outstanding_credit']; $limit = (float)$c['credit_limit']; $overLimit = $outstanding > $limit && $limit > 0; ?>
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-white/5 transition-all customer-row" data-name="<?= strtolower(htmlspecialchars($c['name'])) ?>" data-type="<?= $c['type'] ?>" data-active="<?= (int)$c['active'] ?>">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-brand-500/10 flex items-center justify-center text-brand-500 text-[11px] font-black flex-shrink-0"><?= strtoupper(substr($c['name'], 0, 1)) ?></div>
                                <div>
                                    <p class="text-[13px] font-black text-slate-800 dark:text-slate-200"><?= htmlspecialchars($c['name']) ?></p>
                                    <?php if (!empty($c['address'])): ?><p class="text-[10px] text-stone-400 mt-0.5 truncate max-w-[160px]"><?= htmlspecialchars($c['address']) ?></p><?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[12px] font-bold text-slate-600 dark:text-slate-400"><?= htmlspecialchars($c['phone'] ?? '—') ?></p>
                            <p class="text-[10px] text-stone-400 mt-0.5"><?= htmlspecialchars($c['email'] ?? '—') ?></p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?= $c['type'] === 'wholesale' ? 'bg-purple-100 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400' : 'bg-stone-100 dark:bg-white/10 text-stone-600 dark:text-slate-400' ?>"><?= ucfirst($c['type']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-right text-[12px] font-black text-slate-700 dark:text-slate-300"><?= $limit > 0 ? 'LKR ' . number_format($limit, 2) : '—' ?></td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[12px] font-black <?= $overLimit ? 'text-rose-600' : ($outstanding > 0 ? 'text-amber-600' : 'text-stone-400') ?>"><?= $outstanding > 0 ? 'LKR ' . number_format($outstanding, 2) : '—' ?><?php if ($overLimit): ?> ⚠<?php endif; ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($c['active']): ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Active</span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-stone-100 dark:bg-white/10 text-stone-400"><span class="w-1.5 h-1.5 bg-stone-300 rounded-full"></span> Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right pr-6">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="/customers/<?= $c['id'] ?>/edit" class="p-1.5 rounded-lg text-stone-400 hover:text-brand-500 hover:bg-brand-500/10 transition-all" title="Edit"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                <a href="/customers/<?= $c['id'] ?>/toggle" class="p-1.5 rounded-lg text-stone-400 hover:text-amber-500 hover:bg-amber-500/10 transition-all" title="Toggle"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></a>
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
    const search = document.getElementById('customerSearch');
    const type   = document.getElementById('typeFilter');
    const status = document.getElementById('statusFilter');
    const rows   = document.querySelectorAll('.customer-row');
    const counter = document.getElementById('customerCount');
    function applyFilters() {
        const q = search.value.toLowerCase(), t = type.value, s = status.value;
        let v = 0;
        rows.forEach(r => {
            const show = r.dataset.name.includes(q) && (t===''||r.dataset.type===t) && (s===''||r.dataset.active===s);
            r.style.display = show ? '' : 'none';
            if (show) v++;
        });
        counter.textContent = v + ' shown';
    }
    search.addEventListener('input', applyFilters);
    type.addEventListener('change', applyFilters);
    status.addEventListener('change', applyFilters);
})();
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';