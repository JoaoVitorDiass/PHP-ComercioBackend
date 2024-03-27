<?php

    namespace Comercio\Api\models;

    use Comercio\Api\repository\ClienteRepository;
    use Comercio\Api\utils\SingletonConexao;

    use DateTime;
    class Cliente {

        private int $_codigo;
        private string $_nome;
        private string $_cpf;
        private DateTime $_dataNascimento;
        private string $_telefone;
        private string $_rg;
        private string $_email;
        private string $_endereco;

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
                                string $cpf="",
                                Datetime $dataNascimento=new DateTime(),
                                string $telefone="",
                                string $rg="",
                                string $email="",
                                string $endereco="") {
            $this->_codigo = $codigo;
            $this->_nome = $nome;
            $this->_cpf = $cpf;
            $this->_dataNascimento = $dataNascimento;
            $this->_telefone = $telefone;
            $this->_rg = $rg;
            $this->_email = $email;
            $this->_endereco = $endereco;
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
        function setNome(int $nomePessoa) : void
        {
            $this->_nomePessoa = $nomePessoa;
        }
        function getCpf() : string
        {
            return $this->_cpf;
        }
        function setCpf(string $cpf) : void
        {
            $this->_cpf = $cpf;
        }
        function getDataNascimento() : DateTime
        {
            return $this->_dataNascimento;
        }
        function setDataNascimento(DateTime $dataNascimento) : void
        {
            $this->_dataNascimento = $dataNascimento;
        }
        function getTelefone() : string
        {
            return $this->_telefone;
        }
        function setTelefone(string $telefone) : void
        {
            $this->_telefone = $telefone;
        }
        function getRg() : string
        {
            return $this->_rg;
        }
        function setRg(string $rg) : void
        {
            $this->_rg = $rg;
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

        function Buscar(SingletonConexao $conexao): void
        {
            $repo = new ClienteRepository();
            $repo->Obter($this, $conexao);
        }
        function BuscarTodos(SingletonConexao $conexao): array
        {
            $repo = new ClienteRepository();
            return $repo->ObterTodos($conexao);
        }
        function Adicionar(SingletonConexao $conexao): bool
        {
            $repo = new ClienteRepository();
            return $repo->Adicionar($this, $conexao);
        }
        function Alterar(SingletonConexao $conexao): bool
        {
            $repo = new ClienteRepository();
            return $repo->Alterar($this, $conexao);
        }
        function Deletar(int $codigoCliente, SingletonConexao $conexao): bool
        {
            $repo = new ClienteRepository();
            return $repo->Deletar($codigoCliente, $conexao);
        }
    }