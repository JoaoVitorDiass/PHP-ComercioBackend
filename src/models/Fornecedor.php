<?php

namespace Comercio\Api\models;

use Comercio\Api\repository\FornecedorRepository;
use Comercio\Api\utils\SingletonConexao;
use Comercio\Api\utils\observer\Observer;
use Comercio\Api\utils\observer\Subject;

class Fornecedor implements Observer {

    private int $_codigo;
    private string $_nome;
    private string $_cnpj;
    private string $_endereco;
    private string $_telefone;
    private string $_email;

    /**
     * @param int $codigo
     * @param string $nome
     * @param string $cnpj
     * @param string $telefone
     * @param string $email
     * @param string $endereco
     */
    function __construct(   int $codigo=0,
                            string $nome="",
                            string $cnpj="",
                            string $telefone="",
                            string $email="",
                            string $endereco="") {
        $this->_codigo = $codigo;
        $this->_nome = $nome;
        $this->_cnpj = $cnpj;
        $this->_endereco = $endereco;
        $this->_telefone = $telefone;
        $this->_email = $email;
    }
    function getCodigo() : int
    {
        return $this->_codigo;
    }
    function setCodigo(int $codigo) : void
    {
        $this->_codigo = $codigo;
    }
    function getNome() : string
    {
        return $this->_nome;
    }
    function setNome(string $nomePessoa) : void
    {
        $this->_nomePessoa = $nomePessoa;
    }
    function getCnpj() : string
    {
        return $this->_cnpj;
    }
    function setCnpj(string $cnpj) : void
    {
        $this->_cnpj = $cnpj;
    }
    function getTelefone() : string
    {
        return $this->_telefone;
    }
    function setTelefone(string $telefone) : void
    {
        $this->_telefone = $telefone;
    }
    function getEmail() : string
    {
        return $this->_email;
    }
    function setEmail(string $email) : void
    {
        $this->_email = $email;
    }
    function getEndereco() : string
    {
        return $this->_endereco;
    }
    function setEndereco(string $endereco) : void
    {
        $this->_endereco = $endereco;
    }
    /*------------------------------------------------------------*/
    public function update(Subject $subject): void {  
        if ($subject instanceof Produto) {
            // echo "Fornecedor ".$this->_codigo.": Estoque do produto ".$subject->getCodigo()." - ". $subject->getDescricao()." está baixo.\n";

            $notificacao = new Notificacoes(
                0,
                "Fornecedor ".$this->_codigo." - ".$this->getNome().": Estoque do produto ".$subject->getCodigo()." - ". $subject->getDescricao()." está baixo.",
                $this,
                $subject
            );
            $notificacao->Adicionar(SingletonConexao::getInstancia());
        }
    }
    /*------------------------------------------------------------*/
    function Buscar(SingletonConexao $conexao): void
    {
        $repo = new FornecedorRepository();
        $repo->Obter($this, $conexao);
    }
    function BuscarTodos(SingletonConexao $conexao): array
    {
        $repo = new FornecedorRepository();
        return $repo->ObterTodos($conexao);
    }
    function Adicionar(SingletonConexao $conexao): bool
    {
        $repo = new FornecedorRepository();
        return $repo->Adicionar($this, $conexao); 
    }
    function Alterar(SingletonConexao $conexao): bool
    {
        $repo = new FornecedorRepository();
        return $repo->Alterar($this, $conexao);
    }
    function Deletar(int $codigoCliente, SingletonConexao $conexao): bool
    {
        $repo = new FornecedorRepository();
        return $repo->Deletar($codigoCliente, $conexao);
    }
}