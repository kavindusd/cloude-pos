<?php ob_start(); ?>

<div class="h-full flex flex-col gap-6 w-full max-w-[1600px] mx-auto pb-6 min-w-0 overflow-hidden">
    <!-- Header Area -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-2xl bg-brand-500 flex items-center justify-center text-white shadow-lg shadow-brand-500/20">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">POS Terminal</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Active Session • #001</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="hidden md:flex flex-col text-right">
                <div id="systemClock" class="text-sm font-black text-slate-700 dark:text-slate-300 font-mono"></div>
                <span id="pricingTypeBadge" class="text-[9px] font-bold text-emerald-500 uppercase tracking-[0.2em]">Retail Mode</span>
            </div>
            <div class="h-10 w-[1px] bg-slate-200 dark:bg-white/10 mx-2"></div>
            <div class="flex -space-x-2">
                <div class="w-9 h-9 rounded-full border-2 border-white dark:border-[#0a0a0a] bg-slate-100 dark:bg-white/10 flex items-center justify-center text-xs font-bold text-slate-500">K</div>
            </div>
        </div>
    </div>

    <form method="POST" action="/pos/checkout" id="posForm" class="flex-1 min-h-0 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-full">

            <!-- LEFT: Product Inventory (Soft-Glass List) -->
            <div class="flex flex-col min-h-0 bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden transition-all">
                <!-- List Header / Controls -->
                <div class="p-4 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                    <div class="flex items-center gap-3">
                        <div class="relative flex-1 group">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-brand-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" id="productSearch" placeholder="Quick search inventory..." 
                                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl pl-11 pr-4 py-2.5 text-[13px] text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-400 font-medium">
                        </div>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            </div>
                            <select id="categoryFilter" class="bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl pl-9 pr-8 py-2.5 text-[13px] text-slate-600 dark:text-slate-400 focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer font-medium">
                                <option value="">Filter</option>
                                <?php
                                $cats = [];
                                foreach ($products as $p) {
                                    if ($p['category_name'] && !in_array($p['category_name'], $cats)) {
                                        $cats[] = $p['category_name'];
                                        echo "<option value=\"".htmlspecialchars($p['category_name'])."\">".htmlspecialchars($p['category_name'])."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Product List -->
                <div class="flex-1 overflow-y-auto scrollbar-hide px-4">
                    <table class="w-full text-left">
                        <thead class="sticky top-0 bg-stone-50 dark:bg-[#0a0a0a] z-10 border-b border-stone-200 dark:border-white/5">
                            <tr>
                                <th class="py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Item Details</th>
                                <th class="py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right">Stock</th>
                                <th class="py-4 text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest text-right pr-2">Price</th>
                            </tr>
                        </thead>
                        <tbody id="productGrid" class="divide-y divide-stone-100 dark:divide-white/5">
                            <?php foreach ($products as $p): ?>
                            <?php if (!$p['active'] || (int)$p['qty_total'] <= 0) continue; ?>
                            <tr class="product-card group cursor-pointer hover:bg-blue-50/40 dark:hover:bg-white/5 transition-all relative"
                                data-id="<?= $p['id'] ?>"
                                data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>"
                                data-retail="<?= (float)$p['price_retail'] ?>"
                                data-wholesale="<?= (float)$p['price_wholesale'] ?>"
                                data-stock="<?= (int)$p['qty_total'] ?>"
                                data-category="<?= htmlspecialchars($p['category_name'] ?? '', ENT_QUOTES) ?>"
                                data-barcode="<?= htmlspecialchars($p['barcode'] ?? '', ENT_QUOTES) ?>"
                                onclick="addToCart(this)">
                                <td class="py-4 pl-2">
                                    <p class="text-[13px] font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-500 transition-colors"><?= htmlspecialchars($p['name']) ?></p>
                                    <p class="text-[10px] text-stone-400 dark:text-slate-500 font-bold uppercase tracking-wider mt-0.5"><?= htmlspecialchars($p['category_name'] ?? 'General') ?></p>
                                </td>
                                <td class="py-4 text-right">
                                    <span class="text-[12px] font-black <?= (int)$p['qty_total'] < 10 ? 'text-rose-500' : 'text-slate-600 dark:text-slate-400' ?>"><?= (int)$p['qty_total'] ?></span>
                                </td>
                                <td class="py-4 text-right pr-2">
                                    <div class="flex items-center justify-end gap-3">
                                        <p class="text-[13px] font-black text-slate-900 dark:text-white">LKR <?= number_format((float)$p['price_retail'], 2) ?></p>
                                        <div class="w-6 h-6 rounded-lg bg-brand-500 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all transform scale-75 group-hover:scale-100 shadow-lg shadow-brand-500/20">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div id="noResults" class="hidden flex flex-col items-center justify-center py-20 opacity-20">
                        <svg class="w-12 h-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <p class="text-xs font-bold uppercase tracking-widest">No Matches</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Transaction Terminal (Maximize List View) -->
            <div class="flex flex-col min-h-0 bg-white dark:bg-[#151515] rounded-3xl border border-stone-200/60 dark:border-white/5 shadow-md overflow-hidden transition-all relative">
                
                <!-- Compact Header: Transaction Control -->
                <div class="px-5 py-3 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-4 bg-brand-500 rounded-full shadow-sm shadow-brand-500/30"></div>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Active Order</h3>
                    </div>
                    <div class="flex items-center gap-3 flex-1 max-w-[300px]">
                        <div class="relative flex-1 group">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-brand-500 transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <select name="customer_id" id="customerSelect" onchange="onCustomerChange(this)"
                                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-lg pl-8 pr-4 py-1.5 text-[11px] font-bold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer">
                                <option value="" data-type="retail">Walking Customer</option>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?= $c['id'] ?>" data-type="<?= $c['type'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button type="button" onclick="clearCart()" class="p-2 rounded-lg text-rose-400 hover:bg-rose-500/10 transition-colors" title="Clear Transaction">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>

                <!-- Extendable Cart List Viewer -->
                <div id="cartContainer" class="flex-1 overflow-y-auto p-4 scrollbar-hide bg-stone-50/10">
                    <div id="cartEmpty" class="h-full flex flex-col items-center justify-center opacity-30 text-center py-20">
                        <div class="w-16 h-16 rounded-full bg-stone-100 dark:bg-white/5 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-stone-400">Order is Empty</p>
                    </div>
                    <div id="cartItems" class="space-y-2 hidden"></div>
                </div>

                <!-- Summary & Checkout Area (Unified Warm-Glass Footer) -->
                <div class="p-6 bg-stone-50 dark:bg-white/5 border-t border-stone-200 dark:border-white/5 shadow-[0_-8px_30px_-8px_rgba(0,0,0,0.06)]">
                    <div class="space-y-4">
                        <!-- High Density Totals -->
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Subtotal</span>
                                    <span id="subtotalDisplay" class="text-[12px] font-black text-slate-700 dark:text-slate-300">LKR 0.00</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Discount</span>
                                    <div class="w-24 relative">
                                        <input type="number" name="discount" id="discountInput" min="0" step="0.01" value="0" oninput="updateTotals()"
                                            class="w-full bg-white dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-lg px-3 py-1.5 text-right text-[12px] font-black text-slate-800 dark:text-white focus:ring-2 focus:ring-brand-500/20 transition-all outline-none">
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end justify-center border-l border-stone-200 dark:border-white/5 pl-6">
                                <p class="text-[10px] font-black text-brand-500 uppercase tracking-[0.3em] mb-1">Payable Amount</p>
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-sm font-black text-stone-300 dark:text-slate-600">LKR</span>
                                    <h2 id="totalDisplay" class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">0.00</h2>
                                </div>
                            </div>
                        </div>

                        <div class="h-[1px] bg-stone-200 dark:bg-white/5 w-full"></div>

                        <!-- Payment & Finalize -->
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2 bg-stone-100 dark:bg-white/5 p-1 rounded-xl border border-stone-200 dark:border-white/5">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="payment_method" value="cash" checked onchange="onPaymentChange(this)" class="peer sr-only">
                                    <div class="px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest text-stone-400 peer-checked:bg-white dark:peer-checked:bg-white/10 peer-checked:text-brand-500 peer-checked:shadow-sm transition-all">Cash</div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="payment_method" value="bank" onchange="onPaymentChange(this)" class="peer sr-only">
                                    <div class="px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest text-stone-400 peer-checked:bg-white dark:peer-checked:bg-white/10 peer-checked:text-brand-500 peer-checked:shadow-sm transition-all">Bank</div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="payment_method" value="credit" onchange="onPaymentChange(this)" class="peer sr-only">
                                    <div class="px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest text-stone-400 peer-checked:bg-white dark:peer-checked:bg-white/10 peer-checked:text-brand-500 peer-checked:shadow-sm transition-all">Credit</div>
                                </label>
                            </div>
                            
                            <div id="accountRow" class="flex-1 animate-in slide-in-from-right duration-300">
                                <div class="relative">
                                    <select name="account_id" id="accountSelect" required
                                        class="w-full bg-white dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl pl-4 pr-10 py-3 text-[11px] font-black text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-brand-500/20 uppercase tracking-widest appearance-none cursor-pointer">
                                        <option value="">Select Account</option>
                                        <?php foreach ($accounts as $a): ?>
                                            <option value="<?= $a['id'] ?>"><?= strtoupper(htmlspecialchars($a['name'])) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="hiddenInputs"></div>

                        <button type="submit" id="checkoutBtn" disabled
                            class="w-full bg-brand-500 hover:bg-brand-600 disabled:bg-stone-200 dark:disabled:bg-white/5 disabled:text-stone-400 text-white font-black py-5 rounded-2xl transition-all shadow-xl shadow-brand-500/20 uppercase tracking-[0.25em] text-[13px] flex items-center justify-center gap-3">
                            <span>Complete Transaction</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </div>
                </div>
            </div> <!-- End RIGHT Column -->
        </div> <!-- End Grid -->
    </form>
</div>

<script>
// ── Clock ────────────────────────────────────────────────────────────────────
function updateClock() {
    const clock = document.getElementById('systemClock');
    if (clock) {
        const now = new Date();
        clock.textContent = now.toLocaleTimeString('en-US', { hour12: false });
    }
}
setInterval(updateClock, 1000);
updateClock();

// ── State ────────────────────────────────────────────────────────────────────
let cart       = {};   // { product_id: { name, qty, unit_price, stock } }
let priceType  = 'retail';

// ── Product search / filter ──────────────────────────────────────────────────
const cards    = document.querySelectorAll('.product-card');
const noResult = document.getElementById('noResults');

function filterProducts() {
    const q   = document.getElementById('productSearch').value.toLowerCase();
    const cat = document.getElementById('categoryFilter').value.toLowerCase();
    let visible = 0;
    cards.forEach(card => {
        const nameMatch    = card.dataset.name.toLowerCase().includes(q)
                          || card.dataset.barcode.toLowerCase().includes(q);
        const catMatch     = cat === '' || card.dataset.category.toLowerCase() === cat;
        const show         = nameMatch && catMatch;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    noResult.classList.toggle('hidden', visible > 0);
}

document.getElementById('productSearch').addEventListener('input', filterProducts);
document.getElementById('categoryFilter').addEventListener('change', filterProducts);

// Barcode scanner: auto-add when barcode fully typed + Enter
document.getElementById('productSearch').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const q = this.value.trim().toLowerCase();
        const match = [...cards].find(c => c.dataset.barcode.toLowerCase() === q);
        if (match) { addToCart(match); this.value = ''; filterProducts(); }
    }
});

// ── Customer type toggle ─────────────────────────────────────────────────────
function onCustomerChange(sel) {
    const opt  = sel.options[sel.selectedIndex];
    priceType  = opt.dataset.type || 'retail';
    
    // Update badge
    const badge = document.getElementById('pricingTypeBadge');
    if (badge) {
        badge.textContent = (priceType === 'retail' ? 'Retail' : 'Wholesale') + ' Pricing';
    }
    
    // Re-price cart
    Object.keys(cart).forEach(id => {
        const card = document.querySelector(`.product-card[data-id="${id}"]`);
        if (card) cart[id].unit_price = parseFloat(card.dataset[priceType]) || parseFloat(card.dataset.retail);
    });
    renderCart();
}

// ── Cart ─────────────────────────────────────────────────────────────────────
function addToCart(card) {
    const id    = card.dataset.id;
    const stock = parseInt(card.dataset.stock);
    const price = parseFloat(card.dataset[priceType]) || parseFloat(card.dataset.retail);

    if (cart[id]) {
        if (cart[id].qty >= stock) return;
        cart[id].qty++;
    } else {
        cart[id] = { name: card.dataset.name, qty: 1, unit_price: price, stock };
    }
    renderCart();
}

function updateQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    else if (cart[id].qty > cart[id].stock) cart[id].qty = cart[id].stock;
    renderCart();
}

function clearCart() {
    cart = {};
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const empty     = document.getElementById('cartEmpty');
    const keys      = Object.keys(cart);

    if (keys.length === 0) {
        container.classList.add('hidden');
        empty.classList.remove('hidden');
        document.getElementById('checkoutBtn').disabled = true;
        updateTotals();
        return;
    }

    empty.classList.add('hidden');
    container.classList.remove('hidden');
    document.getElementById('checkoutBtn').disabled = false;

    container.innerHTML = keys.map(id => {
        const item = cart[id];
        const sub  = (item.qty * item.unit_price).toFixed(2);
        return `
        <div class="bg-white/70 dark:bg-white/10 rounded-xl p-2.5 border border-stone-200/40 dark:border-white/5 flex items-center gap-3 group animate-in slide-in-from-right duration-200 shadow-sm">
            <div class="flex-1 min-w-0">
                <p class="text-[12px] font-black text-slate-900 dark:text-slate-100 truncate tracking-tight leading-tight">${item.name}</p>
                <p class="text-[10px] font-bold text-stone-400 dark:text-slate-500 uppercase tracking-wider mt-0.5">LKR ${item.unit_price.toFixed(2)}</p>
            </div>
            <div class="flex items-center gap-1 bg-stone-100 dark:bg-white/10 rounded-lg p-0.5">
                <button type="button" onclick="updateQty('${id}', -1)"
                    class="w-6 h-6 rounded-md hover:bg-white dark:hover:bg-white/10 text-stone-500 dark:text-slate-400 text-sm font-black transition flex items-center justify-center">−</button>
                <span class="w-6 text-center text-[12px] font-black text-slate-900 dark:text-white">${item.qty}</span>
                <button type="button" onclick="updateQty('${id}', 1)"
                    class="w-6 h-6 rounded-md hover:bg-white dark:hover:bg-white/10 text-stone-500 dark:text-slate-400 text-sm font-black transition flex items-center justify-center">+</button>
            </div>
            <div class="text-right min-w-[70px]">
                <p class="text-[12px] font-black text-slate-900 dark:text-white leading-none tracking-tighter">${parseFloat(sub).toLocaleString('en', {minimumFractionDigits:2})}</p>
            </div>
        </div>`;
    }).join('');

    updateTotals();
    injectHiddenInputs();
}

function updateTotals() {
    let subtotal = 0;
    Object.values(cart).forEach(i => subtotal += i.qty * i.unit_price);
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const total    = Math.max(0, subtotal - discount);

    document.getElementById('subtotalDisplay').textContent = 'LKR ' + subtotal.toLocaleString('en', {minimumFractionDigits:2});
    document.getElementById('totalDisplay').textContent    = 'LKR ' + total.toLocaleString('en', {minimumFractionDigits:2});
}

function injectHiddenInputs() {
    const container = document.getElementById('hiddenInputs');
    container.innerHTML = Object.entries(cart).map(([id, item]) => `
        <input type="hidden" name="product_id[]"  value="${id}">
        <input type="hidden" name="qty[]"          value="${item.qty}">
        <input type="hidden" name="unit_price[]"   value="${item.unit_price.toFixed(2)}">
    `).join('');
}

// ── Payment method ───────────────────────────────────────────────────────────
function onPaymentChange(radio) {
    const accountRow = document.getElementById('accountRow');
    const accountSel = document.getElementById('accountSelect');
    if (radio.value === 'credit') {
        accountRow.style.display = 'none';
        accountSel.removeAttribute('required');
    } else {
        accountRow.style.display = '';
        accountSel.setAttribute('required', 'required');
    }
}

// ── Form submit guard ────────────────────────────────────────────────────────
document.getElementById('posForm').addEventListener('submit', function(e) {
    injectHiddenInputs();
    if (Object.keys(cart).length === 0) {
        e.preventDefault();
        alert('Cart is empty.');
    }
});
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';
?>