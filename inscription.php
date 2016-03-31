<?php  

//ERROR 0 : Champs vides
if(isset($_POST['pseudo']) && isset($_POST['pwd']) && isset($_POST['pwd2']) && isset($_POST['mail'])) {

	include('config.php');

	$status0 = "ok";

	//ERROR 1 : Pseudo

	//Alphanumériques
	if (preg_match('/^[a-zA-Z0-9_]+$/', $_POST['pseudo'])) {

		//Longueur mini 6 char
		if(strlen($_POST['pseudo']) >= 5) {

			//Existe déjà
			$reponse = $bdd->prepare("SELECT pseudo FROM utilisateurs WHERE pseudo = ?");
			$reponse->execute(array($_POST['pseudo']));
			while ($donnees = $reponse->fetch())
			{
				$pseudo_valide = $donnees['pseudo'];
			}
			if (!isset($pseudo_valide) && $_POST['pseudo'] != "mypov") {
				$status1 = "ok";
			}
			else
			{
				$status = 1;
				$message = 'Ce pseudo existe déjà.';
			}
		}
		else
		{
			$status = 1;
			$message = "Le pseudo doit faire 5 caractères minimum.";
		}
	}
	else
	{
		$status = 1;
		$message = "Le pseudo ne peut contenir que des caractères alphanumériques";
	}

	//ERROR 2 : Mail

	//Correct
	if (filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {

		//Existe déjà
		$reponse2 = $bdd->prepare("SELECT mail FROM utilisateurs WHERE mail = ?");
		$reponse2->execute(array($_POST['mail']));
		while ($donnees2 = $reponse2->fetch())
		{
			$mail_valide = $donnees2['mail'];
		}
		if (!isset($mail_valide)) {
			$status2 = "ok";
		}
		else
		{
			$status = 1;
			$message = 'Cette adresse est déjà utilisée.';	
		}
	}
	else
	{
		$status = 1;
		$message = "Adresse mail invalide.";
	}

	//ERROR 3 : Pwd identiques
	if ($_POST['pwd'] == $_POST['pwd2']) {
		$status3 = "ok";
	}
	else
	{
		$status = 1;
		$message = "Les mots de passe ne sont pas identiques.";
	}

	//INSCRIPTION

	if ($status0 == "ok" && $status1 == "ok" && $status2 == "ok" && $status3 == "ok") {
		$req = $bdd->prepare("INSERT INTO utilisateurs(pseudo, mail, mdp) VALUES (:pseudo,:mail,:pwd)");
		$req->execute(array(
			'pseudo' => $_POST['pseudo'],
			'mail' => $_POST['mail'],
			'pwd' => $_POST['pwd']
			));
		$status = 0;
		$message = "ok";
		$reponse = $bdd -> prepare("SELECT * 
									FROM utilisateurs
									WHERE mail = :mail 
									");
		$reponse -> bindParam('mail', $_POST['mail']);
		$reponse -> execute();
		$donnees = $reponse->fetch();
		$utilisateur = array(
						'id_user'=>$donnees['id'],
						'pseudo'=>$donnees['pseudo'],
						'mail'=>$donnees['mail'],
						'inscridate'=>strtotime($donnees['inscridate'])
						);
	}
}
else
{
	$status = 1;
	$message = "Veuillez remplir tous les champs.";
}

//ENVOI EN JSON

if ($status == 0) {
	$retour = array('status'=>$status, 'message'=>$message, 'object'=>$utilisateur);
} else {
	$retour = array('status'=>$status, 'message'=>$message);
}

echo json_encode($retour);

?>