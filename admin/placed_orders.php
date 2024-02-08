<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit();
}

$message = [];

if (isset($_POST['update_payment'])) {
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_id]);
   $message[] = 'Payment status updated!';
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
   exit();
}

$select_orders = $conn->prepare("SELECT * FROM `orders`");
$select_orders->execute();
$orders_count = $select_orders->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" href="../bootstrap/css/app.css">
   <link rel="stylesheet" href="../bootstrap/css/app.css.map">
</head>
<body>
<?php include '../components/admin_header.php'; ?>
<section class="orders">
   <nav class="navbar">
      <div class="container-fluid justify-content-center">
         <form action="../admin/search.php" method="POST" class="d-flex" role="search">
            <div class="row">
               <input class="form-control me-5" style="width:400px;height:40px;font-size:20px;" type="search" name="query" placeholder="Search......" aria-label="Search">
            </div>
            <div class="row">
               <button class="btn btn-outline-success" style="width:100px;height:40px;font-size:20px;" type="submit"><strong>Search</strong></button>
            </div>
         </form>
      </div>
   </nav>
   <h1 class="heading">Placed Orders</h1>

   <div class="box-container">
      <?php
      if ($orders_count > 0) {
         while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <div class="box">
               <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
               <p> name : <span><?= $fetch_orders['name']; ?></span> </p>
               <p> number : <span><?= $fetch_orders['number']; ?></span> </p>
               <p> address : <span><?= $fetch_orders['address']; ?></span> </p>
               <p> total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
               <p> total price : <span>$<?= $fetch_orders['total_price']; ?>/-</span> </p>
               <p> payment method : <span><?= $fetch_orders['method']; ?></span> </p>
               <form action="" method="post">
                  <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                  <select name="payment_status" class="select">
                     <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
                     <option value="pending">pending</option>
                     <option value="completed">completed</option>
                  </select>
                  <div class="flex-btn">
                     <input type="submit" value="update" class="option-btn" name="update_payment">
                     <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">delete</a>
                  </div>
               </form>
            </div>
      <?php
         }
      } else {
         echo '<p class="empty">No orders placed yet!</p>';
      }
      ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>
<script src="../bootstrap/js/app.js"></script>
<script src="../bootstrap/js/app.js.map"></script>
</body>
</html>