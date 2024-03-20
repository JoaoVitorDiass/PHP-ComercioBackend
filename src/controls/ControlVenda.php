<?php

    namespace Comercio\Api\Controls;

    require_once "../../vendor/autoload.php";
    
    use Comercio\Api\models\Venda;
    use Comercio\Api\models\ItemVenda;
    use Comercio\Api\utils\Singleton;
    use Comercio\Api\utils\Funcoes;
    use Exception;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    // error_reporting(0);

    function buscar($codigoVenda) {

        $retorno = Funcoes::getRetorno();
        try {
            $venda = new Venda($codigoVenda);
            $itemVenda = new ItemVenda();
            
            $conexao = Singleton::getConexao();
            $venda->Buscar($conexao);
            Singleton::fecharConexao();
            
            if($venda == array()) {
                $retorno['menssage'][] = "Venda não encontrada!";
            }
            else {

                $conexao = Singleton::getConexao();
                $itemVenda->BuscarTodosByVenda($venda, $conexao);
                Singleton::fecharConexao();

                $produtos = array();
                foreach( $venda->getItensVenda() as &$itemVenda) {

                    $conexao = Singleton::getConexao();
                    $itemVenda->getProduto()->Buscar($conexao);
                    Singleton::fecharConexao();

                    $produtos[] = [
                        "codigo"             => $itemVenda->getProduto()->getCodigo(),
                        "descricao"          => $itemVenda->getProduto()->getDescricao(),
                        "valorCusto"         => $itemVenda->getProduto()->getValorCusto(),
                        "valorVenda"         => $itemVenda->getProduto()->getValorVenda(),
                        "quantidadeEstoque"  => $itemVenda->getProduto()->getQuantidadeEstoque(),
                        "estoqueMinimo"      => $itemVenda->getProduto()->getEstoqueMinimo(),
                        "fornecedor"         => $itemVenda->getProduto()->getFornecedor()->getCodigo(),
                    ];
                }
                $retorno['menssage'][] = "Listando Venda ...";
                $retorno['data'][] = [
                    "codigo" => $venda->getCodigo(),
                    "codigoCliente"=> $venda->getCliente()->getCodigo(),
                    "valorTotal" => $venda->getValorTotal(),
                    "items" => $produtos,
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
            $venda = new Venda();
            
            $conexao = Singleton::getConexao();
            $vendas = $venda->BuscarTodos($conexao);
            Singleton::fecharConexao();
            
            if($vendas == array()) {
                $retorno['menssage'][] = "Não há vendas cadastradas!";
            }
            else {
                $retorno['menssage'][] = "Listando vendas ...";
                foreach($vendas as $venda) {
                    $produtos = array();
                    foreach( $venda->getItensVenda() as &$itemVenda) {
                        $itemVenda->getProduto()->Buscar();
                        $produtos[] = [
                            "codigo"             => $itemVenda->getProduto()->getCodigo(),
                            "descricao"          => $itemVenda->getProduto()->getDescricao(),
                            "valorCusto"         => $itemVenda->getProduto()->getValorCusto(),
                            "valorVenda"         => $itemVenda->getProduto()->getValorVenda(),
                            "quantidadeEstoque"  => $itemVenda->getProduto()->getQuantidadeEstoque(),
                            "estoqueMinimo"      => $itemVenda->getProduto()->getEstoqueMinimo(),
                            "fornecedor"         => $itemVenda->getProduto()->getFornecedor()->getCodigo(),
                        ];
                    }
                    $retorno['data'][] = [
                        "codigo" => $venda->getCodigo(),
                        "codigoCliente"=> $venda->getCliente()->getCodigo(),
                        "valorTotal" => $venda->getValorTotal(),
                        "items" => $produtos,
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
    // function Adicionar($data) {
    //     $retorno = Funcoes::getRetorno();

    //     try {
    //         $fornecedor = new Fornecedor(
    //             0,
    //             $data["nome"],
    //             $data["cnpj"],
    //             $data["telefone"],
    //             $data["email"],
    //             $data["endereco"],
    //         );

    //         $conexao = Singleton::getConexao();
    //         $success = $fornecedor->Adicionar($conexao);
    //         Singleton::fecharConexao();

    //         if($success) {
    //             http_response_code(201);
    //         }
    //         else {
    //             throw new Exception("Erro ao adicionar o produto ...");
    //         }
    //     }
    //     catch (Exception $e)
    //     {
    //         $retorno['error'] = true;
    //         $retorno['mensage'][] = $e->getMessage();
    //         http_response_code(400);
    //     }
    //     header('Content-Type: Application/json');
    //     return json_encode($retorno);
    // }
    // function Alterar($data) {
    //     $retorno = Funcoes::getRetorno();
    //     try {
    //         $fornecedor = new Fornecedor(
    //             $data["codigo"],
    //             $data["nome"],
    //             $data["cnpj"],
    //             $data["telefone"],
    //             $data["email"],
    //             $data["endereco"],
    //         );

    //         $conexao = Singleton::getConexao();
    //         $success = $fornecedor->Alterar($conexao);
    //         Singleton::fecharConexao();

    //         if($success) {
    //             http_response_code(202);
    //         }
    //         else {
    //             throw new Exception("Erro ao alterar o produto ...");
    //         }
    //     }
    //     catch (Exception $e)
    //     {
    //         $retorno['error'] = true;
    //         $retorno['mensage'][] = $e->getMessage();
    //         http_response_code(400);
    //     }
    //     header('Content-Type: Application/json');
    //     return json_encode($retorno);
    // }
    // function Deletar($codigoFornecedor) {
    //     $retorno = Funcoes::getRetorno();
    //     try {
    //         $fornecedor = new Fornecedor();

    //         $conexao = Singleton::getConexao();
    //         $success = $fornecedor->Deletar($codigoFornecedor, $conexao);
    //         Singleton::fecharConexao();
            
    //         if($success) {
    //             http_response_code(200);
    //         }
    //         else {
    //             throw new Exception("Erro ao alterar o usuário ...");
    //         }
    //     }
    //     catch (Exception $e)
    //     {
    //         $retorno['error'] = true;
    //         $retorno['mensage'][] = $e->getMessage();
    //         http_response_code(400);
    //     }
    //     header('Content-Type: Application/json');
    //     return json_encode($retorno);
    // }

    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
            if(isset($_GET["funcao"])) {
                switch($_GET["funcao"]) {
                    case "buscar": 
                        echo Buscar($_GET["codigoVenda"]);
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
