<?php 

include("config.php");
include("verifConnexion.php");

$utilisateur = array('id_user'=>$id_user,
						'pseudo'=>$pseudo,
						'mail'=>$mail,
						'inscridate'=>$inscridate);

// POUR LE SITE //
session_start();
$_SESSION['id_user'] = $id_user;
$_SESSION['pseudo'] = $pseudo;
$_SESSION['mail'] = $mail;
$_SESSION['pwd'] = $pwd;
$_SESSION['inscridate'] = $inscridate;
$_SESSION['politesse'] = true;
// ------------ //

$retour = array('status'=>0, 'message'=>'ok', 'object'=>$utilisateur);
echo json_encode($retour);

 ?>