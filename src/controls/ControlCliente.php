<?php

    namespace Comercio\Api\Controls;

    require_once "../../vendor/autoload.php";

    use Comercio\Api\models\Cliente;
    use Comercio\Api\utils\SingletonConexao;
    use Comercio\Api\utils\Funcoes;
    use Exception;
    use Datetime;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    // error_reporting(0);

    function buscar($codigoCliente) {
        $retorno = Funcoes::getRetorno();
        try {
            $cliente = new Cliente($codigoCliente);
            
            $conexao = SingletonConexao::getInstancia();
            $cliente->Buscar($conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            
            if($cliente == array()) {
                $retorno['menssage'][] = "Cliente não encontrado!";
            }
            else {
                $retorno['menssage'][] = "Listando produto ...";
                $retorno['data'][] = [
                    "codigo"         => $cliente->getCodigo(),
                    "nome"           => $cliente->getNome(),
                    "cnpj"           => $cliente->getCpf(),
                    "dataNascimento" => $cliente->getDataNascimento()->format('d/m/Y'),
                    "telefone"       => $cliente->getTelefone(),
                    "rg"             => $cliente->getRg(),
                    "email"          => $cliente->getEmail(),
                    "endereco"       => $cliente->getEndereco(),
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
            $cliente = new Cliente();
            
            $conexao = SingletonConexao::getInstancia();
            $clientes = $cliente->BuscarTodos($conexao);
            SingletonConexao::getInstancia()->fecharConexao();

            if($clientes == array()) {
                $retorno['menssage'][] = "Não há clientes cadastrados!";
            }
            else {
                $retorno['menssage'][] = "Listando clientes ...";

                foreach($clientes as $cliente) {
                    $retorno['data'][] = [
                        "codigo"         => $cliente->getCodigo(),
                        "nome"           => $cliente->getNome(),
                        "cnpj"           => $cliente->getCpf(),
                        "dataNascimento" => $cliente->getDataNascimento()->format('d/m/Y'),
                        "telefone"       => $cliente->getTelefone(),
                        "rg"             => $cliente->getRg(),
                        "email"          => $cliente->getEmail(),
                        "endereco"       => $cliente->getEndereco(),
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
            $cliente = new Cliente(
                0,
                $data["nome"],
                $data["cpf"],
                Datetime::createFromFormat("d/m/Y", $data[""]),
                $data["telefone"],
                $data["rg"],
                $data["email"],
                $data["endereco"],
            );

            $conexao = SingletonConexao::getInstancia();
            $success = $cliente->Adicionar($conexao);
            SingletonConexao::getInstancia()->fecharConexao();

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
            $cliente = new Cliente(
                $data["codigo"],
                $data["nome"],
                $data["cpf"],
                Datetime::createFromFormat("d/m/Y", $data[""]),
                $data["telefone"],
                $data["rg"],
                $data["email"],
                $data["endereco"],
            );

            $conexao = SingletonConexao::getInstancia();
            $success = $cliente->Alterar($conexao);
            SingletonConexao::getInstancia()->fecharConexao();

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
    function Deletar($codigoCliente) {
        $retorno = Funcoes::getRetorno();
        try {
            $produto = new Cliente();

            $conexao = SingletonConexao::getInstancia();
            $success = $produto->Deletar($codigoCliente, $conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            
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
                        echo Buscar($_GET["codigoProduto"]);
                        break;
                    case "buscarTodos":
                        echo BuscarTodos();
                        break;
                }
            }
        break;

        case "POST":
            if($_POST != array())
                echo Adicionar($_POST);
            else
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