<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cadastro_class
 *
 * @author danieltorrico
 */
class cadastro_class {

    //put your code here
    public function __construct() {
        $this->paginaCadsatro($_REQUEST[acao]);
        if ($_POST[executarAcao] == 'cadastrar') {
            $this->executarAcao($_POST[executarAcao]);
        }
    }

    private function paginaCadsatro($acao) {

        echo '<head>
        <link rel="stylesheet" type="text/css" href="estilos.css" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    Página de Cadastro
    <div id="menu5">
        <ul>
            modifiquei
            <li><a href="index.php" title="Link 1">Home</a></li>
            <li><a href="cadastro.php" title="Link 2">Cadastro</a></li>
            <li><a href="#3" title="Link 3">Sobre a festa</a></li>
            <li><a href="#4" title="Link 4">Fotos</a></li>
            <li><a href="#5" title="Link 5">Outros</a></li>	
        </ul>
    </div>
    <body>';
        echo "  <form action='cadastro_class.php' onsubmit='return validarCampos()' method='POST'> ";
        echo "<input type='hidden' name='executarAcao' value='$acao' /> ";
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
            <input type="submit" value="Cadastrar" />
            <input type="button" value="Voltar" onclick="voltar()" />
        </form>

    </body>';
    }

    private function executarAcao($acao) {
        $pessoa = new pessoa();
        $pessoa->nome = $_POST[nome];
        $pessoa->data_nasc = $_POST[data_nasc];
        $pessoa->email = $_POST[email];
        $pessoa->telefone = $_POST[telefone];
        $pessoa->senha = $_POST[senha];
        $pessoa->endereco = $_POST[endereco];


        switch ($acao) {
            case 'inserir':
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
