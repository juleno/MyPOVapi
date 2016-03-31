<?php

if (isset($_POST['id_obs'])) {

	include("config.php");
	include("verifConnexion.php");

	$req = $bdd->prepare("SELECT * FROM notes WHERE id_obs = :id_obs AND id_user = :id_user");
	$req->execute(array(
		'id_obs' => $_POST['id_obs'],
		'id_user' => $id_user
		));
	$donnees = $req->fetch();

	if ($donnees['id_user'] == $id_user) {

			$req = $bdd->prepare("DELETE FROM notes WHERE id = :id_note");
			$req->execute(array(
				'id_note' => $donnees['id']
				));

			$retour = array('status'=>0, 'message'=>'ok');

	}
	else {

		$retour = array("status"=>1, 'message'=>'permission non accordee');

	}

}
else {

	$retour = array("status"=>1, 'message'=>'arguments manquants');

}

echo json_encode($retour);

?>
