<?php

    namespace Comercio\Api\Controls;

    require_once "../../vendor/autoload.php";

    use Comercio\Api\models\Fornecedor;
    use Comercio\Api\utils\Singleton;
    use Comercio\Api\utils\Funcoes;
    use Exception;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    // error_reporting(0);

    function buscar($codigoFornecedor) {

        $retorno = Funcoes::getRetorno();
        try {
            $fornecedor = new Fornecedor($codigoFornecedor);
            
            $conexao = Singleton::getConexao();
            $fornecedor->Buscar($conexao);
            Singleton::fecharConexao();
            
            if($fornecedor == array()) {
                $retorno['menssage'][] = "Fornecedor não encontrado!";
            }
            else {
                $retorno['menssage'][] = "Listando fornecedor ...";
                $retorno['data'][] = [
                    "codigo" => $fornecedor->getCodigo(),
                    "nome"=> $fornecedor->getNome(),
                    "cnpj" => $fornecedor->getCnpj(),
                    "endereco" => $fornecedor->getEndereco(),
                    "telefone" => $fornecedor->getTelefone(),
                    "email" => $fornecedor->getEmail(),
                ];
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
    function buscarTodos() {

        $retorno = Funcoes::getRetorno();
        try {
            $fornecedor = new Fornecedor();
            
            $conexao = Singleton::getConexao();
            $fornecedores = $fornecedor->BuscarTodos($conexao);
            Singleton::fecharConexao();
            
            if($fornecedores == array()) {
                $retorno['menssage'][] = "Não há fornecedores cadastrados!";
            }
            else {
                $retorno['menssage'][] = "Listando fornecedores ...";
                foreach($fornecedores as $fornecedor) {
                    $retorno['data'][] = [
                        "codigo" => $fornecedor->getCodigo(),
                        "nome"=> $fornecedor->getNome(),
                        "cnpj" => $fornecedor->getCnpj(),
                        "endereco" => $fornecedor->getEndereco(),
                        "telefone" => $fornecedor->getTelefone(),
                        "email" => $fornecedor->getEmail(),
                    ];
                }
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
    function Adicionar($data) {
        $retorno = Funcoes::getRetorno();

        try {
            $fornecedor = new Fornecedor(
                0,
                $data["nome"],
                $data["cnpj"],
                $data["telefone"],
                $data["email"],
                $data["endereco"],
            );

            $conexao = Singleton::getConexao();
            $success = $fornecedor->Adicionar($conexao);
            Singleton::fecharConexao();

            if($success) {
                http_response_code(201);
            }
            else {
                throw new Exception("Erro ao adicionar o produto ...");
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
            $fornecedor = new Fornecedor(
                $data["codigo"],
                $data["nome"],
                $data["cnpj"],
                $data["telefone"],
                $data["email"],
                $data["endereco"],
            );

            $conexao = Singleton::getConexao();
            $success = $fornecedor->Alterar($conexao);
            Singleton::fecharConexao();

            if($success) {
                http_response_code(202);
            }
            else {
                throw new Exception("Erro ao alterar o produto ...");
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
    function Deletar($codigoFornecedor) {
        $retorno = Funcoes::getRetorno();
        try {
            $fornecedor = new Fornecedor();

            $conexao = Singleton::getConexao();
            $success = $fornecedor->Deletar($codigoFornecedor, $conexao);
            Singleton::fecharConexao();
            
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
                        echo Buscar($_GET["codigoFornecedor"]);
                        break;
                    case "buscarTodos":
                        echo BuscarTodos();
                        break;
                }
            }
        break;

        case "POST":
            echo Adicionar(Funcoes::getData());
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
