<?php

    namespace Comercio\Api\constrols;
    require_once "../../vendor/autoload.php";

    use Comercio\Api\utils\Conexao;
    use Exception;

    function teste() {

        $retorno = [
            'erro' => false, 
            'mensagem' => [], 
            'dados' => [] 
        ];

        try {

            $conexao = new Conexao();
            http_response_code(200);
        }
        catch (Exception $e)
        {
            $retorno['erro'] = true;
            $retorno['mensagem'][] = $e->getMessage();

            http_response_code(400);
        }

        $conexao->fecharConexao();

        header('Content-Type: Application/json');
        return json_encode($retorno);
    } 

    switch($_SERVER["REQUEST_METHOD"]){
        case "GET":
            echo teste();
        break;

        case "POST":
            
        break;

        case "DELETE":
            
            break;

        case "PUT": // ALTERA UM CAMPO SÓ
    
            break;

        case "PATCH": // ALTERA TODOS
        
            break;

    }