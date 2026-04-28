<?php
include("lorus_includes/class/functions_class.php");
$admin = new Admin();
$items = json_decode($admin->getItems(), true);
$categories = json_decode($admin->getCategories(), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#fff7ed',100:'#ffedd5',200:'#fed7aa',300:'#fdba74',400:'#fb923c',500:'#f97316',600:'#ea580c',700:'#c2410c',800:'#9a3412',900:'#7c2d12' },
                        stone: { 50:'#fafaf9',100:'#f5f5f4',200:'#e7e5e4',300:'#d6d3d1',400:'#a8a29e',500:'#78716c',600:'#57534e',700:'#44403c',800:'#292524',900:'#1c1917' }
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar{width:6px;height:6px}::-webkit-scrollbar-thumb{background:#ccc;border-radius:10px}
        body{font-family:'Poppins',sans-serif}
        .menu-card{padding:0.5rem;transition:all 0.2s;position:relative}
        .menu-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.1);border-color:#fed7aa}
        
        /* ✅ FIXED IMAGE CONTAINER STYLES */
        .img-container{
            height:140px;
            width:100%;
            position:relative;
            background:#f9fafb;
            border-radius:0.5rem;
            overflow:hidden;
            display:flex;
            align-items:center;
            justify-content:center;
        }
        .img-container img{
            width:100%;
            height:100%;
            object-fit:contain;
            object-position:center;
        }
        
        .item-title{font-size:0.875rem;line-height:1.25rem;font-weight:600}
        .item-category{font-size:0.75rem;color:#6b7280}
        .unit-badge{background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:0.65rem;color:#4b5563;font-weight:500;display:inline-block;margin:1px;white-space:nowrap}
        
        /* ✅ UPDATED UNIT SELECT STYLES */
        .unit-select{
            width:100%;
            padding:4px 8px;
            border:1px solid #e5e7eb;
            border-radius:4px;
            font-size:0.7rem;
            font-weight:500;
            color:#374151;
            background:white;
            cursor:pointer;
            margin:4px 0;
        }
        .unit-select:focus{outline:none;border-color:#ea580c;box-shadow:0 0 0 2px rgba(234,88,12,0.15)}
        
        .stepper{display:inline-flex;align-items:center;gap:4px}
        .stepper-btn{width:24px;height:24px;border:1px solid #e5e7eb;border-radius:4px;background:#f9fafb;font-weight:600;color:#374151;font-size:12px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.15s;user-select:none}
        .stepper-btn:hover{background:#e5e7eb;color:#ea580c}
        .stepper-btn:active{transform:scale(0.95)}
        .stepper-input{width:44px;text-align:center;border:1px solid #e5e7eb;border-radius:4px;padding:2px;font-size:0.75rem;font-weight:500}
        .stepper-input[readonly]{background:#f9fafb;color:#6b7280;cursor:not-allowed}
        .stepper-input:not([readonly]){background:white;color:#1f2937}
        .stepper-input:not([readonly]):focus{outline:none;border-color:#ea580c;box-shadow:0 0 0 2px rgba(234,88,12,0.15)}
        .add-btn{background:#ea580c;color:white;padding:4px 12px;border-radius:6px;border:none;cursor:pointer;font-weight:500;font-size:0.75rem;display:flex;align-items:center;justify-content:center;gap:4px;transition:all 0.2s;width:100%}
        .add-btn:hover{background:#c2410c}
        .add-btn:active{transform:scale(0.98)}
        .cart-table th,.cart-table td{padding:6px 8px;font-size:0.75rem}
        .cart-table th{background:#f9fafb;font-weight:600;color:#374151}
        .category-pill{padding:6px 16px;border-radius:9999px;font-size:13px;font-weight:500;background:#f5f5f4;color:#44403c;border:1px solid #e7e5e4;cursor:pointer;transition:all 0.2s}
        .category-pill:hover,.category-pill.active{background:#ea580c;color:white;border-color:#c2410c}
        .search-wrapper{position:relative}
        .search-input{width:100%;padding:6px 32px 6px 36px;border:1px solid #e7e5e4;border-radius:9999px;font-size:13px;color:#292524;background:white;transition:all 0.2s}
        .search-input:focus{outline:none;border-color:#ea580c;box-shadow:0 0 0 3px rgba(234,88,12,0.15)}
        .search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#78716c;font-size:14px;pointer-events:none}
        .search-clear{position:absolute;right:8px;top:50%;transform:translateY(-50%);background:#f5f5f4;border:none;width:20px;height:20px;border-radius:50%;color:#57534e;font-size:14px;cursor:pointer;display:none;align-items:center;justify-content:center}
        .search-clear:hover{background:#e7e5e4;color:#ea580c}
        .search-clear.active{display:flex}
        @keyframes admin-pulse{0%,100%{box-shadow:0 0 0 0 rgba(234,88,12,0.4)}50%{box-shadow:0 0 0 10px rgba(234,88,12,0)}}
        .admin-btn{animation:admin-pulse 2.5s infinite}
        #toast{position:fixed;bottom:20px;right:20px;background:#10b981;color:white;padding:10px 16px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);font-weight:500;font-size:13px;z-index:1000;transform:translateX(200%);transition:transform 0.3s ease;display:flex;align-items:center;gap:6px}
        #toast.show{transform:translateX(0)}
        @media print{body *{visibility:hidden}#printArea,#printArea *{visibility:visible}#printArea{position:absolute;left:0;top:0;width:58mm;padding:2mm;font-family:monospace;font-size:9pt}.no-print{display:none!important}}
        .dashed-line{border-top:1px dashed #000;margin:4px 0}
        .receipt-row{display:flex;justify-content:space-between;font-size:10pt}
        .receipt-row.bold{font-weight:700;font-size:11pt}
        .receipt-row.total{font-weight:700;font-size:12pt;margin-top:4px}
        .center{text-align:center}
        .right{text-align:right}
        .token-number{font-size:18pt;font-weight:800;letter-spacing:2px}
    </style>
</head>
<body class="bg-stone-50 text-stone-800 min-h-screen p-3 md:p-4">

<div id="toast" class="hidden"><span>✅</span><span id="toast-msg">Added!</span></div>

<header class="no-print max-w-7xl mx-auto mb-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-stone-200">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-brand-600 rounded-lg flex items-center justify-center text-white font-bold">☕</div>
            <div>
                <h1 class="text-lg font-bold text-stone-800">Rajasthan Mishthan Bhandar</h1>
                <p class="text-stone-500 text-xs">Token Generator</p>
            </div>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="search-wrapper flex-1 sm:flex-none">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" class="search-input" placeholder="Search..." onkeyup="filterItems()">
                <button class="search-clear" id="clearSearch" onclick="clearSearch()">×</button>
            </div>
            <a href="login.php" class="admin-btn no-print inline-flex items-center gap-1.5 bg-stone-800 hover:bg-stone-900 text-white font-medium py-2 px-3.5 rounded-lg transition shadow border border-stone-700 text-xs whitespace-nowrap">
                <i class="fas fa-th-large text-[10px]"></i> Admin
            </a>
        </div>
    </div>
    <div class="flex flex-wrap gap-1.5 mt-3">
        <span class="category-pill active" onclick="filterCategory('all')">All</span>
        <?php foreach($categories as $cat): ?>
            <span class="category-pill" onclick="filterCategory('<?=strtolower($cat['name'])?>')"><?=htmlspecialchars($cat['name'])?></span>
        <?php endforeach; ?>
    </div>
</header>

<div class="no-print max-w-7xl mx-auto">
    <div id="itemsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 md:gap-3">
        <?php foreach($items as $item): 
            $unitPrices = !empty($item['unit_prices']) ? json_decode($item['unit_prices'], true) : [];
            $catName = strtolower($item['category_name'] ?? '');
            $badge = '';
            if($catName === 'sweets') $badge = 'bg-pink-500';
            elseif(stripos($item['name'], 'chai') !== false) $badge = 'bg-brand-500';
            
            // ✅ BUILD DISPLAY UNITS WITH PLATE SUPPORT (Full/Half as dropdown options)
            $displayUnits = [];
            $hasPlateDropdown = false;
            
            foreach($unitPrices as $u => $p) { 
                $unitKey = strtolower($u); 
                $displayUnits[$unitKey] = floatval($p); 
            }
            
            // 🍽️ Check if plate has Full/Half prices → create dropdown
            if(isset($displayUnits['plt_full']) || isset($displayUnits['plt_half'])) {
                $hasPlateDropdown = true;
            }
            
            // Auto-add g if kg exists (and not plate dropdown)
            if(isset($displayUnits['kg']) && !isset($displayUnits['g']) && !$hasPlateDropdown) {
                $displayUnits['g'] = $displayUnits['kg'] / 1000;
            }
            
            // Default unit selection
            $defaultUnit = 'piece';
            if($hasPlateDropdown) {
                $defaultUnit = isset($displayUnits['plt_full']) ? 'plt_full' : 'plt_half';
            } elseif(!empty($displayUnits)) {
                $defaultUnit = array_key_first($displayUnits);
            }
            
            $isDecimal = in_array($defaultUnit, ['kg', 'plt_full', 'plt_half']);
        ?>
        <div class="menu-card bg-white rounded-lg shadow-sm border border-stone-100 item-card" 
             data-name="<?=strtolower($item['name'])?>" data-category="<?=$catName?>">
            
            <div class="img-container mb-2 cursor-pointer">
                <?php if(!empty($item['image'])): ?>
                    <img src="<?=htmlspecialchars($item['image'])?>" alt="<?=htmlspecialchars($item['name'])?>" onerror="this.parentElement.innerHTML='<span class=\'text-stone-400 text-[10px]\'>No Image</span>'">
                    <?php if($badge): ?><span class="absolute top-1 left-1 px-1.5 py-0.5 bg-brand-500 text-white text-[9px] rounded">Fresh</span><?php endif; ?>
                <?php else: ?>
                    <span class="text-stone-400 text-[10px]">No Image</span>
                <?php endif; ?>
            </div>
            
            <h3 class="item-title text-stone-800 truncate mb-0.5" title="<?=htmlspecialchars($item['name'])?>"><?=htmlspecialchars($item['name'])?></h3>
            <p class="item-category truncate"><?=htmlspecialchars($item['category_name'] ?? 'Uncategorized')?></p>
            
            <!-- ✅ UNIT SELECTOR WITH PLATE DROPDOWN SUPPORT -->
            <?php if($hasPlateDropdown): ?>
                <!-- 🍽️ Plate Dropdown: Full/Half as separate options -->
                <select id="unit-<?=$item['id']?>" class="unit-select" onchange="handleUnitChange(<?=$item['id']?>)">
                    <?php if(isset($displayUnits['plt_full'])): ?>
                        <option value="plt_full" data-price="<?=$displayUnits['plt_full']?>" data-unit-label="Full Plate" selected>
                            🍽️ Full Plate - ₹<?=number_format($displayUnits['plt_full'], 2)?>
                        </option>
                    <?php endif; ?>
                    <?php if(isset($displayUnits['plt_half'])): ?>
                        <option value="plt_half" data-price="<?=$displayUnits['plt_half']?>" data-unit-label="Half Plate" <?=!isset($displayUnits['plt_full'])?'selected':''?>>
                            🍽️ Half Plate - ₹<?=number_format($displayUnits['plt_half'], 2)?>
                        </option>
                    <?php endif; ?>
                </select>
                
            <?php elseif(count($displayUnits) > 1): ?>
                <!-- 📦 Normal multi-unit dropdown (kg, g, pcs, etc.) -->
                <select id="unit-<?=$item['id']?>" class="unit-select" onchange="handleUnitChange(<?=$item['id']?>)">
                    <?php foreach($displayUnits as $unit => $price): ?>
                        <option value="<?=$unit?>" data-price="<?=$price?>" data-unit-label="<?=strtoupper($unit)?>" <?=$unit === $defaultUnit ? 'selected' : ''?>>
                            <?=strtoupper($unit)?> - ₹<?=number_format($price, 3)?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
            <?php elseif(!empty($displayUnits)): ?>
                <!-- Single unit - show as badge -->
                <div class="flex flex-wrap gap-0.5 mt-1 min-h-[20px]">
                    <?php foreach($displayUnits as $unit => $price): ?>
                        <span class="unit-badge">₹<?=number_format($price,2)?>/<?=strtoupper(str_replace('plt_','',$unit))?></span>
                    <?php endforeach; ?>
                </div>
                
            <?php else: ?>
                <span class="text-[10px] text-red-500">No price</span>
            <?php endif; ?>
            
            <!-- Qty + Add Button -->
            <div class="flex items-center gap-1.5 mt-2">
                <div class="stepper">
                    <button class="stepper-btn" onclick="changeQty(<?=$item['id']?>,-1)">−</button>
                    <input type="number" id="qty-<?=$item['id']?>" 
                           value="1" 
                           step="1" 
                           min="1"
                           class="stepper-input"
                           readonly
                           oninput="validateManualInput(<?=$item['id']?>)">
                    <button class="stepper-btn" onclick="changeQty(<?=$item['id']?>,1)">+</button>
                </div>
                <button onclick='addToCart(<?=json_encode($item)?>, <?=json_encode($displayUnits)?>, <?=$hasPlateDropdown?'true':'false'?>)' class="add-btn flex-1">
                    <i class="fas fa-plus text-[10px]"></i> Add
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div id="noResults" class="hidden text-center py-8 text-stone-500">
        <i class="fas fa-search text-xl mb-2"></i>
        <p class="text-sm">No items found for "<span id="searchTerm"></span>"</p>
    </div>
</div>

<!-- Cart -->
<div class="no-print max-w-md mx-auto mt-6">
    <div class="bg-white rounded-lg shadow-sm border border-stone-100 p-3 md:p-4">
        <h2 class="font-bold text-stone-800 mb-3 flex items-center gap-2 text-sm"><i class="fas fa-shopping-cart text-brand-600"></i> Your Order</h2>
        <div class="overflow-x-auto rounded border border-stone-100 mb-3">
            <table class="w-full cart-table">
                <thead><tr><th class="text-left">Item</th><th class="text-right">Qty</th><th class="text-right">Amt</th><th></th></tr></thead>
                <tbody id="cart"><tr><td colspan="4" class="text-stone-400 text-center py-4 text-xs italic">Cart is empty</td></tr></tbody>
            </table>
        </div>
        <div class="border-t border-dashed border-stone-200 pt-3 mb-3">
            <div class="flex justify-between text-xs mb-1"><span class="text-stone-500">Items:</span><span class="font-medium" id="total-items">0</span></div>
            <div class="flex justify-between items-center"><span class="font-bold text-stone-800 text-sm">Total:</span><span class="font-bold text-brand-600">₹<span id="total">0.00</span></span></div>
        </div>
        <button onclick="generateToken()" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-medium py-2 px-3 rounded-lg text-sm transition flex items-center justify-center gap-2"><i class="fas fa-print"></i> Print Token</button>
        <p class="text-[10px] text-stone-400 text-center mt-2">• 58mm thermal printer •</p>
    </div>
</div>

<div id="printArea" class="hidden"></div>

<script>
let cart = [];

function showToast(msg){
    const toast = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    toast.classList.remove('hidden'); toast.classList.add('show');
    setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.classList.add('hidden'), 300); }, 2000);
}

function filterItems(){
    const term = document.getElementById('searchInput').value.toLowerCase().trim();
    document.getElementById('searchTerm').textContent = term;
    document.getElementById('clearSearch').classList.toggle('active', term.length > 0);
    const items = document.querySelectorAll('.item-card'); let visible = 0;
    items.forEach(item => {
        if(item.dataset.name.includes(term) || item.dataset.category.includes(term)) { item.style.display = 'block'; visible++; }
        else { item.style.display = 'none'; }
    });
    document.getElementById('noResults').classList.toggle('hidden', visible > 0 || !term);
    document.getElementById('itemsGrid').style.display = (visible === 0 && term) ? 'none' : 'grid';
}
function clearSearch(){ document.getElementById('searchInput').value = ''; document.getElementById('searchInput').dispatchEvent(new Event('input')); document.getElementById('searchInput').focus(); }

function filterCategory(cat){
    document.querySelectorAll('.category-pill').forEach(p => { p.classList.remove('active'); if(p.textContent.toLowerCase() === cat || (cat==='all' && p.textContent==='All')) p.classList.add('active'); });
    const items = document.querySelectorAll('.item-card'); let visible = 0;
    items.forEach(item => { if(cat === 'all' || item.dataset.category === cat) { item.style.display = 'block'; visible++; } else { item.style.display = 'none'; } });
    document.getElementById('noResults').classList.toggle('hidden', visible > 0);
    document.getElementById('itemsGrid').style.display = (visible === 0 && cat !== 'all') ? 'none' : 'grid';
}

// ===== ✅ UPDATED UNIT & QTY LOGIC WITH PLATE SUPPORT =====
function handleUnitChange(id){
    const unitSelect = document.getElementById('unit-'+id);
    const qtyInput = document.getElementById('qty-'+id);
    
    if(!unitSelect || !qtyInput) return;
    
    const selectedOpt = unitSelect.options[unitSelect.selectedIndex];
    const unit = selectedOpt.value;
    const unitLabel = selectedOpt.dataset.unitLabel || '';
    
    // 🍽️ Plate units (Full/Half) - readonly, qty = 1
    if(unit === 'plt_full' || unit === 'plt_half') {
        qtyInput.value = "1";
        qtyInput.step = "1";
        qtyInput.min = "1";
        qtyInput.setAttribute('readonly', 'readonly');
    }
    // 📦 Kg - decimal allowed, editable
    else if(unit === 'kg') {
        qtyInput.step = "0.01";
        qtyInput.min = "0.01";
        qtyInput.value = "1";
        qtyInput.removeAttribute('readonly');
    }
    // 📦 Gram - whole numbers, editable
    else if(unit === 'g') {
        qtyInput.step = "1";
        qtyInput.min = "1";
        qtyInput.value = "250";
        qtyInput.removeAttribute('readonly');
    }
    // 📦 Pieces/Other - readonly, qty = 1
    else {
        qtyInput.value = "1";
        qtyInput.step = "1";
        qtyInput.min = "1";
        qtyInput.setAttribute('readonly', 'readonly');
    }
}

function validateManualInput(id){
    const input = document.getElementById('qty-'+id);
    const unitSelect = document.getElementById('unit-'+id);
    
    if(!input || input.hasAttribute('readonly')) return;
    
    const unit = unitSelect ? unitSelect.value : '';
    let val = parseFloat(input.value);
    
    if(isNaN(val)) val = (unit === 'kg') ? 1 : 1;
    if(val < 0.01) val = 0.01;
    
    const minVal = (unit === 'kg') ? 0.01 : 1;
    const maxVal = (unit === 'kg') ? 100 : 10000;
    
    if(val < minVal) val = minVal;
    if(val > maxVal) val = maxVal;
    
    if(unit === 'kg'){
        input.value = (Math.round(val * 100) / 100).toFixed(2);
    } else {
        input.value = Math.round(val);
    }
}

function changeQty(id, val){
    const input = document.getElementById('qty-'+id);
    const unitSelect = document.getElementById('unit-'+id);
    
    if(!input || input.hasAttribute('readonly')) return;
    
    const unit = unitSelect ? unitSelect.value : '';
    let step = parseFloat(input.step) || 1;
    let v = parseFloat(input.value) || 1;
    
    v += step * val;
    let minVal = (unit === 'kg') ? 0.01 : 1;
    if(v < minVal) v = minVal;
    
    if(unit === 'kg'){
        input.value = (Math.round(v * 100) / 100).toFixed(2);
    } else {
        input.value = Math.round(v);
    }
}

// ===== ✅ UPDATED CART LOGIC WITH PLATE SUPPORT =====
function addToCart(item, unitPrices, hasPlateDropdown = false){
    const qtyInput = document.getElementById('qty-'+item.id);
    const qty = parseFloat(qtyInput.value) || 1;
    const unitSelect = document.getElementById('unit-'+item.id);
    
    let unit = 'piece', unitLabel = '', price = 0;
    
    if(unitSelect && unitSelect.options.length > 0){
        const opt = unitSelect.options[unitSelect.selectedIndex];
        unit = opt.value;                    // plt_full, plt_half, kg, g, pcs
        unitLabel = opt.dataset.unitLabel || ''; // Full Plate, Half Plate, KG, etc.
        price = parseFloat(opt.dataset.price) || 0;
    } else {
        // Fallback for single unit items
        const firstUnit = Object.keys(unitPrices)[0];
        unit = firstUnit || 'piece';
        unitLabel = firstUnit ? firstUnit.toUpperCase() : 'Piece';
        price = unitPrices[unit] || 0;
    }
    
    // 🍽️ Format unit label for display
    let displayUnit = unitLabel;
    if(!displayUnit) {
        if(unit === 'plt_full') displayUnit = 'Full Plate';
        else if(unit === 'plt_half') displayUnit = 'Half Plate';
        else if(unit === 'kg') displayUnit = 'Kg';
        else if(unit === 'g') displayUnit = 'Gram';
        else if(unit === 'pcs') displayUnit = 'Pcs';
        else displayUnit = unit.toUpperCase();
    }
    
    // Check if same item + same unit already in cart
    const exist = cart.find(i => i.id == item.id && i.unit == unit);
    if(exist){
        exist.qty += qty;
        exist.amount = exist.qty * exist.price;
    } else {
        cart.push({ 
            id: item.id, 
            name: item.name, 
            qty: qty, 
            unit: unit,              // plt_full, plt_half, kg, etc.
            unitLabel: displayUnit,  // Full Plate, Half Plate, Kg, etc.
            price: price, 
            amount: qty * price,
            category: item.category_name 
        });
    }
    
    renderCart(); 
    showToast(`✓ ${item.name} (${displayUnit}) added`);
    
    // Reset qty to 1 after adding (for plate items)
    if(qtyInput) {
        if(unit === 'plt_full' || unit === 'plt_half' || unit === 'pcs') {
            qtyInput.value = "1";
        }
    }
}

function renderCart(){
    const tbody = document.getElementById('cart'), 
          totalEl = document.getElementById('total'), 
          totalItemsEl = document.getElementById('total-items');
    
    if(cart.length === 0){ 
        tbody.innerHTML = '<tr><td colspan="4" class="text-stone-400 text-center py-4 text-xs italic">Cart is empty</td></tr>'; 
        totalEl.textContent = '0.00'; 
        totalItemsEl.textContent = '0'; 
        return; 
    }
    
    let html = '', total = 0;
    cart.forEach(i => { 
        total += i.amount;
        
        // Format quantity with unit for cart display
        let qtyDisplay = '';
        if(i.unit === 'kg') qtyDisplay = i.qty.toFixed(2) + ' kg';
        else if(i.unit === 'g') qtyDisplay = i.qty + ' g';
        else if(i.unit === 'plt_full') qtyDisplay = i.qty + ' Full';
        else if(i.unit === 'plt_half') qtyDisplay = i.qty + ' Half';
        else qtyDisplay = i.qty + ' ' + (i.unitLabel || i.unit).toLowerCase();
        
        html += `<tr class="border-b border-stone-50 last:border-0">
            <td class="text-stone-700 truncate max-w-[100px]" title="${i.name}">${i.name}</td>
            <td class="text-right text-stone-600">${qtyDisplay}</td>
            <td class="text-right font-medium text-stone-800">₹${i.amount.toFixed(2)}</td>
            <td class="text-right"><button onclick="removeItem(${i.id},'${i.unit}')" class="text-red-500 hover:text-red-700 text-xs">×</button></td>
        </tr>`; 
    });
    
    tbody.innerHTML = html; 
    totalEl.textContent = total.toFixed(2); 
    totalItemsEl.textContent = cart.length;
}

function removeItem(id, unit){
    const item = cart.find(i => i.id == id && i.unit == unit); 
    cart = cart.filter(i => !(i.id == id && i.unit == unit)); 
    renderCart(); 
    if(item) showToast(`✕ Removed ${item.name} (${item.unitLabel || unit})`); 
}

// ===== PRINT + DATABASE SAVE =====
function generateToken(){
    if(cart.length === 0){ showToast("⚠️ Cart is empty"); return; }
    
    const token = Math.floor(1000 + Math.random() * 9000), 
          total = cart.reduce((a,b) => a + b.amount, 0), 
          now = new Date();
    const dateStr = now.toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'}), 
          timeStr = now.toLocaleTimeString('en-IN', {hour:'2-digit', minute:'2-digit'});
    
    // === SAVE TO DATABASE ===
    const formData = new URLSearchParams();
    formData.append('action', 'save_order');
    formData.append('token', token);
    formData.append('total', total.toFixed(2));
    formData.append('cart', JSON.stringify(cart));
    
    fetch('lorus_includes/class/ajax.php', { 
        method: 'POST', 
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
        body: formData 
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) console.log('✓ Order saved:', data.order_id);
        else console.warn('⚠ Save issue:', data.message);
    })
    .catch(err => console.error('✗ Save error:', err));
    // === END SAVE ===
    
    // Build receipt
    let html = `<div style="font-family:monospace; width:58mm; padding:2mm;">
        <div class="center" style="margin-bottom:4px;"><div style="font-weight:800; font-size:11pt;">☕ RAJASTHAN MISHTHAN</div><div style="font-size:8pt;">Token Generator</div></div>
        <div class="dashed-line"></div>
        <div class="center" style="margin:4px 0;"><div style="background:#000;color:#fff;padding:6px 4px;border-radius:4px;margin:4px 0;"><div style="font-size:9pt;">TOKEN #</div><div class="token-number">${token}</div></div><div style="font-size:8pt;">${dateStr} • ${timeStr}</div></div>
        <div class="dashed-line"></div>
        <div class="receipt-row bold" style="margin:4px 0;"><div>Item</div><div class="right">Qty</div></div>
        <div style="border-top:1px dotted #000; margin:4px 0;"></div>`;
    
    cart.forEach(i => { 
        // Format quantity with unit for receipt
        let qtyStr = '';
        if(i.unit === 'kg') qtyStr = i.qty.toFixed(2) + 'kg';
        else if(i.unit === 'g') qtyStr = i.qty + 'g';
        else if(i.unit === 'plt_full') qtyStr = i.qty + ' Full';
        else if(i.unit === 'plt_half') qtyStr = i.qty + ' Half';
        else qtyStr = i.qty + ' ' + (i.unitLabel || i.unit).toLowerCase().slice(0,3);
        
        html += `<div class="receipt-row" style="margin:3px 0;">
            <div style="max-width:65%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:9pt;">${i.name}</div>
            <div class="right" style="font-size:9pt;">${qtyStr}</div>
        </div>
        <div style="font-size:7pt;color:#666;margin-top:-2px;margin-bottom:2px;padding-left:2px;">₹${i.amount.toFixed(2)}</div>`; 
    });
    
    html += `<div class="dashed-line" style="margin:6px 0 4px;"></div>
        <div class="receipt-row total" style="margin:4px 0 2px;"><div>GRAND TOTAL</div><div class="right">₹${total.toFixed(2)}</div></div>
        <div style="font-size:8pt;text-align:center;margin:2px 0;">${cart.length} items</div>
        <div class="dashed-line" style="margin:6px 0;"></div>
        <div class="center" style="font-size:8pt; margin:4px 0;"><div style="font-weight:600;">Thank you! Visit again ☕</div><div style="font-size:7pt;opacity:0.7;">*** Computer generated ***</div></div></div>`;
    
    const p = document.getElementById('printArea'); 
    p.innerHTML = html; p.classList.remove('hidden');
    
    setTimeout(() => { 
        window.print(); 
        setTimeout(() => { 
            cart = []; renderCart(); p.classList.add('hidden'); 
            document.querySelectorAll('.stepper-input').forEach(inp => {
                const id = inp.id.replace('qty-','');
                const unitSel = document.getElementById('unit-'+id);
                if(unitSel) handleUnitChange(id); else inp.value = '1';
            });
        }, 500); 
    }, 200);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => { 
    renderCart(); 
    // Initialize all unit selectors
    document.querySelectorAll('[id^="unit-"]').forEach(sel => {
        handleUnitChange(sel.id.replace('unit-',''));
    });
});
</script>
</body>
</html>