<?php ob_start(); ?>

<div class="flex-1 flex flex-col min-h-0 w-full space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">System Users</h2>
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-widest mt-1">Manage administrative and staff access control</p>
        </div>
        <?php if (Auth::is('super_admin')): ?>
            <a href="/users/create" class="w-10 h-10 bg-brand-500 hover:bg-brand-600 text-white flex items-center justify-center rounded-full shadow-lg shadow-brand-500/20 transition-all group" title="Add New User">
                <svg class="w-5 h-5 transform group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </a>
        <?php endif; ?>
    </div>

    <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden transition-all">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-stone-50 dark:bg-white/5 border-b border-stone-200/60 dark:border-white/5">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">User Identity</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Access Role</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">System Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-white/5">
                    <?php foreach ($users as $u): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-stone-100 dark:bg-white/5 flex items-center justify-center text-slate-400 font-bold text-xs uppercase group-hover:bg-brand-500 group-hover:text-white transition-all">
                                    <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="text-[13px] font-bold text-slate-800 dark:text-white leading-none"><?= htmlspecialchars($u['name']) ?></p>
                                    <p class="text-[11px] font-medium text-slate-400 dark:text-slate-500 mt-1.5"><?= htmlspecialchars($u['email']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <?php
                            $roleLabels = [
                                'super_admin'  => ['Super Admin', 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/20'],
                                'admin'        => ['Administrator', 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20'],
                                'manager'      => ['Manager', 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20'],
                                'stock_keeper' => ['Stock Keeper', 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20'],
                                'cashier'      => ['Cashier', 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-500/20'],
                            ];
                            [$label, $cls] = $roleLabels[$u['role']] ?? [$u['role'], 'bg-slate-100 text-slate-600'];
                            ?>
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border <?= $cls ?>"><?= $label ?></span>
                        </td>
                        <td class="px-8 py-5">
                            <?php if ($u['active']): ?>
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></div>
                                    <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Active</span>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                                    <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Disabled</span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <?php if ($u['id'] !== Auth::id()): ?>
                            <a href="/users/<?= $u['id'] ?>/toggle" 
                               class="text-[11px] font-black <?= $u['active'] ? 'text-rose-500 hover:text-rose-600' : 'text-emerald-500 hover:text-emerald-600' ?> uppercase tracking-widest underline decoration-2 underline-offset-4 transition-all">
                                <?= $u['active'] ? 'Disable Access' : 'Enable Access' ?>
                            </a>
                            <?php else: ?>
                                <span class="text-[10px] font-black text-slate-300 dark:text-slate-700 uppercase tracking-widest italic">Self Account</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';
?>