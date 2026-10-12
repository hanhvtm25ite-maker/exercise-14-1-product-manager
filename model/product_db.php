
<?php
require_once('database.php');
require_once('category.php');
require_once('category_db.php');
require_once('product.php');

class ProductDB {
    public function getProductsByCategory($category_id) {
        $db = Database::getDB();

        $category_db = new CategoryDB();
        $category = $category_db->getCategory($category_id);

        $query = 'SELECT * FROM products
                  WHERE categoryID = :category_id
                  ORDER BY productID';

        $statement = $db->prepare($query);
        $statement->bindValue(':category_id', $category_id);
        $statement->execute();

        $rows = $statement->fetchAll();
        $statement->closeCursor();

        $products = array();

        foreach ($rows as $row) {
            $product = new Product();
            $product->setCategory($category);
            $product->setID($row['productID']);
            $product->setCode($row['productCode']);
            $product->setName($row['productName']);
            $product->setPrice($row['listPrice']);
            $products[] = $product;
        }

        return $products;
    }

    public function getProduct($product_id) {
        $db = Database::getDB();

        $query = 'SELECT * FROM products
                  WHERE productID = :product_id';

        $statement = $db->prepare($query);
        $statement->bindValue(':product_id', $product_id);
        $statement->execute();

        $row = $statement->fetch();
        $statement->closeCursor();

        $category_db = new CategoryDB();
        $category = $category_db->getCategory($row['categoryID']);

        $product = new Product();
        $product->setCategory($category);
        $product->setID($row['productID']);
        $product->setCode($row['productCode']);
        $product->setName($row['productName']);
        $product->setPrice($row['listPrice']);

        return $product;
    }

    public function deleteProduct($product_id) {
        $db = Database::getDB();

        $query = 'DELETE FROM products
                  WHERE productID = :product_id';

        $statement = $db->prepare($query);
        $statement->bindValue(':product_id', $product_id);
        $statement->execute();
        $statement->closeCursor();
    }

    public function addProduct($product) {
        $db = Database::getDB();

        $query = 'INSERT INTO products
                     (categoryID, productCode, productName, listPrice)
                  VALUES
                     (:category_id, :code, :name, :price)';

        $statement = $db->prepare($query);
        $statement->bindValue(
            ':category_id',
            $product->getCategory()->getID()
        );
        $statement->bindValue(':code', $product->getCode());
        $statement->bindValue(':name', $product->getName());
        $statement->bindValue(':price', $product->getPrice());
        $statement->execute();
        $statement->closeCursor();
    }
}
?>
