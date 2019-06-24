<?php
require_once('_setup.php');
$output = [
    'success' => false
];

$query = 'SELECT `id`, `name`, `cost` FROM `products`';

$result = $conn->query($query);

if($result){
    $output['success'] = true;
    $output['products'] = [];

    if($result->num_rows){
        while($row = $result->fetch_assoc()){
            $output['products'][] = $row;
        }
    }
} else {
    throw new ApiException($output, 500, 'Error with getting product list from database');
}

print json_encode($output);
