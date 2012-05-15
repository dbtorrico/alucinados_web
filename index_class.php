<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cadastro_class
 *
 * @author danieltorrico
 * 
 */
include_once 'bd.php';
include_once 'funcoes.php';

class index_class {

    public function __construct() {
        //mensagem de boas vindas e ajuda
        $this->cabecalho();
        $this->menu();
        echo "<p>";
        if (date('H') >= 5 AND date('H') < 12)
            echo "Bom dia ";
        elseif (date('H') >= 12 AND date('H') < 18)
            echo "Boa tarde ";
        else
            echo "Boa noite ";
        if (!empty($_SESSION[nome_pessoa])) {
            echo $_SESSION[nome_pessoa];
        }
        echo "</p>";
        $this->paginaPrincipal();
    }

    private function paginaPrincipal() {
        echo '<head>
        <link rel="stylesheet" type="text/css" href="estilos.css" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Alucinados Fest - Encomende seu ingresso</title>
    </head>
    <!--    <ul id="menu">
            <li><a href="index.php" target="_self" title="Home" class="current">Home</a></li>
            <li><a href="cadastro.php" target="_self" title="Cadastrar">Cadastrar</a></li>
        </ul>-->';
        echo'<br />
    <body>

    </body>';
    }

    public function menu() {
        echo'<div id="menu5">
        <ul>
            <li><a href="index_class.php" title="Link 1">Home</a></li>
            <li><a href="cadastro.php" title="Link 2">Cadastro</a></li>
            <li><a href="#3" title="Link 3">Sobre a festa</a></li>
            <li><a href="#4" title="Link 4">Fotos</a></li>
            <li><a href="#5" title="Link 5">Outros</a></li>
            <li><a href="login.php" title="Link 6">Login</a></li>
            <li><a href="login.php?acao=sair" title="Link 7">Sair</a></li>
        </ul>
        </br>
        </br>
    </div>';
    }

    public function cabecalho() {
        echo'<h1>ALUCINADOS</h1>';
    }

}

$index = new index_class();
?>
