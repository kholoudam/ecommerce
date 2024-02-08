<?php

include '../components/connect.php';

// Seuil minimum
$seuilMinimum = 10;

// Sélectionner les produits qui ont une quantité inférieure ou égale au seuil minimum
$select_products = $conn->prepare("SELECT * FROM `products` WHERE quantity <= ?");
$select_products->execute([$seuilMinimum]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products Threshold</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      .product-container {
         display: flex;
         flex-wrap: wrap;
         justify-content: center;
         gap: 20px;
      }

      .product-card {
         width: 300px;
         border: 1px solid #ccc;
         border-radius: 5px;
         padding: 20px;
      }

      .product-card img {
         width: 100%;
         height: auto;
         margin-bottom: 10px;
      }

      .product-card p {
         margin-bottom: 5px;
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="products-threshold">

   <h1 class="heading">Products Threshold</h1>

   <div class="product-container">

   <?php
      if($select_products->rowCount() > 0){
         while($product = $select_products->fetch(PDO::FETCH_ASSOC)){   
   ?>
   <div class="product-card">
      <img src="<?= $product['image_01']; ?>" alt="Product Image">
      <p><strong>Name:</strong> <?= $product['name']; ?></p>
      <p><strong>Details:</strong> <?= $product['details']; ?></p>
      <p><strong>Price:</strong> <?= $product['price']; ?></p>
      <p><strong>Category:</strong> <?= $product['category']; ?></p>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No products below the threshold!</p>';
      }
   ?>

   </div>

</section>
<script src="../js/admin_script.js"></script>
   
</body>
</html>