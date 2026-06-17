<?php

class Cart
{

    public function __construct()
    {
        // check if session has already started, otherwise start one
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function addProductToCart(int $product_id)
    {
        if (isset($_SESSION["cart"][$product_id])) {
            // aantal van een specifiek product +1
            $_SESSION["cart"][$product_id]['aantal'] += 1;
        } else {
            $_SESSION["cart"][$product_id]['aantal'] = 1;
        }
    }

    public function decreaseProductFromCart(int $product_id)
    {
        if (isset($_SESSION["cart"][$product_id])) {
            // aantal van een specifiek product +1
            $_SESSION["cart"][$product_id]['aantal'] -= 1;

            if ($_SESSION["cart"][$product_id]['aantal'] <= 0) {
                // in 1x het product er uit halen.
                $this->removeProductFromCart($product_id);
            }
        }
    }

    public function removeProductFromCart(int $product_id)
    {
        unset($_SESSION["cart"][$product_id]);
    }

    public function removeAllProductsFromCart()
    {
        unset($_SESSION["cart"]);
    }

    public function getAllProductsInCart(): array
    {
        return $_SESSION['cart'];
    }
}

// nieuwe instantie van een product maken
$cart = new Cart();

if ($_SERVER['REQUEST_METHOD'] === "POST") {


    $product_id = $_POST['product_id'];

    // $product = $productRepository->findById($product_id);

    if ($_POST['productAction'] === "add") {
        $cart->addProductToCart($product_id);
    } else if ($_POST['productAction'] === "decrease") {
        $cart->decreaseProductFromCart($product_id);
    } else if ($_POST['productAction'] === "delete") {
        $cart->removeProductFromCart($product_id);
    }

    // ophalen van elk product
    $cartItems = $cart->getAllProductsInCart();

    foreach ($cartItems as $key => $values) {
        echo 'product id: ' . $key . ' : ' . $values['aantal'];
        echo '<br>';
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="post">
        <input type="hidden" name="product_id" value=1>
        <input type="submit" name="productAction" value="add">
        <input type="submit" name="productAction" value="decrease">
        <input type="submit" name="productAction" value="delete">
    </form>

    <form action="" method="post">
        <input type="hidden" name="product_id" value=2>
        <input type="submit" name="productAction" value="add">
        <input type="submit" name="productAction" value="decrease">
        <input type="submit" name="productAction" value="delete">
    </form>
</body>

</html>