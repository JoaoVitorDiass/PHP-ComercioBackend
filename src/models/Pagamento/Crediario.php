<?php

    namespace Comercio\Api\models\Pagamento;

    use Comercio\Api\models\Pagamento\Pagamento;
    use Comercio\Api\models\Venda;

    class Crediario implements Pagamento {
        public function calcular(Venda $venda): void
        {
            if($venda->getMetodoPagamento()->getDescricao() == "CREDIÁRIO") {
                // do nothing
                echo "asdjashdjkhasdasd";
            }
        }
    }