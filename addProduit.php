<?php
session_start();
require_once 'config.php';
include 'navbar.php';

// Valeurs par défaut pour le formulaire
$nom = $_POST['nom'] ?? '';
$description = $_POST['description'] ?? '';
$prix = $_POST['prix'] ?? '';
$quantite = $_POST['quantite'] ?? '0';
$categorie = $_POST['categorie'] ?? '';
$errors = [];

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données du formulaire
    $nom = trim($nom);
    $description = trim($description);
    $prix = floatval($prix);
    $quantite = intval($quantite);
    $categorie = trim($categorie);
    
    // Validation des données
    if (empty($nom)) {
        $errors[] = "Le nom du produit est requis.";
    }
    
    if ($prix <= 0) {
        $errors[] = "Le prix doit être supérieur à 0.";
    }
    
    if ($quantite < 0) {
        $errors[] = "La quantité ne peut pas être négative.";
    }
    
    // Si pas d'erreurs, insérer dans la base de données
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO produit (nom, description, prix, quantite, categorie) VALUES (:nom, :description, :prix, :quantite, :categorie)");
            
            $stmt->execute([
                ':nom' => $nom,
                ':description' => $description,
                ':prix' => $prix,
                ':quantite' => $quantite,
                ':categorie' => $categorie
            ]);
            
            // Redirection avec message de succès vers la liste
            $_SESSION['success_message'] = "Produit ajouté avec succès !";
            header('Location: produit.php?success=1');
            exit();
            
        } catch(PDOException $e) {
            $errors[] = "Erreur lors de l'ajout du produit : " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Ajouter un produit</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="produit.php" class="btn btn-secondary">← Retour à la liste</a>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Formulaire d'ajout de produit</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="addProduit.php">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom du produit *</label>
                                <input type="text" class="form-control" id="nom" name="nom" required
                                       value="<?php echo htmlspecialchars($nom); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="categorie" class="form-label">Catégorie</label>
                                <input type="text" class="form-control" id="categorie" name="categorie"
                                       placeholder="Ex: Électronique, Vêtements, etc."
                                       value="<?php echo htmlspecialchars($categorie); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                      placeholder="Description du produit..."><?php echo htmlspecialchars($description); ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="prix" class="form-label">Prix (€) *</label>
                                <input type="number" class="form-control" id="prix" name="prix" step="0.01" min="0.01" required
                                       value="<?php echo htmlspecialchars($prix); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="quantite" class="form-label">Quantité *</label>
                                <input type="number" class="form-control" id="quantite" name="quantite" min="0" required
                                       value="<?php echo htmlspecialchars($quantite); ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Enregistrer le produit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>

