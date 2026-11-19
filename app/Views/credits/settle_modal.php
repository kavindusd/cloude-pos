<!-- Settle Modal (shared by supplier + customer views) -->
<div id="settleModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-[#111111] rounded-2xl border border-stone-200 dark:border-white/10 shadow-2xl w-full max-w-md mx-4 overflow-hidden">

        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
            <div class="flex items-center gap-2">
                <div class="w-1 h-5 bg-brand-500 rounded-full shadow-sm shadow-brand-500/30"></div>
                <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Record Settlement</h3>
            </div>
            <p id="modalSubtitle" class="text-[11px] text-stone-400 dark:text-slate-500 mt-1 font-bold pl-3"></p>
        </div>

        <form id="settleForm" method="POST" class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="date" required value="<?= date('Y-m-d') ?>"
                        class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Amount (LKR) <span class="text-rose-500">*</span></label>
                    <input type="number" name="amount" id="settleAmount" min="0.01" step="0.01" required
                        class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>
            </div>

            <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-4">
                Outstanding: <span id="settleMax" class="text-amber-600 dark:text-amber-400"></span>
            </p>

            <div class="mb-4">
                <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Account <span class="text-rose-500">*</span></label>
                <select name="account_id" id="settleAccount" required
                    class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer">
                    <option value="">— Select account —</option>
                    <?php foreach ($accounts as $a): ?>
                        <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?> (LKR <?= number_format((float)$a['balance'], 2) ?>)</option>
                    <?php endforeach; ?>
                    <option id="externalOption" value="0" class="hidden">External Payment (Not tracked)</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Note (Optional)</label>
                <textarea name="note" rows="2" placeholder="Reference, check number, etc."
                    class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600 resize-none"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-brand-500 hover:bg-brand-600 text-white text-[11px] font-black py-3 rounded-xl transition-all shadow-lg shadow-brand-500/20 uppercase tracking-widest">Confirm Settlement</button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-stone-100 dark:bg-white/5 hover:bg-stone-200 dark:hover:bg-white/10 text-stone-600 dark:text-slate-400 text-[11px] font-black py-3 rounded-xl transition-all uppercase tracking-widest">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(type, id, remaining, name) {
    const form     = document.getElementById('settleForm');
    const subtitle = document.getElementById('modalSubtitle');
    const maxEl    = document.getElementById('settleMax');
    const amtInput = document.getElementById('settleAmount');
    const extOpt   = document.getElementById('externalOption');

    form.action          = type === 'supplier' ? `/credits/suppliers/${id}/settle` : `/credits/customers/${id}/settle`;
    subtitle.textContent = `Settling for: ${name}`;
    maxEl.textContent    = 'LKR ' + parseFloat(remaining).toLocaleString('en', {minimumFractionDigits: 2});
    amtInput.value       = parseFloat(remaining).toFixed(2);
    amtInput.max         = parseFloat(remaining).toFixed(2);

    type === 'supplier' ? extOpt.classList.remove('hidden') : extOpt.classList.add('hidden');
    document.getElementById('settleModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('settleModal').classList.add('hidden');
}

document.getElementById('settleModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>