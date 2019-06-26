<?php
require_once('_setup.php');

$output = [
    'success' => false
];
$cart_id = null;
$product_quantity = 1;
$existing_cart = false;
$existing_item = false;

if(isset($_POST['product-id'])){
    $product_id = $_POST['product-id'];
} else {
    throw new ApiException($output, 422, 'No product ID provided');
}

if(isset($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] > 0){
    $product_quantity = $_POST['quantity'];
}

if(isset($_SESSION['cart-id'])){
    $cart_id = $_SESSION['cart-id'];
}

if($cart_id){
    $query = "SELECT `id` FROM `cart` WHERE `id`=$cart_id";

    if($result = $conn->query($query)){

        if($result->num_rows){
            $existing_cart = true;
        } else {
            $cart_id = null;
            unset($_SESSION['cart-id']);
        }

    } else {
        throw new ApiException($output, 500, 'Error checking cart data');
    }
}

if(!$cart_id){
    $query = 'INSERT INTO `cart` (`created_at`, `updated_at`) VALUES (CURRENT_TIME, CURRENT_TIME)';

    if($conn->query($query) && $conn->affected_rows){
        $cart_id = $conn->insert_id;
        $_SESSION['cart-id'] = $cart_id;
    } else {
        throw new ApiException($output, 500, 'Error creating new cart');
    }
}

if($existing_cart){
    $query = 'SELECT `id` FROM `cart_items` WHERE `cart_id`=? AND `product_id`=?';

    $find_item_stmt = $conn->prepare($query);

    $find_item_stmt->bind_param('ii', $cart_id, $product_id);

    if($find_item_stmt->execute()){
        $result = $find_item_stmt->get_result();

        if($result->num_rows){
            $existing_item = $result->fetch_assoc();
        }
    } else {
        throw new ApiException($output, 500, 'Error checking cart item data');
    }
}

$query = 'INSERT INTO `cart_items` (`cart_id`, `product_id`, `quantity`, `updated_at`, `created_at`) VALUES (?, ?, ?, CURRENT_TIME, CURRENT_TIME)';

// print 'End of file: '.$cart_id;

if($existing_item){
    $query = 'UPDATE `cart_items` SET `quantity`=`quantity` + ?, `updated_at`=CURRENT_TIME WHERE `id`=?';

    $add_item_stmt = $conn->prepare($query);

    $add_item_stmt->bind_param('ii', $product_quantity, $existing_item['id']);
} else {
    $query = 'INSERT INTO `cart_items` (`cart_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES (?, ?, ?, CURRENT_TIME, CURRENT_TIME)';
    
    $add_item_stmt = $conn->prepare($query);

    $add_item_stmt->bind_param('iii', $cart_id, $product_id, $product_quantity);
}

if($add_item_stmt->execute()){
    if($conn->affected_rows){
        $query = "UPDATE `cart` SET `updated_at`=CURRENT_TIME WHERE `id`=$cart_id";

        $conn->query($query);

        $output['cartId'] = $cart_id;
        $output['message'] = "$product_quantity Item".($product_quantity > 1 ? 's' : '').' added to cart';
        $output['success'] = true;
    } else {
        throw new ApiException($output, 500, 'Item not added to cart');
    }
} else {
    throw new ApiException($output, 500, 'Error adding item to cart');
}

print json_encode($output);
