<?php

    namespace Comercio\Api\models;

    use Comercio\Api\models\Cliente;
    use Comercio\Api\models\ItemVenda;
    use Comercio\Api\repository\VendaRepository;

    use Comercio\Api\utils\SingletonConexao;

    class Venda {
        private int $_codigo;
        private ?Cliente $_cliente;
        private float $_valorTotal;
        private array $_itensVenda;
        

        /**
         * @param int $codigo
         * @param Cliente $cliente
         * @param float $valorTotal
         * @param ItemVenda[] $itensVenda
         */
        function __construct(   int $codigo=0,
                                Cliente $cliente=null,
                                float $valorTotal=0,
                                array $itensVenda=array() ) {
            $this->_codigo = $codigo;
            $this->_cliente = $cliente;
            $this->_valorTotal = $valorTotal;
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
        function getValorTotal() : float
        {
            return $this->_valorTotal;
        }
        function setValorTotal(float $valorTotal) : void
        {
            $this->_valorTotal = $valorTotal;
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

        function Buscar(SingletonConexao $conexao): void
        {
            $repo = new VendaRepository();
            $repo->Obter($this, $conexao);
        }
        function BuscarTodos(SingletonConexao $conexao): array
        {
            $repo = new VendaRepository();
            return $repo->ObterTodos($conexao);
        }
        function Adicionar(SingletonConexao $conexao): bool
        {
            $repo = new VendaRepository();
            return $repo->Adicionar($this, $conexao);
        }
        function Alterar(SingletonConexao $conexao): bool
        {
            $repo = new VendaRepository();
            return $repo->Alterar($this, $conexao);
        }
        function Deletar(int $codigoVenda, SingletonConexao $conexao): bool
        { 
            $repo = new VendaRepository();
            return $repo->Deletar($codigoVenda, $conexao);
        }
    }