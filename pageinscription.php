<?php  
include_once("trait.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>authentification</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>CREATION DE COMPTE ET CONNEXION</h1>
    <div class="contenir">
        <div class=inscription>
            <form action="" method="post">
                <h3>CREER UN COMPTE</h3>
                <label for="nom d'utilisation">nom d'utilisation</label>
                <input type="text" name="nom" require> <br><br>
                <label for="adresse email">adresse email</label>
                <input type="email" name="email" require><br><br>
                <label for="mot de passe">mot de passe</label>
                <input type="password"name="mot_de_passe" require><br><br>
                <label for="confirmation">confirmation</label>
                <input type="password" name="confirmation" require><br><br>
                <input type="submit" name="creer_compte" value="créer un compte">

            </form>
        </div>
        <hr>
        <div class="connexion"> 
             <form action="trait.php" method="post">
            <h3>CONNEXION</h3>
            <label for="nom d'utilisateur">nom d'utilisateur</label>
            <input type="text" name="nom" placeholder="entrez votre nom d'utilisateur" require><br><br>
            <label for="mot_de_passe">mot_de_passe</label>
            <input type="password" name="mot_de_passe" placeholder="entrez votre mot de passe" require><br><br>
            <input type="submit" name="connexion" value="se connecter">
          
             </form> 
        </div>  
    </div>  
</body>
</html