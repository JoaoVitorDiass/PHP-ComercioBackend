<?php

    namespace Comercio\Api\repository;

    use Comercio\Api\models\MetodoPagamento;
    use Comercio\Api\utils\SingletonConexao;
    use Exception;
    
    class MetodoPagamentoRepository {

        function Obter(MetodoPagamento $metodoPagamento, SingletonConexao $conexao): void
        {
            try {
                $sql = "
                    SELECT
                        N_COD_METODO_PAGAMENTO AS CODIGO,
                        C_DESCRICAO            AS DESCRICAO
                    FROM GER_METODO_PAGAMENTO
                    WHERE N_COD_METODO_PAGAMENTO = ':codigoMetodoPagamento'
                ";
                $sql = str_replace(":codigoMetodoPagamento", $metodoPagamento->getCodigo(), $sql);
                $row = $conexao->buscar($sql);
                $metodoPagamento->setDescricao($row["DESCRICAO"]);
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
                        N_COD_METODO_PAGAMENTO AS CODIGO,
                        C_DESCRICAO            AS DESCRICAO
                    FROM GER_METODO_PAGAMENTO
                ";
                $rows = $conexao->buscarTodos($sql);
                foreach($rows as $row) {
                    $metodoPagamento = new MetodoPagamento(
                        $row["CODIGO"],
                        $row["DESCRICAO"]
                    );
                    array_push($arr, $metodoPagamento);
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $arr;
        }
        function Adicionar(MetodoPagamento $metodoPagamento, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                INSERT INTO GER_METODO_PAGAMENTO
                    (N_COD_METODO_PAGAMENTO,
                    C_DESCRICAO)
                VALUES
                    (SEQ_GER_METODO_PAGAMENTO.NEXTVAL,
                    ':descricao')
                ";
                $sql = str_replace(":descricao", $metodoPagamento->getDescricao(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Alterar(MetodoPagamento $metodoPagamento, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    UPDATE GER_METODO_PAGAMENTO
                        SET C_DESCRICAO = ':descricao'
                    WHERE N_COD_METODO_PAGAMENTO = ':codigoMetodoPagamento'
                ";
                $sql = str_replace(":descricao", $metodoPagamento->getDescricao(),$sql);
                $sql = str_replace(":codigoMetodoPagamento", $metodoPagamento->getCodigo(),$sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Deletar(int $codigoMetodoPagamento, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    DELETE GER_METODO_PAGAMENTO
                    WHERE N_COD_METODO_PAGAMENTO = ':codigoMetodoPagamento'
                ";
                $sql = str_replace(":codigoMetodoPagamento", $codigoMetodoPagamento, $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
    }