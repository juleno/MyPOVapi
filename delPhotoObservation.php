<?php

if (isset($_POST['id_photo'])) {

	include("config.php");
	include("verifConnexion.php");

	$req = $bdd->prepare("SELECT photos.id_user AS photos_id_user, observations.id_user AS observations_id_user FROM photos, observations WHERE photos.id_obs = observations.id AND photos.id = ?");
	$req->execute(array($_POST['id_photo']));
	$donnees = $req->fetch();

	if ($donnees['observations_id_user'] == $id_user || $donnees['photos_id_user'] == $id_user) {

			$req = $bdd->prepare("DELETE FROM photos WHERE id = :id_photo");
			$req->execute(array(
				'id_photo' => $_POST['id_photo']
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
