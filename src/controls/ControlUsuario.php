<?php

    namespace Comercio\Api\constrols;
    require_once "../../vendor/autoload.php";

    use Comercio\Api\models\Usuario;
    use Comercio\Api\utils\SingletonConexao;
    use Comercio\Api\utils\Funcoes;
    use Exception;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    error_reporting(0);

    function Buscar($codigoUsuario) {
        $retorno = Funcoes::getRetorno();
        try {
            $usuario = new Usuario();
            
            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $usuario = $usuario->BuscarUsuario($codigoUsuario,$conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();

            if($usuario != null) {
                $retorno['menssage'][]= "Sucesso";
                $retorno['data'] = [
                    "codigo" => $usuario->getCodigo(),
                    "login" => $usuario->getLogin()
                ];
            }
            http_response_code(200);
        }
        catch (Exception $e)
        {
            $retorno['error'] = true;
            $retorno['mensage'][] = $e->getMessage();
            http_response_code(400);
        }
        header('Content-Type: Application/json');
        return json_encode($retorno);
    }
    function BuscarTodos() {

        $retorno = Funcoes::getRetorno();
        try {
            $usuario = new Usuario();

            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $usuarios = $usuario->BuscarTodos($conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();

            if($usuarios != array()) {
                $retorno["menssage"][] = "Sucesso";
                foreach($usuarios as $usuario) {
                    $retorno["data"][] = [
                        "codigo" => $usuario->getCodigo(),
                        "login" => $usuario->getLogin()
                    ];
                }
            }
            http_response_code(200);
        }
        catch (Exception $e)
        {
            $retorno['error'] = true;
            $retorno['mensage'][] = $e->getMessage();
            http_response_code(400);
        }
        header('Content-Type: Application/json');
        return json_encode($retorno);
    }
    function Adicionar( $login, $senha ) {
        $retorno = Funcoes::getRetorno();
        try {
            $usuario = new Usuario();
            $usuario->setLogin($login);
            $usuario->setSenha(base64_encode($senha));
            
            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $success = $usuario->Adicionar($conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();

            if($success) {
                http_response_code(201);
            }
            else {
                throw new Exception("Erro ao adicionar o usuário ...");
            }
        }
        catch (Exception $e)
        {
            $retorno['error'] = true;
            $retorno['mensage'][] = $e->getMessage();
            http_response_code(400);
        }
        header('Content-Type: Application/json');
        return json_encode($retorno);
    }
    function Alterar($data) {
        $retorno = Funcoes::getRetorno();
        try {
            $usuario = new Usuario();
            $usuario->setCodigo($data["codigo"]);
            $usuario->setLogin($data["login"]);
            $usuario->setSenha(base64_encode($data["senha"]));

            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $success = $usuario->Alterar($conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();

            if($success) {
                http_response_code(202);
            }
            else {
                throw new Exception("Erro ao alterar o usuário ...");
            }
        }
        catch (Exception $e)
        {
            $retorno['error'] = true;
            $retorno['mensage'][] = $e->getMessage();
            http_response_code(400);
        }
        header('Content-Type: Application/json');
        return json_encode($retorno);
    }
    function Deletar($codigoUsuario) {
        $retorno = Funcoes::getRetorno();
        try {
            $usuario = new Usuario();

            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $success = $usuario->Deletar($codigoUsuario, $conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();
            
            if($success) {
                http_response_code(200);
            }
            else {
                throw new Exception("Erro ao alterar o usuário ...");
            }
        }
        catch (Exception $e)
        {
            $retorno['error'] = true;
            $retorno['mensage'][] = $e->getMessage();
            http_response_code(400);
        }
        header('Content-Type: Application/json');
        return json_encode($retorno);
    }

    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
            if(isset($_GET["funcao"])) {
                switch($_GET["funcao"]) {
                    case "buscar": 
                        echo Buscar($_GET["codigoUsuario"]);
                        break;
                    case "buscarTodos":
                        echo BuscarTodos();
                        break;
                }
            }
        break;

        case "POST":
            $data = Funcoes::getData();
            echo Adicionar($data["login"], $data["senha"]);
        break;

        case "DELETE":
            echo Deletar($_GET["codigo"]);
            break;

        case "PUT":
            break;

        case "PATCH":
            echo Alterar(Funcoes::getData());
            break;

    }