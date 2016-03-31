<?php 

if (isset($_POST['id_obs'])&&isset($_POST['note'])) {
	
	include("config.php");
	include("verifConnexion.php");

	$req = $bdd->prepare("INSERT INTO notes(id_obs, id_user, note) VALUES (:id_obs,:id_user,:note)");
	$req->execute(array(
		'id_obs' => $_POST['id_obs'],
		'id_user' => $id_user,
		'note' => $_POST['note']
		));

	$retour = array('status'=>0, 'message'=>'ok');

	
}
else {

	$retour = array("status"=>1, 'message'=>'arguments manquants');

}

echo json_encode($retour);

?>