
<?php
require('../model/database.php');
require('../model/category.php');
require('../model/category_db.php');
require('../model/product.php');
require('../model/product_db.php');

$category_db = new CategoryDB();
$product_db = new ProductDB();

$action = filter_input(INPUT_GET, 'action');
if ($action == NULL) {
    $action = 'list_products';
}

if ($action == 'list_products') {
    $category_id = filter_input(
        INPUT_GET,
        'category_id',
        FILTER_VALIDATE_INT
    );

    if ($category_id == NULL || $category_id == FALSE) {
        $category_id = 1;
    }

    $current_category = $category_db->getCategory($category_id);
    $products = $product_db->getProductsByCategory($category_id);

    include('product_list.php');

} else if ($action == 'view_product') {
    $product_id = filter_input(
        INPUT_GET,
        'product_id',
        FILTER_VALIDATE_INT
    );

    if ($product_id == NULL || $product_id == FALSE) {
        $error = "Invalid product ID.";
        include('../errors/error.php');
    } else {
        $product = $product_db->getProduct($product_id);
        $category = $product->getCategory();

        include('product_view.php');
    }
}
?>
