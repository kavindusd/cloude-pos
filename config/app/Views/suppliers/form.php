<?php ob_start();
$editing = isset($supplier);
$old     = $supplier ?? $old ?? [];
?>

<div class="max-w-xl">

    <?php if (!empty($error)): ?>
        <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <form method="POST" action="<?= $editing ? '/suppliers/' . $supplier['id'] . '/update' : '/suppliers/store' ?>">

            <div class="mb-4">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">
                    Supplier Name <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" required
                    value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                    placeholder="e.g. ABC Trading Co."
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Phone</label>
                    <input type="text" name="phone"
                        value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                        placeholder="+94 11 234 5678"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Email</label>
                    <input type="email" name="email"
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        placeholder="supplier@example.com"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Address</label>
                <textarea name="address" rows="3" placeholder="Street, city, province…"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-medium text-slate-500 mb-2">Provides Products</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto p-3 border border-slate-200 rounded-lg bg-slate-50">
                    <?php if (empty($products)): ?>
                        <p class="text-xs text-slate-400 italic col-span-2">No products available. Add products first.</p>
                    <?php else: ?>
                        <?php foreach ($products as $prod): ?>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:bg-slate-100 p-1 rounded transition">
                            <input type="checkbox" name="product_ids[]" value="<?= $prod['id'] ?>"
                                <?= in_array($prod['id'], $current_product_ids ?? []) ? 'checked' : '' ?>
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs text-slate-600"><?= htmlspecialchars($prod['name']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <p class="mt-1.5 text-[10px] text-slate-400 italic">Select the products this supplier provides. This will update the primary supplier for these products.</p>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition">
                    <?= $editing ? 'Save Changes' : 'Add Supplier' ?>
                </button>
                <a href="/suppliers"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium px-5 py-2.5 rounded-lg transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>

    <?php if ($editing): ?>
    <div class="mt-4 bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs text-slate-500 space-y-1">
        <p><span class="font-medium text-slate-600">Supplier ID:</span> #<?= $supplier['id'] ?></p>
        <p><span class="font-medium text-slate-600">Status:</span> <?= $supplier['active'] ? 'Active' : 'Inactive' ?></p>
        <p><span class="font-medium text-slate-600">Added:</span> <?= date('d M Y', strtotime($supplier['created_at'])) ?></p>
    </div>
    <?php endif; ?>

</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/main.php';