<?php

    namespace Comercio\Api\models;

    use Comercio\Api\models\Venda;
    use Comercio\Api\models\Produto;
    use Comercio\Api\repository\ItemVendaRepository;
    use Comercio\Api\utils\SingletonConexao;
    class ItemVenda {
        private int $_codigo; 
        private ?Venda $_venda;
        private ?Produto $_produto;
        private int $_quantidade;

        /**
         * @param int $codigo
         * @param Venda $venda
         * @param Produto $produto
         * @param int $quantidade
         */
        function __construct(   int $codigo=0,
                                Venda $venda=null,
                                Produto $produto=null,
                                int $quantidade=0 ) {
            $this->_codigo = $codigo;
            $this->_venda = $venda;
            $this->_produto = $produto;
            $this->_quantidade = $quantidade;
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
        
        function BuscarTodosByVenda(Venda $venda, SingletonConexao $conexao): void
        {
            $repo = new ItemVendaRepository();
            $repo->ObterTodosByVenda($venda, $conexao);
        }
        function Adicionar(SingletonConexao $conexao): bool
        {
            $repo = new ItemVendaRepository();
            return $repo->Adicionar($this, $conexao);
        }

    }