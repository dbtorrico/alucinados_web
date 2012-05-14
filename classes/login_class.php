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

class login_class {

    //put your code here
    public function __construct() {
        
        if($_REQUEST[email_login] AND $_REQUEST[senha_login]){
            if($this->executarLogon($_REQUEST[email_login], $_REQUEST[senha_login])) echo "BEM VINDO";
            else echo "LOGIN FAIL";
        }
        $this->formularioLogon();
    }
    
    private function formularioLogon()
    {
        echo "<form name='login' action='login.php' method='POST'>";
        echo "<table align='center'>";
        echo "<tr><th>E-mail: </th><td><input type='text' name='email_login' id='email_login' /></td></tr>";
        echo "<tr><th>Senha: </th><td><input type='password' name='senha_login' /></td></tr>";
        echo "</table>";
        echo "<div align='center'><input type='submit' value='Entrar' /></div>";
        echo "</form>";
    }
    private function validarUsuario($login, $password) {
        if(($login=="admin")&&($password=="123")){
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

                //se nao tem papel entao retorna false
                if (empty($_SESSION[role])) {
                    $_SESSION[erro] = "O usuário $login não tem permissão dentro deste sistema.";
                    return false;
                }

                //menu
                $_SESSION[menu][] = array('texto' => 'Home', 'link' => 'home.php');
                if ($_SESSION[role][0]=="administrador") {
                    $_SESSION[menu][] = array('texto' => 'Home', 'link' => 'index.php');
                }
                if ($_SESSION[role][0]=="administrador") {
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

}

?>
