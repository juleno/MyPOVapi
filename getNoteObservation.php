<?php

if (isset($_POST['id_obs'])) {

	include("config.php");
	include("verifConnexion.php");

	$whereIdUser = "";

	if (isset($_POST['id_user'])) {
		$whereIdUser = "AND id_user = :id_user";
	}

	$req = $bdd->prepare("SELECT * FROM notes WHERE id_obs = :id_obs " . $whereIdUser . " ");
	$req -> bindParam(':id_obs', $_POST['id_obs']);
	if (isset($_POST['id_user'])) {
		$req -> bindParam(':id_user', $_POST['id_user']);
	}
	$req->execute();
	while($donnees = $req->fetch()) {
		$notes[] = array('id'=>$donnees['id'],
						'id_user'=>$donnees['id_user'],
						'note'=>$donnees['note']
						);
	}
	$retour = array('status'=>0,
		'message'=>'ok',
		'object'=>$notes);

	

}
else {

	$retour = array("status"=>1, 'message'=>'arguments manquants');

}

echo json_encode($retour);

?>
