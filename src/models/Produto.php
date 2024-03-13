<?php

    namespace Comercio\Api\models;

    use Comercio\Api\models\Fornecedor;

    class Produto {

        private int $_codigo;
        private string $_descricao;
        private float $_valorCusto;
        private float $_valorVenda;
        private int $_quantidadeEstoque;
        private int $_estoqueMinimo;
        private ?Fornecedor $_fornecedor;


        /**
         * @param int $codigo
         * @param int $descricao
         * @param float $valorCusto
         * @param float $valorVenda
         * @param int $quantidadeEstoque
         * @param int $estoqueMinimo
         * @param Fornecedor $fornecedor
         */
        function __construct(   int $codigo=0,
                                int $descricao=0,
                                float $valorCusto=0,
                                float $valorVenda=0,
                                int $quantidadeEstoque=0,
                                int $estoqueMinimo=0,
                                Fornecedor $fornecedor=null ) {
            $this->_codigo = $codigo;
            $this->_descricao = $descricao;
            $this->_valorCusto = $valorCusto;
            $this->_valorVenda = $valorVenda;
            $this->_quantidadeEstoque = $quantidadeEstoque;
            $this->_fornecedor = $fornecedor;
            $this->_estoqueMinimo = $estoqueMinimo;
        }
        function getCodigo(): int
        {
            return $this->_codigo;
        }
        function setCodigo(int $codigo): void
        {
            $this->_codigo = $codigo;
        }
        function getDescricao(): string
        {
            return $this->_descricao;
        }
        function setDescricao(string $descricao): void
        {
            $this->_descricao = $descricao;
        }
        function getValorCusto(): float
        {
            return $this->_valorCusto;
        }
        function setValorCusto(float $valorCusto): void
        {
            $this->_valorCusto = $valorCusto;
        }
        function getValorVenda(): float
        {
            return $this->_valorVenda;
        }
        function setValorVenda(float $valorVenda): void
        {
            $this->_valorVenda = $valorVenda;
        }
        function getQuantidadeEstoque(): int
        {
            return $this->_quantidadeEstoque;
        }
        function setQuantidadeEstoque(int $quantidadeEstoque): void
        {
            $this->_quantidadeEstoque = $quantidadeEstoque;
        }
        function getEstoqueMinimo(): int
        {
            return $this->_estoqueMinimo;
        }
        function setEstoqueMinimo(int $estoqueMinimo): void
        {
            $this->_estoqueMinimo = $estoqueMinimo;
        }
        function getFornecedor(): Fornecedor
        {
            return $this->_fornecedor;
        }
        function setFornecedor(Fornecedor $fornecedor): void
        {
            $this->_fornecedor = $fornecedor;
        }

    }