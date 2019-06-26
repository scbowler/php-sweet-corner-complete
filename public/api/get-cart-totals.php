<?php
require_once('_setup.php');
$output = [
    'success' => false
];

if($_SESSION['cart-id']){
    $cart_id = $_SESSION['cart-id'];
} else {
    throw new ApiException($output, 422, 'No currently active cart');
}

$query = "SELECT SUM(ci.quantity) AS total_items, SUM(p.cost * ci.quantity) AS total_cost FROM `cart_items` AS `ci` JOIN `products` AS `p` ON `ci`.product_id=`p`.id WHERE `ci`.cart_id=$cart_id";

if($result = $conn->query($query)){
    $output['success'] = true;
    $output['cart'] = null;

    if($result->num_rows){
        $cart = $result->fetch_assoc();
        
        $output['cart'] = [
            'total_items' => (int) $cart['total_items'],
            'total_cost' => (int) $cart['total_cost']
        ];
    }
} else {
    throw new ApiException($output, 500, 'Error getting cart data');
}

print json_encode($output);
