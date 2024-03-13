<?php

    namespace Comercio\Api\repository;

    use Comercio\Api\models\Produto;
    use Comercio\Api\models\Fornecedor;
    use Comercio\Api\utils\Conexao;
    use Exception;

    class ProdutoRepository {

        function ObterProduto(int $codigoProduto, Conexao $conexao): ?Produto
        {
            $produto = null;
            try {
                $sql = "
                    SELECT 
                        N_COD_PRODUTO     AS CODIGO,
                        N_COD_FORNECEDOR  AS CODIGO_FORNECEDOR,
                        C_DESCRICAO       AS DESCRICAO,
                        V_VLR_CUSTO       AS VALOR_CUSTO,
                        V_VLR_VENDA       AS VALOR_VENDA,
                        N_QTD_ESTOQUE     AS QUANTIDADE_ESTOQUE,
                        N_ESTOQUE_MINIMO  AS ESTOQUE_MINIMO
                    FROM GER_PRODUTO
                    WHERE N_COD_USUARIO = ':codigoProduto'
                ";
                $sql = str_replace(":codigoProduto", $codigoProduto, $sql);
                $row = $conexao->buscar($sql);

                $produto = new Produto(
                    $row["CODIGO"],
                    $row["DESCRICAO"],
                    $row["VALOR_CUSTO"],
                    $row["VALOR_VENDA"],
                    $row["QUANTIDADE_ESTOQUE"],
                    $row["ESTOQUE_MINIMO"],
                    new Fornecedor($row["CODIGO_FORNECEDOR"]),
                );
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $produto;
        }
        function ObterTodos(Conexao $conexao): array
        {
            $arr = array();
            try {
                $sql = "
                    SELECT 
                        N_COD_PRODUTO     AS CODIGO,
                        N_COD_FORNECEDOR  AS CODIGO_FORNECEDOR,
                        C_DESCRICAO       AS DESCRICAO,
                        V_VLR_CUSTO       AS VALOR_CUSTO,
                        V_VLR_VENDA       AS VALOR_VENDA,
                        N_QTD_ESTOQUE     AS QUANTIDADE_ESTOQUE,
                        N_ESTOQUE_MINIMO  AS ESTOQUE_MINIMO
                    FROM GER_PRODUTO
                ";
                $rows = $conexao->buscarTodos($sql);
                foreach($rows as $row) {
                    $produto = new Produto(
                        $row["CODIGO"],
                        $row["DESCRICAO"],
                        $row["VALOR_CUSTO"],
                        $row["VALOR_VENDA"],
                        $row["QUANTIDADE_ESTOQUE"],
                        $row["ESTOQUE_MINIMO"],
                        new Fornecedor($row["CODIGO_FORNECEDOR"]),
                    );
                    array_push($arr, $produto);
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $arr;
        }
        function Adicionar(Produto $produto, Conexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    INSERT INTO GER_PRODUTO
                        (N_COD_PRODUTO,
                        N_COD_FORNECEDOR,
                        C_DESCRICAO,
                        V_VLR_CUSTO,
                        V_VLR_VENDA,
                        N_QTD_ESTOQUE,
                        N_ESTOQUE_MINIMO)
                    VALUES
                        (SEQ_GER_PRODUTO.NEXTVAL,
                        ':codigoFornecedor',
                        ':descricao',
                        ':valor_custo',
                        ':valor_venda',
                        ':quantidade_estoque',
                        ':estoque_minimo')
                ";
                $sql = str_replace(":codigoFornecedor", $produto->getFornecedor()->getCodigo(), $sql);
                $sql = str_replace(":descricao", $produto->getDescricao(), $sql);
                $sql = str_replace(":valor_custo", $produto->getValorCusto(), $sql);
                $sql = str_replace(":valor_venda", $produto->getValorVenda(), $sql);
                $sql = str_replace(":quantidade_estoque", $produto->getQuantidadeEstoque(), $sql);
                $sql = str_replace(":estoque_minimo", $produto->getEstoqueMinimo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Alterar(Produto $produto, Conexao $conexao): bool
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