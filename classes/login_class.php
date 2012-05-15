<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of login
 *
 * @author danieltorrico
 */
include_once 'bd.php';
include_once 'funcoes.php';
include_once 'index_class.php';

class login_class extends index_class {

    //put your code here
    public function __construct() {

        if ($_REQUEST[email_login] AND $_REQUEST[senha_login]) {
            if ($this->executarLogon($_REQUEST[email_login], $_REQUEST[senha_login]))
                echo "<meta http-equiv='Refresh' content='0;URL=index_class.php' />";
            else
                echo "LOGIN FAIL";
        }
        if($_GET[acao]=='sair'){
            $this->sair();
        }
        
        $this->formularioLogon();
    }

    private function formularioLogon() {
        echo "<form name='login' action='login.php' method='POST'>";
        echo "<table align='center'>";
        echo "<tr><th>E-mail: </th><td><input type='text' name='email_login' id='email_login' /></td></tr>";
        echo "<tr><th>Senha: </th><td><input type='password' name='senha_login' /></td></tr>";
        echo "</table>";
        echo "<div align='center'><input type='submit' value='Entrar' /></div>";
        echo "</form>";
    }

    private function validarUsuario($login, $password) {
        $bd = new BD();
        $sql = "SELECT * FROM pessoa WHERE pessoa.email= '$login' AND pessoa.senha= '$password'";
        $bd->executarSQL($sql);
        if ($bd->getQuantidadeRegistros() == 1) {
            return true;
        } else if ($bd->getQuantidadeRegistros() > 1) {
            echo"<script>alert('Atenção, este usuário esta replicado no Banco de Dados.')</script>";
            return true;
        }else
            return false;
    }

    private function executarLogon($login, $password) {
        if ($this->validarUsuario($login, $password)) {
            $bd = new BD();
            //nome, pessoa_id e email (login) do usuario
            $sql = "SELECT pessoa.id_pessoa, pessoa.nome, pessoa.email FROM pessoa WHERE pessoa.email = '$login'";
            $bd->executarSQL($sql);
            $row = $bd->getObjeto();
            if ($bd->getQuantidadeRegistros() > 0) {
                $_SESSION[email] = $login;
                $_SESSION[id_pessoa] = $row->id_pessoa;
                $_SESSION[nome_pessoa] = $row->nome;

                //dando os papeis ao usuario
                if (in_array($login, array('admin')))
                    $_SESSION[role][] = 'administrador';
                echo 'administrador!';

                //se nao tem papel entao retorna false
                if (empty($_SESSION[role])) {
                    $_SESSION[erro] = "O usuário $login não tem permissão dentro deste sistema.";
                    return false;
                }

                //menu
                $_SESSION[menu][] = array('texto' => 'Home', 'link' => 'home.php');
                if ($_SESSION[role][0] == "administrador") {
                    $_SESSION[menu][] = array('texto' => 'Home', 'link' => 'index.php');
                }
                if ($_SESSION[role][0] == "administrador") {
                    $_SESSION[menu][] = array('texto' => 'Administrar', 'link' => 'administrador.php');
                }

                //$_SESSION[menu][] = array('texto' => 'Sair', 'link' => 'logon.php?sair=sim');
                //log no sistema
                //$bd->log('logon no sistema');
                setcookie('usuario', $login, time() + 3600 * 24 * 30); //cria cookie para salvar o login do usuario para a proxima vez (na mesma maquina) nao ser necessario digitar o login
                //permite logon
                return true;
            } else {
                $_SESSION[erro] = "O usuário $login está inativo ou não existe seu cadastro como pessoa.";
                return false;
            }
        } else {
            $_SESSION[erro] = "Usuário ou/e senha incompativeis.<br/>Favor tentar novamente.";
            return false;
        }
    }

    private function sair() {
        //se sair destruir a sessao
        // Unset all of the session variables.
        $_SESSION = array();
        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (isset($_COOKIE[session_name()])) {
            //setcookie(session_name(), '', time()-42000, '/');
            unset($_COOKIE[session_name()]);
        }
        // Finally, destroy the session.
        session_destroy();

        session_unset();

        echo"Saída do sistema realizada com sucesso.";
    }

}

?>
