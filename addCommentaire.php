<?php 

if (isset($_POST['id_obs'])&&isset($_POST['texte'])) {
	
	include("config.php");
	include("verifConnexion.php");

	$req = $bdd->prepare("INSERT INTO commentaires(id_observation, id_user, texte) VALUES (:id_obs,:id_user,:texte)");
	$req->execute(array(
		'id_obs' => $_POST['id_obs'],
		'id_user' => $id_user,
		'texte' => $_POST['texte']
		));

	$retour = array('status'=>0, 'message'=>'ok');

	
}
else {

	$retour = array("status"=>1, 'message'=>'arguments manquants');

}

echo json_encode($retour);

?>