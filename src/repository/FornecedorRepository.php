<?php

    namespace Comercio\Api\repository;

    use Comercio\Api\models\Fornecedor;
    use Comercio\Api\utils\SingletonConexao;
    use Exception;

    class FornecedorRepository {

        function Obter(Fornecedor $fornecedor, SingletonConexao $conexao): void
        {
            try {
                $sql = "
                    SELECT 
                        N_COD_FORNECEDOR                   AS CODIGO,
                        C_NOME                             AS NOME,
                        FORMATOS.GET_CPFCNPJ(N_CPNJ)       AS CNPJ,
                        C_ENDERECO                         AS ENDERECO,
                        FORMATOS.GET_TELEFONE(N_TELEFONE)  AS TELEFONE,
                        C_EMAIL                            AS EMAIL
                    FROM GER_FORNECEDOR
                    WHERE N_COD_FORNECEDOR = ':codigoFornecedor'
                ";
                $sql = str_replace(":codigoFornecedor", $fornecedor->getCodigo(), $sql);
                $row = $conexao->buscar($sql);

                $fornecedor->setCodigo($row["CODIGO"]);
                $fornecedor->setNome($row["NOME"]);
                $fornecedor->setCnpj($row["CNPJ"]);
                $fornecedor->setEndereco($row["ENDERECO"]);
                $fornecedor->setTelefone($row["TELEFONE"]);
                $fornecedor->setEmail($row["EMAIL"]);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        function ObterTodos(SingletonConexao $conexao): array
        {
            $arr = array();
            try {
                $sql = "
                    SELECT 
                        N_COD_FORNECEDOR                   AS CODIGO,
                        C_NOME                             AS NOME,
                        FORMATOS.GET_CPFCNPJ(N_CPNJ)       AS CNPJ,
                        C_ENDERECO                         AS ENDERECO,
                        FORMATOS.GET_TELEFONE(N_TELEFONE)  AS TELEFONE,
                        C_EMAIL                            AS EMAIL
                    FROM GER_FORNECEDOR
                ";
                $rows = $conexao->buscarTodos($sql);
                foreach($rows as $row) {
                    $fornecedor = new Fornecedor(
                        $row["CODIGO"],
                        $row["NOME"],
                        $row["CNPJ"],
                        $row["TELEFONE"],
                        $row["EMAIL"],
                        $row["ENDERECO"],
                    );
                    array_push($arr, $fornecedor);
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $arr;
        }
        function Adicionar(Fornecedor $fornecedor, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    INSERT INTO GER_FORNECEDOR
                        (N_COD_FORNECEDOR,
                        C_NOME, 
                        N_CPNJ, 
                        C_ENDERECO, 
                        N_TELEFONE, 
                        C_EMAIL)
                    VALUES
                        (SEQ_GER_FORNECEDOR.NEXTVAL,
                        ':nome',
                        :cnpj,
                        ':endereco',
                        :telefone,
                        ':email')
                ";
                $sql = str_replace(":nome", $fornecedor->getNome(), $sql);
                $sql = str_replace(":cnpj", $fornecedor->getNome(), $sql);
                $sql = str_replace(":endereco", $fornecedor->getEndereco(), $sql);
                $sql = str_replace(":telefone", $fornecedor->getTelefone(), $sql);
                $sql = str_replace(":email", $fornecedor->getEmail(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Alterar(Fornecedor $fornecedor, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    UPDATE GER_FORNECEDOR
                        SET N_COD_FORNECEDOR = :,
                            C_NOME = ':nome',
                            N_CPNJ = :cpnj,
                            C_ENDERECO = ':endereco',
                            N_TELEFONE = :telefone,
                            C_EMAIL = ':email'
                    WHERE N_COD_FORNECEDOR = :codigoFornecedor
                ";
                $sql = str_replace(":nome", $fornecedor->getNome(), $sql);
                $sql = str_replace(":cnpj", $fornecedor->getNome(), $sql);
                $sql = str_replace(":endereco", $fornecedor->getEndereco(), $sql);
                $sql = str_replace(":telefone", $fornecedor->getTelefone(), $sql);
                $sql = str_replace(":email", $fornecedor->getEmail(), $sql);
                $sql = str_replace(":codigoFornecedor", $fornecedor->getCodigo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Deletar(int $codigoFornecedor, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    DELETE GER_FORNECEDOR
                    WHERE N_COD_FORNECEDOR = :codigoFornecedor
                ";
                $sql = str_replace(":codigoFornecedor", $codigoFornecedor, $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
    }