<?php

    namespace Comercio\Api\models;

    use Comercio\Api\models\Fornecedor;
    use Comercio\Api\repository\ProdutoRepository;
    use Comercio\Api\utils\SingletonConexao;
    use Comercio\Api\utils\observer\Subject;
    use Comercio\Api\utils\observer\Observer;

    class Produto implements Subject{ 

        private int $_codigo;
        private string $_descricao;
        private float $_valorCusto;
        private float $_valorVenda;
        private int $_quantidadeEstoque;
        private int $_estoqueMinimo;
        private ?Fornecedor $_fornecedor; // Observador

        private Observer $observer;

        /**
         * @param int $codigo
         * @param string $descricao
         * @param float $valorCusto
         * @param float $valorVenda
         * @param int $quantidadeEstoque
         * @param int $estoqueMinimo
         * @param ?Fornecedor $fornecedor
         */
        function __construct(   int $codigo=0,
                                string $descricao="",
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
            // tenho que instanciar todos os observadores
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
        function setQuantidadeEstoque(int $quantidadeEstoque, array $retorno, bool $flagRepository=true): void
        {
            $this->_quantidadeEstoque = $quantidadeEstoque;
            if($flagRepository == true && $this->_quantidadeEstoque <= $this->_estoqueMinimo) {
                $this->notify($retorno);
            }
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
        /*------------------------------------------------------------*/

        public function attach(Observer $observer): void
        {
            $this->observer = $observer;
        }
        public function detach(Observer $observer): void { }
        public function notify(array $retorno): void
        {
            $this->observer->update($this, $retorno);
        }

        /*------------------------------------------------------------*/
        function Buscar(SingletonConexao $conexao): void
        {
            $repo = new ProdutoRepository();
            $repo->Obter($this, $conexao);
        }
        function BuscarTodos(SingletonConexao $conexao): array
        {
            $repo = new ProdutoRepository();
            return $repo->ObterTodos($conexao);
        }
        function Adicionar(SingletonConexao $conexao): bool
        {
            $repo = new ProdutoRepository();
            return $repo->Adicionar($this, $conexao);
        }
        function Alterar(SingletonConexao $conexao): bool
        {
            $repo = new ProdutoRepository();
            return $repo->Alterar($this, $conexao);
        }
        function Deletar(int $codigoVenda, $conexao): bool
        {
            $repo = new ProdutoRepository();
            return $repo->Deletar($codigoVenda, $conexao);
        }
    }