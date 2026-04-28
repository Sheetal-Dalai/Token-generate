<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Optional: Check admin authentication here
// if(!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

include("include/sidebar.php");
include("../lorus_includes/class/functions_class.php");

$admin = new Admin();

// 🔐 Sanitize & validate date input
$selectedDate = isset($_GET['date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date']) 
    ? $_GET['date'] 
    : date('Y-m-d');

// Fetch filtered data
$totalSales = $admin->getTotalSales($selectedDate);
$totalTokens = $admin->getTotalTokens($selectedDate);
$totalItems = $admin->getTotalItems($selectedDate);
$totalCategories = $admin->getTotalCategories($selectedDate);
$transactions = $admin->getRecentTransactions(10, $selectedDate);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Morning Brew</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .orange-btn { background-color: #ea580c; color: white; }
        .orange-btn:hover { background-color: #c94a09; }
        .orange-border { border-color: #ea580c; }
        .orange-text { color: #ea580c; }
        .orange-bg { background-color: #fff4e6; }
    </style>
</head>
<body class="bg-stone-50 text-stone-800 flex">

<!-- SIDEBAR -->
<?php include("include/sidebar.php"); ?>

<!-- MAIN CONTENT -->
<main class="flex-1 w-full min-h-screen md:ml-64 transition-all duration-300">
    <div class="p-4 sm:p-6 md:p-10">

        <!-- 🔥 TOP BAR -->
        <div class="flex items-center justify-between mb-6">
            <button onclick="toggleSidebar()" class="md:hidden text-2xl text-stone-700 hover:text-orange-600 transition">☰</button>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold orange-text">Dashboard Overview</h1>
                <p class="text-xs sm:text-sm text-stone-500">Welcome back! Here's what's happening.</p>
            </div>
        </div>

        <!-- 📅 Date Filter Form -->
        <form method="get" class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-end bg-white p-4 rounded-xl border shadow-sm">
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-stone-700 mb-1">Filter by Date</label>
                <input type="date" name="date" value="<?= htmlspecialchars($selectedDate) ?>" max="<?= date('Y-m-d') ?>"
                    class="border border-stone-200 rounded-lg px-3 py-2 text-sm w-full sm:w-48 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="orange-btn px-5 py-2 rounded-lg text-sm font-medium hover:shadow transition">
                    🔍 Filter
                </button>
                <a href="?date=<?= date('Y-m-d') ?>" class="px-4 py-2 rounded-lg text-sm font-medium border border-stone-300 hover:bg-stone-100 transition text-stone-700">
                    ↺ Reset
                </a>
            </div>
            <?php if($selectedDate !== date('Y-m-d')): ?>
                <p class="text-xs text-stone-500 mt-2 sm:mt-0 sm:ml-2">
                    Showing data for: <span class="font-semibold orange-text"><?= date('F j, Y', strtotime($selectedDate)) ?></span>
                </p>
            <?php endif; ?>
        </form>

        <!-- 📊 Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <?php
            $stats = [
                ["label"=>"Total Sales (₹)", "value"=>number_format($totalSales, 2)],
                ["label"=>"Tokens Generated", "value"=>$totalTokens],
                ["label"=>"Total Items", "value"=>$totalItems],
                ["label"=>"Categories ", "value"=>$totalCategories]
            ];
            foreach($stats as $s):
            ?>
            <div class="bg-white orange-bg p-4 sm:p-6 rounded-xl shadow-sm border border-stone-200 hover:shadow-md transition">
                <h3 class="text-xs sm:text-sm text-stone-500"><?= $s['label'] ?></h3>
                <p class="text-lg sm:text-2xl font-bold text-stone-800 mt-1"><?= $s['value'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- 🧾 Recent Transactions -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6">
            <div class="p-4 sm:p-6 border-b border-stone-200 flex justify-between items-center">
                <h3 class="text-base sm:text-lg font-bold text-stone-800">Recent Transactions</h3>
                <span class="text-xs text-stone-500"><?= count($transactions) ?> records</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[600px] w-full text-sm text-stone-600">
                    <thead class="bg-stone-50 text-xs uppercase text-stone-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Token ID</th>
                            <th class="px-4 py-3 text-left">Items</th>
                            <th class="px-4 py-3 text-right">Amount</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php if(empty($transactions)): ?>
                            <tr><td colspan="4" class="px-4 py-6 text-center text-stone-400">No transactions found for this date.</td></tr>
                        <?php else: ?>
                            <?php foreach($transactions as $t): ?>
                            <tr class="hover:bg-orange-50 transition">
                                <td class="px-4 py-3 font-medium text-stone-800">#<?= htmlspecialchars($t['token_id']) ?></td>
                                <td class="px-4 py-3 text-stone-600"><?= htmlspecialchars($t['items']) ?></td>
                                <td class="px-4 py-3 text-right font-semibold">₹<?= number_format($t['amount'], 2) ?></td>
                                <td class="px-4 py-3 text-center">
                                    <?php if($t['status'] == "Completed"): ?>
                                        <span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full"><?= $t['status'] ?></span>
                                    <?php elseif($t['status'] == "Pending"): ?>
                                        <span class="inline-block px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full"><?= $t['status'] ?></span>
                                    <?php else: ?>
                                        <span class="inline-block px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full"><?= $t['status'] ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        

    </div>
</main>

<!-- 🧠 JavaScript Utilities -->
<script>
// Mobile sidebar toggle
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if(sidebar) {
        sidebar.classList.toggle('hidden');
        sidebar.classList.toggle('absolute');
        sidebar.classList.toggle('z-50');
        sidebar.classList.toggle('h-screen');
        sidebar.classList.toggle('w-64');
    }
}

// Close sidebar when clicking outside (mobile)
document.addEventListener('click', function(e) {
    const sidebar = document.querySelector('.sidebar');
    const hamburger = document.querySelector('button[onclick="toggleSidebar()"]');
    if(window.innerWidth < 768 && sidebar && !sidebar.contains(e.target) && !hamburger.contains(e.target)) {
        sidebar.classList.add('hidden');
    }
});

// Optional: Auto-submit form on date change (uncomment if desired)
// document.querySelector('input[name="date"]')?.addEventListener('change', function() {
//     this.form.submit();
// });
</script>

</body>
</html>