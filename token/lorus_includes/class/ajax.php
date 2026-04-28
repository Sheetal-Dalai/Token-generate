<?php
include_once("../class/functions_class.php");

$admin = new Admin();

if(isset($_POST['action'])){

    switch($_POST['action']){

        // ================= LOGIN =================
        case "login":
            $username = $_POST['username'];
            $password = $_POST['password'];
            echo $admin->login($username, $password);
            break;

        // ================= CATEGORY ADD =================
        case "add_category":
            $name   = $_POST['name'];
            $status = $_POST['status'];
            echo $admin->addCategory($name, $status);
            break;

        // ================= CATEGORY LIST =================
        case "get_categories":
            echo $admin->getCategories();
            break;

        // ================= CATEGORY DELETE =================
        case "delete_category":
            $id = $_POST['id'];
            echo $admin->deleteCategory($id);
            break;

        // ================= CATEGORY UPDATE =================
        case "update_category":
            $id     = $_POST['id'];
            $name   = $_POST['name'];
            $status = $_POST['status'];
            echo $admin->updateCategory($id, $name, $status);
            break;

      // ================= ITEM ADD =================
  case "add_item":

    $category_id = $_POST['category_id'] ?? '';
    $name        = $_POST['name'] ?? '';
    $status      = $_POST['status'] ?? 'Active';

    $unit_prices = $_POST['unit_prices'] ?? '{}';
    $default_unit = $_POST['default_unit'] ?? '';

    $image = $_FILES['image'] ?? null;

    echo $admin->addItem(
        $category_id,
        $name,
        $unit_prices,
        $default_unit,
        $status,
        $image
    );
    break;

    // ================= ITEM LIST =================
    case "get_items":
        echo $admin->getItems();
        break;

    // ================= ITEM EDIT =================
   case "edit_item":

    $id          = $_POST['id'] ?? 0;
    $category_id = $_POST['category_id'] ?? '';
    $name        = $_POST['name'] ?? '';
    $status      = $_POST['status'] ?? 'Active';

    $unit_prices = $_POST['unit_prices'] ?? '{}';
    $default_unit = $_POST['default_unit'] ?? '';

    $image = $_FILES['image'] ?? null;

    echo $admin->editItem(
        $id,
        $category_id,
        $name,
        $unit_prices,
        $default_unit,
        $status,
        $image
    );
    break; 

    // ================= ITEM DELETE =================
    case "delete_item":
        $id = $_POST['id'];
        echo $admin->deleteItem($id);
        break;

    // ================= ORDER SAVE =================
    // ================= ORDER SAVE =================
case "save_order":
    $cart = json_decode($_POST['cart'], true);
    if(!$cart || count($cart)===0){
        echo json_encode(['status'=>'error','message'=>'Cart is empty']); exit;
    }

    $token = $_POST['token'] ?? '';
    $totalAmount = floatval($_POST['total'] ?? 0);
    
    // Calculate total items (count of line items, not qty sum)
    $totalItems = count($cart);

    // Insert order header
    $orderId = $admin->addOrder($token, $totalAmount, $totalItems);
    if(!$orderId){ 
        echo json_encode(['status'=>'error','message'=>'Order insert failed']); exit; 
    }

    // Insert each order item WITH unit support
    foreach($cart as $item){
        $itemId = intval($item['id'] ?? $item['item_id'] ?? 0);
        $itemName = $item['name'] ?? $item['item_name'] ?? 'Unknown';
        $unit = strtolower($item['unit'] ?? 'piece'); // 'kg', 'g', or 'piece'
        $qty = floatval($item['qty'] ?? 0);
        $price = floatval($item['price'] ?? $item['price_per_unit'] ?? 0);
        $total = floatval($item['amount'] ?? $item['line_total'] ?? ($price * $qty));
        
        // Use your existing addOrderItem but pass unit via a wrapper
        $admin->addOrderItemWithUnit($orderId, $itemId, $itemName, $unit, $price, $qty, $total);
    }

    echo json_encode(['status'=>'success','order_id'=>$orderId, 'token'=>$token]);
    break;

          // ================= FETCH ORDERS =================
        case "get_orders":
            $orders = $admin->getOrders();
            echo json_encode(['status'=>'success','orders'=>$orders]);
            break;    

        
        // ================= DASHBOARD STATS =================
        case "get_dashboard_stats":
            $data = [
                'total_sales' => $admin->getTotalSales(),
                'total_tokens' => $admin->getTotalTokens(),
                'total_items' => $admin->getTotalItems(),
                'total_categories' => $admin->getTotalCategories(),
                'recent_transactions' => $admin->getRecentTransactions()
              
            ];
            echo json_encode(['status'=>'success','data'=>$data]);
            break;

        case "get_reports":

            $from = $_POST['from_date'] ?? date('Y-m-d');
            $to   = $_POST['to_date'] ?? date('Y-m-d');

            $total_sales  = $admin->getReportTotalSales($from, $to);
            $total_tokens = $admin->getReportTotalTokens($from, $to);
            $total_items  = $admin->getReportItemsSold($from, $to);
            $top_items    = $admin->getReportTopItems($from, $to);

            echo json_encode([
                "status" => "success",
                "total_sales" => $total_sales,
                "total_tokens" => $total_tokens,
                "total_items" => $total_items,
                "avg_order" => $total_tokens > 0 ? $total_sales / $total_tokens : 0,
                "top_items" => $top_items
            ]);

        break;    
        // ================= DEFAULT =================
        default:
            echo json_encode(["status"=>"error","message"=>"Invalid Action"]);
            break;
         
            
    }

}
?>