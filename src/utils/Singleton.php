<?php

    namespace Comercio\Api\utils;

    use Comercio\Api\utils\Conexao;

    abstract class Singleton {
        private static Conexao $conn;

        private function __construct() { 

            
        }

        public static function getConexao(): Conexao
        {
            if(!isset(self::$conn)) {
                self::$conn = new Conexao();
            }
            if(!self::$conn->verificaConexao()) {
                self::$conn->abrirConexao();
            }
            return self::$conn;
        }
        





        public static function fecharConexao(): void
        {
            self::$conn->fecharConexao();
        }
    }