<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pessoa
 *
 * @author danieltorrico
 */
class pessoa {

    
    public $bd;
    public $nome;
    public $email;
    public $senha;
    public $data_nasc;
    public $endereco;
    
    public function __construct() {
        $this->bd = new BD();
    }
    
    
    public function bdInserir() {
        $sql = "INSERT INTO teste1.pessoa (nome, email, senha, data_nasc, telefone, endereco)
        VALUES('$this->nome', '$this->email', '$this->senha', '$this->data_nasc', '$this->telefone', '$this->endereco') RETURNING id";
        $this->bd->executarSQL($this->bd->substituiVazioPorNull($sql));
        $obj = $this->bd->getObjeto();
        if (!empty($obj->id)) {
            $this->id = $obj->id; //atribuindo o id inserido ao objeto da classe TermoResponsabilidade
            $executou = $this->bd->executarSQL($sql);
            if (!$executou)
                $_SESSION[erro][] = "ERRO - Problema ao atualizar status do termo de responsabilidade anterior.";
            return $obj->id;
        } else {
            $_SESSION[erro][] = "ERRO - Problema ao inserir termo de responsabilidade.";
            return false;
        }
    }

    /**
     * Alteração na tabela patrimonio.termoresponsabilidade e gera log. 
     * Se der algum problema faz uma sessão com erro.
     * @return Verdadeiro para uma alteração sem erros e 
     * falso para algum possível erro que venha acontecer na alteração.
     */
    public function bdEditar() {
        $sql = "UPDATE patrimonio.termoresponsabilidade SET 
                    bemmovel_id = '$this->bemmovel_id', 
                    servidor_id = '$this->servidor_id', 
                    unidade_id = '$this->unidade_id', 
                    data = '$this->data', 
                    identificacaodocumento = '$this->identificacaodocumento', 
                    status = '$this->status'
                WHERE id = $this->id";
        $this->bd->executarSQL($this->bd->substituiVazioPorNull($sql));
        if ($this->bd->getQuantidadeRegistros() == 1) {
            $this->bd->log('editar termo de responsabilidade', $sql, $this->id);
            return true;
        } else {
            $_SESSION[erro][] = "ERRO - Problema ao editar termo de responsabilidade.";
            return false;
        }
    }

    /**
     * Exclui na tabela patrimonio.termoresponsabilidade e gera log. 
     * Se der algum problema faz uma sessão com erro.
     * @return Verdadeiro se conseguir excluir, ou se não excluiu mais inativou termo de responsabilidade.
     * Falso se não conseguir excluir e também não conseguir inativar bem móvel.
     */
    public function bdExcluir() {
        //excluindo ou inativando o termo de responsabilidade
        $sql = "DELETE FROM patrimonio.termoresponsabilidade WHERE id = $this->id";
        $this->bd->executarSQL($sql);

        if ($this->bd->getQuantidadeRegistros() == 1) {
            $this->bd->log('excluir termo de responsabilidade', $sql, $this->id);
            return true;
        } else {
            $_SESSION[erro] = null;
            $sql = "UPDATE patrimonio.termoresponsabilidade SET 
                        status = 'INATIVO' 
                     WHERE id = $this->id";
            $this->bd->executarSQL($sql);
            if ($this->bd->getQuantidadeRegistros() == 1) {
                $this->bd->log('inativar termo de responsabilidade', $sql, $this->id);
                return true;
            } else {
                $_SESSION[erro][] = "Problema ao excluir termo de responsabilidade.";
                return false;
            }
        }
    }

    /**
     * Procura na tabela patrimonio.termoresponsabilidade.
     * @param type $id Id do Termo Responsabilidade selecionado.
     * @return type $obj Retorna objeto procurado.
     */
    public function bdEncontrar($id) {
        $query = "SELECT * FROM patrimonio.termoresponsabilidade WHERE id = $id";
        $this->bd->executarSQL($query);
        $obj = $this->bd->getObjeto();

        $this->id = $obj->id;
        $this->bemmovel_id = $obj->bemmovel_id;
        $this->servidor_id = $obj->servidor_id;
        $this->unidade_id = $obj->unidade_id;
        $this->data = $obj->data;
        $this->identificacaodocumento = $obj->identificacaodocumento;
        $this->status = $obj->status;

        return $obj;
    }

}

?>
