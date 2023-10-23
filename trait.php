<?php
session_start();

$servername = 'localhost';
$username = 'root';
$password = '';
try {
    $conn = new PDO("mysql:host=$servername;dbname=gestion_des_taches", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST["creer_compte"])) {
        $nom = $_POST["nom"];
        $email = $_POST["email"];
        $mot_de_passe = $_POST["mot_de_passe"];
        $confirmation = $_POST["confirmation"];
        $erreur = [];

        // Validation du nom
        if (!preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $nom)) {
            $erreur[] = "Veuillez entrer un nom valide";
        }

        // Validation de l'e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur[] = "Veuillez entrer une adresse email valide";
        }

        // Validation du mot de passe
        if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $mot_de_passe)) {
            $erreur[] = "Veuillez entrer un mot de passe valide";
        }

        // Validation de la confirmation du mot de passe
        if ($mot_de_passe !== $confirmation) {
            $erreur[] = "Les mots de passe ne correspondent pas";
        }

        // Vérification du nom d'utilisateur existant
        if (verification_nom($nom, $conn)) {
            $erreur[] = "Nom d'utilisateur existe déjà";
        }

        if (!empty($erreur)) {
            print_r($erreur);
        } else {
            // Code d'insertion dans la base de données
           
            $sql = "INSERT INTO les_inscriptions (nom, email, mot_de_passe, confirmation) VALUES (:nom, :email, :mot_de_passe, :confirmation)";
            $requete = $conn->prepare($sql);
            $requete->bindParam(':nom', $nom);
            $requete->bindParam(':email', $email);
            $requete->bindParam(':mot_de_passe', $mot_de_passe);
            $requete->bindParam(':confirmation', $confirmation);
            if ($requete->execute()) {
                echo "Inscription réussie";
            } else {
                echo "Échec de l'inscription";
            }
        }
    }
}

function verification_nom($nom, $conn) {
    if ($conn) {
        $recup = $conn->prepare("SELECT * FROM les_inscriptions WHERE nom=:nom");
        $recup->bindParam(':nom', $nom);
        $recup->execute();
        $tableau_les_inscriptions = $recup->fetchAll(PDO::FETCH_ASSOC);
        return count($tableau_les_inscriptions) > 0;
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Page d'Inscription</title>
</head>
<body>
   
</body>
</html>
