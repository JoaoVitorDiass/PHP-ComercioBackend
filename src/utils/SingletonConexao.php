<?php

    namespace Comercio\Api\utils;
    use Exception;

    class SingletonConexao {

        private static ?SingletonConexao $instancia = null;
        private $conexao;

        private function __construct() {
            $this->conexao = oci_connect('262113651', '262113651', '177.131.33.17/XE', 'AL32UTF8');
            if (!$this->conexao) {
                $erro = oci_error();
                die("Erro na conexão com o banco de dados Oracle: " . $erro['message']);
            }
        }
        // Evita a clonagem da instância
        private function __clone() {}

        // Evita a desserialização da instância
        public function __wakeup() {}
        
        public static function getInstancia()
        {
            if (self::$instancia === null) {
                self::$instancia = new self();
            }
            if(self::$instancia->conexao) {
                self::$instancia->conexao = oci_connect('262113651', '262113651', '177.131.33.17/XE', 'AL32UTF8');
                if (!self::$instancia->conexao) {
                    $erro = oci_error();
                    die("Erro na conexão com o banco de dados Oracle: " . $erro['message']);
                }
            }
            return self::$instancia;
        }
        public function getConexao()
        {   
            return $this->conexao;
        }
        public function fecharConexao(): void
        {
            oci_close($this->conexao);
        }
        public function executar($query): bool
        {
            try{ 
                $stmt = oci_parse($this->conexao, $query);
                if (!$stmt) {
                    $erro = oci_error($this->conexao);
                    throw new Exception("Erro ao preparar a query: " . $erro['message']);
                }
                $rs = oci_execute($stmt);
                if ($rs === false) {
                    $erro = oci_error($stmt);
                    throw new Exception("Erro ao executar a query: " . $erro['message']);
                }
                if(oci_num_rows($stmt) == 0) {
                    return false;
                }
                oci_free_statement($stmt);
                return true;
            }
            catch(Exception $e) {
                throw new Exception($e->getMessage());
            }
            return false;
        }
        public function buscar($query): array
        {
            try{ 
                $stmt = oci_parse($this->conexao, $query);
                if (!$stmt) {
                    $erro = oci_error($this->conexao);
                    throw new Exception("Erro ao preparar a query: " . $erro['message']);
                }
                $rs = oci_execute($stmt);
                if ($rs === false) {
                    $erro = oci_error($stmt);
                    throw new Exception("Erro ao executar a query: " . $erro['message']);
                }
                $row = oci_fetch_assoc($stmt);
                oci_free_statement($stmt);
                return $row;
            }
            catch(Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        public function buscarTodos($query): array 
        {
            try{ 
                $stmt = oci_parse($this->conexao, $query);
                if (!$stmt) {
                    $erro = oci_error(self::$conexao);
                    throw new Exception("Erro ao preparar a query: " . $erro['message']);
                }
                $rs = oci_execute($stmt);
                if ($rs === false) {
                    $erro = oci_error($stmt);
                    throw new Exception("Erro ao executar a query: " . $erro['message']);
                }
                $row = array();
                oci_fetch_all($stmt, $row, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                oci_free_statement($stmt);
                return $row;
            }
            catch(Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }