<?php

    namespace Comercio\Api\models\Pagamento;

    use Comercio\Api\models\Pagamento\Pagamento;
    use Comercio\Api\models\Pagamento\Cartao;
    use Comercio\Api\models\Venda;
    class Dinheiro implements Pagamento{
        private Pagamento $_pagamento;

        public function __construct() {}
        public function calcular(Venda $venda): void
        {
            if($venda->getMetodoPagamento()->getDescricao() == "DINHEIRO") {
                $venda->setValorTotal(
                    $venda->getValorTotal() - ($venda->getValorTotal() * 0.05 )
                );
            }
            else { 
                $this->_pagamento = new Cartao();
                $this->_pagamento->calcular($venda);
            }
        }
    }