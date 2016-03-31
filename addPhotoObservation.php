<?php

if (isset($_POST['id_obs']) && isset($_FILES['img'])) {

	include("config.php");
	include("verifConnexion.php");

	$ds = DIRECTORY_SEPARATOR;
	$storeFolder = '../uploads/observations/'.$_POST['id_obs'];

	if (!file_exists('path/to/directory')) {
    mkdir($storeFolder, 0755);
	}

	$filename = $_FILES["img"]["name"];

	$extension = explode('.', $filename);

	$ext = $extension[count($extension) - 1];

	$req = $bdd->prepare("INSERT INTO photos(id_user, id_obs, format) VALUES (:id_user, :id_obs, :format)");
	$req->execute(array(
		'id_user' => $id_user,
		'id_obs' => $_POST['id_obs'],
		'format' => $ext
	));

	$tempFile = $_FILES['img']['tmp_name'];

	$targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;

	$req = $bdd->prepare("SELECT id FROM photos WHERE id_obs = ? ORDER BY id DESC LIMIT 1");
	$req->execute(array($_POST['id_obs']));
	$donnees = $req->fetch();

	$targetFile =  $targetPath.$donnees['id'].".".$ext;

	move_uploaded_file($tempFile,$targetFile);

	$retour = array('status'=>0, 'message'=>'ok');


}
else {

	$retour = array("status"=>1, 'message'=>'arguments manquants');

}

if (isset($_GET['web'])) {
	header("location: ../observation.php?id_obs=".$_POST['id_obs']);
}
else {
echo json_encode($retour);
}
?>
