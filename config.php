<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'ipd';
$username = 'root';
$password = '';

try {
    // Connexion PDO à MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",
     $username, $password);
    
    // Configuration des options PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>

