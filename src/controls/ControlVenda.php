<?php

    namespace Comercio\Api\Controls;

    require_once "../../vendor/autoload.php";
    
    use Comercio\Api\models\Venda;
    use Comercio\Api\models\Cliente;
    use Comercio\Api\models\ItemVenda;
    use Comercio\Api\utils\SingletonConexao;
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
            
            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $venda->Buscar($conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();
            
            if($venda == array()) {
                $retorno['menssage'][] = "Venda não encontrada!"; 
            }
            else {

                // $conexao = Singleton::getConexao();
                $conexao = SingletonConexao::getInstancia();
                $itemVenda->BuscarTodosByVenda($venda, $conexao);
                SingletonConexao::getInstancia()->fecharConexao();
                // Singleton::fecharConexao();

                $produtos = array();
                foreach( $venda->getItensVenda() as &$itemVenda) {

                    // $conexao = Singleton::getConexao();
                    $conexao = SingletonConexao::getInstancia();
                    $itemVenda->getProduto()->Buscar($conexao);
                    SingletonConexao::getInstancia()->fecharConexao();
                    // Singleton::fecharConexao();

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
                    "valorTotal" => (float)$venda->getValorTotal(),
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
            
            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $vendas = $venda->BuscarTodos($conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();
            
            if($vendas == array()) {
                $retorno['menssage'][] = "Não há vendas cadastradas!";
            }
            else {
                $retorno['menssage'][] = "Listando vendas ...";
                foreach($vendas as $venda) {
                    $produtos = array();
                    foreach( $venda->getItensVenda() as &$itemVenda) {

                        // $conexao = Singleton::getConexao();
                        $conexao = SingletonConexao::getInstancia();
                        $itemVenda->getProduto()->Buscar($conexao);
                        SingletonConexao::getInstancia()->fecharConexao();
                        // Singleton::fecharConexao();

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
    function Adicionar($data) {
        $retorno = Funcoes::getRetorno();
        try {
            $venda = new Venda(
                0,
                new Cliente($data["codigoCliente"]),
            );

            $conexao = SingletonConexao::getInstancia();
            $success = $venda->Adicionar($conexao);
            SingletonConexao::getInstancia()->fecharConexao();

            foreach($data["items"] as $item) {
                $itemVenda = new ItemVenda(
                    0,
                    $data["codigoCliente"],
                    $item["codigoProduto"],
                    $item["quantidade"],
                );
                $conexao = SingletonConexao::getInstancia();
                $success = $itemVenda->Adicionar($conexao);
                SingletonConexao::getInstancia()->fecharConexao();
            }

            if($success) {
                http_response_code(201);
            }
            else {
                throw new Exception("Erro ao adicionar a venda ...");
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
            $venda = new Venda(
                $data["codigo"],
                new Cliente($data["codigoCliente"]),
                $data["valorTotal"],
            );

            $conexao = SingletonConexao::getInstancia();
            $success = $venda->Alterar($conexao);
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
    function Deletar($codigoVenda) {
        $retorno = Funcoes::getRetorno();
        try {
            $venda = new Venda();

            $conexao = SingletonConexao::getInstancia();
            $success = $venda->Deletar($codigoVenda, $conexao);
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

        // case "DELETE":
        //     echo Deletar($_GET["codigo"]);
        //     break;

        // case "PUT":
        //     break;

        // case "PATCH":
        //     echo Alterar(Funcoes::getData());
        //     break;
    }
