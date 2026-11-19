<?php ob_start();

function lkr(float $amount): string {
    return 'LKR ' . number_format($amount, 2);
}
?>

<div class="flex-1 flex flex-col min-h-0 w-full">
    <div class="flex items-center justify-between mb-8 flex-shrink-0">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Financial Accounts</h2>
            <p class="text-[10px] font-bold text-stone-400 dark:text-slate-500 uppercase tracking-widest mt-1">Cash drawers & bank account balances</p>
        </div>
        <span class="text-[10px] font-black text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-500/10 px-3 py-1.5 rounded-full uppercase tracking-widest border border-brand-100 dark:border-brand-500/20"><?= count($accounts) ?> Accounts</span>
    </div>

    <div class="flex-1 min-h-0 overflow-y-auto scrollbar-hide space-y-8">
        <?php foreach ($accounts as $acc): ?>
        <?php
            $isCash  = $acc['type'] === 'cash';
            $gradient = $isCash ? 'from-[#0f172a] via-[#083344] to-[#0f766e]' : 'from-[#0f172a] via-[#1e3a8a] to-[#1e40af]';
            $shadow   = $isCash ? 'shadow-teal-900/20' : 'shadow-blue-900/20';
            $textColor= $isCash ? 'text-teal-100' : 'text-blue-100';
            $txns    = $transactions[$acc['id']] ?? [];
        ?>
        <div class="bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-xl <?= $shadow ?> overflow-hidden flex flex-col">

            <!-- Account Header (Gradient) -->
            <div class="relative bg-gradient-to-br <?= $gradient ?> px-8 py-6 group overflow-hidden flex-shrink-0">
                <?php if ($isCash): ?>
                    <svg class="absolute -right-6 -bottom-10 w-48 h-48 text-white/5 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <?php else: ?>
                    <svg class="absolute -right-6 -bottom-10 w-48 h-48 text-white/5 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-700 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <?php endif; ?>
                
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/10 group-hover:bg-white/20 transition-all shadow-sm">
                            <?php if ($isCash): ?>
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <?php else: ?>
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white tracking-tight mb-1"><?= htmlspecialchars($acc['name']) ?></p>
                            <span class="text-[10px] font-black px-2.5 py-1 rounded-lg bg-white/10 backdrop-blur-md text-white border border-white/5 uppercase tracking-widest inline-block"><?= ucfirst($acc['type']) ?></span>
                        </div>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-5xl font-black text-white tracking-tighter mb-1"><?= lkr((float)$acc['balance']) ?></p>
                        <p class="text-[11px] font-bold <?= $textColor ?> uppercase tracking-widest">Current Balance</p>
                    </div>
                </div>
            </div>

            <!-- Update Balance Form -->
            <?php if (Auth::can('accounts')): ?>
            <div class="px-8 py-5 border-b border-stone-200/60 dark:border-white/5 bg-stone-50/50 dark:bg-white/[0.02]">
                <form method="POST" action="/accounts/update" class="flex flex-col sm:flex-row gap-4 items-end">
                    <input type="hidden" name="account_id" value="<?= $acc['id'] ?>">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Set New Balance</label>
                        <input type="number" name="balance" step="0.01" min="0"
                            value="<?= number_format((float)$acc['balance'], 2, '.', '') ?>"
                            class="w-full bg-white dark:bg-[#0a0a0a] border border-stone-200/60 dark:border-white/10 rounded-xl px-4 py-3 text-[14px] text-slate-900 dark:text-white font-black focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all shadow-sm">
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Note (optional)</label>
                        <input type="text" name="note" placeholder="e.g. Opening balance"
                            class="w-full bg-white dark:bg-[#0a0a0a] border border-stone-200/60 dark:border-white/10 rounded-xl px-4 py-3 text-[14px] text-slate-900 dark:text-white font-black focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600 shadow-sm">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-brand-500 hover:bg-brand-600 text-white text-[12px] font-black px-8 py-3 rounded-xl transition-all shadow-lg shadow-brand-500/30 uppercase tracking-widest whitespace-nowrap">Update</button>
                </form>
            </div>
            <?php endif; ?>

            <!-- Account Footer / Link to Transactions -->
            <div class="px-8 py-4 bg-white dark:bg-[#151515] flex justify-end">
                <a href="/transactions?account_id=<?= $acc['id'] ?>" class="text-[11px] font-black text-brand-600 hover:text-brand-500 uppercase tracking-widest flex items-center gap-2 transition-colors">
                    View Transaction History
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';