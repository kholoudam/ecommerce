<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['name'])){
   $username = $_GET['name'];

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE name = ?");
   $select_user->execute([$username]);
   $user = $select_user->fetch(PDO::FETCH_ASSOC);

   $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE name = ?");
   $select_orders->execute([$user['name']]);
   $orders = $select_orders->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Orders</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="user-orders">

   <h1 class="heading">User Orders</h1>

   <?php if(isset($user)): ?>
      <div class="user-info">
         <p><strong>User ID:</strong> <?= $user['id']; ?></p>
         <p><strong>Username:</strong> <?= $user['name']; ?></p>
         <p><strong>Email:</strong> <?= $user['email']; ?></p>
      </div>

      <div class="order-container">
         <?php if(count($orders) > 0): ?>
            <h2>Orders:</h2>

            <ul class="order-list">
               <?php foreach($orders as $order): ?>
                    <div class="box">
                        <div class="id"><strong>Order ID:</strong> <?= $order['id']; ?></div>
                        <div class="name"><strong>Name:</strong> <?= $order['name']; ?></div>
                        <div class="number"><strong>Number:</strong> <?= $order['number']; ?></div>
                        <div class="email"><strong>Email:</strong> <?= $order['email']; ?></div>
                        <div class="method"><strong>Method:</strong> <?= $order['method']; ?></div>
                        <div class="adress"><strong>Address:</strong> <?= $order['address']; ?></div>
                        <div class="total_products"><strong>Total Products:</strong> <?= $order['total_products']; ?></div>
                        <div class="total_price"><strong>Total Price:</strong> <?= $order['total_price']; ?></div>
                        <div class="placed_on"><strong>Placed On:</strong> <?= $order['placed_on']; ?></div>
                        <div class="payment_status"><strong>Payment Status:</strong> <?= $order['payment_status']; ?></div>
                    </div>
               <?php endforeach; ?>
            </ul>
         <?php else: ?>
            <p>No orders available for this user.</p>
         <?php endif; ?>
      </div>
   <?php else: ?>
      <p>No user specified.</p>
   <?php endif; ?>
</section>
<script src="../js/admin_script.js"></script>
<script src="../js/script.js"></script>
</body>
</html>