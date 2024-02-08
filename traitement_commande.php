<?php
    $serveur = "localhost";
    $utilisateur = "root";
    $motdepasse = "root";
    $base_de_donnees = "shop_db";
    $conn = mysqli_connect($serveur, $utilisateur, $motdepasse, $base_de_donnees);
    if (!$connexion) {
        die("La connexion à la base de données a échoué : " . mysqli_connect_error());
    }
    // $reference_article = $_POST['pid'];
    $pid = $_POST['pid'];
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
    $select_products->execute([$pid]);
    $qte_stock = $_POST['qte_stock'];
    $query = "SELECT quantity FROM products WHERE id = '$pid'";
    $resultat = mysqli_query($conn, $query);
    if (mysqli_num_rows($resultat) > 0) {
        $article = mysqli_fetch_assoc($resultat);
        $quantity = $article['quantity'];
        if ($qte_stock <= $quantity) {
            $nouvelle_qte_stock = $quantity - $qte_stock;
            $query_update = "UPDATE products SET quantity = $nouvelle_qte_stock WHERE id = '$pid'";
            mysqli_query($connexion, $query_update);

            echo "La commande a été traitée avec succès.";
        } else {
            echo "La quantité demandée n'est pas disponible en stock.";
        }
    } else {
        echo "L'article n'a pas été trouvé dans la base de données.";
    }
    mysqli_close($connexion);
?>
