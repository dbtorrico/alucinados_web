<?php

include_once 'bd.php';
include_once 'funcoes.php';
include_once 'index_class.php';
include_once './classes/pessoa.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cadastro_class
 *
 * @author danieltorrico
 */
class cadastro_class extends index_class {

    //put your code here
    public function __construct() {
        
        $this->paginaCadastro();
        if ($_POST[executarAcao] == 'cadastrar') {
            $this->executarAcao($_POST[executarAcao]);
        }
    }

    private function paginaCadastro() {

        echo '<head>
        <link rel="stylesheet" type="text/css" href="estilos.css" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>';
        //parent::menu();
        echo'<body>';
        echo "  <form action='cadastro.php' onsubmit='return validarCampos()' method='POST'> ";
        echo "<input type='hidden' name='executarAcao' value='cadastrar' /> ";
        echo '   <fieldset> <legend>Novo Cadastro</legend>
                <table>
                    <tr><td><br>Nome*: </td><td><input type="text" id="txtNome" name="txtNome"/></td></tr>
                    <tr><td><br>Data de Nascimento: </td><td><input type="text" id="txtDataNasc" name="txtDataNasc"/></td></tr>
                    <tr><td><br>Email*: </td><td><input type="text" id="txtEmail" name="txtEmail"/></td></tr>
                    <tr><td><br>Telefone: </td><td><input type="text" id="txtTel" name="txtTel" /></td></tr>
                    <tr><td><br>Senha*: </td><td><input type="password" id="txtSenha" name="txtSenha" /></td></tr>
                    <tr><td><br>Confirmar Senha*: </td><td><input type="password" id="txtConfSenha" name="txtConfSenha" /></td></tr>
                    <tr><td><br>Endereço: </td><td><input type="text" id="txtEnd" name="txtEnd" /></td><tr>
                </table>
            </fieldset>
            <input type="submit" value="cadastrar" />
            <input type="button" value="Voltar" onclick="voltar()" />
        </form>

    </body>';
    }

    private function executarAcao($acao) {
        $pessoa = new pessoa();
        $pessoa->nome = $_POST[txtNome];
        $pessoa->data_nasc = $_POST[txtDataNasc];
        $pessoa->email = $_POST[txtEmail];
        $pessoa->telefone = $_POST[txtTel];
        $pessoa->senha = $_POST[txtSenha];
        $pessoa->endereco = $_POST[txtEnd];


        switch ($acao) {
            case 'cadastrar':
                $pessoa->bdInserir();
                break;
//            case 'excluir':
//                $termocorresponsabilidade->bdExcluir();
//                echo"<script>alert('Registro apagado com sucesso.')</script>";
//                break;
//            case 'editar':
//                $termoresponsabilidade->bdEditar();
//                break;
        }
        echo "<body onload=\"window.opener.location.reload(); window.close();\"></body>";
    }

}

?>
