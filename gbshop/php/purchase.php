<?php
session_start();
include_once("dbconnect.php");
if (!isset($_COOKIE['email'])) {
    echo "<script>loadCookies()</script>";
    echo "<script> window.location.replace('../php/mainpage.php')</script>";
} else {
    $email = $_COOKIE["email"];
    if (isset($_GET['button'])) {
        $op = $_GET["button"];
        if ($op == "delete") {
            $prid = $_GET['prid'];
            $sqldelete = "DELETE FROM tbl_carts WHERE email='$email' AND prid = '$prid'";
            $stmt = $conn->prepare($sqldelete);
            if ($stmt->execute()) {
                echo "<script> alert('Delete Success')</script>";
                echo "<script>window.location.replace('cart.php')</script>";
            } else {
                echo "<script> alert('Delete Failed')</script>";
            }
        }
        if ($op == "addcart") {
            $prid = $_GET['prid'];
            $sqlupdatecart = "UPDATE tbl_carts SET qty = qty +1 WHERE prid = '$prid' AND email = '$email'";
            if ($conn->exec($sqlupdatecart)) {
                echo "<script>alert('Success')</script>";
                echo "<script> window.location.replace('cart.php')</script>";
            } else {
                echo "<script>alert('Failed add')</script>";
                echo "<script> window.location.replace('cart.php')</script>";
            }
        }
        if ($op == "removecart") {
            $prid = $_GET['prid'];
            $qty = $_GET['qty'];
            if ($qty == 1) {
                echo "<script>alert('Failed.')</script>";
                echo "<script> window.location.replace('cart.php')</script>";
            } else {
                $sqlupdatecart = "UPDATE tbl_carts SET qty = qty - 1 WHERE prid = '$prid' AND email = '$email'";
                if ($conn->exec($sqlupdatecart)) {
                    echo "<script>alert('Success')</script>";
                    echo "<script> window.location.replace('cart.php')</script>";
                } else {
                    echo "<script>alert('Failed')</script>";
                    echo "<script> window.location.replace('cart.php')</script>";
                }
            }
        }

    }
    $sqlloadcart = "SELECT * FROM tbl_purchased ORDER BY id DESC";
    $stmt = $conn->prepare($sqlloadcart);
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $rows = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>My Purchase</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src='../js/myscript.js'></script>
</head>

<body>
<div class="header">
    
    <a href="#default" class="logo">"Surprises Gift Box-Bouquet Shop"</a>
        <div class="header-right">
            <a class="active" href="../php/mainpage.php">Home</a>
            <a href="../index.php">Logout</a>
        </div>
    </div>
    <center>
       <br> <br> <h2>My Purchase</h2>
    </center>
    <?php
    $sumtotal = 0.0;
    echo "<div class='container'>";
    echo "<div class='card-row'>";
    foreach ($rows as $purchased) {
        $orderid = $purchased['orderid'];
        $email = $purchased['email'];
        $paid = $purchased['paid'];
        $status = $purchased['status'];
        $total = 0.0;
        $total = $purchased['paid'];
        echo " <div class='card'>";
        //echo "<img src='$imgurl' class='primage'>";
        echo "<br><br><h4 align='center' >" . ($purchased['orderid']) . "</h3>";
        //echo "<p align='center'> RM " . number_format($purchased['paid'], 2) . "/unit<br>";
        echo "<td>Status " . $status . "</td>" ;
        echo "<br> Total RM " . number_format($paid, 2) . "<br><br>";
        echo "</div>";
        $sumtotal = $total + $sumtotal;
    }
    echo "</div>";
    echo "</div>";
    echo "<div class='card1'>
    <h3 style=color:#ffffff>Total Purchase: RM " . number_format($sumtotal, 2) . "</h3></div> <br> <br>";
    ?>

</a>
</body>


</html>