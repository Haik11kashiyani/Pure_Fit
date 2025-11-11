<?php
include('../connection.php');
$product_id = (int)($_GET['product_id'] ?? 0);
$vres = mysqli_query($conn, "SELECT * FROM product_variants WHERE product_id = $product_id");
$data = [];
while ($v = mysqli_fetch_assoc($vres)) $data[] = $v;
header('Content-type: application/json');
echo json_encode($data);
exit;
?>
