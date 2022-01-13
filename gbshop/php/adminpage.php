<?php
session_start();

if ($_SESSION["session_id"]) {
    $username = $_SESSION["email"];
    $_SESSION["session_id"];

} else {
    echo "<script> alert('Session not available. Please login')</script>";
    echo "<script> window.location.replace('../php/adminlogin.php')</script>";
}



include_once("../php/dbconnect.php");

if (!isset($_COOKIE['email'])) {
    echo "<script>loadCookies()</script>";
} else {
    $email = $_COOKIE["email"];
    if (isset($_GET['button'])) {
        $op = $_GET["button"];
        if ($op == "addpr") {
            $prodid = $_GET['prid'];
            $qty = $_GET['prqty'];
            $sqlupdatepr = "UPDATE tbl_products SET tbl_products.prqty = tbl_products.prqty +1 WHERE tbl_products.prid = '$prodid'";
            if ($conn->exec($sqlupdatepr)) {
                //echo "<script>alert('Success')</script>";
                echo "<script> window.location.replace('adminpage.php')</script>";
            } else {
                echo "<script>alert('Failed add')</script>";
                echo "<script> window.location.replace('adminpage.php')</script>";
            }
        }
        if ($op == "removepr") {
            $prodid = $_GET['prid'];
            $qty = $_GET['prqty'];
            if ($qty == 1) {
                echo "<script>alert('Failed.')</script>";
                echo "<script> window.location.replace('adminpage.php')</script>";
            } else {
                $sqlupdatepr = "UPDATE tbl_products SET tbl_products.prqty = tbl_products.prqty -1  WHERE tbl_products.prid = '$prodid' ";
                if ($conn->exec($sqlupdatepr)) {
                    //echo "<script>alert('Success')</script>";
                    echo "<script> window.location.replace('adminpage.php')</script>";
                } else {
                    echo "<script>alert('Failed')</script>";
                    echo "<script> window.location.replace('adminpage.php')</script>";
                }
            }
        }
        if ($op == "delete") {
            $prodid = $_GET['prid'];
           // $qty = $_GET['prqty'];
            //$sqldelete ="UPDATE tbl_carts, tbl_products SET tbl_carts.qty = tbl_carts.qty - tbl_carts.qty , tbl_products.prqty = tbl_products.prqty + tbl_carts.qty  WHERE tbl_carts.prid = '$prid' AND tbl_products.prid = '$prid' ";
            $sqldelete = "UPDATE tbl_products SET tbl_products.prqty = 0 WHERE tbl_products.prid = '$prodid'; DELETE FROM tbl_products WHERE prid = '$prodid'";
            $stmt = $conn->prepare($sqldelete);
            if ($stmt->execute()) {
                echo "<script> alert('Delete Success')</script>";
                echo "<script>window.location.replace('adminpage.php')</script>";
            } else {
                echo "<script> alert('Delete Failed')</script>";
            }
        }
        if ($op == "updatepr") {
            $prodid = $_GET['prid'];
            //$qty = $_GET['prqty'];
            $sqlupdatepr = "UPDATE tbl_products SET prqty = '".$_GET['nprqty']."' WHERE prid = '$prodid'";
            if ($conn->exec($sqlupdatepr)) {
                //echo "<script>alert('Success')</script>";
                echo "<script> window.location.replace('adminpage.php')</script>";
            } else {
                echo "<script>alert('Failed add')</script>";
                echo "<script> window.location.replace('adminpage.php')</script>";
            }
        }
        
       
       // if (isset($_GET['submit'])) {
         //   $prodid = $_GET["prodid"];
           // $nqty = $_GET["prqty"];
         //   $sqlupdateall = "UPDATE tbl_products SET prqty = '$nqty' WHERE prid = '$prodid'";
           // $sqlupdate = "UPDATE tbl_user SET phone='$nphone',name='$nname',school='$nschool' WHERE email = '$username'";
        //   $stmt = $conn->prepare($sqlupdateall);
        //   if ($stmt->execute()) {
          //     echo "<script> alert('Update Success')</script>";
          //     echo "<script>window.location.replace('adminpanel.php')</script>";
        //   } else {
         //      echo "<script> alert('Update Failed')</script>";
         //  }
    //}

    }
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
                    $sqladdtocart = "INSERT INTO tbl_carts (email, prid, qty) VALUES ('$email','$prodid','1')";
                    if ($conn->exec($sqladdtocart)) {
                        echo "<script>alert('Success')</script>";
                        echo "<script> window.location.replace('mainpage.php')</script>";
                    } else {
                        echo "<script>alert('Failed')</script>";
                        echo "<script> window.location.replace('mainpage.php')</script>";
                    }
                } else { //update cart if the item already in the cart
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
    $sqlsearch = "SELECT * FROM tbl_products ORDER BY prid DESC";
}
$stmt = $conn->prepare($sqlsearch);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">          <!—connect to css style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src='js/myscript.js'></script>
</head>

<body onload="loadCookies()">
    <div class="header">
    
    <a href="#default" class="logo">"Surprises Gift Box-Bouquet Shop"</a>
        <div class="header-right">
            <a class="active" href="../php/adminpage.php">Home</a>
            <a href="../index.php">Logout</a>
        </div>
    </div>
    
    <div class="container-src">
        <form action="adminpage.php" method="get">
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
    <br><center><h2>List of Products</h2></center>
    </div>

    <?php
   


    echo "<div class='container'>";
    echo "<div class='card-row'>";

    foreach ($rows as $products) {
        $prodid = $products['prid'];
        $qty = $products['prqty'];
        
        echo " <div class='card'>";
        echo "<p align='right'><a href='adminpage.php?button=delete&prid=$prodid' class='fa fa-remove' onclick='return deleteDialog()' style=' color:#ff4242'></a></p>";
        $imgurl = "../images/" . $products['picture'];
        echo "<img src='$imgurl' class='primage'>";
        echo "<br><br><h3 align='center' >" . ($products['prname']) . "</h3>";
        //echo "<br><br><h3 align='center' ><input type='text' id='prname' name'prname' value = ".$products['prname']."></h3>";
        echo "<p align='center'> RM " . number_format($products['prprice'], 2) . "<br>";
        echo "<table class='center'><tr><td><a href='adminpanel.php?button=removepr&prid=$prodid&prqty=$qty'><i class='fa fa-minus' ' style='font-size:24px;color:#ff4242'></i></a></td>";
        echo "<td>Avail: " . $qty . " unit/s</td>";
        echo "<td>&nbsp<a href='adminpage.php?button=addpr&prid=$prodid&prqty=$qty'><i class='fa fa-plus' ' style='font-size:24px;color:#ff4242'></i></a></td></tr></table>";
       // echo "<form action='adminpanel.php' method='get'>
          //   <input type='text' id='prqty' name=".$products['prqty'].">
           //  <input type='submit' name='submit' value='Submit'>";
               //<td>&nbsp<a href='adminpanel.php?button=update&prid=$prodid&prqty=$qty'><i class='fa fa-plus' ' style='font-size:24px;color:#ff4242'></i></a></td></tr></table>";

               // echo "<br><a  href='updateproduct'><i class='fa fa-update style='font-size:24px; color:#ff4242'></i></a>";
        echo "</div>";
        }
   
    echo "</div>";
    ?>
    <a href="../php/newproduct.php" class="float">
        <i class="fa fa-plus my-float"></i>                 <!—Add Button -->
    </a>
</body>

</html>