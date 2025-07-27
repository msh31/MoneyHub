<?php
require "db/dbconnection.class.php";

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemName'])) {
    try {
        $dbconnect = new dbconnection();
        
        // Sanitize and validate input
        $name = trim($_POST['itemName']);
        $amount = floatval($_POST['amount']);
        $type = intval($_POST['type']);
        $category = intval($_POST['category']);
        $period = intval($_POST['period']);
        
        // Basic validation
        if (empty($name)) {
            throw new Exception("Item name is required");
        }
        
        if ($amount <= 0) {
            throw new Exception("Amount must be greater than 0");
        }
        
        if (!in_array($type, [0, 1])) {
            throw new Exception("Invalid transaction type");
        }
        
        // Use prepared statement to prevent SQL injection
        $sql = "INSERT INTO items (item_name, amount, type, category_id, period_id) VALUES (?, ?, ?, ?, ?)";
        $query = $dbconnect->prepare($sql);
        $query->execute([$name, $amount, $type, $category, $period]);
        
        // Redirect with success message
        header('Location: index.php?added=true');
        exit();
        
    } catch (Exception $e) {
        // Log error and redirect with error message
        error_log("Transaction insert error: " . $e->getMessage());
        header('Location: index.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If accessed directly without POST data, redirect to main page
    header('Location: index.php');
    exit();
}
?>