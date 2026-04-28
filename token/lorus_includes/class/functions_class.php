<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Admin {

    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "token_system");
        if ($this->conn->connect_error) {
            die("Connection Failed: " . $this->conn->connect_error);
        }
    }

    // ================= LOGIN =================
    public function login($username, $password) {
        $username = $this->conn->real_escape_string($username);
        $query = "SELECT * FROM admins WHERE username='$username'";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_name'] = $row['username'];
                return json_encode(["status"=>"success"]);
            } else {
                return json_encode(["status"=>"error","message"=>"Wrong Password"]);
            }
        } else {
            return json_encode(["status"=>"error","message"=>"User Not Found"]);
        }
    }

    // ================= CATEGORY CRUD FUNCTIONS =================

    // Add Category
    public function addCategory($name, $status) {
        $stmt = $this->conn->prepare("INSERT INTO categories (name, status) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $status);
        if($stmt->execute()){
            return json_encode(["status"=>"success","message"=>"Category Added"]);
        }
        return json_encode(["status"=>"error","message"=>"Failed to add category"]);
    }

    // Get Categories (Optionally Active Only)
    public function getCategories($onlyActive = true) {
        $sql = "SELECT * FROM categories";
        if($onlyActive){
            $sql .= " WHERE status='Active'";
        }
        $sql .= " ORDER BY id DESC";

        $result = $this->conn->query($sql);
        $categories = [];
        while($row = $result->fetch_assoc()){
            $categories[] = $row;
        }
        return json_encode($categories);
    }

    // Update Category
    public function updateCategory($id, $name, $status) {
        $stmt = $this->conn->prepare("UPDATE categories SET name=?, status=? WHERE id=?");
        $stmt->bind_param("ssi",$name,$status,$id);
        if($stmt->execute()){
            return json_encode(["status"=>"success","message"=>"Category Updated"]);
        }
        return json_encode(["status"=>"error","message"=>"Failed to update category"]);
    }

    // Soft Delete Category (mark as Inactive)
    public function deleteCategory($id) {
        $stmt = $this->conn->prepare("UPDATE categories SET status='Inactive' WHERE id=?");
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            return json_encode(["status"=>"success","message"=>"Category marked as Inactive"]);
        }
        return json_encode(["status"=>"error","message"=>"Failed to mark category Inactive"]);
    }

  // ================= ITEM CRUD FUNCTIONS =================
public function addItem($category_id, $name, $unit_prices, $default_unit, $status, $imageFile){

    $imagePath = null;

    if($imageFile && isset($imageFile['tmp_name']) && $imageFile['tmp_name']){

        $ext = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $newName = "item_" . time() . "." . $ext;

        $uploadDir = __DIR__ . "/../../uploads/items/";
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if(move_uploaded_file($imageFile['tmp_name'], $uploadDir . $newName)){
            $imagePath = "uploads/items/" . $newName;
        }
    }

    $stmt = $this->conn->prepare("
        INSERT INTO items 
        (category_id, name, unit_prices, default_unit, image, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "isssss",
        $category_id,
        $name,
        $unit_prices,
        $default_unit,
        $imagePath,
        $status
    );

    if($stmt->execute()){
        return json_encode([
            "status" => "success",
            "message" => "Item Added Successfully"
        ]);
    }

    return json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}

public function editItem($id, $category_id, $name, $unit_prices, $default_unit, $status, $imageFile = null){

    $currentItem = json_decode($this->getItem($id), true);
    $imagePath = $currentItem['image'] ?? null;

    if($imageFile && isset($imageFile['tmp_name']) && $imageFile['tmp_name']){

        $ext = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $newName = "item_" . time() . "." . $ext;

        $uploadDir = __DIR__ . "/../../uploads/items/";
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if(move_uploaded_file($imageFile['tmp_name'], $uploadDir . $newName)){
            $imagePath = "uploads/items/" . $newName;
        }
    }

    if($imagePath){

        $stmt = $this->conn->prepare("
            UPDATE items 
            SET category_id=?, name=?, unit_prices=?, default_unit=?, status=?, image=?
            WHERE id=?
        ");

        $stmt->bind_param(
            "isssssi",
            $category_id,
            $name,
            $unit_prices,
            $default_unit,
            $status,
            $imagePath,
            $id
        );

    } else {

        $stmt = $this->conn->prepare("
            UPDATE items 
            SET category_id=?, name=?, unit_prices=?, default_unit=?, status=?
            WHERE id=?
        ");

        $stmt->bind_param(
            "issssi",
            $category_id,
            $name,
            $unit_prices,
            $default_unit,
            $status,
            $id
        );
    }

    if($stmt->execute()){
        return json_encode([
            "status"=>"success",
            "message"=>"Item Updated Successfully"
        ]);
    }

    return json_encode([
        "status"=>"error",
        "message"=>$stmt->error
    ]);
}

public function getItems(){

    $query = "
        SELECT 
            i.id,
            i.category_id,
            i.name,
            i.unit_prices,
            i.default_unit,
            i.image,
            i.status,
            c.name AS category_name
        FROM items i
        JOIN categories c ON i.category_id = c.id
        WHERE i.status = 'Active'   -- ✅ yahi add karna hai
        ORDER BY i.id DESC
    ";

    $result = $this->conn->query($query);
    $items = [];

    while($row = $result->fetch_assoc()){
        $items[] = $row;
    }

    return json_encode($items);
}

public function getItem($id){
    $stmt = $this->conn->prepare("SELECT * FROM items WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $item = $res->fetch_assoc();
    return json_encode($item);
}

public function deleteItem($id){
    $stmt = $this->conn->prepare("UPDATE items SET status='Inactive' WHERE id=?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        return ["status"=>"success","message"=>"Item marked as Inactive"];
    } else {
        return ["status"=>"error","message"=>$stmt->error];
    }
}

      // ================= ORDER FUNCTIONS =================
    public function addOrder($token, $totalAmount, $totalItems){
        $stmt = $this->conn->prepare("INSERT INTO orders (token_number, total_amount, total_items, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sdi", $token, $totalAmount, $totalItems);
        if($stmt->execute()) return $stmt->insert_id;
        return false;
    }

    public function addOrderItem($orderId, $itemId, $itemName, $price, $qty, $total){
        $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, item_id, item_name, price, qty, total) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisddd", $orderId, $itemId, $itemName, $price, $qty, $total);
        return $stmt->execute();
    }

        // ================= FETCH ORDERS =================
    public function getOrders(){
        $res = $this->conn->query("SELECT id, token_number, total_amount, total_items, created_at FROM orders ORDER BY id DESC");
        $orders = [];
        while($row = $res->fetch_assoc()){
            $orders[] = $row;
        }
        return $orders;
    }

    public function getOrderItems($orderId){
        $stmt = $this->conn->prepare("SELECT id, order_id, item_id, item_name, price, qty, total FROM order_items WHERE order_id=?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $res = $stmt->get_result();
        $items = [];
        while($row = $res->fetch_assoc()){
            $items[] = $row;
        }
        return $items;
    }


    // ================= DASHBOARD STATS =================

public function getTotalSales($date = null) {
    $stmt = $this->conn->prepare("SELECT SUM(total_amount) AS total FROM orders WHERE DATE(created_at)=?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
}

public function getTotalTokens($date = null) {
    $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM orders WHERE DATE(created_at)=?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
}

public function getTotalItems() {
    $res = $this->conn->query("SELECT COUNT(*) AS total FROM items");
    $row = $res->fetch_assoc();
    return $row['total'] ?? 0;
}

public function getTotalCategories() {
    $res = $this->conn->query("SELECT COUNT(*) AS total FROM categories");
    $row = $res->fetch_assoc();
    return $row['total'] ?? 0;
}


public function getRecentTransactions($limit = 10, $date = null) {

    if($date){
        $stmt = $this->conn->prepare("
            SELECT 
                token_number AS token_id, 
                total_items AS items, 
                total_amount AS amount, 
                'Completed' AS status
            FROM orders
            WHERE DATE(created_at) = ?
            ORDER BY id DESC
            LIMIT ?
        ");
        $stmt->bind_param("si", $date, $limit);

    } else {
        // fallback (agar date na mile)
        $stmt = $this->conn->prepare("
            SELECT 
                token_number AS token_id, 
                total_items AS items, 
                total_amount AS amount, 
                'Completed' AS status
            FROM orders
            ORDER BY id DESC
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
    }

    $stmt->execute();
    $res = $stmt->get_result();

    $transactions = [];
    while($row = $res->fetch_assoc()){
        $transactions[] = $row;
    }

    return $transactions;
}



    // ================= SESSION & LOGOUT =================
    public function checkLogin() {
        if(!isset($_SESSION['admin_id'])){
            header("Location: ../login.php");
            exit();
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: ../login.php");
        exit();
    }

    // ================= REPORT FUNCTIONS =================

public function getReportTotalSales($from, $to){
    $stmt = $this->conn->prepare("SELECT SUM(total_amount) as total FROM orders WHERE DATE(created_at) BETWEEN ? AND ?");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
}

public function getReportTotalTokens($from, $to){
    $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) BETWEEN ? AND ?");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
}

public function getReportItemsSold($from, $to){
    $stmt = $this->conn->prepare("SELECT SUM(total_items) as total FROM orders WHERE DATE(created_at) BETWEEN ? AND ?");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'] ?? 0;
}

public function getReportTopItems($from, $to){
    $stmt = $this->conn->prepare("
        SELECT oi.item_name, SUM(oi.qty) as qty, SUM(oi.total) as revenue
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        WHERE DATE(o.created_at) BETWEEN ? AND ?
        GROUP BY oi.item_name
        ORDER BY qty DESC
        LIMIT 10
    ");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();

    $res = $stmt->get_result();
    $data = [];
    while($row = $res->fetch_assoc()){
        $data[] = $row;
    }
    return $data;
}
public function getFilteredTransactions($from, $to, $type = 'month') {

    if($type == 'day'){
        $where = "DATE(created_at) BETWEEN ? AND ?";
    } else {
        // Month wise → pura month ka data
        $where = "DATE_FORMAT(created_at, '%Y-%m') BETWEEN DATE_FORMAT(?, '%Y-%m') AND DATE_FORMAT(?, '%Y-%m')";
    }

    $stmt = $this->conn->prepare("
        SELECT 
            token_number AS token_id,
            total_items AS items,
            total_amount AS amount,
            created_at AS date
        FROM orders
        WHERE $where
        ORDER BY id DESC
    ");

    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();

    $res = $stmt->get_result();
    $data = [];

    while($row = $res->fetch_assoc()){
        $data[] = $row;
    }

    return $data;
}
// ================= ORDER ITEM WITH UNIT =================
public function addOrderItemWithUnit($orderId, $itemId, $itemName, $unit, $price, $qty, $total){
    // Check if 'unit' column exists, fallback if not
    $hasUnit = true; // assuming you ran the ALTER TABLE above
    
    if($hasUnit){
        $stmt = $this->conn->prepare("
            INSERT INTO order_items 
            (order_id, item_id, item_name, unit, price, qty, total) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iissddd", $orderId, $itemId, $itemName, $unit, $price, $qty, $total);
    } else {
        // Fallback for old schema (ignores unit)
        $stmt = $this->conn->prepare("
            INSERT INTO order_items 
            (order_id, item_id, item_name, price, qty, total) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisddd", $orderId, $itemId, $itemName, $price, $qty, $total);
    }
    
    return $stmt->execute();
}
}
?>