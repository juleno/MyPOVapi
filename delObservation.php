<?php

if (isset($_POST['id_obs'])) {

	include("config.php");
	include("verifConnexion.php");

	$req = $bdd->prepare("SELECT id_user FROM observations WHERE id = ?");
	$req->execute(array($_POST['id_obs']));
	$donnees = $req->fetch();

	if ($donnees['id_user'] == $id_user) {

			$req = $bdd->prepare("DELETE FROM observations WHERE id = :id_obs");
			$req->execute(array(
				'id_obs' => $_POST['id_obs']
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
