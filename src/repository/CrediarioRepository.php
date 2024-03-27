<?php

    namespace Comercio\Api\repository;

    use Comercio\Api\models\Venda;
    use Comercio\Api\models\Cliente;
    use Comercio\Api\models\Crediario;
    use Comercio\Api\utils\SingletonConexao;

    use Exception;
    use Datetime;

    class CrediarioRepository {

        function Obter(Crediario $crediario, SingletonConexao $conexao): void
        {
            try {
                $sql = "
                    SELECT 
                        N_COD_CREDIARIO                       AS CODIGO,
                        N_COD_CLIENTE                         AS CODIGO_CLIENTE,
                        N_COD_VENDA                           AS CODIGO_VENDA,
                        V_VALOR_ORIGINAL                      AS VALOR_ORIGINAL,
                        TO_CHAR(D_DATA_INCLUSAO,'DD/MM/YYYY') AS DATA_INCLUSAO
                    FROM GER_CREDIARIO T
                    WHERE N_COD_CREDIARIO = ':codigoCrediario'
                ";
                $sql = str_replace(":codigoCrediario", $crediario->getCodigo(), $sql);
                $row = $conexao->buscar($sql);

                $crediario->setCliente(new Cliente($row["CODIGO_CLIENTE"]));
                $crediario->setVenda(new Venda($row["CODIGO_VENDA"]));
                $crediario->setValorOriginal((float)str_replace(",",".",str_replace(".","",$row["VALOR_ORIGINAL"])));
                $crediario->setDataInclusao(Datetime::createFromFormat("d/m/Y",$row["DATA_INCLUSAO"]));

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
                        N_COD_CREDIARIO                       AS CODIGO,
                        N_COD_CLIENTE                         AS CODIGO_CLIENTE,
                        N_COD_VENDA                           AS CODIGO_VENDA,
                        V_VALOR_ORIGINAL                      AS VALOR_ORIGINAL,
                        TO_CHAR(D_DATA_INCLUSAO,'DD/MM/YYYY') AS DATA_INCLUSAO
                    FROM GER_CREDIARIO T
                ";
                $rows = $conexao->buscarTodos($sql);

                foreach($rows as $row) {
                    $crediario = new Crediario(
                        $row["CODIGO"],
                        new Cliente($row["CODIGO_CLIENTE"]),
                        new Venda($row["CODIGO_VENDA"]),
                        (float)str_replace(",",".",str_replace(".","",$row["VALOR_ORIGINAL"])),
                        Datetime::createFromFormat("d/m/Y",$row["DATA_INCLUSAO"]),
                    );
                    array_push($arr, $crediario);
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $arr;
        }
        function Adicionar(Crediario $crediario, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    INSERT INTO GER_CREDIARIO
                        (N_COD_CREDIARIO,
                        N_COD_CLIENTE,
                        N_COD_VENDA,
                        V_VALOR_ORIGINAL,
                        D_DATA_INCLUSAO)
                    VALUES
                        (SEQ_GER_CREDIARIO.NEXTVAL,
                        ':codigoCliente',
                        ':codigoVenda',
                        :valorOriginal,
                        SYSDATE)
                ";
                $sql = str_replace(":codigoCliente", $crediario->getCliente()->getCodigo(), $sql);
                $sql = str_replace(":codigoVenda", $crediario->getVenda()->getCodigo(), $sql);
                $sql = str_replace(":valorOriginal",  $crediario->getValorOriginal(), $sql);
                // $sql = str_replace(":dataInclusao", $crediario->getDataInclusao()->format("d/m/Y"), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Alterar(Crediario $crediario, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    UPDATE GER_CREDIARIO
                        SET N_COD_CLIENTE = ':codigoCliente',
                            N_COD_VENDA = ':codigoVenda',
                            V_VALOR_ORIGINAL = :valorOriginal,
                            D_DATA_INCLUSAO = ':dataInclusao'
                    WHERE N_COD_CREDIARIO = ':codigoCrediario'
                ";
                $sql = str_replace(":codigoCliente", $crediario->getCliente()->getCodigo(), $sql);
                $sql = str_replace(":codigoVenda", $crediario->getVenda()->getCodigo(), $sql);
                $sql = str_replace(":valorOriginal",  str_replace(".",",",$crediario->getValorOriginal()), $sql);
                $sql = str_replace(":dataInclusao", $crediario->getDataInclusao()->format("d/m/Y"), $sql);
                $sql = str_replace(":codigoCrediario", $crediario->getCodigo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Deletar(int $codigoCrediario, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    DELETE GER_CREDIARIO
                    WHERE N_COD_CREDIARIO = ':codigoCrediario'
                ";
                $sql = str_replace(":codigoCrediario", $codigoCrediario, $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
    }