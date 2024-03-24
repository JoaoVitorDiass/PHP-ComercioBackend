<?php

    namespace Comercio\Api\models;

    use Comercio\Api\repository\MetodoPagamentoRepository;
    use Comercio\Api\utils\SingletonConexao;

    class MetodoPagamento {

        private int $_codigo;
        private string $_descricao;

        public function __construct(int $codigo=0, string $descricao="") {
            $this->_codigo = $codigo;
            $this->_descricao = $descricao;
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
        /*------------------------------------------------------------*/

        /*------------------------------------------------------------*/
        function Buscar(SingletonConexao $conexao): void
        {
            $repo = new MetodoPagamentoRepository();
            $repo->Obter($this, $conexao);
        }
        function BuscarTodos(SingletonConexao $conexao): array
        {
            $repo = new MetodoPagamentoRepository();
            return $repo->ObterTodos($conexao);
        }
        function Adicionar(SingletonConexao $conexao): bool
        {
            $repo = new MetodoPagamentoRepository();
            return $repo->Adicionar($this, $conexao);
        }
        function Alterar(SingletonConexao $conexao): bool
        {
            $repo = new MetodoPagamentoRepository();
            return $repo->Alterar($this, $conexao);
        }
        function Deletar(int $codigoVenda, $conexao): bool
        {
            $repo = new MetodoPagamentoRepository();
            return $repo->Deletar($codigoVenda, $conexao);
        }
    }