<?php
error_reporting(0);
include_once("dbconnect.php");
$userid = $_GET['userid'];
$mobile = $_GET['mobile'];
$amount = $_GET['amount'];

$data = array(
    'id' =>  $_GET['billplz']['id'],
    'paid_at' => $_GET['billplz']['paid_at'] ,
    'paid' => $_GET['billplz']['paid'],
    'x_signature' => $_GET['billplz']['x_signature']
);

$paidstatus = $_GET['billplz']['paid'];

if ($paidstatus=="true"){
  $receiptid = $_GET['billplz']['id'];
  $signing = '';
    foreach ($data as $key => $value) {
        $signing.= 'billplz'.$key . $value;
        if ($key === 'paid') {
            break;
        } else {
            $signing .= '|';
        }
    }
        
    $signed= hash_hmac('sha256', $signing, 'S-wzNn8FTL0endIB4wgi728w');
    if ($signed === $data['x_signature']) {
        

    }
    
    $sqlinsertpurchased = "INSERT INTO tbl_purchased(orderid,email,paid,status) VALUES('$receiptid','$userid', '$amount','paid')";
    $sqldeletecart = "DELETE FROM tbl_carts WHERE email='$userid'";

    if ($conn->exec($sqlinsertpurchased) && $conn->exec($sqldeletecart)) {
        echo "<script>alert('Payment Completed')</script>";
    //    echo "<script>window.location.replace('cart.php')</script>";
    }
      echo ' <div class="header">
    
      <a href="mainpage.php" class="logo">Surprises Gift Box-Bouquet Shop</a>
      <div class="header-right">
      </div>
  </div>
  <br><br><body><div><h2><br><br><center>Your Receipt</center><br><br>
      </h1>
      <table border=1 width=100% align=center>
      <tr><td>Receipt ID</td><td>'.$receiptid.'</td></tr><tr><td>Email to </td>
      <td>'.$userid. ' </td></tr><td>Amount </td><td>RM '.$amount.'</td></tr>
      <tr><td>Payment Status </td><td>'.$paidstatus.'</td></tr>
     <tr><td>Date </td><td>'.date("d/m/Y").'</td></tr>
      <tr><td>Time </td><td>'.date("h:i a").'</td></tr>
      </table><br>';
    //  <p><center>Press back button to return to Samballana Bistro</center></p></div></body>
     echo "<link rel='stylesheet' href='../css/index.css'>

              </div>";
              
           
}
else{
     echo 'Payment Failed!';
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Successful Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src='../js/myscript.js'></script>
</head>

<body>
   


    </body>
    </html>