<?php
    abstract class BaseService {
        protected function LireCorps(): ?array{
            $input=json_decode(file_get_contents("php://input"),true);
            return is_array($input) ? $input : null;
        }

        protected function LireIdRequete(): ?int{
            return isset($_GET["id"]) && $_GET["id"]!=="" ? (int)$_GET["id"] : null;
        }
    }
?>