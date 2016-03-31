<?php

if (isset($_POST['mail']) && isset($_POST['pwd'])) {

	$reponse = $bdd -> prepare("SELECT *
								FROM utilisateurs
								WHERE mail = :mail
								AND mdp = :pwd
								");
	$reponse->bindValue('mail', str_replace('"', '', $_POST['mail']));
	$reponse->bindValue('pwd', str_replace('"', '', $_POST['pwd']));
	$reponse->execute();
	$donnees = $reponse->fetch();

	if (isset($donnees['mail']) && isset($donnees['mdp'])) {

		$id_user = $donnees['id'];
		$pseudo = $donnees['pseudo'];
		$mail = $donnees['mail'];
		$inscridate = strtotime($donnees['inscridate']);
		$pwd = $donnees['mdp'];

	} else {
		$retour = array("status"=>1, 'message'=>'Erreur lors de la connexion.');
		echo json_encode($retour);
		exit(1);
	}

} else {

	$retour = array("status"=>1, 'message'=>'Veuillez vous connecter.');
	echo json_encode($retour);
	exit(1);

}

?>
