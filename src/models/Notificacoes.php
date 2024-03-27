<?php

    namespace Comercio\Api\models;

    use Comercio\Api\repository\NotificacoesRepository;
    use Comercio\Api\utils\SingletonConexao;

    class Notificacoes {

        private int $_codigo;
        private string $_mensagem;
        private Fornecedor $_fornecedor;
        private Produto $_produto;

        public function __construct(int $codigo=0,
                                    string $mensagem="",
                                    Fornecedor $fornecedor=null,
                                    Produto $produto=null) {
            $this->_codigo = $codigo;
            $this->_mensagem = $mensagem;
            $this->_fornecedor = $fornecedor;
            $this->_produto = $produto;
        }
        function getCodigo(): int
        {
            return $this->_codigo;
        }
        function setCodigo(int $codigo): void
        {
            $this->_codigo = $codigo;
        }
        function getMensagem(): string
        {
            return $this->_mensagem;
        }
        function setMensagem(string $mensagem): void
        {
            $this->_mensagem = $mensagem;
        }
        function getFornecedor(): Fornecedor
        {
            return $this->_fornecedor;
        }
        function setFornecedor(Fornecedor $fornecedor): void
        {
            $this->_fornecedor = $fornecedor;
        }
        function getProduto() : Produto
        {
            return $this->_produto;
        }
        function setProduto(Produto $produto) : void
        {
            $this->_produto = $produto;
        }

        function Buscar(SingletonConexao $conexao): void
        {
            $repo = new NotificacoesRepository();
            $repo->Obter($this, $conexao);
        }
        function BuscarTodos(SingletonConexao $conexao): array
        {
            $repo = new NotificacoesRepository();
            return $repo->ObterTodos($conexao);
        }
        function Adicionar(SingletonConexao $conexao): bool
        {
            $repo = new NotificacoesRepository();
            return $repo->Adicionar($this, $conexao);
        }
        // function Alterar(SingletonConexao $conexao): bool
        // {
        //     $repo = new NotificacoesRepository();
        //     return $repo->Alterar($this, $conexao);
        // }
        function Deletar(int $codigoVenda, $conexao): bool
        {
            $repo = new NotificacoesRepository();
            return $repo->Deletar($codigoVenda, $conexao);
        }
        function DeletarbByVenda(Venda $venda, $conexao): bool
        {
            $repo = new NotificacoesRepository();
            return $repo->DeletarByVenda($venda, $conexao);
        }
    }