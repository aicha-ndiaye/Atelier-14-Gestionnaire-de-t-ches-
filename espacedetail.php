<?php
session_start();


    // Connexion à la base de données
    $servername = 'localhost';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO("mysql:host=$servername;dbname=gestion_des_taches", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
   // ...
$tache_id = $_GET["id_tache"];

// Effectuez une requête SQL pour obtenir les détails de la tâche avec cet ID
$query = $conn->prepare("SELECT * FROM taches WHERE id_taches = :id_tache");
$query->bindParam(':id_tache', $tache_id, PDO::PARAM_INT);
$query->execute();

// Récupérez les détails de la tâche
$tache = $query->fetch(PDO::FETCH_ASSOC);

// ... (le reste de votre code pour afficher les détails de la tâche)





// ... (le reste de votre code pour afficher la page des détails)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
<div class="tache">
<h1>Detail : <?php echo $tache['titre']; ?></h1>
</div>
    <div class="details">
        <p>Titre : <?php echo $tache['titre']; ?></p>
        <p>Description : <?php echo $tache['descriptions']; ?></p>
        <p>Priorité : <?php echo $tache['priorite']; ?></p>
        <p>État : <?php echo $tache['statut']; ?></p>

        <p>Connecté en tant que : <?php echo $_SESSION['nom']; ?></p>

        <div class="button-container">
    <form method="post" action="marque_terminer.php">
        <input type="hidden" name="tache_id" value="<?php echo $tache_id; ?>">
        <input class="terminer" type="submit" name="marque_terminer" value="Marquer comme Terminé">
    </form>

    <form method="post" action="supprimertache.php">
        <input type="hidden" name="tache_id" value="<?php echo $tache_id; ?>">
        <input class="supprimer" type="submit" name="supprimer_tache" value="Supprimer">
       
    </form>
    
</div>
<div class="retour">
    <a href="GestionTache.php">Retour a la Gestion des tache</a>
    </div>
    </div>
</body>

</html>
