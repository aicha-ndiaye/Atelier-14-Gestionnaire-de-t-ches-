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

function verification_nom($nom)
{
    global $conn;
    if ($conn) {
        $recup = $conn->prepare("SELECT * FROM les_inscriptions");
        $recup->execute();
        $tableau_les_inscriptions = $recup->fetchAll(PDO::FETCH_ASSOC);

        $bool = false;
        foreach ($tableau_les_inscriptions as $table) {
            if ($table["nom"] == $nom) {
                $bool = true;
                break;
            }
        }
        return $bool;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['ajouter'])) {
        $titre = $_POST["titre"];
        $priorite = $_POST["priorite"];
        $statut = $_POST["statut"];
        $description = $_POST["description"];
        $id_employe=$_POST["boutton"];
      
        $erreur = [];

        if (!preg_match('/^[A-Za-z][A-Za-z ]*$/', $titre)) {
            $erreur[] = "Veuillez entrer un titre valide.";
        }
        if (empty($priorite)) {
            $erreur[] = "Le champ priorité ne peut pas être vide.";
        }
        if (empty($statut)) {
            $erreur[] = "Le champ statut ne peut pas être vide.";
        }
        if (empty($description)) {
            $erreur[] = "Le champ description ne peut pas être vide.";
        }

        if (!empty($erreur)) {
            print_r($erreur);
        } 
        
    }
}

    if (isset($_POST["creer_compte"])) {
        $nom = $_POST["nom"];
        $email = $_POST["email"];
        $mot_de_passe =$_POST["mot_de_passe"];
        $confirmation = $_POST["confirmation"] ;
        $erreur = [];

        // Validation pour la création de compte
        if (!preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $nom)) {
            $erreur[] = "Veuillez entrer un nom valide";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur[] = "Veuillez entrer une adresse email valide";
        }
        if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $mot_de_passe)) {
            $erreur[] = "Veuillez entrer un mot de passe valide";
        }
        if ($mot_de_passe !== $confirmation) {
            $erreur[] = "Les mots de passe ne correspondent pas";
        }
        if (verification_nom($nom)) {   //j'appel ici la fonction verification nom
            $erreur[] = "Nom d'utilisateur existe déjà";
        }

        if (!empty($erreur)) {
            print_r($erreur);
            
        } else {
            $newp= md5( $mot_de_passe);
            $conf=md5($confirmation);
            $sql = "INSERT INTO les_inscriptions (nom, email, mot_de_passe, confirmation) VALUES (:nom, :email, :mot_de_passe, :confirmation)";
            $requete = $conn->prepare($sql);
            $requete->bindParam(':nom', $nom);
            $requete->bindParam(':email', $email);
            $requete->bindParam(':mot_de_passe', $newp);
            $requete->bindParam(':confirmation', $conf);
            if ($requete->execute()) {
                echo "Inscription réussie";
               
            } else {
                echo "Échec de l'inscription";
            }
      }
    } elseif (isset($_POST["connexion"])) {
        $nom = $_POST['nom'];
        $mot_de_passe = $_POST['mot_de_passe'];
        $erreur = [];

        // Validation pour la connexion
        if (!preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $nom)) {
            $erreur[] = "Veuillez entrer un nom valide";
        }
        if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $mot_de_passe)) {
            $erreur[] = "Veuillez entrer un Mot de passe valide";
        }

        if (!empty($erreur)) {
            print_r($erreur);
        } else {

            $newp= md5( $mot_de_passe);
            $req = "SELECT * FROM les_inscriptions WHERE nom=:nom";
            $requete = $conn->prepare($req);
            $requete->bindParam(':nom', $nom);
            $requete->execute();
            $user = $requete->fetch();
            
            if ($user && $newp === $user['mot_de_passe']) {
           
              $_SESSION["utilisateur_id"]=$user["id_nom"];
              $_SESSION["nom"] = $user["nom"];
              header("Location:espaceperso.php");

              // $_SESSION['id']=$req;

            } else {
                echo "Échec de la connexion";
            }
        }
    }

