<?php ob_start(); ?>

<div class="max-w-3xl mx-auto space-y-8 bg-white p-8">
    <!-- Header -->
    <div class="flex justify-between items-start border-b border-stone-200 pb-8">
        <div>
            <?php if ($purchase['status'] === 'pending'): ?>
                <h1 class="text-4xl font-black text-amber-600 tracking-tighter">PURCHASE REQUEST</h1>
                <p class="text-stone-400 mt-2 uppercase text-[10px] font-black tracking-widest">Request #<?= $purchase['id'] ?></p>
            <?php else: ?>
                <h1 class="text-4xl font-black text-emerald-600 tracking-tighter">PURCHASE ORDER</h1>
                <p class="text-stone-400 mt-2 uppercase text-[10px] font-black tracking-widest">Order #<?= $purchase['id'] ?></p>
            <?php endif; ?>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-black text-slate-900 tracking-tight">POS System</h2>
            <p class="text-stone-500 text-[12px] font-medium mt-1">Main Branch, City Center</p>
            <p class="text-stone-500 text-[12px] font-medium">+94 11 234 5678</p>
        </div>
    </div>

    <!-- Info Grid -->
    <div class="grid grid-cols-2 gap-12">
        <div>
            <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest mb-3 border-b border-stone-100 pb-2">Supplier Info</p>
            <p class="text-[14px] font-black text-slate-800"><?= htmlspecialchars($purchase['supplier_name']) ?></p>
            <p class="text-stone-500 text-[12px] font-medium italic mt-1">Primary Supplier</p>
        </div>
        <div class="text-right">
            <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest mb-3 border-b border-stone-100 pb-2">Order Details</p>
            <div class="space-y-1.5">
                <p class="text-[12px] text-stone-600"><span class="font-black text-slate-800 mr-2">Date:</span> <?= date('d M Y, h:i A', strtotime($purchase['created_at'])) ?></p>
                <p class="text-[12px] text-stone-600"><span class="font-black text-slate-800 mr-2">Payment:</span> <?= strtoupper($purchase['payment_method']) ?></p>
                <p class="text-[12px] text-stone-600"><span class="font-black text-slate-800 mr-2">Created By:</span> <?= htmlspecialchars($purchase['created_by_name'] ?? 'System') ?></p>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="mt-8">
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-stone-200">
                    <th class="text-left py-3 text-[10px] font-black text-stone-400 uppercase tracking-widest">Product Description</th>
                    <th class="text-center py-3 text-[10px] font-black text-stone-400 uppercase tracking-widest">Batch</th>
                    <th class="text-center py-3 text-[10px] font-black text-stone-400 uppercase tracking-widest">Qty</th>
                    <th class="text-right py-3 text-[10px] font-black text-stone-400 uppercase tracking-widest">Unit Cost</th>
                    <th class="text-right py-3 text-[10px] font-black text-stone-400 uppercase tracking-widest">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                <?php foreach ($items as $item): ?>
                <tr>
                    <td class="py-4">
                        <p class="text-[13px] font-black text-slate-800"><?= htmlspecialchars($item['product_name']) ?></p>
                    </td>
                    <td class="py-4 text-center text-[10px] text-stone-400 font-mono"><?= htmlspecialchars($item['batch_number'] ?? '—') ?></td>
                    <td class="py-4 text-center text-[12px] font-black text-slate-600"><?= $item['qty'] ?></td>
                    <td class="py-4 text-right text-[12px] font-bold text-stone-500">LKR <?= number_format($item['unit_cost'], 2) ?></td>
                    <td class="py-4 text-right text-[13px] font-black text-slate-800">LKR <?= number_format($item['subtotal'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Calculation -->
    <div class="flex justify-end pt-4 border-t-2 border-stone-200">
        <div class="w-full max-w-xs space-y-3">
            <div class="flex justify-between items-center text-xl font-black text-slate-900 pt-3 border-t border-stone-200">
                <span class="uppercase tracking-widest text-[13px]">Total Amount</span>
                <span>LKR <?= number_format($purchase['total'], 2) ?></span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="pt-12 mt-12 text-center">
        <p class="text-stone-400 text-[11px] font-bold italic">This is a system-generated document for inventory acquisition.</p>
        <?php if ($purchase['note']): ?>
            <div class="mt-8 bg-stone-50 p-6 rounded-2xl text-left border border-stone-200">
                <p class="text-[10px] font-black text-stone-400 uppercase tracking-widest mb-2">Internal Notes</p>
                <p class="text-[12px] font-medium text-slate-700"><?= nl2br(htmlspecialchars($purchase['note'])) ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . '/app/Views/layouts/print.php';
