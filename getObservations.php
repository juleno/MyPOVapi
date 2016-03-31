<?php

if (isset($_POST['lat']) && isset($_POST['lng']) && isset($_POST['distance'])) {

	include("config.php");
	include("verifConnexion.php");

	$whereIdUser = "";

	if (isset($_POST['id_user'])) {
		$whereIdUser = "AND id_user = :id_user";
	}

	$reponse = $bdd -> prepare("SELECT 	`observations`.id AS id_obs,
		publidate,
		lat,
		lng,
		id_user,
		nom,
		description,
		pseudo,
		( 6371 * acos( cos( radians(:lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(:lng) ) + sin( radians(:lat) ) * sin( radians( lat ) ) ) ) AS distance
		FROM observations, utilisateurs
		WHERE `observations`.id_user = `utilisateurs`.id "
		. $whereIdUser .
		" HAVING distance < :distance
		ORDER BY distance");
	$reponse -> bindParam(':lat', $_POST['lat']);
	$reponse -> bindParam(':lng', $_POST['lng']);
	$reponse -> bindParam(':distance', $_POST['distance']);
	$reponse -> bindParam(':id_user', $_POST['id_user']);
	$reponse -> execute();


	while ($donnees = $reponse->fetch()) {

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

		$observations[] = array('id'=>$donnees['id_obs'],
			'publidate'=>$donnees['publidate'],
			'lat'=>$donnees['lat'],
			'lng'=>$donnees['lng'],
			'nom'=>$donnees['nom'],
			'description'=>$donnees['description'],
			'observateur'=>$observateur,
			'photos'=>$photos,
			'nb_commentaires'=>$nbCommentaires,
			'notes'=>$notes
			);
		unset($photos);
		unset($notes);

	}

	$retour = array('status'=>0,
		'message'=>'ok',
		'object'=>$observations);

}

else {

	$retour = array('status'=>1,
		'message'=>'arguments manquants');

}

echo json_encode($retour);

?>
