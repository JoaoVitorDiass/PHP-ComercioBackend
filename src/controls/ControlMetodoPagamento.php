<?php

    namespace Comercio\Api\Controls;

    require_once "../../vendor/autoload.php";

    use Comercio\Api\models\MetodoPagamento;
    use Comercio\Api\utils\SingletonConexao;
    use Comercio\Api\utils\Funcoes;
    use Exception;

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    error_reporting(0);
    function buscarTodos() {

        $retorno = Funcoes::getRetorno();
        try {
            $metodoPagamento = new MetodoPagamento();
            
            // $conexao = Singleton::getConexao();
            $conexao = SingletonConexao::getInstancia();
            $metodosPagamento = $metodoPagamento->BuscarTodos($conexao);
            SingletonConexao::getInstancia()->fecharConexao();
            // Singleton::fecharConexao();
            
            if($metodosPagamento == array()) {
                $retorno['menssage'][] = "Não há Metodos de Pagamento cadastrados!";
            }
            else {
                $retorno['menssage'][] = "Listando Metodos de Pagamento ...";
                foreach($metodosPagamento as $metodoPagamento) {
                    $retorno['data'][] = [
                        "codigo" => $metodoPagamento->getCodigo(),
                        "descricao"=> $metodoPagamento->getDescricao(),
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

    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
            if(isset($_GET["funcao"])) {
                switch($_GET["funcao"]) {
                    // case "buscar": 
                    //     echo Buscar($_GET["codigoFornecedor"]);
                    //     break;
                    case "buscarTodos":
                        echo BuscarTodos();
                        break;
                }
            }
        break;

        // case "POST":
        //     echo Adicionar(Funcoes::getData());
        // break;

        // case "DELETE":
        //     echo Deletar($_GET["codigo"]);
        //     break;

        // case "PUT":
        //     break;

        // case "PATCH":
        //     echo Alterar(Funcoes::getData());
        //     break;
    }
