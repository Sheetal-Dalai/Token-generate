<!-- Overlay (Mobile Only) -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-30 hidden md:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar"
class="fixed top-0 left-0 h-full w-64 bg-white border-r border-stone-200 flex flex-col z-40 font-sans 
transform -translate-x-full md:translate-x-0 transition-transform duration-300">

    <!-- Logo -->
    <div class="h-20 flex items-center px-6 border-b border-stone-100">
        <div class="w-9 h-9 bg-brand-600 rounded-xl flex items-center justify-center mr-3 text-white font-bold text-lg shadow-sm">☕</div>
        <span class="text-lg font-bold text-stone-800 tracking-tight">Rajasthan Mishthan Bhandar</span>

        <!-- Close Button (Mobile Only) -->
        <button onclick="toggleSidebar()" class="ml-auto text-xl md:hidden">✕</button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
        <p class="px-4 text-[11px] font-semibold text-stone-400 uppercase tracking-wider mb-2 mt-1">Main</p>

        <!-- Dashboard -->
        <a href="index.php" class="group flex items-center px-4 py-2.5 bg-brand-50 text-brand-700 rounded-xl font-medium text-sm transition-all">
            <svg class="w-5 h-5 mr-3 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            Dashboard
        </a>

        <!-- Item Category -->
        <a href="item_category.php" class="group flex items-center px-4 py-2.5 text-stone-600 hover:bg-brand-50 hover:text-brand-700 rounded-xl font-medium text-sm transition-all">
            <svg class="w-5 h-5 mr-3 text-stone-400 group-hover:text-brand-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Item Category
        </a>

        <!-- Items -->
        <a href="add_item.php" class="group flex items-center px-4 py-2.5 text-stone-600 hover:bg-brand-50 hover:text-brand-700 rounded-xl font-medium text-sm transition-all">
            <svg class="w-5 h-5 mr-3 text-stone-400 group-hover:text-brand-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Items
        </a>

        <!-- Orders -->
        <a href="orders.php" class="group flex items-center px-4 py-2.5 text-stone-600 hover:bg-brand-50 hover:text-brand-700 rounded-xl font-medium text-sm transition-all">
            <svg class="w-5 h-5 mr-3 text-stone-400 group-hover:text-brand-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
            </svg>
            Orders
        </a>

        <!-- Reports--> 
        <p class="px-4 text-[11px] font-semibold text-stone-400 uppercase tracking-wider mb-2 mt-4">Reports</p>
        <a href="reports.php" class="group flex items-center px-4 py-2.5 text-stone-600 hover:bg-brand-50 hover:text-brand-700 rounded-xl font-medium text-sm transition-all">
            <svg class="w-5 h-5 mr-3 text-stone-400 group-hover:text-brand-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Reports
        </a>
    </nav>

    <!-- User Profile -->
    <div class="p-4 border-t border-stone-100 bg-stone-50/50">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-100 rounded-lg flex items-center justify-center text-brand-700 font-semibold text-sm">
                    A
                </div>
                <div>
                    <p class="text-sm font-medium text-stone-800">Admin User</p>
                    <p class="text-[11px] text-stone-400">Administrator</p>
                </div>
            </div>

            <button onclick="window.location.href='../index.php'" 
            class="flex items-center gap-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg text-sm font-medium transition-all">
                Logout
            </button>
        </div>
    </div>

</aside>

<!-- SCRIPT -->
<script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");

    sidebar.classList.toggle("-translate-x-full");
    overlay.classList.toggle("hidden");
}

document.getElementById("sidebarOverlay").addEventListener("click", toggleSidebar);
</script>