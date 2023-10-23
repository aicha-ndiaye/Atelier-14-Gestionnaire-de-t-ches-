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

if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST["ajouter"])) {
    $titre = $_POST["titre"];
    $priorite = $_POST["priorite"];
    $statut = $_POST["statut"];
    $description = $_POST["description"];
    $id_nom = $_SESSION["utilisateur_id"];
    $sql = "INSERT INTO taches (titre, priorite, statut, descriptions, id_employe) VALUES (:titre, :priorite, :statut, :descriptions, :id_employe)";
    $requete = $conn->prepare($sql);
    $requete->bindParam(':titre', $titre, PDO::PARAM_STR);
    $requete->bindParam(':priorite', $priorite, PDO::PARAM_STR);
    $requete->bindParam(':statut', $statut, PDO::PARAM_STR);
    $requete->bindParam(':descriptions', $description, PDO::PARAM_STR);
    $requete->bindParam(':id_employe', $id_nom, PDO::PARAM_INT);

    if ($requete->execute()) {
        // header("Location: espaceperso.php");
        echo "tache ajouter avec succe";
        exit(); // Assurez-vous de quitter le script après la redirection
    } else {
        echo "La tâche n'a pas été ajoutée.";
    }
}


$id_nom = $_SESSION["utilisateur_id"];
$recup = $conn->prepare("SELECT * FROM taches where id_employe=:id_employe" );
$recup->bindParam(':id_employe',$id_nom,PDO::PARAM_STR);
$recup->execute();
$recu= $recup->fetchAll(PDO::FETCH_ASSOC);

// var_dump($recu);
// die();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <title>Document</title>
</head>
<body>
   <header>
     <h1>Gestion de mes taches</h1>
    <p><em><?php echo $_SESSION["nom"];   ?></em></p>
    
    </header>
    <?php foreach($recu as  $value) { 
          ?>
<form action="espacedetail.php"  method="post">
<div class="vente">
   
   <p><?php echo $value['titre'] ;?></p><br>
   <p><?php echo $value['descriptions'] ;?></p><br>
  <div class="h5">
    <h5 class="h">  priorité:<?php echo $value['priorite'] ;?></h5><br>
    <h5>   </h5>
   <h5 class="h0">  Statut : <?php echo $value['statut'] ;?></h5><br>
   </div>
   <a name="detail" href="espacedetail.php?id_tache=<?php echo $value['id_taches']; ?>">Voir les détails</a>
</div>
</form>
<?php } ?>

    <h1>Ajouter une nouvelle tache</h1>

<form action="" method="post">
    <label for="titre">titre:</label><br>
    <input class="input" type="text" name="titre"><br><br>
    <label for="priorité">priorité :</label><br>
    <select class="select" name="priorite" id="priorite"><br>µ
        <option value="haute">Haute</option><br>
        <option value="moyenne">Moyenne</option><br>
        <option value="basse">Basse</option><br>
    </select><br>
  
    <label for="statut">statut :</label><br>
    <select class="select" name="statut" id="statut"><br>
        <option value="en_cours">En cours</option><br>
        <option value="en_attente">En attente</option><br>
        <option value="terminee">Terminée</option><br>
    </select><br>

    <label for="description">description</label><br>
    <input class="input" type="textarea" name="description" id=""><br><br>
    <input class="submit" type="submit" name="ajouter" value="ajouter"><br>
    <!-- <button >se deconnecter</button>
            <a href="deconnexion.php"></a> -->
</form>

</body>
</html>