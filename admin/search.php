<?php
include '../components/connect.php';

if (isset($_POST['query'])) {
    $searchQuery = $_POST['query'];
    $searchOrders = $conn->prepare("SELECT * FROM `orders` WHERE `total_products` LIKE ?");
    $searchOrders->execute(['%'.$searchQuery.'%']);
    $ordersCount = $searchOrders->rowCount();
} else {
    header("Location: ../admin/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<?php include '../components/admin_header.php'; ?>
<section class="accounts">
    <div class="container">
        <center>
            <strong>
                <h1 style="font-size: 50px;">Search Results</h1>
                <p style="font-size: 20px;">Your search for "<?php echo htmlspecialchars($searchQuery); ?>" returned <?php echo $ordersCount; ?> results:</p>
            </strong>
        </center>
        <?php if ($ordersCount > 0) { ?>
            <div class="box-container">
                <?php while ($order = $searchOrders->fetch(PDO::FETCH_ASSOC)) { ?>
                    <div class="box">
                        <h3>Order ID: <span><?php echo $order['id']; ?></span></h3>
                        <p>User ID: <span><?php echo $order['user_id']; ?></span></p>
                        <p>Name: <span><?php echo $order['order_name']; ?></span></p>
                        <p>Contact Number: <span><?php echo $order['number']; ?></span></p>
                        <p>Email: <span><?php echo $order['order_email']; ?></span></p>
                        <p>Payment Method: <span><?php echo $order['method']; ?></span></p>
                        <p>Delivery Address: <span><?php echo $order['address']; ?></span></p>
                        <p>Total Products: <span><?php echo $order['total_products']; ?></span></p>
                        <p>Total Price: <span><?php echo $order['total_price']; ?></span></p>
                        <p>Placed On: <span><?php echo $order['placed_on']; ?></span></p>
                        <p>Payment Status: <span><?php echo $order['payment_status']; ?></span></p>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p>No orders found.</p>
        <?php } ?>
    </div>
</section>
<!-- Include Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Include your own JavaScript scripts -->
<script src="scripts.js"></script>

</body>
</html>