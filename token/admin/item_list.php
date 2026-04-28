<?php 
session_start();
include("include/sidebar.php"); 
include("../lorus_includes/class/functions_class.php");

$admin = new Admin();
$items = json_decode($admin->getItems(), true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventory</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
body { font-family: 'Poppins', sans-serif; }
.item-card-img { width: 100%; height: 180px; object-fit: cover; }
</style>
</head>

<body class="bg-gray-100">

<div class="p-6">

<h1 class="text-2xl font-bold mb-6">Menu Inventory</h1>

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

<?php if(!empty($items)) { ?>
<?php foreach($items as $item) { 

    // Status color
    $statusColor = "green-500";
    if($item['status'] == "Inactive") $statusColor = "red-500";

    // Image fix
    $imagePath = !empty($item['image']) ? "../".$item['image'] : "https://via.placeholder.com/400x300";
?>

<div class="bg-white rounded shadow p-3">

    <!-- Image -->
    <div class="relative">
        <img src="<?= $imagePath ?>" class="item-card-img rounded">

        <span class="absolute top-2 left-2 bg-<?= $statusColor ?> text-white text-xs px-2 py-1 rounded">
            <?= $item['status'] ?>
        </span>

        <button onclick="confirmDelete('<?= $item['name'] ?>', <?= $item['id'] ?>)"
        class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded">
        Delete
        </button>
    </div>

    <!-- Content -->
    <div class="mt-3">
        <h2 class="font-bold"><?= $item['name'] ?></h2>

        <p class="text-sm text-gray-500">
            Category: <?= $item['category_name'] ?>
        </p>
    </div>

</div>

<?php } ?>
<?php } else { ?>
<p>No items found</p>
<?php } ?>

</div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
<div class="bg-white p-6 rounded shadow text-center">

<h2 class="text-lg font-bold mb-4">Delete Item?</h2>
<p id="delete-item-name"></p>

<div class="mt-4 flex gap-4">
<button onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
<button id="confirm-delete" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
</div>

</div>
</div>

<script>
let deleteItemId = null;

function confirmDelete(name, id){
    document.getElementById("delete-item-name").innerText = name;
    document.getElementById("delete-modal").classList.remove("hidden");
    deleteItemId = id;
}

function closeModal(){
    document.getElementById("delete-modal").classList.add("hidden");
}

document.getElementById("confirm-delete").addEventListener("click", function(){
    fetch("delete-item.php?id=" + deleteItemId)
    .then(res => res.text())
    .then(data => {
        alert("Deleted Successfully");
        location.reload();
    });
});
</script>

</body>
</html>