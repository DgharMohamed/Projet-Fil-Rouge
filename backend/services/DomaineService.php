<?php
    require_once "../dao/DomaineDAO.php";
    require_once "../dao/OffreDAO.php";
    require_once "BaseService.php";
    class DomaineService extends BaseService{
        private DomaineDAO $domaineDao;
        private OffreDAO $offreDao;

        public function __construct(){
            $this->domaineDao= new DomaineDAO();
            $this->offreDao= new OffreDAO();
        }

        public function listerDomaines(){
            $id=$this->LireIdRequete();

            if($id!==null){
                $domaine=$this->domaineDao->TrouverParId($id);

                if($domaine===null){
                    http_response_code(404);
                    return ["message"=>"Domaine introuvable"];
                }

                return $domaine->toArray();
            }

            $data=[];
            foreach($this->domaineDao->AfficherTous() as $domaine){
                $data[]=$domaine->toArray();
            }
            return $data;
        }

        public function ajouterDomaine(){
            $input=$this->LireCorps();

            if($input===null){
                http_response_code(400);
                return ["message"=>"Corps de requête JSON invalide"];
            }

            try{
                $nom=$input["nom"] ?? "";

                if(!is_scalar($nom)){
                    throw new InvalidArgumentException("Le champ 'nom' est invalide");
                }

                $domaine=new Domaine($this->domaineDao->ProchainId(),(string)$nom);
                $this->domaineDao->Ajouter($domaine);

                http_response_code(201);
                return $domaine->toArray();
            }catch(InvalidArgumentException $e){
                http_response_code(422);
                return ["message"=>$e->getMessage()];
            }
        }

        public function modifierDomaine(){
            $id=$this->LireIdRequete();

            if($id===null){
                http_response_code(400);
                return ["message"=>"Paramètre id manquant"];
            }

            $existant=$this->domaineDao->TrouverParId($id);

            if($existant===null){
                http_response_code(404);
                return ["message"=>"Domaine introuvable"];
            }

            $input=$this->LireCorps();

            if($input===null){
                http_response_code(400);
                return ["message"=>"Corps de requête JSON invalide"];
            }

            try{
                $nom=$input["nom"] ?? "";

                if(!is_scalar($nom)){
                    throw new InvalidArgumentException("Le champ 'nom' est invalide");
                }

                $domaine=new Domaine($id,(string)$nom);
                $this->domaineDao->Modifier($domaine);

                return $domaine->toArray();
            }catch(InvalidArgumentException $e){
                http_response_code(422);
                return ["message"=>$e->getMessage()];
            }
        }

        public function supprimerDomaine(){
            $id=$this->LireIdRequete();

            if($id===null){
                http_response_code(400);
                return ["message"=>"Paramètre id manquant"];
            }

            $domaine=$this->domaineDao->TrouverParId($id);

            if($domaine===null){
                http_response_code(404);
                return ["message"=>"Domaine introuvable"];
            }

            $utilisees=$this->offreDao->CompterParDomaine($id);

            if($utilisees>0){
                http_response_code(409);
                return ["message"=>"Impossible de supprimer : $utilisees offre(s) rattachée(s) à ce domaine"];
            }

            $supprime=$this->domaineDao->Supprimer($id);
            return $supprime->toArray();
        }
    }
?>