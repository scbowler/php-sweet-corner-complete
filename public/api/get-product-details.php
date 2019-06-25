<?php
require_once('_setup.php');
$output = [
    'success' => false
];

if(empty($_GET['product-id'])){
    throw new ApiException($output, 422, 'Missing product ID');
}

$id = $_GET['product-id'];

if(!is_numeric($id)){
    throw new ApiException($output, 422, 'Product ID must be a number');
}

$query = 'SELECT * FROM `products` WHERE `id`=?';

$stmt = $conn->prepare($query);

$stmt->bind_param('i', $id);

if($stmt->execute()){
    $result = $stmt->get_result();

    $output['success'] = true;
    $output['product'] = null;

    if($result->num_rows){
        $output['product'] = $result->fetch_assoc();
    } else {
        $output['message'] = "No product found with ID of $id";
    }
} else {
    throw new ApiException([], 500, 'Database Error: Unable to read product details');
}

print json_encode($output);
