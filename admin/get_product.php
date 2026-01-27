<?php
include('../connection.php');

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);

    if ($product = mysqli_fetch_assoc($result)) {
        // Handle missing subcategory_id (if column not present or null)
        if (!array_key_exists('subcategory_id', $product)) {
            $product['subcategory_id'] = null;
        }
        header('Content-Type: application/json');
        echo json_encode($product);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No product ID provided']);
}
?>
