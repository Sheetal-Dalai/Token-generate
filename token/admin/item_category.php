<?php include("include/sidebar.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Categories</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }

.orange-btn { background-color: #ea580c; color: white; }
.orange-btn:hover { background-color: #c94a09; }
.orange-border { border-color: #ea580c; }
.orange-text { color: #ea580c; }
.orange-bg { background-color: #fff4e6; }
body { font-family: 'Poppins', sans-serif; }

/* Search highlight */
.highlight { background-color: #fef3c7; font-weight: 500; }

/* No results animation */
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.no-results { animation: fadeIn 0.3s ease; }
</style>
</head>

<body class="bg-stone-50 flex">

<!-- MAIN -->
<div class="flex-1 flex flex-col min-h-screen md:ml-64">

    <!-- CONTENT -->
    <main class="p-4 sm:p-6 md:p-8 overflow-y-auto">

        <!-- 🔥 TOP BAR -->
        <div class="flex items-center justify-between mb-6">
            <button onclick="toggleSidebar()" class="md:hidden text-2xl">☰</button>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold orange-text">Category Management</h1>
                <p class="text-xs sm:text-sm text-stone-500">Manage your categories</p>
            </div>
        </div>

        <!-- ✅ Search + Button with Real-time Search -->
        <div class="flex flex-col md:flex-row gap-3 mb-6">
            
            <!-- 🔍 Real-time Search Input -->
            <div class="relative w-full md:w-60">
                <input type="text" id="searchInput" placeholder="Search categories..."
                    class="border border-stone-200 px-4 py-2 pl-10 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-sm"></i>
                <button id="clearSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 hidden">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <button onclick="openModal()"
                class="orange-btn px-4 py-2 rounded-lg flex items-center justify-center gap-2 w-full md:w-auto">
                <i class="fas fa-plus"></i> Add Category
            </button>
        </div>

        <!-- ✅ Search Results Info -->
        <div id="searchInfo" class="text-xs text-stone-500 mb-2 hidden">
            Showing <span id="visibleCount">0</span> of <span id="totalCount">0</span> categories
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-x-auto border border-orange-border">
            <table class="min-w-[500px] w-full text-sm">
                <thead class="bg-stone-100 text-stone-800">
                    <tr>
                        <th class="p-3 text-left">S.no</th>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody id="categoryTable"></tbody>
            </table>
            
            <!-- ✅ No Results Message -->
            <div id="noResults" class="hidden p-8 text-center text-stone-500 no-results">
                <i class="fas fa-search text-3xl mb-2 text-stone-300"></i>
                <p class="text-sm">No categories found matching "<span id="searchTerm" class="font-medium orange-text"></span>"</p>
                <button onclick="clearSearch()" class="mt-2 text-xs orange-text hover:underline">Clear search</button>
            </div>
        </div>

    </main>
</div>

<!-- MODAL -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-[90%] max-w-md border border-orange-border">
        <h2 class="text-lg font-bold mb-4 orange-text">Add / Edit Category</h2>
        <form id="categoryForm">
            <input type="hidden" id="categoryId">
            <input type="text" id="categoryName" placeholder="Category Name"
                class="w-full border border-stone-200 p-2 mb-3 rounded" required>
            <select id="categoryStatus" class="w-full border border-stone-200 p-2 mb-3 rounded">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="bg-stone-300 px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="orange-btn px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// ==================== GLOBAL STATE ====================
let allCategories = []; // Store all categories for client-side search

// ==================== SIDEBAR TOGGLE ====================
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");
    if(sidebar) sidebar.classList.toggle("-translate-x-full");
    if(overlay) overlay.classList.toggle("hidden");
}

// ==================== MODAL FUNCTIONS ====================
function openModal(id='', name='', status='Active'){
    $("#categoryId").val(id);
    $("#categoryName").val(name);
    $("#categoryStatus").val(status);
    $("#modal").removeClass('hidden').addClass('flex');
}

function closeModal(){
    $("#modal").addClass('hidden').removeClass('flex');
    $("#categoryForm")[0].reset();
    $("#categoryId").val('');
}

// Close modal on outside click
$("#modal").on('click', function(e){
    if($(e.target).is('#modal')) closeModal();
});

// ==================== ✅ REAL-TIME SEARCH ✅ ====================

// Search input handler - REAL TIME
$('#searchInput').on('input', function(){
    const term = $(this).val().toLowerCase().trim();
    
    // Show/hide clear button
    $('#clearSearch').toggleClass('hidden', term === '');
    
    // Show/hide search info
    $('#searchInfo').toggleClass('hidden', term === '');
    
    if(term === ''){ 
        // If search is empty, show all categories
        renderCategories(allCategories);
        $('#noResults').addClass('hidden');
        return; 
    }
    
    // Filter categories client-side
    const filtered = allCategories.filter(cat => {
        return cat.name.toLowerCase().includes(term) || 
               cat.id.toString().includes(term) ||
               cat.status.toLowerCase().includes(term);
    });
    
    // Update UI
    $('#searchTerm').text(term);
    $('#visibleCount').text(filtered.length);
    
    if(filtered.length === 0){
        $('#categoryTable').html('');
        $('#noResults').removeClass('hidden');
    } else {
        $('#noResults').addClass('hidden');
        renderCategories(filtered, term); // Pass term for highlighting
    }
});

// Clear search button
$('#clearSearch').on('click', function(){
    $('#searchInput').val('');
    $('#clearSearch').addClass('hidden');
    $('#searchInfo').addClass('hidden');
    $('#noResults').addClass('hidden');
    renderCategories(allCategories);
    $('#searchInput').focus();
});

// Helper function to clear search programmatically
function clearSearch(){
    $('#searchInput').val('');
    $('#clearSearch').addClass('hidden');
    $('#searchInfo').addClass('hidden');
    $('#noResults').addClass('hidden');
    renderCategories(allCategories);
}

// ==================== RENDER CATEGORIES ====================
// ==================== RENDER CATEGORIES ====================
function renderCategories(categories, searchTerm = ''){
    let html = '';
    
    categories.forEach((cat, index) => {  // ✅ index parameter add kiya
        
        // ✅ Serial Number: Search ke saath bhi 1,2,3... rahega
        const serialNo = index + 1;
        
        // ✅ Highlight matching text if searching
        let displayName = cat.name;
        if(searchTerm && cat.name.toLowerCase().includes(searchTerm)){
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            displayName = cat.name.replace(regex, '<span class="highlight">$1</span>');
        }
        
        // Status badge color
        const statusClass = cat.status === 'Active' 
            ? 'bg-green-100 text-green-700' 
            : 'bg-red-100 text-red-700';
        
        html += `<tr class="border-t hover:bg-orange-bg transition">
            <!-- ✅ SERIAL NUMBER instead of ID -->
            <td class="p-3 font-mono text-xs text-stone-500 text-center w-12">${serialNo}</td>
            
            <td class="p-3 font-medium">${displayName}</td>
            <td class="p-3">
                <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">
                    ${cat.status}
                </span>
            </td>
            <td class="p-3 text-right">
                <button class="text-orange-600 hover:text-orange-800 mr-3 transition" 
                    onclick="openModal('${cat.id}','${cat.name.replace(/'/g, "\\'")}','${cat.status}')" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="text-red-600 hover:text-red-800 transition" 
                    onclick="deleteCategory('${cat.id}')" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    });
    
    $("#categoryTable").html(html);
    $('#totalCount').text(categories.length);
    $('#visibleCount').text(categories.length);
}
// ==================== LOAD CATEGORIES ====================
function loadCategories(){
    $.ajax({
        url: "../lorus_includes/class/ajax.php",
        type: "POST",
        data: {action: "get_categories"},
        success: function(res){
            try {
                allCategories = JSON.parse(res); // ✅ Store in global variable
                renderCategories(allCategories);
            } catch(e){
                console.error("Parse error:", e);
                $("#categoryTable").html('<tr><td colspan="4" class="p-4 text-center text-red-500">Error loading categories</td></tr>');
            }
        },
        error: function(){
            $("#categoryTable").html('<tr><td colspan="4" class="p-4 text-center text-red-500">Failed to load categories</td></tr>');
        }
    });
}

// ==================== FORM SUBMIT ====================
$("#categoryForm").submit(function(e){
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

    $.ajax({
        url: "../lorus_includes/class/ajax.php",
        type: "POST",
        data: {
            action: $("#categoryId").val() ? "update_category" : "add_category",
            id: $("#categoryId").val(),
            name: $("#categoryName").val(),
            status: $("#categoryStatus").val()
        },
        success: function(res){
            try {
                let r = JSON.parse(res);
                alert(r.message);
                if(r.status == 'success'){
                    closeModal();
                    loadCategories(); // Reload to get updated list
                }
            } catch(e){
                alert("Invalid response from server");
            }
        },
        error: function(){
            alert("Request failed. Please try again.");
        },
        complete: function(){
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
});

// ==================== DELETE CATEGORY ====================
function deleteCategory(id){
    if(confirm("Are you sure you want to delete this category?")){
        $.post("../lorus_includes/class/ajax.php", {
            action: "delete_category",
            id: id
        }, function(res){
            try {
                let r = JSON.parse(res);
                alert(r.message);
                if(r.status == 'success'){
                    loadCategories();
                }
            } catch(e){
                alert("Error processing response");
            }
        }).fail(function(){
            alert("Failed to connect to server");
        });
    }
}

// ==================== KEYBOARD SHORTCUTS ====================
$(document).ready(function(){
    // Focus search on Ctrl+K or /
    $(document).on('keydown', function(e){
        if((e.ctrlKey && e.key === 'k') || (e.key === '/' && !['INPUT','TEXTAREA'].includes(e.target.tagName))){
            e.preventDefault();
            $('#searchInput').focus();
        }
        // Escape to close modal or clear search
        if(e.key === 'Escape'){
            if(!$('#modal').hasClass('hidden')){
                closeModal();
            } else if($('#searchInput').val() !== ''){
                clearSearch();
            }
        }
    });
    
    // Initialize
    loadCategories();
});
</script>

</body>
</html>