<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../lorus_includes/class/functions_class.php");

$admin = new Admin();

// ================= DEFAULT DATE (LAST 30 DAYS) =================
$from_date = isset($_GET['from_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['from_date']) 
    ? $_GET['from_date'] 
    : date('Y-m-d', strtotime('-30 days'));
    
$to_date = isset($_GET['to_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['to_date']) 
    ? $_GET['to_date'] 
    : date('Y-m-d');

// Prevent future dates & ensure from <= to
if(strtotime($to_date) > time()) $to_date = date('Y-m-d');
if(strtotime($from_date) > strtotime($to_date)) $from_date = $to_date;

// ================= FETCH TRANSACTIONS =================
$transactions = $admin->getFilteredTransactions($from_date, $to_date);

// ================= TOTAL CALCULATION =================
$total_sales = 0;
$total_tokens = 0;
$avg_order_value = 0;

if(!empty($transactions)){
    foreach($transactions as $row){
        $total_sales += $row['amount'];
        $total_tokens++;
    }
    $avg_order_value = $total_tokens > 0 ? $total_sales / $total_tokens : 0;
}

// Format dates for display
$from_display = date('M j, Y', strtotime($from_date));
$to_display = date('M j, Y', strtotime($to_date));
$days_range = max(1, (strtotime($to_date) - strtotime($from_date)) / 86400 + 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Transactions Report - Morning Brew</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Custom Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        orange: {
                            50: '#fff7ed', 100: '#ffedd5', 400: '#fb923c',
                            500: '#f97316', 600: '#ea580c', 700: '#c2410c', 800: '#9a3412',
                        },
                        stone: {
                            50: '#fafaf9', 100: '#f5f5f4', 200: '#e7e5e4', 300: '#d6d3d1',
                            400: '#a8a29e', 500: '#78716c', 600: '#57534e', 700: '#44403c', 800: '#292524',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-up': 'slideUp 0.4s ease-out',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(12px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            -webkit-tap-highlight-color: transparent;
        }
        html { scroll-behavior: smooth; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Card hover lift */
        .card-hover { transition: all 0.2s ease; }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        
        /* Button ripple */
        .btn-ripple { position: relative; overflow: hidden; }
        .btn-ripple:active::after {
            content: ''; position: absolute; top: 50%; left: 50%;
            width: 200px; height: 200px; background: rgba(255,255,255,0.3);
            border-radius: 50%; transform: translate(-50%, -50%) scale(0);
            animation: ripple 0.6s ease-out;
        }
        @keyframes ripple { to { transform: translate(-50%, -50%) scale(1); opacity: 0; } }
        
        /* Mobile table stacked */
        @media (max-width: 768px) {
            .mobile-table thead { display: none; }
            .mobile-table tbody tr { 
                display: block; margin-bottom: 1rem; 
                border: 1px solid #e7e5e4; border-radius: 16px;
                padding: 1rem; background: white;
            }
            .mobile-table td { 
                display: flex; justify-content: space-between; align-items: center;
                padding: 0.6rem 0; border: none; border-bottom: 1px dashed #e7e5e4;
            }
            .mobile-table td:last-child { border-bottom: none; }
            .mobile-table td::before {
                content: attr(data-label);
                font-weight: 600; color: #78716c; margin-right: 1rem;
                text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em;
            }
        }
        
        /* Date input styling */
        input[type="date"] {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8v4m8-4v4M4 4h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z'/%3e%3c/svg%3e");
            background-repeat: no-repeat; background-position: right 0.75rem center;
            background-size: 1.2em; padding-right: 2.5rem;
        }
        
        /* Sidebar overlay */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4);
            z-index: 40; opacity: 0; transition: opacity 0.3s ease;
        }
        .sidebar-overlay.active { display: block; opacity: 1; }
        
        /* Print styles */
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .card-hover { box-shadow: none !important; transform: none !important; }
            table { width: 100% !important; }
        }
    </style>
</head>
<body class="bg-stone-50 text-stone-800 flex min-h-screen overflow-x-hidden">

<!-- 🌙 Sidebar Overlay (Mobile) -->
<div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- 🧭 SIDEBAR -->
<div class="sidebar-wrapper w-64 h-screen sticky top-0 overflow-y-auto bg-white border-r border-stone-200 hidden md:block z-30">
    <?php include("include/sidebar.php"); ?>
</div>

<!-- 📱 MAIN CONTENT -->
<main class="flex-1 w-full min-h-screen transition-all duration-300">
    
    <!-- 🔝 Sticky Header -->
    <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-stone-200 px-4 py-3 sm:px-6 no-print">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <!-- Mobile Menu Button -->
            <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-2 text-stone-600 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition active:scale-95" aria-label="Toggle menu">
                <i class="ph ph-list text-2xl"></i>
            </button>
            
            <!-- Page Title -->
            <div>
                <h1 class="text-lg sm:text-xl font-bold bg-gradient-to-r from-orange-600 to-orange-400 bg-clip-text text-transparent">
                    Transactions Report
                </h1>
                <p class="text-xs text-stone-500 hidden sm:block">View and analyze your sales data</p>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="hidden sm:flex items-center gap-2 px-3 py-2 text-sm font-medium text-stone-600 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition active:scale-95" title="Print Report">
                    <i class="ph ph-printer"></i>
                    Print
                </button>
               
            </div>
        </div>
    </header>

    <!-- 📄 Page Content -->
    <div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto animate-fade-in">

        <!-- 📅 Date Range Filter -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-stone-200 shadow-sm mb-6 card-hover no-print">
            <form method="GET" class="flex flex-col sm:flex-row gap-4 sm:items-end">
                
                <!-- From Date -->
                <div class="flex-1 min-w-0">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5 flex items-center gap-2">
                        <i class="ph ph-calendar-blank text-orange-500"></i>
                        From Date
                    </label>
                    <input type="date" name="from_date" value="<?= htmlspecialchars($from_date) ?>" max="<?= $to_date ?>"
                        class="w-full px-4 py-2.5 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition bg-stone-50 hover:bg-white">
                </div>
                
                <!-- To Date -->
                <div class="flex-1 min-w-0">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5 flex items-center gap-2">
                        <i class="ph ph-calendar-check text-orange-500"></i>
                        To Date
                    </label>
                    <input type="date" name="to_date" value="<?= htmlspecialchars($to_date) ?>" min="<?= $from_date ?>" max="<?= date('Y-m-d') ?>"
                        class="w-full px-4 py-2.5 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition bg-stone-50 hover:bg-white">
                </div>
                
                <!-- Apply Button -->
                <div class="flex gap-2">
                    <button type="submit" class="btn-ripple orange-btn px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 hover:shadow-lg active:scale-95 transition whitespace-nowrap">
                        <i class="ph ph-magnifying-glass"></i>
                        Apply Filter
                    </button>
                    <a href="?" class="px-4 py-2.5 rounded-xl text-sm font-medium border border-stone-200 hover:bg-stone-50 hover:border-stone-300 transition flex items-center justify-center text-stone-600 active:scale-95" title="Reset to last 30 days">
                        <i class="ph ph-arrow-counter-clockwise text-lg"></i>
                    </a>
                </div>
            </form>
            
            <!-- Active Filter Badge -->
            <div class="mt-4 pt-4 border-t border-stone-100 flex flex-wrap items-center gap-2 text-sm">
                <span class="text-stone-500">Showing:</span>
                <span class="px-2.5 py-1 bg-orange-100 text-orange-700 font-medium rounded-lg flex items-center gap-1.5">
                    <i class="ph ph-calendar text-orange-500"></i>
                    <?= $from_display ?> → <?= $to_display ?>
                </span>
                <span class="text-stone-400">•</span>
                <span class="text-stone-500"><?= (int)$days_range ?> day<?= $days_range>1?'s':'' ?></span>
                <?php if($from_date !== date('Y-m-d', strtotime('-30 days')) || $to_date !== date('Y-m-d')): ?>
                    <a href="?" class="ml-auto text-xs text-orange-600 hover:text-orange-700 font-medium flex items-center gap-1">
                        Reset to default →
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- 📊 Summary Cards -->
       <!-- 📊 Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    
    <!-- Total Sales (Updated: White + Orange) -->
    <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm card-hover animate-slide-up">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-stone-500 text-sm font-medium">Total Sales</p>
                <p class="text-2xl sm:text-3xl font-bold text-stone-800 mt-1">₹<?= number_format($total_sales, 0) ?></p>
                <p class="text-stone-400 text-xs mt-2">
                    <span class="font-medium text-stone-600">₹<?= number_format($total_sales, 2) ?></span> exact
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                <i class="ph ph-currency-inr text-orange-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-stone-100 flex items-center gap-2 text-sm">
            <i class="ph ph-trend-up text-orange-500"></i>
            <span class="text-stone-600">Avg: ₹<?= number_format($avg_order_value, 0) ?>/order</span>
        </div>
    </div>
    
    <!-- Total Tokens -->
    <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm card-hover animate-slide-up" style="animation-delay: 100ms">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-stone-500 text-sm font-medium">Total Orders</p>
                <p class="text-2xl sm:text-3xl font-bold text-stone-800 mt-1"><?= number_format($total_tokens) ?></p>
                <p class="text-stone-400 text-xs mt-2">Tokens generated</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="ph ph-ticket text-blue-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-stone-100">
            <div class="flex justify-between text-xs text-stone-500">
                <span>Daily Avg</span>
                <span class="font-medium text-stone-700"><?= $total_tokens > 0 ? number_format($total_tokens / $days_range, 1) : 0 ?></span>
            </div>
        </div>
    </div>
    
    <!-- Date Range Info -->
    <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm card-hover animate-slide-up" style="animation-delay: 200ms">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-stone-500 text-sm font-medium">Report Period</p>
                <p class="text-lg font-bold text-stone-800 mt-1"><?= (int)$days_range ?> Days</p>
                <p class="text-stone-400 text-xs mt-2 truncate"><?= $from_display ?> - <?= $to_display ?></p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                <i class="ph ph-calendar-range text-orange-600 text-2xl"></i>
            </div>
        </div>
        
    </div>
    
</div>
        <!-- 🧾 Transactions Table -->
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden card-hover">
            <!-- Card Header -->
            <div class="p-4 sm:p-5 border-b border-stone-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-stone-100 flex items-center justify-center">
                        <i class="ph ph-receipt text-stone-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-stone-800">Transaction History</h3>
                        <p class="text-xs text-stone-500"><?= count($transactions) ?> records found</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="hidden sm:inline text-xs text-stone-500">Sorted by:</span>
                    <span class="px-2.5 py-1 bg-stone-100 text-stone-600 text-xs font-medium rounded-lg flex items-center gap-1">
                        <i class="ph ph-sort-descending"></i>
                        Newest First
                    </span>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto scrollbar-hide">
                <table class="w-full text-sm mobile-table">
                    <thead class="bg-stone-50 text-xs uppercase text-stone-500 font-medium">
                        <tr>
                            <th class="px-4 py-3.5 text-left w-12">#</th>
                            <th class="px-4 py-3.5 text-left">Token ID</th>
                            <th class="px-4 py-3.5 text-left">Date & Time</th>
                            <th class="px-4 py-3.5 text-left">Items</th>
                            <th class="px-4 py-3.5 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php if(empty($transactions)): ?>
                            <tr>
                                <td colspan="5" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4 max-w-sm mx-auto">
                                        <div class="w-20 h-20 rounded-full bg-stone-100 flex items-center justify-center">
                                            <i class="ph ph-receipt-x text-4xl text-stone-400"></i>
                                        </div>
                                        <div class="text-center">
                                            <p class="font-semibold text-stone-700">No transactions found</p>
                                            <p class="text-sm text-stone-400 mt-1">Try adjusting your date range or check back later</p>
                                        </div>
                                        <button onclick="location.href='?'" class="mt-2 px-4 py-2 bg-orange-100 text-orange-700 rounded-lg text-sm font-medium hover:bg-orange-200 transition">
                                            Reset Filters
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($transactions as $index => $row): ?>
                            <tr class="hover:bg-orange-50/50 transition group animate-fade-in" style="animation-delay: <?= min(300, $index * 40) ?>ms">
                                <td class="px-4 py-3.5 text-stone-400 font-medium" data-label="#">
                                    <?= $index + 1 ?>
                                </td>
                                <td class="px-4 py-3.5 font-semibold text-stone-800" data-label="Token ID">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-stone-100 rounded-lg text-xs">
                                        <i class="ph ph-hash text-stone-400"></i>
                                        <?= htmlspecialchars($row['token_id']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-stone-600" data-label="Date">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-stone-800"><?= date('M j, Y', strtotime($row['date'])) ?></span>
                                        <span class="text-xs text-stone-400"><?= date('g:i A', strtotime($row['date'])) ?></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-stone-600 max-w-[180px] truncate" data-label="Items">
                                    <?= htmlspecialchars($row['items']) ?>
                                </td>
                                <td class="px-4 py-3.5 text-right font-bold text-emerald-600" data-label="Amount">
                                    ₹<?= number_format($row['amount'], 2) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer / Pagination Placeholder -->
            <?php if(!empty($transactions) && count($transactions) >= 10): ?>
            <div class="p-4 border-t border-stone-200 flex items-center justify-between text-sm">
                <span class="text-stone-500">Showing 1-<?= min(10, count($transactions)) ?> of <?= count($transactions) ?></span>
                <div class="flex gap-1">
                    <button class="px-3 py-1.5 border border-stone-200 rounded-lg text-stone-400 cursor-not-allowed" disabled>← Prev</button>
                    <button class="px-3 py-1.5 border border-stone-200 rounded-lg text-stone-400 cursor-not-allowed" disabled>Next →</button>
                </div>
            </div>
            <?php endif; ?>
        </div>

       

    </div>
    
    <!-- Bottom Spacer for Mobile -->
    <div class="h-16 md:hidden"></div>
</main>

<!-- 🧠 JavaScript -->
<script>
// Mobile Sidebar Toggle
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar-wrapper');
    const overlay = document.getElementById('sidebarOverlay');
    
    if(sidebar) {
        sidebar.classList.toggle('hidden');
        sidebar.classList.toggle('fixed');
        sidebar.classList.toggle('inset-y-0');
        sidebar.classList.toggle('left-0');
        sidebar.classList.toggle('z-50');
        sidebar.classList.toggle('w-72');
        sidebar.classList.toggle('shadow-xl');
    }
    if(overlay) overlay.classList.toggle('active');
    document.body.style.overflow = sidebar?.classList.contains('hidden') ? '' : 'hidden';
}

// Close sidebar on escape / overlay click
document.addEventListener('keydown', e => {
    if(e.key === 'Escape') {
        const sidebar = document.querySelector('.sidebar-wrapper');
        if(sidebar && !sidebar.classList.contains('hidden')) toggleSidebar();
    }
});
document.getElementById('sidebarOverlay')?.addEventListener('click', toggleSidebar);

// Auto-hide sidebar on desktop
window.addEventListener('resize', () => {
    if(window.innerWidth >= 768) {
        const sidebar = document.querySelector('.sidebar-wrapper');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar?.classList.remove('hidden', 'fixed', 'inset-y-0', 'left-0', 'z-50', 'w-72', 'shadow-xl');
        overlay?.classList.remove('active');
        document.body.style.overflow = '';
    }
});

// Touch feedback for buttons
document.querySelectorAll('button, a[role="button"]').forEach(el => {
    el.addEventListener('touchstart', function() { this.style.transform = 'scale(0.98)'; });
    el.addEventListener('touchend', function() { this.style.transform = ''; });
});

// Animate cards on scroll
if('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.animate-slide-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(12px)';
        el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        observer.observe(el);
    });
}

// Date validation: prevent to_date < from_date
document.querySelectorAll('input[type="date"]').forEach(input => {
    input.addEventListener('change', function() {
        const from = document.querySelector('input[name="from_date"]');
        const to = document.querySelector('input[name="to_date"]');
        if(from && to && from.value && to.value) {
            if(this.name === 'from_date' && new Date(to.value) < new Date(this.value)) {
                to.value = this.value;
            }
            if(this.name === 'to_date' && new Date(from.value) > new Date(this.value)) {
                from.value = this.value;
            }
        }
    });
});
</script>

</body>
</html>