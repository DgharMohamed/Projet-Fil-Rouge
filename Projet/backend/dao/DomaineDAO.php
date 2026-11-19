<?php
    require_once "../classes/Domaine.php";
    class DomaineDAO{
        private static $dataFile="../data/Domaines.json";

        public function AfficherTous(){
            $data=[];
            foreach($this->Charger() as $domaine){
                $data[]=Domaine::fromArray($domaine);
            }
            return $data;
        }

        public function TrouverParId(int $id): ?Domaine{
            foreach($this->Charger() as $domaine){
                if((int)($domaine["id"] ?? 0)===$id){
                    return Domaine::fromArray($domaine);
                }
            }
            return null;
        }

        public function ProchainId(): int{
            $domaines=$this->Charger();
            return $domaines ? max(array_column($domaines,"id"))+1 : 1;
        }

        public function Ajouter(Domaine $domaine): void{
            $domaines=$this->Charger();
            $domaines[]=$domaine->toArray();
            $this->Enregistrer($domaines);
        }

        public function Modifier(Domaine $domaine): void{
            $domaines=$this->Charger();
            $index=array_search($domaine->getId(), array_column($domaines,"id"), true);
            if($index===false){
                throw new Error("Domaine introuvable");
            }
            $domaines[$index]=$domaine->toArray();
            $this->Enregistrer($domaines);
        }

        public function Supprimer(int $id): Domaine{
            $domaines=$this->Charger();
            $index=array_search($id, array_column($domaines,"id"), true);
            if($index===false){
                throw new Error("Domaine introuvable");
            }
            $supprime=Domaine::fromArray($domaines[$index]);
            array_splice($domaines,$index,1);
            $this->Enregistrer($domaines);
            return $supprime;
        }

        private function Charger(): array{
            if(!file_exists(self::$dataFile)){
                return [];
            }
            return json_decode(file_get_contents(self::$dataFile),true) ?: [];
        }

        private function Enregistrer(array $domaines): void{
            file_put_contents(self::$dataFile,json_encode($domaines,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        }
    }

?>