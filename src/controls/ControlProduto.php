<?php

    namespace Comercio\Api\Controls;

    require_once "../../vendor/autoload.php";

    use Comercio\Api\models\Produto;
    use Comercio\Api\models\Fornecedor;
    use Comercio\Api\utils\Funcoes;
    use Comercio\Api\utils\SingletonConexao;
    use Exception;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    error_reporting(0);

    function buscar($codigoProduto) {

        $retorno = Funcoes::getRetorno();
        try {
            $produto = new Produto($codigoProduto);
            
            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $produto->Buscar($conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();
            
            if($produto == array()) {
                $retorno['menssage'][] = "Produto não encontrado!";
            }
            else {
                $retorno['menssage'][] = "Listando produto ...";
                $retorno['data'][] = [
                    "codigo" => $produto->getCodigo(),
                    "descricao"=> $produto->getDescricao(),
                    "valorCusto" => $produto->getValorCusto(),
                    "valorVenda" => $produto->getValorVenda(),
                    "quantidadeEstoque" => $produto->getQuantidadeEstoque(),
                    "estoqueMinimo" => $produto->getEstoqueMinimo(),
                    "codigoFornecedor" => $produto->getFornecedor()->getCodigo()
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
            $produto = new Produto();
            
            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $produtos = $produto->BuscarTodos($conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();

            if($produtos == array()) {
                $retorno['menssage'][] = "Não há produtos cadastrados!";
            }
            else {
                $retorno['menssage'][] = "Listando produtos ...";

                foreach($produtos as $produto) {
                    $retorno['data'][] = [
                        "codigo" => $produto->getCodigo(),
                        "descricao"=> $produto->getDescricao(),
                        "valorCusto" => number_format($produto->getValorCusto(),2,",","."),
                        "valorVenda" => number_format($produto->getValorVenda(),2,",","."),
                        "quantidadeEstoque" => $produto->getQuantidadeEstoque(),
                        "estoqueMinimo" => $produto->getEstoqueMinimo(),
                        "codigoFornecedor" => $produto->getFornecedor()->getCodigo()
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
            $produto = new Produto(
                0,
                $data["descricao"],
                (float)str_replace(',', '.', str_replace('.', '', $data["valorCusto"])),
                (float)str_replace(',', '.', str_replace('.', '', $data["valorVenda"])),
                $data["quantidadeEstoque"],
                $data["estoqueMinimo"],
                new Fornecedor($data["codigoFornecedor"])
            );
            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $success = $produto->Adicionar($conexao);
            SingletonConexao::getInstancia()->persistir();
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();
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
            $produto = new Produto(
                $data["codigo"],
                $data["descricao"],
                $data["valorCusto"],
                $data["valorVenda"],
                $data["quantidadeEstoque"],
                $data["estoqueMinimo"],
                new Fornecedor($data["codigoFornecedor"])
            );

            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $success = $produto->Alterar($conexao);
            SingletonConexao::getInstancia()->persistir();
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();

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
    function Deletar($codigoProduto) {
        $retorno = Funcoes::getRetorno();
        try {
            $produto = new Produto();

            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $success = $produto->Deletar($codigoProduto, $conexao);
            SingletonConexao::getInstancia()->persistir();
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
