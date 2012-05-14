<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<?php
include_once 'bd.php';
include_once './classes/login_class.php';
include_once 'funcoes.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php 
           $login = new login_class();
        ?>
<!--    <form action='index.php' onsubmit='return validarCampos()' method='POST'>
        
            Página de Login
            <fieldset> <legend>Login</legend>
                <table>
                    <tr><td><br>E-mail: </td><td><input type='text' id='txtEmail_Login' name='txtEmail_Login'/></td></tr>
                    <tr><td><br>Senha: </td><td><input type='password' id='txtSenha_Login' name='txtSenha_Login' /></td></tr>
                </table>
            </fieldset>
            <input type='submit' value='Login' />
            <input type='button' value='Voltar' onclick="voltar()" />
    </form>-->
</body>
</html>
