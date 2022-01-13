<?php
session_start();

if ($_SESSION["session_id"]) {
    $username = $_SESSION["email"];
    $name = $_SESSION["name"];
    $_SESSION["session_id"];

} else {
    echo "<script> alert('Session not available. Please login')</script>";
    echo "<script> window.location.replace('../php/login.php')</script>";
}



include_once("../php/dbconnect.php");

if (!isset($_COOKIE['email'])) {
    echo "<script>loadCookies()</script>";
} else {
    $email = $_COOKIE['email'];
    //add to cart button
    if (isset($_GET['op'])) {
        $prodid = $_GET['prodid'];
        $sqlcheckstock = "SELECT * FROM tbl_products WHERE prid = '$prodid' "; //check product in stock
        $stmt = $conn->prepare($sqlcheckstock);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        foreach ($rows as $product) {
            $quantity = $product['prqty']; //check qty  in stock?
            if ($quantity == 0) {
                echo "<script>alert('Quantity not available');</script>";
                echo "<script> window.location.replace('mainpage.php')</script>";
            } else {
                //continue insert to cart
                $sqlcheckcart = "SELECT * FROM tbl_carts WHERE prid = '$prodid' AND email = '$email'";
                $stmt = $conn->prepare($sqlcheckcart);
                $stmt->execute();
                $number_of_result = $stmt->rowCount();
                if ($number_of_result == 0) { //insert cart if not in the cart
                    $sqladdtocart = "INSERT INTO tbl_carts (email, prid, qty) VALUES ('$email','$prodid','1'); UPDATE tbl_products SET prqty = prqty -1 WHERE prid = '$prodid'";
                    if ($conn->exec($sqladdtocart)) {
                        echo "<script>alert('Success')</script>";
                        echo "<script> window.location.replace('mainpage.php')</script>";
                    } else {
                        echo "<script>alert('Failed')</script>";
                        echo "<script> window.location.replace('mainpage.php')</script>";
                    }
                } else { //update cart if the item already in the cart
                    $sqlupdatepr = "UPDATE tbl_carts, tbl_products SET tbl_carts.qty = tbl_carts.qty +1, tbl_products.prqty = tbl_products.prqty-1  WHERE tbl_carts.prid = '$prodid' AND tbl_products.prid = '$prodid'";
                    $sqlupdatecart = "UPDATE tbl_carts SET qty = qty +1 WHERE prid = '$prodid' AND email = '$email'";
                    if ($conn->exec($sqlupdatecart)) {
                        echo "<script>alert('Success')</script>";
                        echo "<script> window.location.replace('mainpage.php')</script>";
                    } else {
                        echo "<script>alert('Failed')</script>";
                        echo "<script> window.location.replace('mainpage.php')</script>";
                    }
                }
            }
        }
    }
}

//search and list products
if (isset($_GET['button'])) {
    $prname = $_GET['prname'];
    $prtype = $_GET['prtype'];
    if ($prtype == "all") {
        $sqlsearch = "SELECT * FROM tbl_products WHERE prname LIKE '%$prname%'";
    } else {
        $sqlsearch = "SELECT * FROM tbl_products WHERE prtype = '$prtype' AND prname LIKE '%$prname%'";
    }
} else {
    $sqlsearch = "SELECT * FROM tbl_products ORDER BY prid DESC"; // list latest data
};

$stmt = $conn->prepare($sqlsearch);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Gb Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">          <!—connect to css style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src='../js/myscript.js'></script>
</head>

<body onload="loadCookies()">
    <div class="header">
        <a href="#default" class="logo">"Surprises Gift Box-Bouquet Shop"</a>
        <div class="header-right">
            <a class="active" href="../index.php">Home</a>
            <a href="../php/cart.php">My Cart</a>
            <a href="../php/purchase.php">My Purcase</a>
            <a href="../index.php">Logout</a>

        </div>
    </div>
    <center><h2>List of Products</h2></center>
    <div class="container-src">
        <form action="mainpage.php" method="get">
            <div class="row">
                <div class="column">
                    <input type="text" id="fprname" name="prname" placeholder="Product name..">
                </div>
                <div class="column">
                    <select id="idprtype" name="prtype">
                        <option value="all">All</option>
                        <option value="flower">Flower</option>
                        <option value="bouquet">Bouquet</option>
                        <option value="giftbox">Gift Box</option>
                        <option value="combo">Combo</option>
                    </select>
                </div>
                <div class="column">
                    <input type="submit" name="button" value="Search">
                </div>
            </div>
        </form>
    </div>
    <?php
    echo "<div class='container'>";
    echo "<div class='card-row'>";


    foreach ($rows as $products) {
        $prodid = $products['prid'];
        $qty = $products['prqty'];
        echo " <div class='card'>";
        $imgurl = "../images/" . $products['picture']; // load the picture to mainpage
        echo "<img src='$imgurl' class='primage'>";
        
        echo "<h4 align='center' >" . ($products['prname']) . "</h3>"; // load name product to mainpage
        echo "<p align='center'> RM " . number_format($products['prprice'], 2) . "<br>"; // load price to mainpage
        echo "Avail:" . ($products['prqty']) . " unit/s</p>";               // load quantity product to mainpage
        echo "<a href='mainpage.php?op=cart&prodid=$prodid'><i class='fa fa-cart-plus'  onclick='return cartDialog()' style='font-size:24px;color:dodgerblue'></i></a>";
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";
    ?>

    </a>
</body>

</html>