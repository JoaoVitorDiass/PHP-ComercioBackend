<?php

    namespace Comercio\Api\repository;

    use Comercio\Api\models\Produto;
    use Comercio\Api\models\Fornecedor;
    use Comercio\Api\utils\SingletonConexao;
    use Exception;

    class ProdutoRepository {

        function Obter(Produto $produto, SingletonConexao $conexao): void
        {
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
                    WHERE N_COD_PRODUTO = ':codigoProduto'
                ";
                $sql = str_replace(":codigoProduto", $produto->getCodigo(), $sql);
                $row = $conexao->buscar($sql);

                $produto->setCodigo($row["CODIGO"]);
                $produto->setDescricao($row["DESCRICAO"]);
                $produto->setValorCusto((float)str_replace(",",".",str_replace(".","",$row["VALOR_CUSTO"])));
                $produto->setValorVenda((float)str_replace(",",".",str_replace(".","",$row["VALOR_VENDA"])));
                $produto->setQuantidadeEstoque($row["QUANTIDADE_ESTOQUE"], array(), false);
                $produto->setEstoqueMinimo($row["ESTOQUE_MINIMO"]);
                $produto->setFornecedor(new Fornecedor($row["CODIGO_FORNECEDOR"]));
                // echo $sql; exit;
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
                        N_COD_PRODUTO     AS CODIGO,
                        N_COD_FORNECEDOR  AS CODIGO_FORNECEDOR,
                        C_DESCRICAO       AS DESCRICAO,
                        V_VLR_CUSTO       AS VALOR_CUSTO,
                        V_VLR_VENDA       AS VALOR_VENDA,
                        N_QTD_ESTOQUE     AS QUANTIDADE_ESTOQUE,
                        N_ESTOQUE_MINIMO  AS ESTOQUE_MINIMO
                    FROM GER_PRODUTO
                    ORDER BY N_COD_PRODUTO DESC
                ";
                $rows = $conexao->buscarTodos($sql);
                foreach($rows as $row) {
                    $produto = new Produto(
                        $row["CODIGO"],
                        $row["DESCRICAO"],
                        (float)str_replace(",",".",str_replace(".","",$row["VALOR_CUSTO"])),
                        (float)str_replace(",",".",str_replace(".","",$row["VALOR_VENDA"])),
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
        function Adicionar(Produto $produto, SingletonConexao $conexao): bool
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
                        UPPER(':descricao'),
                        :valorCusto,
                        :valorVenda,
                        ':quantidadeEstoque',
                        ':estoqueMinimo')
                ";
                $sql = str_replace(":codigoFornecedor", $produto->getFornecedor()->getCodigo(), $sql);
                $sql = str_replace(":descricao", $produto->getDescricao(), $sql);
                $sql = str_replace(":valorCusto", str_replace(".",",",$produto->getValorCusto()), $sql);
                $sql = str_replace(":valorVenda", str_replace(".",",",$produto->getValorVenda()), $sql);
                $sql = str_replace(":quantidadeEstoque", $produto->getQuantidadeEstoque(), $sql);
                $sql = str_replace(":estoqueMinimo", $produto->getEstoqueMinimo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Alterar(Produto $produto, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    UPDATE GER_PRODUTO
                        SET N_COD_FORNECEDOR = ':codigoFornecedor',
                            C_DESCRICAO = ':descricao',
                            V_VLR_CUSTO = ':valorCusto',
                            V_VLR_VENDA = ':valorVenda',
                            N_QTD_ESTOQUE = ':quantidadeEstoque',
                            N_ESTOQUE_MINIMO = ':estoqueMinimo'
                    WHERE N_COD_PRODUTO = ':codigoProduto'
                ";

                $sql = str_replace(":codigoFornecedor", $produto->getFornecedor()->getCodigo(), $sql);
                $sql = str_replace(":descricao", $produto->getDescricao(), $sql);
                $sql = str_replace(":valorCusto", str_replace(".",",",$produto->getValorCusto()), $sql);
                $sql = str_replace(":valorVenda", str_replace(".",",",$produto->getValorVenda()), $sql);
                $sql = str_replace(":quantidadeEstoque", $produto->getQuantidadeEstoque(), $sql);
                $sql = str_replace(":estoqueMinimo", $produto->getEstoqueMinimo(), $sql);
                $sql = str_replace(":codigoProduto", $produto->getCodigo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Deletar(int $codigoProduto, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    DELETE GER_PRODUTO
                    WHERE N_COD_PRODUTO = ':codigoProduto'
                ";
                $sql = str_replace(":codigoProduto", $codigoProduto, $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }

    }