<?php ob_start(); ?>

<div class="max-w-6xl mx-auto space-y-8 pb-12">
    
    <!-- Profile & Security Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Personal Profile Card -->
        <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden transition-all">
            <div class="px-6 py-5 border-b border-stone-200/60 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-brand-500/10 flex items-center justify-center text-brand-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[13px] font-black text-slate-900 dark:text-white tracking-wider">Account Profile</h3>
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-widest mt-0.5">Your personal identity settings</p>
                    </div>
                </div>
            </div>
            
            <form action="/settings/profile" method="POST" class="p-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 tracking-widest mb-2.5">Display Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 tracking-widest mb-2.5">Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white text-[11px] font-black py-4 rounded-xl transition-all shadow-lg shadow-brand-500/20 uppercase tracking-[0.1em]">
                            Update Identity
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Security Card -->
        <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden transition-all">
            <div class="px-6 py-5 border-b border-stone-200/60 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2-2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-wider">Security Access</h3>
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">Control your login credentials</p>
                    </div>
                </div>
            </div>
            
            <form action="/settings/password" method="POST" class="p-8">
                <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2.5">Current Password</label>
                        <input type="password" name="current_password" required placeholder="••••••••"
                            class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2.5">New Password</label>
                            <input type="password" name="new_password" required placeholder="New Password"
                                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2.5">Confirm New</label>
                            <input type="password" name="confirm_password" required placeholder="Confirm New"
                                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-slate-900 dark:bg-white/10 hover:bg-slate-800 dark:hover:bg-white/20 text-white text-[11px] font-black py-4 rounded-xl transition-all shadow-lg uppercase tracking-[0.1em]">
                            Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- User Management Section (Super Admin Only) -->
    <?php if (Auth::is('super_admin')): ?>
    <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden transition-all">
        <div class="px-8 py-6 border-b border-stone-200/60 dark:border-white/5 bg-stone-50 dark:bg-white/5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-white tracking-tight">User Management</h3>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">Administrative access control center</p>
                </div>
            </div>
            <a href="/users/create" class="w-11 h-11 bg-brand-500 hover:bg-brand-600 text-white flex items-center justify-center rounded-full shadow-lg shadow-brand-500/20 transition-all group" title="Add New Admin/User">
                <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </a>
        </div>
        
        <div class="p-0">
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
                        <?php 
                        // Fetch all users for the management table
                        $users = User::all();
                        foreach ($users as $u): 
                        ?>
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
        <div class="px-8 py-4 bg-stone-50 dark:bg-white/5 border-t border-stone-200/60 dark:border-white/5">
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest italic">
                * Super Admins can manage all system user access and permissions.
            </p>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';
?>
