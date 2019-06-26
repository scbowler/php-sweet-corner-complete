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

$query = "SELECT p.name, p.cost, ci.quantity, p.cost * ci.quantity AS total FROM cart_items AS ci JOIN products AS p ON ci.product_id=p.id WHERE ci.cart_id=$cart_id";

if($result = $conn->query($query)){
    $output['success'] = true;
    $output['items'] = null;
    $output['cart'] = null;

    if($result->num_rows){
        $total_cost = 0;
        $total_items = 0;

        while($row = $result->fetch_assoc()){
            $quantity = (int) $row['quantity'];
            $total = (int) $row['total'];
            
            $output['items'][] = [
                'cost' => (int) $row['cost'],
                'name' => $row['name'],
                'quantity' => $quantity,
                'total' => $total
            ];

            $total_cost += $total;
            $total_items += $quantity;
        }

        $output['cart'] = [
            'total_cost' => $total_cost,
            'total_items' => $total_items
        ];
    }
}

print json_encode($output);
