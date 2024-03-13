<?php

    namespace Comercio\Api\models;
    use Comercio\Api\repository\UsuarioRepository;
    use Comercio\Api\utils\Conexao;

    class Usuario {

        private int $_codigo;
        private string $_login;
        private string $_senha;

        function __construct(int $codigo=0, string $login="", string $senha="") {
            $this->_codigo = $codigo;
            $this->_login = $login;
            $this->_senha = $senha;
        }

        function getCodigo(): int
        {
            return $this->_codigo;
        }
        function setCodigo(int $codigo): void
        {
            $this->_codigo = $codigo;
        }
        function getLogin(): string
        {
            return $this->_login;
        }
        function setLogin(string $login): void
        {
            $this->_login = $login;
        }
        function getSenha(): string
        {
            return $this->_senha;
        }
        function setSenha(string $senha): void
        {
            $this->_senha = $senha;
        }

        function BuscarUsuario(int $codigoUsuario, Conexao $conexao): ?Usuario
        {
            $repo = new UsuarioRepository();
            return $repo->ObterUsuario($codigoUsuario, $conexao);
        }
        function BuscarUsuarioByLogin( string $login, Conexao $conexao): ?Usuario
        {
            $repo = new UsuarioRepository();
            return $repo->ObterUsuarioByLogin($login, $conexao);
        }
        function BuscarTodos(Conexao $conexao): array
        {
            $repo = new UsuarioRepository();
            return $repo->ObterTodos($conexao);
        }
        function Adicionar(Conexao $conexao): bool
        {
            $repo = new UsuarioRepository();
            return $repo->Adicionar($this, $conexao);
        }
        function Alterar(Conexao $conexao): bool
        {
            $repo = new UsuarioRepository();
            return $repo->Alterar($this, $conexao);
        }
        function Deletar(int $codigoUsuario, Conexao $conexao): bool
        {
            $repo = new UsuarioRepository();
            return $repo->Deletar($codigoUsuario, $conexao);
        }
    }