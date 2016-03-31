<?php

if (isset($_POST['lat'])&&isset($_POST['lng'])&&isset($_POST['nom'])&&isset($_POST['description'])&&isset($_POST['date'])) {

	include("config.php");
	include("verifConnexion.php");

	$req = $bdd->prepare("INSERT INTO observations(id, publidate, id_user, lat, lng, nom, description) VALUES (NULL, :publidate, :id_user,:lat,:lng,:nom,:description)");
	$req->execute(array(
		'id_user' => $id_user,
		'lat' => $_POST['lat'],
		'lng' => $_POST['lng'],
		'nom' => $_POST['nom'],
		'description' => $_POST['description'],
		'publidate' => $_POST['date']
		));

	/*$req = $bdd->prepare("SELECT id FROM observations WHERE id_user = :id_user AND lat = :lat AND lng = :lng AND nom = :nom AND description = :description");
	$req->execute(array(
		'id_user' => $id_user,
		'lat' => $_POST['lat'],
		'lng' => $_POST['lng'],
		'nom' => $_POST['nom'],
		'description' => $_POST['description']
		));
	$donnees = $req->fetch();*/

	$retour = array('status'=>0, 'message'=>'ok', 'object'=>array('id'=>$bdd->lastInsertId()));


}
else {

	$retour = array('status'=>1, 'message'=>'arguments manquants');

}

echo json_encode($retour);

?>
