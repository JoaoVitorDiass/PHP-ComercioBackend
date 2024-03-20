<?php

    namespace Comercio\Api\repository;

    use Comercio\Api\models\ItemVenda;
    use Comercio\Api\models\Produto;
    use Comercio\Api\models\Venda;

    use Comercio\Api\utils\Conexao;

    use Exception;
    class ItemVendaRepository {
        function ObterTodosByVenda(Venda $venda, Conexao $conexao): void
        {
            try {
                $sql = "
                    SELECT
                        N_COD_VENDA_X_PRODUTO AS CODIGO,
                        N_COD_VENDA           AS CODIGO_VENDA,    
                        N_COD_PRODUTO         AS CODIGO_PRODUTO,
                        N_QUANTIDADE          AS QUANTIDADE
                    FROM GER_VENDA_X_PRODUTO
                    WHERE N_COD_VENDA = ':codigoVenda'
                ";
                $sql = str_replace(":codigoVenda", $venda->getCodigo(), $sql);
                $rows = $conexao->buscarTodos($sql);
                foreach($rows as $row) {
                    $itemVenda = new ItemVenda(
                        $row["CODIGO"],
                        $venda,
                        new Produto($row["CODIGO_PRODUTO"]),
                        $row["QUANTIDADE"]
                    );
                    $venda->addItensVenda($itemVenda);
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        
        function Adicionar(ItemVenda $itemVenda, Conexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    INSERT INTO GER_VENDA_X_PRODUTO
                        (N_COD_VENDA_X_PRODUTO,
                        N_COD_VENDA,
                        N_COD_PRODUTO,
                        N_QUANTIDADE)
                    VALUES
                        (SEQ_GER_VENDA_X_PRODUTO.NEXTVAL,
                        ':codigoVenda',
                        ':codigoProduto',
                        ':quantidade');
                ";
                $sql = str_replace(":codigoVenda", $itemVenda->getVenda()->getCodigo(), $sql);
                $sql = str_replace(":codigoProduto", $itemVenda->getProduto()->getCodigo(), $sql);
                $sql = str_replace(":quantidade", $itemVenda->getQuantidade(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Alterar(ItemVenda $itemVenda, Conexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    UPDATE GER_VENDA_X_PRODUTO
                        SET N_QUANTIDADE = ':quantidade'
                    WHERE N_COD_VENDA_X_PRODUTO = ':codigoItemVenda'
                ";
                $sql = str_replace(":quantidade", $itemVenda->getQuantidade(), $sql);
                $sql = str_replace(":codigoItemVenda", $itemVenda->getCodigo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Deletar(int $codigoItemVenda, Conexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    DELETE GER_VENDA_X_PRODUTO
                    WHERE N_COD_VENDA_X_PRODUTO = ':codigoItemVenda'
                ";
                $sql = str_replace(":codigoItemVenda", $codigoItemVenda, $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }

    }