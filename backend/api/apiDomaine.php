<?php
require_once "../services/DomaineService.php";
header("Content-Type: application/json");
$service = new DomaineService();

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo json_encode($service->listerDomaines());
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    echo json_encode($service->ajouterDomaine());
}
if($_SERVER['REQUEST_METHOD'] == 'PUT'){
    echo json_encode($service->modifierDomaine());
}
if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    echo json_encode($service->supprimerDomaine());
}