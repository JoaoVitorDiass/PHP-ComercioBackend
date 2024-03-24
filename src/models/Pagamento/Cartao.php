<?php

    namespace Comercio\Api\models\Pagamento;

    use Comercio\Api\models\Pagamento\Pagamento;
    use Comercio\Api\models\Pagamento\Crediario;
    use Comercio\Api\models\Venda;

    class Cartao implements Pagamento{
        private Pagamento $_pagamento;

        public function __construct() {}
        public function calcular(Venda $venda): void
        {
            if($venda->getMetodoPagamento()->getDescricao() == "CARTÃO") {
                $venda->setValorTotal(
                    $venda->getValorTotal() + $venda->getValorTotal() * 0.013
                );
            }
            else { 
                $this->_pagamento = new Crediario(); 
                $this->_pagamento->calcular($venda);  
            }
        }
    }