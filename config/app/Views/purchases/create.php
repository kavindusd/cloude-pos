<?php ob_start(); ?>

<form method="POST" action="/purchases/store" id="purchaseForm" class="flex-1 flex flex-col min-h-0 w-full space-y-6">

    <!-- Header -->
    <div class="flex items-center gap-4 flex-shrink-0">
        <a href="/purchases" class="p-2 rounded-xl bg-white dark:bg-white/5 border border-stone-200 dark:border-white/10 text-stone-400 hover:text-brand-500 hover:border-brand-500/30 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">New Purchase</h2>
            <p class="text-[10px] font-bold text-stone-400 dark:text-slate-500 uppercase tracking-widest mt-1">Record a new incoming inventory shipment</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-1 min-h-0">

        <!-- Left: Header fields -->
        <div class="lg:col-span-1 flex flex-col gap-6 overflow-y-auto scrollbar-hide min-h-0">

            <!-- Details Form -->
            <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-5 bg-brand-500 rounded-full"></div>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Purchase Details</h3>
                    </div>
                </div>
                <div class="p-6 space-y-5">
                    <!-- Supplier -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Supplier <span class="text-rose-500">*</span></label>
                        <select name="supplier_id" required class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer">
                            <option value="">— Select supplier —</option>
                            <?php foreach ($suppliers as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Payment method -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Payment Method <span class="text-rose-500">*</span></label>
                        <select name="payment_method" id="paymentMethod" required class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="credit">Credit (Pay Later)</option>
                        </select>
                    </div>

                    <!-- Account (hidden for credit) -->
                    <div id="accountRow" class="transition-all">
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Debit Account <span class="text-rose-500">*</span></label>
                        <select name="account_id" id="accountSelect" class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer">
                            <option value="">— Select account —</option>
                            <?php foreach ($accounts as $a): ?>
                                <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?> (LKR <?= number_format((float)$a['balance'], 2) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Note -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Note (Optional)</label>
                        <textarea name="note" rows="2" placeholder="Optional note…" class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600 resize-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Summary card -->
            <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between text-stone-500 dark:text-slate-400">
                    <span class="text-[11px] font-black uppercase tracking-widest">Items Count</span>
                    <span id="summaryItems" class="text-[12px] font-black text-slate-900 dark:text-white">0</span>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-stone-200 dark:border-white/5">
                    <span class="text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Grand Total</span>
                    <span id="summaryTotal" class="text-xl font-black text-brand-600 dark:text-brand-400 tracking-tighter">LKR 0.00</span>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white text-[11px] font-black py-3.5 rounded-xl transition-all shadow-lg shadow-brand-500/20 uppercase tracking-widest">Save Purchase</button>
                    <a href="/purchases" class="block text-center mt-3 text-[10px] font-black text-stone-400 hover:text-stone-600 dark:hover:text-slate-300 uppercase tracking-widest transition-all">Cancel</a>
                </div>
            </div>

        </div>

        <!-- Right: Line items -->
        <div class="lg:col-span-2 flex flex-col min-h-0">
            <div class="flex-1 min-h-0 bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden flex flex-col">
                <div class="flex items-center justify-between px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5 flex-shrink-0">
                    <h2 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Products</h2>
                    <button type="button" id="addLineBtn" class="text-[10px] font-black bg-stone-200 dark:bg-white/10 hover:bg-brand-500 hover:text-white text-stone-600 dark:text-slate-300 px-4 py-2 rounded-lg transition-all uppercase tracking-widest">
                        + Add Line
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto scrollbar-hide">
                    <table class="w-full text-left" id="lineTable">
                        <thead class="sticky top-0 bg-stone-50 dark:bg-[#0a0a0a] z-10 border-b border-stone-200 dark:border-white/5">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Product</th>
                                <th class="px-4 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-center w-28">Qty</th>
                                <th class="px-4 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right w-36">Unit Cost</th>
                                <th class="px-6 py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right w-36">Subtotal</th>
                                <th class="w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="lineItems" class="divide-y divide-stone-100 dark:divide-white/5">
                            <!-- rows injected by JS -->
                        </tbody>
                    </table>

                    <div id="emptyState" class="px-6 py-24 text-center">
                        <div class="w-12 h-12 rounded-full bg-stone-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4 text-stone-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Click <strong class="text-slate-700 dark:text-slate-300">+ Add Line</strong> to add products.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<!-- Product data for JS -->
<script>
const PRODUCTS = <?= json_encode(array_values($products)) ?>;

const productOptions = PRODUCTS.map(p =>
    `<option value="${p.id}" data-cost="${p.buying_price || p.price_retail}">${p.name}</option>`
).join('');

let rowIndex = 0;

function addLine() {
    rowIndex++;
    const tbody  = document.getElementById('lineItems');
    const empty  = document.getElementById('emptyState');
    empty.style.display = 'none';

    const tr = document.createElement('tr');
    tr.className = 'line-row hover:bg-blue-50/20 dark:hover:bg-white/5 transition-all group';
    tr.dataset.row = rowIndex;
    tr.innerHTML = `
        <td class="px-6 py-3">
            <select name="product_id[]" required onchange="onProductChange(this)"
                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-3 py-2 text-[12px] font-black text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer">
                <option value="">— Select —</option>
                ${productOptions}
            </select>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="qty[]" min="1" value="1" required oninput="recalcRow(this)"
                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-3 py-2 text-[12px] font-black text-slate-800 dark:text-slate-200 text-center focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
        </td>
        <td class="px-4 py-3">
            <input type="number" name="unit_cost[]" min="0" step="0.01" value="0.00" required oninput="recalcRow(this)"
                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-3 py-2 text-[12px] font-black text-slate-800 dark:text-slate-200 text-right focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
        </td>
        <td class="px-6 py-3 text-right font-black text-[13px] text-slate-900 dark:text-white subtotal-cell whitespace-nowrap">
            LKR 0.00
        </td>
        <td class="px-2 py-3 text-center">
            <button type="button" onclick="removeLine(this)"
                class="w-8 h-8 rounded-lg text-stone-300 dark:text-slate-600 hover:text-rose-500 hover:bg-rose-500/10 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100 focus:opacity-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    updateSummary();
}

function onProductChange(sel) {
    const opt      = sel.options[sel.selectedIndex];
    const cost     = parseFloat(opt.dataset.cost) || 0;
    const row      = sel.closest('tr');
    const costInput = row.querySelector('input[name="unit_cost[]"]');
    costInput.value = cost.toFixed(2);
    recalcRow(costInput);
}

function recalcRow(input) {
    const row      = input.closest('tr');
    const qty      = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
    const cost     = parseFloat(row.querySelector('input[name="unit_cost[]"]').value) || 0;
    const sub      = qty * cost;
    row.querySelector('.subtotal-cell').textContent = 'LKR ' + sub.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    updateSummary();
}

function removeLine(btn) {
    const row   = btn.closest('tr');
    const tbody = document.getElementById('lineItems');
    row.remove();
    if (tbody.children.length === 0) {
        document.getElementById('emptyState').style.display = '';
    }
    updateSummary();
}

function updateSummary() {
    const rows  = document.querySelectorAll('.line-row');
    let total   = 0;
    rows.forEach(row => {
        const qty  = parseFloat(row.querySelector('input[name="qty[]"]').value)       || 0;
        const cost = parseFloat(row.querySelector('input[name="unit_cost[]"]').value) || 0;
        total += qty * cost;
    });
    document.getElementById('summaryItems').textContent = rows.length;
    document.getElementById('summaryTotal').textContent = 'LKR ' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// Show / hide account row
document.getElementById('paymentMethod').addEventListener('change', function () {
    const accountRow = document.getElementById('accountRow');
    const accountSel = document.getElementById('accountSelect');
    if (this.value === 'credit') {
        accountRow.style.display = 'none';
        accountSel.removeAttribute('required');
    } else {
        accountRow.style.display = '';
        accountSel.setAttribute('required', 'required');
    }
});

document.getElementById('addLineBtn').addEventListener('click', addLine);

// Start with one empty row
addLine();
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';