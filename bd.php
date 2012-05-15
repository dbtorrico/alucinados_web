<?php

//session_name('patrimonio');
session_start();

/**
 * Classe BD. Executa operações com o Banco de Dados
 *
 * @author (Gustavo Afonso) <gustavo.afonso@unifal-mg.edu.br>
 * sistema Alucinados_Web
 */
class BD {

    private $BD; //nome do BD
    private $HOST; //servidor do BD
    private $USUARIO; //usuario para acessar o BD
    private $PWD; //senha do usuario para acessar o BD
    private $SGBD; //o SGBD do BD
    private $PORTA; //porta do BD
    private $bdc; //bd conection
    private $resultado; //apontador para o resultado de um sql
    private $quantidadeRegistros; //quantidade de registros retornados por um sql

    /**
     * Seta os parametros da conexão com o BD e conecta a este BD
     *
     * @param string $BD        O nome do BD
     * @param string $HOST      O endereço IP do servidor do BD
     * @param string $USUARIO   O usuario para conexão com o BD
     * @param string $PWD       O password do usuario para conexão com o BD
     * @param string $SGBD      O SGBD do BD, podendo ser mysql ou postgres
     * @param string $PORTA     A porta de conexão com o BD
     */

    public function __construct($BD = 'teste1', $HOST = '127.0.0.1', $USUARIO = 'root', $PWD = 'unifal', $SGBD = 'mysql', $PORTA = '3306') {
        $this->BD = $BD;
        $this->HOST = $HOST;
        $this->USUARIO = $USUARIO;
        $this->PWD = $PWD;
        $this->SGBD = $SGBD;
        $this->PORTA = $PORTA;
        $this->conectar();
    }

    /**
     * Conecta ao BD. Conexao com charset UTF8 para MySQL.
     */
    private function conectar() {
        if ($this->SGBD == 'mysql') {//conectando ao mysql
            $this->bdc = mysql_connect($this->HOST, $this->USUARIO, $this->PWD);
            if ($this->bdc == FALSE) {
                //echo "<div id='mensagem' class='erro'>ERRO - Não foi possível conectar ao servidor do Banco de Dados $this->SGBD em $this->HOST:$this->PORTA</div>";
                echo "<div id='mensagem' class='erro'>ERRO - Não foi possível conectar ao servidor do Banco de Dados $this->SGBD</div>";
                exit();
            }
            mysql_set_charset('utf8', $this->bdc); //configurando para utf8 o character set (conjunto de caracteres) da conexao com o cliente
            if (!mysql_select_db($this->BD, $this->bdc)) {//selecionando o bd
                echo "<div id='mensagem' class='erro'>ERRO - Não foi possível conectar ao Banco de Dados $this->BD</div>";
                exit();
            }
        }
        if ($this->SGBD == 'postgres') {//conectando ao postgres
            $this->bdc = pg_connect("host=$this->HOST port=$this->PORTA dbname=$this->BD user=$this->USUARIO password=$this->PWD");
            if ($this->bdc == FALSE) {
                //echo "<div id='mensagem' class='erro'>ERRO - Não foi possível conectar ao servidor do Banco de Dados $this->SGBD em $this->HOST:$this->PORTA no banco $this->BD</div>";
                echo "<div id='mensagem' class='erro'>ERRO - Não foi possível conectar ao servidor do Banco de Dados $this->SGBD no banco $this->BD</div>";
                exit();
            }
        }
    }

    /**
     * Desconecta do BD
     */
    public function desconectar() {
        if ($this->SGBD == 'mysql') {
            mysql_close($this->bdc);
            unset($this->bdc);
        }
        if ($this->SGBD == 'postgres') {
            pg_close($this->bdc);
            unset($this->bdc);
        }
    }

    /**
     * Executa a sql passada como parametro.
     * Retorna TRUE em caso de sucesso na execução e FALSE caso contrario
     *
     * @param string $sql
     * @return boolean (TRUE, FALSE)
     */
    public function executarSQL($sql) {
        if ($this->SGBD == 'mysql') {
            $this->resultado = mysql_query($sql, $this->bdc); //executando o codigo sql
            if ($this->resultado == FALSE) {
                //$_SESSION['erro'][] = "Erro ao executar o comando SQL: $sql<br/>".mysql_error();
                $_SESSION['erro'][] = "Erro ao executar um comando SQL";
                return FALSE;
            }
            //atribuindo a quantidade de registros retornados (SELECT) ou afetados (INSERT, UPDATE...) pelo sql
            //para SELECTs
            if (stripos($sql, 'SELECT') !== FALSE) {
                if (mysql_num_rows($this->resultado) > 0) {//atribuindo a quantidade de registros retornados
                    $this->quantidadeRegistros = mysql_num_rows($this->resultado);
                } else {
                    $this->quantidadeRegistros = NULL;
                }
            }
            //para INSERTs, UPDATEs etc
            else {
                if ($this->resultado) {//atribuindo a quantidade de registros afetados
                    $this->quantidadeRegistros = mysql_affected_rows();
                } else {
                    $this->quantidadeRegistros = NULL;
                }
            }
            return TRUE;
        }
        if ($this->SGBD == 'postgres') {
            @$this->resultado = pg_query($this->bdc, $sql); //executando o codigo sql
            if ($this->resultado == FALSE) {
                $_SESSION['erro'][] = "Erro ao executar o comando SQL: $sql<br/>" . pg_errormessage();
                //$_SESSION['erro'][] = "Erro ao executar um comando SQL";
                return FALSE;
            }
            //atribuindo a quantidade de registros retornados (SELECT) ou afetados (INSERT, UPDATE...) pelo sql
            //para SELECTs
            if (stripos($sql, 'SELECT') !== FALSE) {
                if (pg_num_rows($this->resultado) > 0) {//atribuindo a quantidade de registros retornados
                    $this->quantidadeRegistros = pg_num_rows($this->resultado);
                } else {
                    $this->quantidadeRegistros = NULL;
                }
            }
            //para INSERTs, UPDATEs etc
            else {
                if (pg_affected_rows($this->resultado) > 0) {//atribuindo a quantidade de registros afetados
                    $this->quantidadeRegistros = pg_affected_rows($this->resultado);
                } else {
                    $this->quantidadeRegistros = NULL;
                }
            }
            return TRUE;
        }
    }

    /**
     * Retorna o ponteiro (resource) do resultado da execucao do sql (function executarSQL)
     *
     * @return resource
     */
    public function getResultado() {
        return $this->resultado;
    }

    /**
     * Retorna um objeto com propriedades que correspondem a linha obtida e move o ponteiro interno dos dados adiante.
     *
     * @return object Retorna um object com propriedades strings que correspondem a linha obtida, ou FALSE se não houverem mais linhas.
     */
    public function getObjeto() {
        if ($this->SGBD == 'mysql')
            return @mysql_fetch_object($this->resultado);
        if ($this->SGBD == 'postgres')
            return @pg_fetch_object($this->resultado);
    }

    /**
     * Obtém uma linha como uma matriz numérica
     *
     * @return array Retorna uma array que corresponde a linha obtida, ou FALSE  se não houver mais linhas.
     */
    public function getLinha() {
        if ($this->SGBD == 'mysql')
            return @mysql_fetch_row($this->resultado);
        if ($this->SGBD == 'postgres')
            return @pg_fetch_row($this->resultado);
    }

    /**
     * Retorna a quantidades de registros afetados ou retornados na execucao do sql (function executarSQL)
     *
     * @return integer
     */
    public function getQuantidadeRegistros() {
        return $this->quantidadeRegistros;
    }

    /**
     * Substitui todos os '' por NULL de uma query sql passada como parametro $sql
     *
     * @param string $sql
     * @return string $sql retorna a string $sql com todos os '' por NULL
     */
    public function substituiVazioPorNull($sql) {
        $sql = str_replace("''", "NULL", $sql);
        return $sql;
    }

    /**
     * Gera um registro de log no BD do sistema.
     * O registro contem:
     * - data/horario
     * - identificacaousuario
     * - operacao
     * - query (sql) executado
     * - identificacaotupla
     * - endereco IP da maquina cliente
     * - sistema que registrou o log
     *
     * @param string $operacao
     * @param string $query
     * @param string $identificacaotupla
     * @param string $sistema
     */
    public function log($operacao = null, $query = null, $identificacaotupla = null, $sistema = 'patrimonio') {
        $bd = new BD();
        $identificacaousuario = "login=$_SESSION[uid]|nome=$_SESSION[pessoanome]|pessoa_id=$_SESSION[pessoa_id]";
        $ip = $this->getRealIpAddr();
        $query = str_replace("'", "`", $query);
        $querylog = "INSERT INTO log(operacao, identificacaousuario, query, identificacaotupla, ip, sistema) " .
                "VALUES('$operacao', '$identificacaousuario', '$query', '$identificacaotupla', '$ip', '$sistema')";
        $bd->executarSQL($bd->substituiVazioPorNull($querylog));
    }

    /**
     * Obtem o endereço IP do cliente
     *
     * @return string $ip   O endereço IP do cliente
     */
    private function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) { //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


}

?>
