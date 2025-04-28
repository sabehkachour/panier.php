<?php
session_start(); // Démarre la session pour utiliser $_SESSION

// Initialiser le panier si il n'existe pas encore
function initialiserPanier() {
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
    }
}

// Ajouter un produit dans le panier
function ajouterProduit($nomProduit, $prixProduit, $quantiteProduit) {
    initialiserPanier();
    $_SESSION['panier'][] = array(
        'nom' => $nomProduit,
        'prix' => $prixProduit,
        'quantite' => $quantiteProduit
    );
}

// Supprimer un produit du panier
function supprimerProduit($index) {
    if (isset($_SESSION['panier'][$index])) {
        unset($_SESSION['panier'][$index]);
        $_SESSION['panier'] = array_values($_SESSION['panier']); // Réindexer
    }
}

// Afficher le contenu du panier
function afficherPanier() {
    if (empty($_SESSION['panier'])) {
        echo "Le panier est vide.";
    } else {
        echo "<h2>Contenu du panier :</h2>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Produit</th><th>Prix</th><th>Quantité</th><th>Total</th><th>Action</th></tr>";
        $prixTotal = 0;
        foreach ($_SESSION['panier'] as $index => $produit) {
            $totalProduit = $produit['prix'] * $produit['quantite'];
            $prixTotal += $totalProduit;
            echo "<tr>
                    <td>{$produit['nom']}</td>
                    <td>{$produit['prix']} €</td>
                    <td>{$produit['quantite']}</td>
                    <td>{$totalProduit} €</td>
                    <td><a href='?supprimer=$index'>Supprimer</a></td>
                </tr>";
        }
        echo "<tr>
                <td colspan='3'><strong>Total général :</strong></td>
                <td colspan='2'><strong>{$prixTotal} €</strong></td>
              </tr>";
        echo "</table>";
    }
}

// Ajouter un produit si le formulaire est soumis
if (isset($_POST['ajouter'])) {
    ajouterProduit($_POST['nom'], $_POST['prix'], $_POST['quantite']);
}

// Supprimer un produit si demandé
if (isset($_GET['supprimer'])) {
    supprimerProduit($_GET['supprimer']);
}

// Appel de l'affichage
?>

<h1>Ajout d'un produit au panier</h1>

<form method="post" action="">
    Nom du produit : <input type="text" name="nom" required><br><br>
    Prix du produit (€) : <input type="number" step="0.01" name="prix" required><br><br>
    Quantité : <input type="number" name="quantite" required><br><br>
    <input type="submit" name="ajouter" value="Ajouter au panier">
</form>

<br><hr><br>

<?php
afficherPanier();
?>
