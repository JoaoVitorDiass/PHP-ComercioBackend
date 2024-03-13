<?php

    namespace Comercio\Api\utils;
    use Exception;
    
    class Conexao{
            
        private $conn;

        public function __construct()
        {
            $this->conn = oci_connect('JOAO', '147147', '192.168.1.100/XE', 'AL32UTF8');

            if (!$this->conn) {
                $erro = oci_error();
                die("Erro na conexão com o banco de dados Oracle: " . $erro['message']);
            }
        }
        public function abrirConexao()
        {
            $this->conn = oci_connect('JOAO', '147147', '192.168.1.100/XE', 'AL32UTF8');

            if (!$this->conn) {
                $erro = oci_error();
                die("Erro na conexão com o banco de dados Oracle: " . $erro['message']);
            }
        }
        public function executar($query): bool
        {
            try{ 
                $stmt = oci_parse($this->conn, $query);

                if (!$stmt) {
                    $erro = oci_error($this->conn);
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
                $stmt = oci_parse($this->conn, $query);

                if (!$stmt) {
                    $erro = oci_error($this->conn);
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
                $stmt = oci_parse($this->conn, $query);

                if (!$stmt) {
                    $erro = oci_error($this->conn);
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
        public function fecharConexao() 
        {
            oci_close($this->conn);
        }

        public function verificaConexao() {
            if ( php_sapi_name() !== 'cli' ) {
                if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                    return session_status() === PHP_SESSION_ACTIVE ? true : false;
                } else {
                    return session_id() === '' ? false : true;
                }
            }
            return false;
        }
    }