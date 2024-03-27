<?php

    namespace Comercio\Api\models;
    
    use Comercio\Api\utils\SingletonConexao;
    use Comercio\Api\repository\CrediarioRepository;
    use DateTime;
    
    class Crediario {
        private int $_codigo;
        private ?Cliente $_cliente;
        private ?Venda $_venda;
        private float $_valorOriginal;
        private ?DateTime $_dataInclusao;

        public function __construct(int $codigo=0,
                                    Cliente $cliente=null,
                                    Venda $venda=null,
                                    float $valorOriginal=0,
                                    DateTime $dataInclusao=null) {
            $this->_codigo = $codigo;
            $this->_cliente = $cliente;
            $this->_venda = $venda;
            $this->_valorOriginal = $valorOriginal;
            $this->_dataInclusao = $dataInclusao;
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
        function getVenda() : Venda
        {
            return $this->_venda;
        }
        function setVenda(Venda $venda) : void
        {
            $this->_venda = $venda;
        }
        function getValorOriginal() : float
        {
            return $this->_valorOriginal;
        }
        function setValorOriginal(float $valorOriginal) : void
        {
            $this->_valorOriginal = $valorOriginal;
        }
        function getDataInclusao() : DateTime
        {
            return $this->_dataInclusao;
        }
        function setDataInclusao(DateTime $dataInclusao) : void
        {
            $this->_dataInclusao = $dataInclusao;
        }
        /*------------------------------------------------------------*/
        function Buscar(SingletonConexao $conexao): void
        {
            $repo = new CrediarioRepository();
            $repo->Obter($this, $conexao);
        }
        function BuscarTodos(SingletonConexao $conexao): array
        {
            $repo = new CrediarioRepository();
            return $repo->ObterTodos($conexao);
        }
        function Adicionar(SingletonConexao $conexao): bool
        {
            $repo = new CrediarioRepository();
            return $repo->Adicionar($this, $conexao); 
        }
        function Alterar(SingletonConexao $conexao): bool
        {
            $repo = new CrediarioRepository();
            return $repo->Alterar($this, $conexao);
        }
        function Deletar(int $codigoCliente, SingletonConexao $conexao): bool
        {
            $repo = new CrediarioRepository();
            return $repo->Deletar($codigoCliente, $conexao);
        }
    }