<?php

    namespace Comercio\Api\repository;

    use Comercio\Api\models\Usuario;
    use Comercio\Api\utils\SingletonConexao;
    use Exception;

    class UsuarioRepository {

        function ObterUsuario(int $codigoUsuario, SingletonConexao $conexao): ?Usuario
        {
            $usuario = null;
            try {
                $sql = "
                    SELECT 
                        N_COD_USUARIO   AS CODIGO,
                        C_LOGIN         AS LOGIN,
                        C_SENHA         AS SENHA
                    FROM GER_USUARIO 
                    WHERE N_COD_USUARIO = ':codigoUsuario'
                ";
                $sql = str_replace(":codigoUsuario", $codigoUsuario, $sql);
                $row = $conexao->buscar($sql);
    
                $usuario = new Usuario(
                    $row["CODIGO"],
                    $row["LOGIN"],
                    $row["SENHA"],
                );
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $usuario;
        }
        function ObterUsuarioByLogin(string $login, SingletonConexao $conexao): ?Usuario
        {
            $usuario = null;
            try {
                $sql = "
                    SELECT 
                        COUNT(1) AS CONTADOR
                    FROM GER_USUARIO 
                    WHERE UPPER(C_LOGIN) LIKE UPPER(':login')
                ";
                $sql = str_replace(":login", $login, $sql);
                $row = $conexao->buscar($sql);

                if($row["CONTADOR"] > 0) {
                    $sql = "
                        SELECT 
                            N_COD_USUARIO   AS CODIGO,
                            C_LOGIN         AS LOGIN,
                            C_SENHA         AS SENHA
                        FROM GER_USUARIO 
                        WHERE UPPER(C_LOGIN) LIKE UPPER(':login')
                    ";
                    $sql = str_replace(":login", $login, $sql);
                    $row = $conexao->buscar($sql);
                    $usuario = new Usuario(
                        $row["CODIGO"],
                        $row["LOGIN"],
                        $row["SENHA"],
                    );
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $usuario;
        }
        function ObterTodos(SingletonConexao $conexao): array
        {
            $arr = array();
            try {
                $sql = "
                    SELECT 
                        N_COD_USUARIO   AS CODIGO,
                        C_LOGIN         AS LOGIN,
                        C_SENHA         AS SENHA
                    FROM GER_USUARIO 
                ";
                $rows = $conexao->buscarTodos($sql);
                foreach($rows as $row) {
                    $usuario = new Usuario(
                        $row["CODIGO"],
                        $row["LOGIN"],
                        $row["SENHA"],
                    );
                    array_push($arr, $usuario);
                }
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $arr;
        }
        function Adicionar(Usuario $usuario, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    INSERT INTO GER_USUARIO
                        (N_COD_USUARIO, C_LOGIN, C_SENHA)
                    VALUES
                        (SEQ_GER_USUARIO.NEXTVAL, UPPER(':login'), ':senha' )
                ";
                $sql = str_replace(":login", $usuario->getLogin(), $sql);
                $sql = str_replace(":senha", $usuario->getSenha(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Alterar(Usuario $usuario, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    UPDATE GER_USUARIO
                        SET C_LOGIN = UPPER(':login'),
                            C_SENHA = ':senha'
                    WHERE N_COD_USUARIO = ':codigo'
                ";
                $sql = str_replace(":login", $usuario->getLogin(), $sql);
                $sql = str_replace(":senha", $usuario->getSenha(), $sql);
                $sql = str_replace(":codigo", $usuario->getCodigo(), $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        function Deletar(int $codigoUsuario, SingletonConexao $conexao): bool
        {
            $success = false;
            try {
                $sql = "
                    DELETE GER_USUARIO
                    WHERE N_COD_USUARIO = ':codigo'
                ";
                $sql = str_replace(":codigo", $codigoUsuario, $sql);
                $success = $conexao->executar($sql);
            }
            catch ( Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $success;
        }
        
    }