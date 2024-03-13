<?php

    namespace Comercio\Api\models;

    use Comercio\Api\models\Cliente;
    use Comercio\Api\models\ItemVenda;

    class Venda {
        private int $_codigo;
        private ?Cliente $_cliente;
        private array $_itensVenda;
        

        /**
         * @param int $codigo
         * @param Cliente $cliente
         * @param ItemVenda[] $itensVenda
         */
        function __construct(   int $codigo=0,
                                Cliente $cliente=null,
                                array $itensVenda=array() ) {
            $this->_codigo = $codigo;
            $this->_cliente = $cliente;
            $this->_itensVenda = $itensVenda;
        }

        function getCodigo() : int
        {
            return $this->_codigo;
        }
        function setCodigo(int $codigo) : void
        {
            $this->_codigo = $codigo;
        }
        function getCliente() : Cliente
        {
            return $this->_cliente;
        }
        function setCliente(Cliente $cliente) : void
        {
            $this->_cliente = $cliente;
        }
        function getItensVenda() : array
        {
            return $this->_itensVenda;
        }
        function setItensVenda(array $itensVenda) : void
        {
            $this->_itensVenda = $itensVenda;
        }
        function addItensVenda(ItemVenda $itemVenda) : void
        {
            array_push($this->_itensVenda, $itemVenda);
        }
    }