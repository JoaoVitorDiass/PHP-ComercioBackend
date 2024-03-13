<?php

namespace Comercio\Api\models;

class Fornecedor {

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
    function setNome(int $nomePessoa) : void
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

}