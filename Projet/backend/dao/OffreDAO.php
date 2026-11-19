<?php
    require_once "../classes/Offre.php";
    class OffreDAO{
        private static $dataFile="../data/Offres.json";

        public function AfficherTous(){
            $data=[];
            foreach($this->Charger() as $offre){
                $data[]=Offre::fromArray($offre);
            }
            return $data;
        }

        public function TrouverParId(int $id): ?Offre{
            foreach($this->Charger() as $offre){
                if((int)($offre["id"] ?? 0)===$id){
                    return Offre::fromArray($offre);
                }
            }
            return null;
        }

        public function ProchainId(): int{
            $offres=$this->Charger();
            return $offres ? max(array_column($offres,"id"))+1 : 1;
        }

        public function CompterParDomaine(int $domaineId): int{
            $compteur=0;
            foreach($this->Charger() as $offre){
                if((int)($offre["domaine_id"] ?? 0)===$domaineId){
                    $compteur++;
                }
            }
            return $compteur;
        }

        public function Ajouter(Offre $offre): void{
            $offres=$this->Charger();
            $offres[]=$offre->toArray();
            $this->Enregistrer($offres);
        }

        public function Modifier(Offre $offre): void{
            $offres=$this->Charger();
            $index=array_search($offre->getId(), array_column($offres,"id"), true);
            if($index===false){
                throw new Error("Offre introuvable");
            }
            $offres[$index]=$offre->toArray();
            $this->Enregistrer($offres);
        }

        public function Supprimer(int $id): Offre{
            $offres=$this->Charger();
            $index=array_search($id, array_column($offres,"id"), true);
            if($index===false){
                throw new Error("Offre introuvable");
            }
            $supprime=Offre::fromArray($offres[$index]);
            array_splice($offres,$index,1);
            $this->Enregistrer($offres);
            return $supprime;
        }

        private function Charger(): array{
            if(!file_exists(self::$dataFile)){
                return [];
            }
            return json_decode(file_get_contents(self::$dataFile),true) ?: [];
        }

        private function Enregistrer(array $offres): void{
            file_put_contents(self::$dataFile,json_encode($offres,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        }
    }

?>