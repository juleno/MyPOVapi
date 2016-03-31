<?php 

if (isset($_POST['oldpwd'])&&isset($_POST['newpwd'])&&isset($_POST['newpwd2'])) {

	include("config.php");
	include("verifConnexion.php");

	if ($_POST['oldpwd'] == $pwd) {

		if ($_POST['newpwd'] == $_POST['newpwd2']) {

			$req = $bdd->prepare("UPDATE utilisateurs SET mdp = :mdp WHERE id = :id_user");
			$req->execute(array(
				'mdp' => $_POST['newpwd'],
				'id_user' => $id_user
				));

			$retour = array('status'=>0, 'message'=>'ok');
		} else {
			$retour = array('status'=>1, 'message'=>'Les mots de passes ne sont pas identiques');
		}

	}

	else {
		$retour = array('status'=>1, 'message'=>'Mauvais ancien mot de passe');
	}

} else {
	$retour = array('status'=>1, 'message'=>'Arguments manquants');
	

}

session_start();
$_SESSION['pwd'] = $_POST['newpwd'];

echo json_encode($retour);

?>