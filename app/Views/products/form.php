<?php ob_start();
$editing = isset($product);
$old     = $product ?? $old ?? [];
?>

<div class="flex-1 flex flex-col min-h-0 w-full">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8 flex-shrink-0">
        <a href="/products" class="p-2 rounded-xl bg-white dark:bg-white/5 border border-stone-200 dark:border-white/10 text-stone-400 hover:text-brand-500 hover:border-brand-500/30 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight"><?= $editing ? 'Edit Product' : 'New Product' ?></h2>
            <p class="text-[10px] font-bold text-stone-400 dark:text-slate-500 uppercase tracking-widest mt-1"><?= $editing ? 'Update product details and pricing' : 'Add a new item to the inventory catalog' ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-1 min-h-0">

        <!-- Main Form -->
        <div class="lg:col-span-2">
            <?php if (!empty($error)): ?>
                <div class="mb-5 px-4 py-3 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-600 dark:text-rose-400 rounded-xl text-[12px] font-bold mb-4"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-5 bg-brand-500 rounded-full"></div>
                        <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Product Details</h3>
                    </div>
                </div>

                <form method="POST" action="<?= $editing ? '/products/' . $product['id'] . '/update' : '/products/store' ?>" class="p-6 space-y-5">

                    <!-- Name + Barcode -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Product Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required value="<?= htmlspecialchars($old['name'] ?? '') ?>" placeholder="e.g. Coca-Cola 330ml"
                                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Barcode</label>
                            <input type="text" name="barcode" value="<?= htmlspecialchars($old['barcode'] ?? '') ?>" placeholder="Scan or type barcode"
                                class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] font-mono text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all placeholder-stone-300 dark:placeholder-slate-600">
                        </div>
                    </div>

                    <!-- Category & Supplier -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Category</label>
                            <select name="category_id" class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer">
                                <option value="">— No category —</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ((int)($old['category_id'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Primary Supplier</label>
                            <select name="supplier_id" class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none cursor-pointer">
                                <option value="">— No supplier —</option>
                                <?php foreach ($suppliers as $sup): ?>
                                    <option value="<?= $sup['id'] ?>" <?= ((int)($old['supplier_id'] ?? 0) === (int)$sup['id']) ? 'selected' : '' ?>><?= htmlspecialchars($sup['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Prices -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-3">Pricing (LKR) <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-stone-400 mb-1.5">Buying</label>
                                <input type="number" name="buying_price" min="0" step="0.01" required value="<?= htmlspecialchars($old['buying_price'] ?? '0.00') ?>"
                                    class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-stone-400 mb-1.5">Retail</label>
                                <input type="number" name="price_retail" min="0" step="0.01" required value="<?= htmlspecialchars($old['price_retail'] ?? '0.00') ?>"
                                    class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-stone-400 mb-1.5">Wholesale</label>
                                <input type="number" name="price_wholesale" min="0" step="0.01" required value="<?= htmlspecialchars($old['price_wholesale'] ?? '0.00') ?>"
                                    class="w-full bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock -->
                    <div>
                        <label class="block text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest mb-2">Low-Stock Alert Threshold</label>
                        <input type="number" name="low_threshold" min="0" step="1" value="<?= htmlspecialchars($old['low_threshold'] ?? '5') ?>"
                            class="w-36 bg-stone-50 dark:bg-white/5 border border-stone-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-[13px] text-slate-900 dark:text-white font-medium focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                        <p class="text-[10px] text-stone-400 dark:text-slate-600 mt-1.5 font-bold">Alert triggers when stock falls to or below this value.</p>
                    </div>

                    <!-- Active toggle on edit -->
                    <?php if ($editing): ?>
                    <div class="flex items-center gap-3 p-4 bg-stone-50 dark:bg-white/5 rounded-xl border border-stone-200 dark:border-white/5">
                        <input type="hidden" name="active" value="0">
                        <input type="checkbox" name="active" value="1" <?= ($old['active'] ?? 1) ? 'checked' : '' ?> id="activeToggle"
                            class="w-4 h-4 rounded border-stone-300 text-brand-500 focus:ring-brand-500">
                        <label for="activeToggle" class="cursor-pointer">
                            <p class="text-[12px] font-black text-slate-700 dark:text-slate-300">Product is active</p>
                            <p class="text-[10px] text-stone-400 dark:text-slate-500 font-bold mt-0.5">Inactive products won't appear in POS or purchase forms.</p>
                        </label>
                    </div>
                    <?php endif; ?>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white text-[11px] font-black px-8 py-3 rounded-xl transition-all shadow-lg shadow-brand-500/20 uppercase tracking-widest">
                            <?= $editing ? 'Save Changes' : 'Create Product' ?>
                        </button>
                        <a href="/products" class="bg-stone-100 dark:bg-white/5 hover:bg-stone-200 dark:hover:bg-white/10 text-stone-600 dark:text-slate-400 text-[11px] font-black px-6 py-3 rounded-xl transition-all uppercase tracking-widest">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Info Panel -->
        <?php if ($editing && !empty($product)): ?>
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-stone-200 dark:border-white/5 bg-stone-50 dark:bg-white/5">
                    <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Product Info</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Product ID</p>
                        <p class="text-[13px] font-black text-slate-900 dark:text-white mt-1 font-mono">#<?= $product['id'] ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Current Stock</p>
                        <p class="text-[13px] font-black text-slate-900 dark:text-white mt-1"><?= (int)$product['qty_total'] ?> units</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-stone-400 dark:text-slate-500 uppercase tracking-widest">Created</p>
                        <p class="text-[13px] font-black text-slate-900 dark:text-white mt-1"><?= date('d M Y', strtotime($product['created_at'])) ?></p>
                    </div>
                    <div class="pt-2 border-t border-stone-200 dark:border-white/5">
                        <p class="text-[10px] text-stone-400 dark:text-slate-500 font-bold leading-relaxed">Stock quantity is managed through the <a href="/purchases" class="text-brand-500 hover:underline">Purchases module</a>, not directly here.</p>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-white/5 rounded-2xl border border-stone-200 dark:border-white/5 shadow-sm p-6">
                <h3 class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest mb-4">Tips</h3>
                <div class="space-y-3 text-[11px] text-stone-400 dark:text-slate-500 font-bold leading-relaxed">
                    <p>• Use a unique barcode for fast POS scanning.</p>
                    <p>• Set retail & wholesale prices correctly — they affect pricing at the POS terminal.</p>
                    <p>• Stock is added via the <a href="/purchases/create" class="text-brand-500 hover:underline">New Purchase</a> flow.</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';