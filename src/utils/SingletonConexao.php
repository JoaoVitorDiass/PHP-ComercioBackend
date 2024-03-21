<?php

    namespace Comercio\Api\utils;
    use Exception;

    class SingletonConexao {

        private static $instancia = null;
        private $conexao;

        private function __construct() {
            $this->conexao = oci_connect('262113651', '262113651', '177.131.33.17/XE', 'AL32UTF8');
            if (!$this->conexao) {
                $erro = oci_error();
                die("Erro na conexão com o banco de dados Oracle: " . $erro['message']);
            }
        }
        public static function getConexao()
        {
            if (self::$instancia === null) {
                self::$instancia = new self();
            }
            return self::$instancia;
        }
        public static function fecharConexao(): void
        {
            oci_close(self::$conexao);
        }
        public static function executar($query): bool
        {
            try{ 
                $stmt = oci_parse(self::$conexao, $query);
                if (!$stmt) {
                    $erro = oci_error(self::$conexao->conn);
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
        public static function buscar($query): array
        {
            try{ 
                $stmt = oci_parse(self::$conexao, $query);
                if (!$stmt) {
                    $erro = oci_error(self::$conexao);
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
        public static function buscarTodos($query): array 
        {
            try{ 
                $stmt = oci_parse(self::$conexao, $query);
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