<?php

    namespace Comercio\Api\models;

    use Comercio\Api\models\Venda;
    use Comercio\Api\models\Produto;

    class ItemVenda {
        private int $_codigo;
        private Venda $_venda;
        private Produto $_produto;
        private int $_quantidade;
        private float $_valorTotalItem;

        /**
         * @param int $codigo
         * @param Venda $venda
         * @param Produto $produto
         * @param int $quantidade
         * @param float $valorTotalItem
         */
        function __construct(   int $codigo,
                                Venda $venda,
                                Produto $produto,
                                int $quantidade,
                                float $valorTotalItem ) {
            $this->_codigo = $codigo;
            $this->_venda = $venda;
            $this->_produto = $produto;
            $this->_quantidade = $quantidade;
            $this->_valorTotalItem = $valorTotalItem;
        }
        function getCodigo() : int
        {
            return $this->_codigo;
        }
        function setCodigo(int $codigo) : void
        {
            $this->_codigo = $codigo;
        }
        function getVenda() : Venda
        {
            return $this->_venda;
        }
        function setVenda(Venda $venda) : void
        {
            $this->_venda = $venda;
        }
        function getProduto() : Produto
        {
            return $this->_produto;
        }
        function setProduto(Produto $produto) : void
        {
            $this->_produto = $produto;
        }
        function getQuantidade() : int
        {
            return $this->_quantidade;
        }
        function setQuantidade(int $quantidade) : void
        {
            $this->_quantidade = $quantidade;
        }
        function getValorTotalItem() : float
        {
            return $this->_valorTotalItem;
        }
        function setValorTotalItem(float $valorTotalItem) : void
        {
            $this->_valorTotalItem = $valorTotalItem;
        }
    }