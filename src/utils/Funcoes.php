<?php

    namespace Comercio\Api\utils;

    abstract class Funcoes {
        static function getRetorno(): array
        {
            return [
                'error' => false, 
                'menssage' => [], 
                'data' => [] 
            ];
        }
        static function getPatchData() {
            // Inicializa variável para armazenar os dados
            $data = array();
            // Verifica se a requisição é PATCH e o corpo da requisição contém dados
            if (file_get_contents('php://input')) {
                // Obtém os dados do corpo da requisição e decodifica-os
                $data = json_decode(file_get_contents('php://input'), true);
            }
            // Retorna os dados extraídos
            return $data;
        }

    }