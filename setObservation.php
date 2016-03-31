<?php

if (isset($_POST['lat'])&&isset($_POST['lng'])&&isset($_POST['nom'])&&isset($_POST['description'])&&isset($_POST['date'])&&isset($_POST['id_obs'])) {

	include("config.php");
	include("verifConnexion.php");

	$req = $bdd->prepare("SELECT id_user FROM observations WHERE id = ?");
	$req->execute(array($_POST['id_obs']));
	$donnees = $req->fetch();

	if ($donnees['id_user'] == $id_user) {

		$req = $bdd->prepare("UPDATE observations SET publidate=:publidate, lat=:lat, lng=:lng, nom=:nom, description=:description WHERE id = :id_obs");
		$req->execute(array(
			'publidate' => $_POST['date'],
			'lat' => $_POST['lat'],
			'lng' => $_POST['lng'],
			'nom' => $_POST['nom'],
			'description' => $_POST['description'],
			'id_obs' => $_POST['id_obs']
			));

		$donnees['id_obs'] = $_POST['id_obs'];
		$donnees['pseudo'] = $pseudo;

		$reponse2 = $bdd -> prepare("SELECT `photos`.id as id_photo,
			publidate,
			id_obs,
			id_user,
			format,
			pseudo
			FROM photos, utilisateurs
			WHERE `photos`.id_user = `utilisateurs`.id
			AND id_obs = ?");
		$reponse2 -> execute(array($donnees['id_obs']));

		while ($donnees2 = $reponse2->fetch()) {

			if ($donnees2['id_obs'] == $donnees['id_obs']) {
				$utilisateur  = array('id_user'=>$donnees2['id_user'],
					'pseudo'=>$donnees2['pseudo']
					);
				$photos[] = array('id'=>$donnees2['id_photo'],
					'publidate'=>strtotime($donnees2['publidate']),
					'utilisateur'=>$utilisateur,
					'format'=>$donnees2['format']
					);
			}

		}

		$reponse3 = $bdd -> prepare("SELECT COUNT(id) as nbAffirmation
			FROM notes
			WHERE id_obs = ? AND note = 1");
		$reponse3 -> execute(array($donnees['id_obs']));
		$donnees3 = $reponse3->fetch();

		$reponse4 = $bdd -> prepare("SELECT COUNT(id) as nbInfirmation
			FROM notes
			WHERE id_obs = ? AND note = 0");
		$reponse4 -> execute(array($donnees['id_obs']));
		$donnees4 = $reponse4->fetch();

		$reponse5 = $bdd -> prepare("SELECT COUNT(id) as nbCommentaires
			FROM commentaires
			WHERE id_observation = ?");
		$reponse5 -> execute(array($donnees['id_obs']));
		$donnees5 = $reponse5->fetch();

		$nbCommentaires = $donnees5['nbCommentaires'];

		$notes = array('nb_affirmations'=>$donnees3['nbAffirmation'],
			'nb_infirmations'=>$donnees4['nbInfirmation']
			);

		$observateur = array('id_user'=>$donnees['id_user'],
			'pseudo'=>$donnees['pseudo']
			);

		$observation = array('id'=>$donnees['id_obs'],
			'publidate'=>$_POST['date'],
			'lat'=>$_POST['lat'],
			'lng'=>$_POST['lng'],
			'nom'=>$_POST['nom'],
			'description'=>$_POST['description'],
			'observateur'=>$observateur,
			'photos'=>$photos,
			'nb_commentaires'=>$nbCommentaires,
			'notes'=>$notes
			);
		unset($photos);
		unset($notes);

		$retour = array('status'=>0, 'message'=>'ok', 'object'=>$observation);

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
