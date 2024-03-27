<?php

    namespace Comercio\Api\repository;

    use Comercio\Api\models\Notificacoes;
    use Comercio\Api\models\Produto;
    use Comercio\Api\models\Fornecedor;
    use Comercio\Api\models\Venda;
    use Comercio\Api\utils\SingletonConexao;
    use Exception;

    class NotificacoesRepository {

        function Obter(Notificacoes $notificacoes, SingletonConexao $conexao): void
        {
            try {
                $sql = "
                    SELECT 
                        C_MENSAGEM        AS MENSAGEM,
                        N_COD_FORNECEDOR  AS CODIGO_FORNECEDOR,
                        N_COD_PRODUTO     AS CODIGO_PRODUTO
                    FROM GER_NOTIFICACOES_FORNECEDORES T
                    WHERE N_COD_NOTIFICACAO = ':codigoNotificacao'
                ";
                $sql = str_replace(":codigoNotificacao", $notificacoes->getCodigo(), $sql);
                $row = $conexao->buscar($sql);

                $notificacoes->setMensagem($row["MENSAGEM"]);
                $notificacoes->setFornecedor(new Fornecedor($row["CODIGO_FORNECEDOR"]));
                $notificacoes->setProduto(new Produto($row["CODIGO_PRODUTO"]));
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
                        N_COD_NOTIFICACAO AS CODIGO,
                        C_MENSAGEM        AS MENSAGEM,
                        N_COD_FORNECEDOR  AS CODIGO_FORNECEDOR,
                        N_COD_PRODUTO     AS CODIGO_PRODUTO
                    FROM GER_NOTIFICACOES_FORNECEDORES T
                ";
                $rows = $conexao->buscarTodos($sql);
                foreach($rows as $row) {
                    $notificacao = new Notificacoes(
                        $row["CODIGO"],
                        $row["MENSAGEM"],
                        new Fornecedor($row["CODIGO_FORNECEDOR"]),
                        new Produto($row["CODIGO_PRODUTO"])
                    );
                    array_push($arr, $notificacao);
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $arr;
        }
        function ObterTodosByVenda(Venda $venda, SingletonConexao $conexao): array
        {
            $arr = array();
            try {
                $sql = "
                    SELECT 
                        N_COD_NOTIFICACAO AS CODIGO,
                        C_MENSAGEM        AS MENSAGEM,
                        N_COD_FORNECEDOR  AS CODIGO_FORNECEDOR,
                        N_COD_PRODUTO     AS CODIGO_PRODUTO
                    FROM GER_NOTIFICACOES_FORNECEDORES T
                    WHERE N_COD_FORNECEDOR = ':codigoFornecedor'
                    AND N_COD_PRODUTO IN (:codigosProdutos)
                ";

                $codigosProdutos = "";
                $sql = str_replace(":codigoFornecedor", $venda->getCliente()->getCodigo(), $sql);


                foreach($venda->getItensVenda() as $itemVenda) {
                    $codigosProdutos .= "'".$itemVenda->getProduto()->getCodigo()."',";
                }
                $sql = str_replace(":codigosProdutos", substr($codigosProdutos,0,strlen($codigosProdutos)-1), $sql);

                $rows = $conexao->buscarTodos($sql);
                foreach($rows as $row) {
                    $notificacao = new Notificacoes(
                        $row["CODIGO"],
                        $row["MENSAGEM"],
                        new Fornecedor($row["CODIGO_FORNECEDOR"]),
                        new Produto($row["CODIGO_PRODUTO"])
                    );
                    array_push($arr, $notificacao);
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $arr;
        }
        function Adicionar(Notificacoes $notificacoes, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                INSERT INTO GER_NOTIFICACOES_FORNECEDORES
                    (N_COD_NOTIFICACAO,
                    C_MENSAGEM, 
                    N_COD_FORNECEDOR, 
                    N_COD_PRODUTO)
                VALUES
                    (SEQ_NOT_FORN.NEXTVAL, 
                    ':mensagem', 
                    ':codigoFornecedor', 
                    ':codigoProduto')";
                $sql = str_replace(":codigoFornecedor", $notificacoes->getFornecedor()->getCodigo(), $sql);
                $sql = str_replace(":mensagem", $notificacoes->getMensagem(), $sql);
                $sql = str_replace(":codigoProduto", $notificacoes->getProduto()->getCodigo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        // function Alterar(Produto $produto, SingletonConexao $conexao): bool
        // {
        //     $success = false;
        //     try {
        //         $sql = "
        //             UPDATE GER_PRODUTO
        //                 SET N_COD_FORNECEDOR = ':codigoFornecedor',
        //                     C_DESCRICAO = ':descricao',
        //                     V_VLR_CUSTO = ':valorCusto',
        //                     V_VLR_VENDA = ':valorVenda',
        //                     N_QTD_ESTOQUE = ':quantidadeEstoque',
        //                     N_ESTOQUE_MINIMO = ':estoqueMinimo'
        //             WHERE N_COD_PRODUTO = ':codigoProduto'
        //         ";

        //         $sql = str_replace(":codigoFornecedor", $produto->getFornecedor()->getCodigo(), $sql);
        //         $sql = str_replace(":descricao", $produto->getDescricao(), $sql);
        //         $sql = str_replace(":valorCusto", str_replace(".",",",$produto->getValorCusto()), $sql);
        //         $sql = str_replace(":valorVenda", str_replace(".",",",$produto->getValorVenda()), $sql);
        //         $sql = str_replace(":quantidadeEstoque", $produto->getQuantidadeEstoque(), $sql);
        //         $sql = str_replace(":estoqueMinimo", $produto->getEstoqueMinimo(), $sql);
        //         $sql = str_replace(":codigoProduto", $produto->getCodigo(), $sql);
        //         $success = $conexao->executar($sql);
        //     }
        //     catch ( Exception $e) {
        //         throw new Exception($e->getMessage());
        //     }
        //     return $success;
        // }
        function Deletar(int $codigoNotificacao, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    DELETE GER_NOTIFICACOES_FORNECEDORES
                    WHERE N_COD_NOTIFICACAO = ':codigoNotificacao'
                ";
                $sql = str_replace(":codigoNotificacao", $codigoNotificacao, $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function DeletarByVenda(Venda $venda, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    DELETE GER_NOTIFICACOES_FORNECEDORES
                    WHERE N_COD_FORNECEDOR = ':codigoFornecedor'
                    AND N_COD_PRODUTO IN (:codigosProdutos)
                ";
                $codigosProdutos = "";
                $sql = str_replace(":codigoFornecedor", $venda->getCliente()->getCodigo(), $sql);
                foreach($venda->getItensVenda() as $itemVenda) {
                    $codigosProdutos .= "'".$itemVenda->getProduto()->getCodigo()."',";
                }
                $sql = str_replace(":codigosProdutos", substr($codigosProdutos,0,strlen($codigosProdutos)-1), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
    }