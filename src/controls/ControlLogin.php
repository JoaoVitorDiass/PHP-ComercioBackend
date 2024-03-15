<?php

    namespace Comercio\Api\Controls;

    require_once "../../vendor/autoload.php";

    use Comercio\Api\models\Usuario;
    use Comercio\Api\utils\Singleton;
    use Comercio\Api\utils\Funcoes;
    use Exception;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    function ValidaLogin($login, $senha) {

        $retorno = Funcoes::getRetorno();
        try {
            $usuario = new Usuario();
            
            $conexao = Singleton::getConexao();
            $usuario = $usuario->BuscarUsuarioByLogin($login,$conexao);
            Singleton::fecharConexao();

            if($usuario != null && $usuario->getLogin() == strtoupper($login) && $usuario->getSenha() == base64_encode($senha)) {
                $retorno['menssage'][] = "Logando...";
                $retorno['data'][] = [
                    "codigo" => $usuario->getCodigo(),
                    "login" => $usuario->getLogin(),
                ];
            }
            else {
                $retorno['error'] = true;
                $retorno['menssage'][] = "Usuario ou senha incorretos! Tente novamente...";
            }
            http_response_code(200);
        }
        catch (Exception $e)
        {
            $retorno['error'] = true;
            $retorno['menssage'][] = $e->getMessage();
            http_response_code(400);
        }
        header('Content-Type: Application/json');
        return json_encode($retorno);
    }


    switch($_SERVER["REQUEST_METHOD"]){
        case "POST":
            if ( isset($_POST["login"]) && isset($_POST["senha"]) ) {
                echo ValidaLogin($_POST["login"], $_POST["senha"]);
            }
        break;
    }
