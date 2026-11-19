<?php
require_once "../services/OffreService.php";
header("Content-Type: application/json");
$service = new OffreService();

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo json_encode($service->listerOffres());
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    echo json_encode($service->ajouterOffre());
}
if($_SERVER['REQUEST_METHOD'] == 'PUT'){
    echo json_encode($service->modifierOffre());
}
if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    echo json_encode($service->supprimerOffre());
}