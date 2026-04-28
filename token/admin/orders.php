<?php
include("../lorus_includes/class/functions_class.php");
$admin = new Admin();
$orders = $admin->getOrders();
?>

<?php include("include/sidebar.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders - Morning Brew</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body { font-family: 'Poppins', sans-serif; }
    .orange-btn { background-color: #ea580c; color: white; }
    .orange-btn:hover { background-color: #c94a09; }
    .orange-text { color: #ea580c; }

    .sidebar-wrapper { width: 256px; flex-shrink: 0; }
    .main-wrapper { flex: 1; min-width: 0; }
  </style>
</head>

<body class="bg-gray-100 text-stone-800 flex min-h-screen">

<!-- Sidebar -->
<div class="sidebar-wrapper h-screen sticky top-0 overflow-y-auto bg-white border-r">
  <?php // sidebar include ?>
</div>

<!-- Main -->
<main class="main-wrapper flex flex-col h-screen overflow-hidden">

<div class="flex-1 overflow-y-auto p-4 md:p-8">

  <!-- Header -->
  <div class="mb-6 flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-bold orange-text">Orders</h1>
      <p class="text-sm text-stone-600">All orders with items details</p>
    </div>

    <button onclick="location.reload()" class="orange-btn px-4 py-2 rounded-lg text-sm">
      <i class="fas fa-sync-alt"></i> Refresh
    </button>
  </div>

  <!-- Table -->
  <div class="bg-white rounded-xl shadow border p-4 overflow-x-auto">

    <table class="min-w-full text-sm border-collapse">

      <thead class="bg-stone-100">
        <tr>
          <th class="px-4 py-3 text-left">S.No</th>
          <th class="px-4 py-3 text-left">Token</th>
          <th class="px-4 py-3 text-left">Total Items</th>
          <th class="px-4 py-3 text-left">Total Amount</th>
          <th class="px-4 py-3 text-left">Item Name</th>
          <th class="px-4 py-3 text-left">Price</th>
          <th class="px-4 py-3 text-left">Qty</th>
          <th class="px-4 py-3 text-left">Total</th>
        </tr>
      </thead>

      <tbody>

      <?php if(empty($orders)): ?>
        <tr>
          <td colspan="8" class="text-center py-8 text-stone-500">
            <i class="fas fa-inbox text-3xl mb-2"></i><br>
            No orders found
          </td>
        </tr>

      <?php else: ?>

        <?php $sn = 1; foreach($orders as $order): ?>

        <?php
          $items = $admin->getOrderItems($order['id']);
          $rowCount = !empty($items) ? count($items) : 1;
          $first = true;
        ?>

        <?php if(!empty($items)): ?>

            <?php foreach($items as $item): ?>
            <tr class="border-b hover:bg-stone-50">

              <?php if($first): ?>
              <td class="px-4 py-3" rowspan="<?= $rowCount ?>">
                <?= $sn++ ?>
              </td>

              <td class="px-4 py-3" rowspan="<?= $rowCount ?>">
                <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-semibold">
                  #<?= htmlspecialchars($order['token_number']) ?>
                </span>
              </td>

              <td class="px-4 py-3" rowspan="<?= $rowCount ?>">
                <?= (int)$order['total_items'] ?>
              </td>

              <td class="px-4 py-3 font-semibold" rowspan="<?= $rowCount ?>">
                ₹<?= number_format($order['total_amount'],2) ?>
              </td>
              <?php $first = false; endif; ?>

              <!-- ITEM DATA -->
              <td class="px-4 py-3">
                <?= htmlspecialchars($item['item_name']) ?>
              </td>

              <td class="px-4 py-3">
                ₹<?= number_format($item['price'],2) ?>
              </td>

              <td class="px-4 py-3">
                <?= (int)$item['qty'] ?>
              </td>

              <td class="px-4 py-3 font-semibold">
                ₹<?= number_format($item['total'],2) ?>
              </td>

            </tr>
            <?php endforeach; ?>

        <?php else: ?>

            <tr class="border-b">
              <td class="px-4 py-3"><?= $sn++ ?></td>
              <td class="px-4 py-3">#<?= htmlspecialchars($order['token_number']) ?></td>
              <td class="px-4 py-3"><?= (int)$order['total_items'] ?></td>
              <td class="px-4 py-3 font-semibold">₹<?= number_format($order['total_amount'],2) ?></td>
              <td colspan="4" class="text-center text-stone-400">
                No items
              </td>
            </tr>

        <?php endif; ?>

        <?php endforeach; ?>

      <?php endif; ?>

      </tbody>

    </table>

  </div>

</div>
</main>

</body>
</html>