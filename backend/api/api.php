<?php
    require_once "../services/DomaineService.php";
    require_once "../services/OffreService.php";
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    if($_SERVER["REQUEST_METHOD"]==="OPTIONS"){
        http_response_code(204);
        exit;
    }

    $methode=$_SERVER["REQUEST_METHOD"];
    $estOffres=($_GET["entity"] ?? "domaines")==="offres";
    $service=$estOffres ? new OffreService() : new DomaineService();

    if($methode=="GET"){
        echo json_encode($estOffres ? $service->listerOffres() : $service->listerDomaines(),JSON_UNESCAPED_UNICODE);
    }

    if($methode=="POST"){
        echo json_encode($estOffres ? $service->ajouterOffre() : $service->ajouterDomaine(),JSON_UNESCAPED_UNICODE);
    }

    if($methode=="PUT"){
        echo json_encode($estOffres ? $service->modifierOffre() : $service->modifierDomaine(),JSON_UNESCAPED_UNICODE);
    }

    if($methode=="DELETE"){
        echo json_encode($estOffres ? $service->supprimerOffre() : $service->supprimerDomaine(),JSON_UNESCAPED_UNICODE);
    }

    if($methode!="GET" && $methode!="POST" && $methode!="PUT" && $methode!="DELETE"){
        http_response_code(405);
        echo json_encode(["message"=>"Méthode non autorisée"],JSON_UNESCAPED_UNICODE);
    }
?>