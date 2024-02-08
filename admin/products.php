<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $details = $_POST['details'];
    $category = $_POST['category'];

    // Vérifier si des fichiers sont téléchargés avec succès
    if (isset($_FILES['image_01']) && isset($_FILES['image_02']) && isset($_FILES['image_03'])) {
        $image_01 = $_FILES['image_01']['name'];
        $image_02 = $_FILES['image_02']['name'];
        $image_03 = $_FILES['image_03']['name'];

        // Emplacement du dossier de téléchargement des images
        $target_dir = "../uploaded_img/";

        // Chemin complet des fichiers téléchargés
        $target_file_01 = $target_dir . basename($image_01);
        $target_file_02 = $target_dir . basename($image_02);
        $target_file_03 = $target_dir . basename($image_03);

        // Déplacer les fichiers téléchargés vers le dossier de destination
        if (move_uploaded_file($_FILES['image_01']['tmp_name'], $target_file_01) &&
            move_uploaded_file($_FILES['image_02']['tmp_name'], $target_file_02) &&
            move_uploaded_file($_FILES['image_03']['tmp_name'], $target_file_03)) {

            // Connexion à la base de données et insertion des données
            $conn = new PDO('mysql:host=localhost;dbname=shop_db', 'root', 'root');

            $insert_product = $conn->prepare("INSERT INTO `products` (name, details, price, image_01, image_02, image_03, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert_product->execute([$name, $details, $price, $image_01, $image_02, $image_03, $category]);

            // Redirection vers la page des produits ou autre action souhaitée
            header('location: products.php');
            exit();
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $delete_product_image->execute([$delete_id]);
    $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
    unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
    unlink('../uploaded_img/' . $fetch_delete_image['image_02']);
    unlink('../uploaded_img/' . $fetch_delete_image['image_03']);
    $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_product->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
    $delete_cart->execute([$delete_id]);
    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
    $delete_wishlist->execute([$delete_id]);
    header('location: products.php');
    exit();
}

if (isset($_POST['stop_promotion'])) {
   $product_id = $_POST['product_id'];

   // Connexion à la base de données
   $conn = new PDO('mysql:host=localhost;dbname=shop_db', 'root', 'root');

   // Mettre à jour la colonne "promotion" à 0 pour arrêter la promotion
   $stopPromotion = $conn->prepare("UPDATE `products` SET promotion = 0 WHERE id = ?");
   $stopPromotion->execute([$product_id]);

   // Redirection vers la page des produits ou autre action souhaitée
   header('location: products.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include '../components/admin_header.php'; ?>

    <section class="add-products">

        <h1 class="heading">add product</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="flex">
                <div class="inputBox">
                    <span>product name (required)</span>
                    <input type="text" class="box" required maxlength="100" placeholder="enter product name"
                        name="name">
                </div>
                <div class="inputBox">
                    <span>product price (required)</span>
                    <input type="number" min="0" class="box" required max="9999999999"
                        placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;"
                        name="price">
                </div>
                <div class="inputBox">
                    <span>image 01 (required)</span>
                    <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp"
                        class="box" required>
                </div>
                <div class="inputBox">
                    <span>image 02 (required)</span>
                    <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp"
                        class="box" required>
                </div>
                <div class="inputBox">
                    <span>image 03 (required)</span>
                    <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp"
                        class="box" required>
                </div>
                <div class="inputBox">
                    <span>product details (required)</span>
                    <textarea name="details" placeholder="enter product details" class="box" required
                        maxlength="1500" cols="30" rows="10"></textarea>
                </div>
                <div class="inputBox">
                    <span>product category (required)</span>
                    <textarea name="category" placeholder="enter product category" class="box" required
                        maxlength="500" cols="30" rows="10"></textarea>
                </div>
            </div>

            <input type="submit" value="add product" class="btn" name="add_product">
        </form>

    </section>

    <section class="show-products">

        <h1 class="heading">products added</h1>
        <section class="category">
            <div class="swiper category-slider" style="display: flex; justify-content: center;">
               <div class="swiper-wrapper" style="display: flex; gap: 20px;">
                  <a style="margin-left:200px;" href="category.php?category=fruit" class="slide">
                     <img src="../images/des-fruits.png" alt="">
                     <h3>Fruits</h3>
                  </a>
                  <a href="category.php?category=legume" class="slide">
                     <img src="../images/des-legumes.png" alt="">
                     <h3>Légumes</h3>
                  </a>
                  <a href="category.php?category=pain" class="slide">
                     <img src="../images/pain.png" alt="">
                     <h3>Pain</h3>
                  </a>
                  <a href="category.php?category=jus" class="slide">
                     <img src="../images/jus-de-pomme.png" alt="">
                     <h3>Jus</h3>
                  </a>
               </div>
            </div>
         </section>
        <div class="box-container">
            <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="box">
               <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
               <div class="name"><?= $fetch_products['name']; ?></div>
               <div class="price">$<span><?= $fetch_products['price']; ?></span>/-</div>
               <div class="details"><span><?= $fetch_products['details']; ?></span></div>
               <div class="flex-btn">
                  <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
                 <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
               </div><br/>
               <div class="flex-btn">
                  <?php if ($fetch_products['promotion'] > 0) { ?>
                 <form action="" method="post" style="margin-left:70px;">
                     <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
                     <input type="submit" value="Stop Promotion" class="btn btn-primary mb-3" name="stop_promotion">
                    </form>
                    <?php } ?>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>
    <script src="../js/admin_script.js"></script>
</body>
</html>