<?php

    namespace Comercio\Api\repository;

    use Comercio\Api\models\Venda;
    use Comercio\Api\models\Cliente;

    use Comercio\Api\utils\Conexao;

    use Exception;

    class VendaRepository {

        function Obter(Venda $venda, Conexao $conexao): void
        {
            try {
                $sql = "
                    SELECT
                        N_COD_VENDA   AS CODIGO,
                        N_COD_CLIENTE AS CODIGO_CLIENTE,
                        V_VLR_TOTAL   AS VALOR_TOTAL
                    FROM GER_VENDA
                    WHERE N_COD_VENDA = ':codigoVenda'
                ";
                $sql = str_replace(":codigoVenda", $venda->getCodigo(), $sql);
                $row = $conexao->buscar($sql);
                
                
                $venda->setCliente(new Cliente($row["CODIGO_CLIENTE"]));
                $venda->setValorTotal($row["VALOR_TOTAL"]);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        function ObterTodos(Conexao $conexao): array
        {
            $arr = array();
            try {
                $sql = "
                    SELECT
                        N_COD_VENDA   AS CODIGO,
                        N_COD_CLIENTE AS CODIGO_CLIENTE,
                        V_VLR_TOTAL   AS VALOR_TOTAL
                    FROM GER_VENDA
                ";
                $rows = $conexao->buscarTodos($sql);
                foreach($rows as $row) {
                    $venda = new Venda(
                        $row["CODIGO"],
                        new Cliente($row["CODIGO_CLIENTE"]),
                        $row["VALOR_TOTAL"]
                    );
                    array_push($arr, $venda);
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $arr;
        }
        function Adicionar(Venda $venda, Conexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    INSERT INTO GER_VENDA
                        (N_COD_VENDA,
                        N_COD_CLIENTE,
                        V_VLR_TOTAL)
                    VALUES
                        (SEQ_GER_VENDA.NEXTVAL,
                        ':codigoCliente',
                        ':valorTotal');
                ";
                $sql = str_replace(":codigoCliente", $venda->getCliente()->getCodigo(), $sql);
                $sql = str_replace(":valorTotal", $venda->getValorTotal(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Alterar(Venda $venda, Conexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    UPDATE GER_VENDA
                    SET N_COD_CLIENTE = ':codigoCliente',
                        V_VLR_TOTAL = ':valorTotal'
                    WHERE N_COD_VENDA = ':codigoVenda'
                ";
                $sql = str_replace(":codigoCliente", $venda->getCliente()->getCodigo(), $sql);
                $sql = str_replace(":valorTotal", $venda->getValorTotal(), $sql);
                $sql = str_replace(":codigoVenda", $venda->getCodigo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Deletar(int $codigoVenda, Conexao $conexao): bool
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