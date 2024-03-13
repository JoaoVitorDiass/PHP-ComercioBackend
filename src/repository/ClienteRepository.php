<?php

    namespace Comercio\Api\repository;

    use Comercio\Api\models\Cliente;
    use Comercio\Api\utils\Conexao;
    use Exception;

    class ClienteRepository {
        function ObterCliente(int $codigoCliente, Conexao $conexao): ?Cliente
        {
            $cliente = null;
            try {
                $sql = "
                    SELECT 
                        N_COD_CLIENTE                       AS CODIGO,
                        C_NOME                              AS NOME,
                        FORMATOS.GET_CPFCNPJ(N_CPF)         AS CPF,
                        TO_CHAR(D_NASCIMENTO,'DD/MM/YYYY')  AS DATA_NASCIMENTO,
                        FORMATOS.GET_TELEFONE(N_TELEFONE)   AS TELEFONE,
                        FORMATOS.GET_RG(N_RG)               AS RG,
                        C_EMAIL                             AS EMAIL,
                        C_ENDERECO                          AS ENDERECO
                    FROM GER_CLIENTE T
                    WHERE N_COD_CLIENTE = ':codigoCliente'
                ";
                $sql = str_replace(":codigoCliente", $codigoCliente, $sql);
                $row = $conexao->buscar($sql);
                $cliente = new Cliente(
                    $row["CODIGO"],
                    $row["NOME"],
                    $row["CPF"],
                    $row["DATA_NASCIMENTO"],
                    $row["TELEFONE"],
                    $row["RG"],
                    $row["EMAIL"],
                    $row["ENDERECO"],
                );
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $cliente;
        }
        function ObterTodos(Conexao $conexao): array
        {
            $arr = array();
            try {
                $sql = "
                    SELECT 
                        N_COD_CLIENTE                       AS CODIGO,
                        C_NOME                              AS NOME,
                        FORMATOS.GET_CPFCNPJ(N_CPF)         AS CPF,
                        TO_CHAR(D_NASCIMENTO,'DD/MM/YYYY')  AS DATA_NASCIMENTO,
                        FORMATOS.GET_TELEFONE(N_TELEFONE)   AS TELEFONE,
                        FORMATOS.GET_RG(N_RG)               AS RG,
                        C_EMAIL                             AS EMAIL,
                        C_ENDERECO                          AS ENDERECO
                    FROM GER_CLIENTE T
                ";
                $rows = $conexao->buscarTodos($sql);
                foreach($rows as $row) {
                    $cliente = new Cliente(
                        $row["CODIGO"],
                        $row["NOME"],
                        $row["CPF"],
                        $row["DATA_NASCIMENTO"],
                        $row["TELEFONE"],
                        $row["RG"],
                        $row["EMAIL"],
                        $row["ENDERECO"],
                    );
                    array_push($arr, $cliente);
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $arr;
        }
        function Adicionar(Cliente $cliente, Conexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    INSERT INTO GER_CLIENTE
                        (N_COD_CLIENTE,
                        C_NOME,
                        N_CPF,
                        D_NASCIMENTO,
                        N_TELEFONE,
                        N_RG,
                        C_EMAIL,
                        C_ENDERECO)
                    VALUES
                        (SEQ_GER_CLIENTE.NEXTVAL,
                        UPPER(':nome'),
                        FORMATOS.CLEAR_FORMAT(':cpf'),
                        ':data_nascimento',
                        FORMATOS.CLEAR_FORMAT(':telefone'),
                        FORMATOS.CLEAR_FORMAT(':rg'),
                        ':email',
                        ':endereco')
                ";
                $sql = str_replace(":nome", $cliente->getNome(), $sql);
                $sql = str_replace(":cpf", $cliente->getCpf(), $sql);
                $sql = str_replace(":data_nascimento", $cliente->getDataNascimento(), $sql);
                $sql = str_replace(":telefone", $cliente->getTelefone(), $sql);
                $sql = str_replace(":rg", $cliente->getRg(), $sql);
                $sql = str_replace(":email", $cliente->getEmail(), $sql);
                $sql = str_replace(":endereco", $cliente->getEndereco(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Alterar(Cliente $cliente, Conexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    UPDATE GER_CLIENTE
                        SET C_NOME = UPPER(':nome'),
                            N_CPF = FORMATOS.CLEAR_FORMAT(':cpf'),
                            D_NASCIMENTO = ':data_nascimento',
                            N_TELEFONE = FORMATOS.CLEAR_FORMAT(':telefone'),
                            N_RG = FORMATOS.CLEAR_FORMAT(':rg'),
                            C_EMAIL = ':email',
                            C_ENDERECO = ':endereco'
                    WHERE N_COD_CLIENTE = ':codigoCliente'
                ";
                $sql = str_replace(":nome", $cliente->getNome(), $sql);
                $sql = str_replace(":cpf", $cliente->getCpf(), $sql);
                $sql = str_replace(":data_nascimento", $cliente->getDataNascimento(), $sql);
                $sql = str_replace(":telefone", $cliente->getTelefone(), $sql);
                $sql = str_replace(":rg", $cliente->getRg(), $sql);
                $sql = str_replace(":email", $cliente->getEmail(), $sql);
                $sql = str_replace(":endereco", $cliente->getEndereco(), $sql);
                $sql = str_replace(":codigoCliente", $cliente->getCodigo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Deletar(int $codigoCliente, Conexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    DELETE GER_CLIENTE
                    WHERE N_COD_CLIENTE = ':codigoCliente'
                ";
                $sql = str_replace(":codigoCliente", $codigoCliente, $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
    }