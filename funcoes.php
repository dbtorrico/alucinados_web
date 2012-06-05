<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<?php
include_once 'bd.php';
?>

    <script language="javascript">
    function validarCampos(){
        if (document.getElementById('txtNome').value == ''){
            window.alert("Campo Nome não pode estar em branco!");
            return false;
        }
        if (document.getElementById('txtEmail').value == ''){
            window.alert("Campo E-mail não pode estar em branco");
            return false;
        }
        if (document.getElementById('txtSenha').value == ''){
            window.alert("Campo Senha não pode estar em branco");
            return false;
        }
        if (document.getElementById('txtConfSenha').value == ''){
            window.alert("Campo Confirmar Senha não pode estar em branco");
            return false;
        }
        if((document.getElementById('txtSenha').value)!=(document.getElementById('txtConfSenha').value)){
            window.alert("Erro:\nConfirme novamente sua senha");
            document.getElementById('txtConfSenha').value = '';
            return false;
        }
        else return true;
    }
    function voltar(){
        history.go(-1);
    }
    </script>

