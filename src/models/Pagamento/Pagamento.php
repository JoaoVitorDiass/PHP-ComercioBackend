<?php

    namespace Comercio\Api\models\Pagamento;

    use Comercio\Api\models\Venda;

    interface Pagamento {
        public function calcular(Venda $venda): void;
    }