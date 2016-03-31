<?php 

if (isset($_POST['id_obs'])) {

	include("config.php");
	include("verifConnexion.php");
	
	$reponse = $bdd -> prepare("SELECT `commentaires`.id as id_com, 
										publidate, 
										texte, 
										id_observation, 
										id_user, 
										pseudo 
										FROM commentaires, utilisateurs 
										WHERE `commentaires`.id_user = `utilisateurs`.id 
										AND id_observation = :id_obs 
										ORDER BY publidate DESC");
	$reponse -> bindParam('id_obs', $_POST['id_obs']);
	$reponse -> execute();

	while ($donnees = $reponse->fetch()) {

		$utilisateur  = array('id_user'=>$donnees['id_user'],
							 'pseudo'=>$donnees['pseudo']
							);
		$commentaires[] = array('id'=>$donnees['id_com'],
								'publidate'=>strtotime($donnees['publidate']),
								'texte'=>$donnees['texte'],
								'utilisateur'=>$utilisateur
								);
	}

	$retour = array('status'=>0,
		'message'=>'ok',
		'object'=>$commentaires);

}

else {

	$retour = array("status"=>1,
					'message'=>'arguments manquants');

}

echo json_encode($retour);

 ?>