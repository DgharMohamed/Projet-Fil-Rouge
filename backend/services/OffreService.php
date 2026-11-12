<?php
    require_once "../dao/OffreDAO.php";
    require_once "../dao/DomaineDAO.php";
    require_once "BaseService.php";
    class OffreService extends BaseService{
        private OffreDAO $offreDao;
        private DomaineDAO $domaineDao;

        public function __construct(){
            $this->offreDao= new OffreDAO();
            $this->domaineDao= new DomaineDAO();
        }

        public function listerOffres(){
            $id=$this->LireIdRequete();

            if($id!==null){
                $offre=$this->offreDao->TrouverParId($id);

                if($offre===null){
                    http_response_code(404);
                    return ["message"=>"Offre introuvable"];
                }

                return $offre->toArray();
            }

            $data=[];
            foreach($this->offreDao->AfficherTous() as $offre){
                $data[]=$offre->toArray();
            }
            return $data;
        }

        public function ajouterOffre(){
            $input=$this->LireCorps();

            if($input===null){
                http_response_code(400);
                return ["message"=>"Corps de requête JSON invalide"];
            }

            try{
                $offre=Offre::fromArray($input);
                $this->VerifierDomaineExiste($offre->getDomaineId());
                $offre->setId($this->offreDao->ProchainId());

                $this->offreDao->Ajouter($offre);

                http_response_code(201);
                return $offre->toArray();
            }catch(InvalidArgumentException $e){
                http_response_code(422);
                return ["message"=>$e->getMessage()];
            }
        }

        public function modifierOffre(){
            $id=$this->LireIdRequete();

            if($id===null){
                http_response_code(400);
                return ["message"=>"Paramètre id manquant"];
            }

            $existant=$this->offreDao->TrouverParId($id);

            if($existant===null){
                http_response_code(404);
                return ["message"=>"Offre introuvable"];
            }

            $input=$this->LireCorps();

            if($input===null){
                http_response_code(400);
                return ["message"=>"Corps de requête JSON invalide"];
            }

            try{
                $this->VerifierChamps($input,["titre","description","salaire","domaine_id"]);

                $offre=Offre::fromArray($existant->toArray());

                if(array_key_exists("titre",$input)){
                    $offre->setTitre($this->ChampTexte($input,"titre"));
                }

                if(array_key_exists("description",$input)){
                    $offre->setDescription($input["description"]);
                }

                if(array_key_exists("salaire",$input)){
                    $offre->setSalaire($input["salaire"]);
                }

                if(array_key_exists("domaine_id",$input)){
                    $offre->setDomaineId((int) $input["domaine_id"]);
                }

                $this->VerifierDomaineExiste($offre->getDomaineId());

                $this->offreDao->Modifier($offre);

                return $offre->toArray();
            }catch(InvalidArgumentException $e){
                http_response_code(422);
                return ["message"=>$e->getMessage()];
            }
        }

        public function supprimerOffre(){
            $id=$this->LireIdRequete();

            if($id===null){
                http_response_code(400);
                return ["message"=>"Paramètre id manquant"];
            }

            $offre=$this->offreDao->TrouverParId($id);

            if($offre===null){
                http_response_code(404);
                return ["message"=>"Offre introuvable"];
            }

            $supprime=$this->offreDao->Supprimer($id);
            return $supprime->toArray();
        }

        private function VerifierChamps(array $data,array $keys): void{
            foreach($keys as $key){
                if(array_key_exists($key,$data) && !is_scalar($data[$key])){
                    throw new InvalidArgumentException("Le champ '$key' est invalide");
                }
            }
        }

        private function ChampTexte(array $data,string $key): string{
            $value=$data[$key] ?? "";

            if(!is_scalar($value)){
                throw new InvalidArgumentException("Le champ '$key' est invalide");
            }

            return (string) $value;
        }

        private function VerifierDomaineExiste(int $domaineId): void{
            if(!$this->domaineDao->TrouverParId($domaineId)){
                throw new InvalidArgumentException("Le domaine sélectionné n'existe pas");
            }
        }
    }
?>