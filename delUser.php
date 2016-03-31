<?php 

include("config.php");
include("verifConnexion.php");

$req = $bdd->prepare("DELETE FROM utilisateurs WHERE id=:id_user");
$req->execute(array(
	'id_user' => $id_user
	));

$retour = array('status'=>0, 'message'=>'ok');
echo json_encode($retour);

?>