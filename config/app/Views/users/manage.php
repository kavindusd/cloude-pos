<?php ob_start(); ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <p class="text-sm text-gray-400">This section is coming in a later step.</p>
</div>

<?php $content = ob_get_clean();
$title = 'Manage';
require BASE_PATH . '/app/Views/layouts/main.php';
