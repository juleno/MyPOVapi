<?php 

if (isset($_POST['id_obs'])) {

	include("config.php");
	include("verifConnexion.php");
	
	$reponse = $bdd -> prepare("SELECT `photos`.id as id_photo, 
										publidate, 
										id_obs, 
										id_user, 
										format,
										pseudo
										FROM photos, utilisateurs 
										WHERE `photos`.id_user = `utilisateurs`.id 
										AND id_obs = :id_obs");
	$reponse -> bindParam('id_obs', $_POST['id_obs']);
	$reponse -> execute();

	while ($donnees = $reponse->fetch()) {

		$utilisateur  = array('id_user'=>$donnees['id_user'],
							 'pseudo'=>$donnees['pseudo']
							);
		$photos[] = array('id'=>$donnees['id_photo'],
								'publidate'=>strtotime($donnees['publidate']),
								'utilisateur'=>$utilisateur,
								'format'=>$donnees['format']
								);
	}

	$retour = array('status'=>0,
		'message'=>'ok',
		'object'=>$photos);

}

else {

	$retour = array("status"=>1,
					'message'=>'arguments manquants');

}

echo json_encode($retour);

 ?>