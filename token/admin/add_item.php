<?php include("include/sidebar.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Items - Multi-Unit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        .sidebar-wrapper { flex-shrink: 0; width: 256px; }
        .main-wrapper { flex: 1; min-width: 0; }
        #modal { z-index: 9999; }
        
        /* Compact card styles */
        .item-card { padding: 0.5rem; }
        .item-card .img-container { 
            height: 140px; 
            width: 100%;
            position: relative;
            background: #f3f4f6;
            border-radius: 0.375rem;
            overflow: hidden;
        }
        .item-card .img-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
        }
        .item-card .title { font-size: 0.875rem; line-height: 1.25rem; }
        .item-card .text-sm { font-size: 0.75rem; }
        .item-card .badge { padding: 0.125rem 0.5rem; font-size: 0.625rem; }
        
        /* Compact modal */
        .modal-compact { max-width: 480px !important; }
        .modal-compact .form-label { font-size: 0.75rem; margin-bottom: 0.25rem; }
        .modal-compact .form-input { padding: 0.375rem 0.5rem; font-size: 0.75rem; margin-bottom: 0.5rem; }
        .modal-compact .form-row { display: flex; gap: 0.5rem; }
        .modal-compact .form-row > * { flex: 1; margin-bottom: 0.5rem; }
        
        /* Unit badge styling */
        .unit-badge { 
            background: #f3f4f6; 
            padding: 2px 6px; 
            border-radius: 4px; 
            font-size: 0.65rem; 
            color: #4b5563; 
            font-weight: 500;
            display: inline-block;
            margin: 1px;
        }
        .unit-price-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 0;
        }
        .unit-price-row label {
            min-width: 70px;
            font-size: 0.75rem;
            color: #374151;
            font-weight: 500;
        }
        .unit-checkbox {
            width: 14px;
            height: 14px;
            cursor: pointer;
        }
        
        /* Plate dual-price section */
        .plate-prices-box {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 0.375rem;
            padding: 0.5rem;
        }
        .plate-prices-box .plate-title {
            color: #c2410c;
            font-weight: 600;
            font-size: 0.7rem;
            margin-bottom: 0.375rem;
        }
        .plate-input-group {
            display: flex;
            gap: 0.5rem;
        }
        .plate-input-group > div {
            flex: 1;
        }
        .plate-input-group label {
            font-size: 0.65rem;
            color: #6b7280;
            margin-bottom: 0.125rem;
            display: block;
        }
        .plate-input-group input {
            width: 100%;
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
        }
        .plate-input-group input:focus {
            outline: none;
            border-color: #f97316;
            box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.1);
        }
        .plate-note {
            font-size: 0.6rem;
            color: #9ca3af;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar -->
    <div class="sidebar-wrapper h-screen sticky top-0 overflow-y-auto bg-white border-r">
        <?php // Sidebar content loaded from include/sidebar.php ?>
    </div>

    <!-- Main Content -->
    <div class="main-wrapper flex flex-col">
        <main class="p-4 md:p-6 overflow-y-auto">

            <!-- Header + Search -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                <div>
                    <h1 class="text-xl font-bold">Item Management</h1>
                    <p class="text-gray-500 text-xs">Add items with multiple units (kg, pcs, plates, etc.)</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                    <!-- Realtime Search Bar -->
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Search items..." 
                            class="pl-8 pr-3 py-1.5 border rounded-lg text-sm w-full sm:w-48 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <i class="fas fa-search absolute left-2.5 top-2 text-gray-400 text-xs"></i>
                    </div>
                    <button onclick="openModal()" class="bg-orange-600 text-white px-3 py-1.5 rounded-lg flex items-center gap-1.5 text-sm hover:bg-orange-700 transition whitespace-nowrap">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>
            </div>

            <!-- Items Grid -->
            <div id="itemsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3"></div>

            <!-- No Results -->
            <div id="noResults" class="hidden text-center py-8 text-gray-500">
                <i class="fas fa-search text-2xl mb-2"></i>
                <p>No items found matching "<span id="searchTerm"></span>"</p>
            </div>

        </main>
    </div>

    <!-- MODAL FORM - MULTI-UNIT WITH SEPARATE PLATE PRICE INPUTS -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
        <div class="modal-compact bg-white p-4 rounded-xl w-full mx-4 shadow-2xl border border-gray-200 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-3 pb-2 border-b sticky top-0 bg-white">
                <h2 class="text-base font-bold" id="modalTitle">Add Item</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            
            <form id="addItemForm" enctype="multipart/form-data" class="space-y-2">
                
                <!-- Category + Status Row -->
                <div class="form-row">
                    <div>
                        <label class="form-label block font-medium text-gray-700">Category</label>
                        <select name="category_id" id="categorySelect" class="form-input w-full border rounded bg-white" required>
                            <option value="">Loading...</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label block font-medium text-gray-700">Status</label>
                        <select name="status" class="form-input w-full border rounded bg-white">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Item Name -->
                <div>
                    <label class="form-label block font-medium text-gray-700">Item Name *</label>
                    <input type="text" name="name" class="form-input w-full border rounded" required placeholder="e.g., Rice, Sugar, Plates">
                </div>

                <!-- MULTI-UNIT PRICES SECTION -->
                <div>
                    <label class="form-label block font-medium text-gray-700 mb-1">Available Units & Prices *</label>
                    <p class="text-[10px] text-gray-400 mb-2">Select units this item can be sold in</p>
                    
                    <!-- Unit Checkboxes -->
                    <div class="flex flex-wrap gap-2 mb-3 p-2 bg-gray-50 rounded" id="unitCheckboxes">
                        <label class="flex items-center gap-1.5 text-xs cursor-pointer hover:bg-gray-100 p-1 rounded">
                            <input type="checkbox" class="unit-checkbox" value="pcs" data-label="Pieces"> 
                            <span>Pieces</span>
                        </label>
                        <label class="flex items-center gap-1.5 text-xs cursor-pointer hover:bg-gray-100 p-1 rounded">
                            <input type="checkbox" class="unit-checkbox" value="kg" data-label="Kilogram"> 
                            <span>Kg</span>
                        </label>
                        <label class="flex items-center gap-1.5 text-xs cursor-pointer hover:bg-gray-100 p-1 rounded">
                            <input type="checkbox" class="unit-checkbox" value="g" data-label="Gram"> 
                            <span>Gram</span>
                        </label>
                        <!-- ✅ PLATE checkbox - triggers 2 separate price inputs -->
                        <label class="flex items-center gap-1.5 text-xs cursor-pointer hover:bg-gray-100 p-1 rounded">
                            <input type="checkbox" class="unit-checkbox" value="plt" data-label="Plate" data-has-dual="1"> 
                            <span>Plate</span>
                        </label>
                    </div>

                    <!-- Dynamic Price Inputs (generated by JS) -->
                    <div id="unitPricesContainer" class="space-y-2 min-h-[30px]"></div>
                    
                    <!-- Hidden input to store JSON of unit prices -->
                    <input type="hidden" name="unit_prices" id="unitPricesJson">
                    <input type="hidden" name="default_unit" id="defaultUnit">
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="form-label block font-medium text-gray-700">Image</label>
                    <input type="file" name="image" id="itemImage" class="form-input w-full text-xs" accept="image/*">
                    <img id="previewImg" class="w-16 h-16 object-cover rounded mt-1 hidden border"/>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2 pt-2 border-t mt-3 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeModal()" class="bg-gray-200 text-gray-700 px-3 py-1.5 rounded text-xs hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" class="bg-orange-600 text-white px-4 py-1.5 rounded text-xs hover:bg-orange-700 transition font-medium">
                        <i class="fas fa-save mr-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // ==================== CONFIGURATION ====================
        const API_URL = '../lorus_includes/class/ajax.php';
        
        const UNIT_LABELS = {
            'pcs': 'Pieces', 
            'kg': 'Kg', 
            'g': 'Gram', 
            'plt': 'Plate',
            'plt_full': 'Full Plate',
            'plt_half': 'Half Plate'
        };

        // ==================== STATE ====================
        let editingItemId = null;
        let allItems = [];

        // ==================== MODAL FUNCTIONS ====================
        function openModal(){
            $('#modal').removeClass('hidden').addClass('flex');
            if(!editingItemId) {
                $('#addItemForm')[0].reset();
                $('#previewImg').hide().attr('src', '');
                $('.unit-checkbox').prop('checked', false);
                $('#unitPricesContainer').empty();
                $('#unitPricesJson').val('');
                $('#defaultUnit').val('');
                $('#modalTitle').text('Add Item');
                renderUnitPriceFields();
            }
        }

        function closeModal(){
            $('#modal').addClass('hidden').removeClass('flex');
            $('#addItemForm')[0].reset();
            $('#previewImg').hide().attr('src', '');
            $('.unit-checkbox').prop('checked', false);
            $('#unitPricesContainer').empty();
            $('#unitPricesJson').val('');
            $('#defaultUnit').val('');
            editingItemId = null;
            $('#modalTitle').text('Add Item');
        }

        $('#modal').on('click', function(e){
            if($(e.target).is('#modal')) closeModal();
        });

        // ==================== IMAGE PREVIEW ====================
        $('#itemImage').change(function(){
            const file = this.files[0];
            if(file){
                if(!file.type.match('image.*')){
                    alert('Please select an image file');
                    this.value = '';
                    return;
                }
                if(file.size > 2 * 1024 * 1024){
                    alert('Image size should be less than 2MB');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e){
                    $('#previewImg').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            }
        });

        // ==================== UNIT CHECKBOX CHANGE HANDLER ====================
        $('.unit-checkbox').on('change', function(){
            renderUnitPriceFields();
        });

        // ==================== ✅ RENDER PRICE FIELDS - WITH 2 SEPARATE PLATE INPUTS ✅ ====================
        function renderUnitPriceFields() {
            const container = $('#unitPricesContainer');
            container.empty();
            
            // Get all checked units
            const selected = [];
            $('.unit-checkbox:checked').each(function(){
                selected.push({ 
                    unit: $(this).val(), 
                    label: $(this).data('label'),
                    hasDual: $(this).data('has-dual') == '1'
                });
            });
            
            if(selected.length === 0) {
                container.html('<p class="text-xs text-gray-400 italic p-2">Select at least one unit above</p>');
                $('#unitPricesJson').val('');
                return;
            }
            
            let firstUnit = true;
            
            selected.forEach(function(u) {
                
                // 🍽️ PLATE - TWO SEPARATE PRICE INPUTS (Full & Half)
                if(u.hasDual) {
                    container.append(`
                        <div class="plate-prices-box">
                            <div class="plate-title">
                                <i class="fas fa-utensils mr-1"></i>Plate Prices (Optional - fill any or both)
                            </div>
                            <div class="plate-input-group">
                                <div>
                                    <label>Full Plate (₹)</label>
                                    <input type="number" step="0.01" min="0" 
                                           class="unit-price-input" 
                                           data-unit="plt_full" 
                                           placeholder="e.g., 120">
                                </div>
                                <div>
                                    <label>Half Plate (₹)</label>
                                    <input type="number" step="0.01" min="0" 
                                           class="unit-price-input" 
                                           data-unit="plt_half" 
                                           placeholder="e.g., 60">
                                </div>
                            </div>
                            <div class="plate-note">* Dono fill karna zaroori nahi</div>
                        </div>
                    `);
                    
                    // Set default unit to plt_full if not set yet
                    if(firstUnit) {
                        $('#defaultUnit').val('plt_full');
                        firstUnit = false;
                    }
                    
                } 
                // 📦 Normal units (pcs, kg, g) - single price input
                else {
                    container.append(`
                        <div class="unit-price-row">
                            <label>
                                <input type="radio" name="default_unit_radio" 
                                       value="${u.unit}" 
                                       class="default-unit-radio" 
                                       ${firstUnit ? 'checked' : ''}>
                                ${u.label}
                            </label>
                            <input type="number" step="0.01" min="0"
                                   class="form-input flex-1 border rounded text-xs py-1 px-2 unit-price-input" 
                                   data-unit="${u.unit}" 
                                   placeholder="Price per ${u.label}">
                        </div>
                    `);
                    
                    if(firstUnit) {
                        $('#defaultUnit').val(u.unit);
                        firstUnit = false;
                    }
                }
            });
            
            // Attach event listeners
            $('.default-unit-radio').off('change').on('change', function(){
                $('#defaultUnit').val($(this).val());
            });
            
            $('.unit-price-input').off('input').on('input', function(){
                updateUnitPricesJson();
            });
            
            // Initial JSON update
            updateUnitPricesJson();
        }

        // ==================== UPDATE HIDDEN JSON FIELD ====================
        function updateUnitPricesJson() {
            const prices = {};
            $('.unit-price-input').each(function(){
                const unit = $(this).data('unit');
                const val = $(this).val().trim();
                if(val !== '' && !isNaN(parseFloat(val))) {
                    prices[unit] = parseFloat(val);
                }
            });
            $('#unitPricesJson').val(JSON.stringify(prices));
        }

        // ==================== API: LOAD CATEGORIES ====================
        function loadCategories(){
            $.post(API_URL, {action:'get_categories'}, function(res){
                try{
                    const data = JSON.parse(res);
                    let html = '<option value="">Select Category</option>';
                    if(Array.isArray(data) && data.length > 0){
                        data.forEach(c=>{
                            html += `<option value="${c.id}">${c.name}</option>`;
                        });
                    }
                    $('#categorySelect').html(html);
                }catch(e){
                    console.error("Categories error:", e, res);
                    $('#categorySelect').html('<option value="">Error loading</option>');
                }
            }).fail(function(){
                console.error("AJAX request failed for categories");
            });
        }

        // ==================== API: LOAD ITEMS ====================
        function loadItems(){
            $.post(API_URL, {action:'get_items'}, function(res){
                try{
                    allItems = JSON.parse(res);
                    renderItems(allItems);
                }catch(e){
                    console.error("Items error:", e, res);
                    $('#itemsGrid').html('<p class="text-red-500 text-center col-span-full">Error loading items</p>');
                }
            }).fail(function(){
                console.error("AJAX request failed for items");
            });
        }

        // ==================== RENDER ITEMS GRID ====================
        function renderItems(items){
            if(!items || items.length === 0){
                $('#itemsGrid').html('');
                $('#noResults').removeClass('hidden');
                return;
            }
            $('#noResults').addClass('hidden');
            
            let html = '';
            items.forEach(i => {
                const img = i.image ? `../${i.image}?t=${Date.now()}` : '';
                
                // Parse unit_prices
                let unitPrices = {};
                try {
                    unitPrices = typeof i.unit_prices === 'string' ? JSON.parse(i.unit_prices) : (i.unit_prices || {});
                } catch(e) {
                    console.warn('Could not parse unit_prices for item', i.id);
                }
                
                // Generate price badges - ✅ BOTH PLATE PRICES SHOW SEPARATELY
                const priceBadges = [];
                for(const [unit, price] of Object.entries(unitPrices)) {
                    let label = UNIT_LABELS[unit] || unit;
                    priceBadges.push(
                        `<span class="unit-badge">₹${parseFloat(price).toFixed(2)} / ${label}</span>`
                    );
                }
                
                html += `
<div class="item-card bg-white rounded-lg shadow hover:shadow-md transition p-2 relative group border border-gray-100">
    <div class="absolute top-1 right-1 flex gap-1 opacity-0 group-hover:opacity-100 transition z-10">
        <button onclick="editItem(${i.id})" class="text-blue-600 hover:text-blue-800 p-0.5 bg-white rounded shadow-sm" title="Edit">
            <i class="fas fa-edit text-xs"></i>
        </button>
        <button onclick="deleteItem(${i.id})" class="text-red-600 hover:text-red-800 p-0.5 bg-white rounded shadow-sm" title="Delete">
            <i class="fas fa-trash text-xs"></i>
        </button>
    </div>
    
    <div class="img-container bg-gray-100 rounded-md overflow-hidden flex items-center justify-center mb-2 cursor-pointer" onclick="editItem(${i.id})">
        ${i.image ? `<img src="${img}" onerror="this.parentElement.innerHTML='<span class=\\'text-gray-400 text-[10px]\\'>No Img</span>'">` : `<span class="text-gray-400 text-[10px]">No Img</span>`}
    </div>
    
    <h3 class="title font-semibold truncate" title="${i.name}">${i.name}</h3>
    <p class="text-[11px] text-gray-500 truncate" title="${i.category_name}">${i.category_name}</p>
    
    <div class="flex flex-wrap gap-0.5 mt-1 min-h-[20px]">
        ${priceBadges.length > 0 ? priceBadges.join('') : `<span class="text-[10px] text-gray-400">No price</span>`}
    </div>
    
    <div class="mt-1">
        <span class="badge rounded-full ${i.status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
            ${i.status}
        </span>
    </div>
</div>`;
            });
            $('#itemsGrid').html(html);
        }

        // ==================== SEARCH FUNCTIONALITY ====================
        $('#searchInput').on('input', function(){
            const term = $(this).val().toLowerCase().trim();
            $('#searchTerm').text(term);
            
            if(term === ''){ 
                renderItems(allItems); 
                return; 
            }
            
            const filtered = allItems.filter(item => {
                if(item.name && item.name.toLowerCase().includes(term)) return true;
                if(item.category_name && item.category_name.toLowerCase().includes(term)) return true;
                if(item.id && item.id.toString().includes(term)) return true;
                if(item.unit_prices) {
                    try {
                        const prices = typeof item.unit_prices === 'string' ? JSON.parse(item.unit_prices) : item.unit_prices;
                        if(Object.values(prices).some(p => p.toString().includes(term))) return true;
                    } catch(e) {}
                }
                return false;
            });
            renderItems(filtered);
        });

        // ==================== ✅ EDIT ITEM - LOADS BOTH PLATE PRICES ✅ ====================
        function editItem(id){
            editingItemId = id;
            $('#modalTitle').text('Edit Item');
            $('#modal').removeClass('hidden').addClass('flex');
            
            $('#unitPricesContainer').html('<p class="text-xs text-gray-400 text-center py-2"><i class="fas fa-spinner fa-spin"></i> Loading...</p>');
            
            $.post(API_URL, {action:'get_item', id:id}, function(res){
                try{
                    const item = JSON.parse(res);
                    
                    // Fill basic fields
                    $('#categorySelect').val(item.category_id);
                    $('input[name="name"]').val(item.name);
                    $('select[name="status"]').val(item.status);
                    
                    // Parse unit_prices
                    let unitPrices = {};
                    try {
                        if(item.unit_prices) {
                            unitPrices = typeof item.unit_prices === 'string' ? JSON.parse(item.unit_prices) : item.unit_prices;
                        }
                    } catch(e) { console.warn('parse error', e); }
                    
                    // Legacy fallback
                    if(Object.keys(unitPrices).length === 0 && item.unit && item.base_price) {
                        unitPrices[item.unit] = parseFloat(item.base_price);
                    }
                    
                    // Mark checkboxes
                    $('.unit-checkbox').prop('checked', false);
                    for(const unit of Object.keys(unitPrices)) {
                        if(unit === 'plt_full' || unit === 'plt_half') {
                            $('.unit-checkbox[value="plt"]').prop('checked', true);
                        } else {
                            $(`.unit-checkbox[value="${unit}"]`).prop('checked', true);
                        }
                    }
                    
                    // Render price fields AFTER checkboxes are set
                    renderUnitPriceFields();
                    
                    // Fill price values - ✅ BOTH plt_full AND plt_half will be filled if present
                    $('.unit-price-input').each(function(){
                        const u = $(this).data('unit');
                        if(unitPrices[u] !== undefined) {
                            $(this).val(unitPrices[u]);
                        }
                    });
                    
                    // Set default unit
                    const defUnit = item.default_unit || Object.keys(unitPrices)[0] || '';
                    $('#defaultUnit').val(defUnit);
                    $(`.default-unit-radio[value="${defUnit}"]`).prop('checked', true);
                    
                    // Image preview
                    if(item.image){
                        const imgSrc = item.image.startsWith('../') ? item.image : '../' + item.image;
                        $('#previewImg').attr('src', imgSrc).show();
                    } else {
                        $('#previewImg').hide();
                    }
                    
                    updateUnitPricesJson();
                    
                } catch(e){
                    console.error("Edit error:", e);
                    alert("Could not load item data");
                    closeModal();
                }
            }).fail(function(){
                alert("Failed to connect to server");
                closeModal();
            });
        }

        // ==================== FORM SUBMIT ====================
        $('#addItemForm').submit(function(e){
            e.preventDefault();
            
            if($('.unit-checkbox:checked').length === 0) {
                alert('Please select at least one unit');
                return;
            }
            
            updateUnitPricesJson();
            
            const jsonVal = $('#unitPricesJson').val();
            if(!jsonVal || jsonVal === '{}') {
                alert('Please enter price for at least one unit');
                return;
            }
            
            const formData = new FormData(this);
            formData.append('action', editingItemId ? 'edit_item' : 'add_item');
            if(editingItemId) formData.append('id', editingItemId);
            
            const btn = $(this).find('button[type="submit"]');
            const originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
            
            $.ajax({
                url: API_URL,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res){
                    try{
                        const r = JSON.parse(res);
                        if(r.status === 'success'){
                            alert(r.message);
                            closeModal();
                            loadItems();
                        } else {
                            alert(r.message || 'Error: ' + res);
                        }
                    } catch(e){
                        alert("Invalid server response");
                        console.error(e, res);
                    }
                },
                error: function(xhr){
                    alert("Request failed: " + (xhr.responseText || 'Unknown error'));
                },
                complete: function(){
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

        // ==================== DELETE ITEM ====================
        function deleteItem(id){
            if(!confirm("Are you sure you want to delete this item?")) return;
            
            fetch(API_URL, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "action=delete_item&id=" + id
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === "success"){
                    loadItems();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Delete failed");
            });
        }

        // ==================== INIT ====================
        $(document).ready(function(){
            loadCategories();
            loadItems();
            renderUnitPriceFields();
        });
    </script>
</body>
</html>