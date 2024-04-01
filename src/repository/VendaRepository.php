<?php

    namespace Comercio\Api\repository;

    use Comercio\Api\models\Venda;
    use Comercio\Api\models\Cliente;
    use Comercio\Api\models\MetodoPagamento;

    use Comercio\Api\utils\SingletonConexao;

    use Exception;

    class VendaRepository {

        function Obter(Venda $venda, SingletonConexao $conexao): void
        {
            try {
                $sql = "
                    SELECT
                        N_COD_VENDA             AS CODIGO,
                        N_COD_CLIENTE           AS CODIGO_CLIENTE,
                        V_VLR_TOTAL             AS VALOR_TOTAL,
                        N_COD_METODO_PAGAMENTO  AS CODIGO_METODO_PAGAMENTO
                    FROM GER_VENDA
                    WHERE N_COD_VENDA = ':codigoVenda'
                ";
                $sql = str_replace(":codigoVenda", $venda->getCodigo(), $sql);
                $row = $conexao->buscar($sql);
                
                $venda->setCliente(new Cliente($row["CODIGO_CLIENTE"]));
                $venda->setValorTotal((float)str_replace(",",".",str_replace(".","",$row["VALOR_TOTAL"])));
                $venda->setMetodoPagamento(new MetodoPagamento($row["CODIGO_METODO_PAGAMENTO"]));
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
                        N_COD_VENDA             AS CODIGO,
                        N_COD_CLIENTE           AS CODIGO_CLIENTE,
                        V_VLR_TOTAL             AS VALOR_TOTAL,
                        N_COD_METODO_PAGAMENTO  AS CODIGO_METODO_PAGAMENTO
                    FROM GER_VENDA
                ";
                $rows = $conexao->buscarTodos($sql);
                foreach($rows as $row) {
                    $venda = new Venda(
                        $row["CODIGO"],
                        new Cliente($row["CODIGO_CLIENTE"]),
                        (float)str_replace(",",".",str_replace(".","",$row["VALOR_TOTAL"])),
                        new MetodoPagamento($row["CODIGO_METODO_PAGAMENTO"]) 
                    );
                    array_push($arr, $venda);
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $arr;
        }
        function Adicionar(Venda $venda, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    SELECT SEQ_GER_VENDA.NEXTVAL AS CODIGO_VENDA FROM DUAL
                ";
                $row = $conexao->buscar($sql);
                $venda->setCodigo($row["CODIGO_VENDA"]);
                $sql = "
                    INSERT INTO GER_VENDA
                        (N_COD_VENDA,
                        N_COD_CLIENTE,
                        V_VLR_TOTAL,
                        N_COD_METODO_PAGAMENTO)
                    VALUES
                        (':codigoVenda',
                        ':codigoCliente',
                        :valorTotal,
                        ':codigoMetodoPagamento')
                ";
                $sql = str_replace(":codigoVenda", $venda->getCodigo(), $sql);
                $sql = str_replace(":codigoCliente", $venda->getCliente()->getCodigo(), $sql);
                $sql = str_replace(":valorTotal", $venda->getValorTotal(), $sql);
                $sql = str_replace(":codigoMetodoPagamento", $venda->getMetodoPagamento()->getCodigo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Alterar(Venda $venda, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    UPDATE GER_VENDA
                    SET N_COD_CLIENTE = ':codigoCliente',
                        V_VLR_TOTAL = :valorTotal,
                        N_COD_METODO_PAGAMENTO = ':codigoMetodoPagamento'
                    WHERE N_COD_VENDA = ':codigoVenda'
                ";
                $sql = str_replace(":codigoCliente", $venda->getCliente()->getCodigo(), $sql);
                $sql = str_replace(":valorTotal", $venda->getValorTotal(), $sql);
                $sql = str_replace(":codigoVenda", $venda->getCodigo(), $sql);
                $sql = str_replace(":codigoMetodoPagamento", $venda->getMetodoPagamento()->getCodigo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Deletar(int $codigoVenda, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    DELETE GER_VENDA
                    WHERE N_COD_VENDA = ':codigoVenda'
                ";
                $sql = str_replace(":codigoVenda", $codigoVenda, $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
    }