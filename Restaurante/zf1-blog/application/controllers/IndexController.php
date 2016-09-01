<?php

class IndexController extends Prato_Controller_Action {

    public function indexAction() {

        $idcategoria = (int) $this->getParam('idcategoria', 0);        

    	$select = $this ->select();  //chama o objeto select

        if($idcategoria > 0)
        {
            $select ->where('c.idcategoria  = ?', $idcategoria);
        }

    	$select ->order('p.idprato desc');   //order by do maior p/ menor
    	$select ->limit(10);   //limite de 10 resgistros

    	$consulta = $select ->query() ->fetchAll();

    	$this ->view ->pratos = $consulta;   //passa para a view

    	//index passando parametro a mais
        
    }

    public function categoriasAction() {

    	$dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
    	$select = $dbAdapter ->select();
    	$select ->from(array(
    		'c' => 'categoria'
    	),
    	array('categoria', 'idcategoria'
    	));

    	$consulta = $select ->query() ->fetchAll();

    	$this ->view ->categorias = $consulta; 
        
    }

    public function pratoAction() {

    	$idprato = (int)  $this ->getParam('idprato', 0);  //converte o parametro da url para inteiro

    	$select = $this ->select();  //chama o objeto select
    	$select ->where('p.idprato = ?', $idprato);  //paramentro passado no where

    	$prato = $select ->query() ->fetch();  //query executa o select e fetch pega um registro

    	$this ->view ->prato = $prato;   //passa para a view do post.phtml do views - scripts - index


    }

    private function select() {

    	$dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
    	$select = $dbAdapter ->select();
    	$select ->from(array(
    		'p' => 'prato'  //consultado e pegando tudo de post
    	),
    	array('nomeprato', 'preco', 'idprato'   //atributos da tabela post
    	));

    	$select ->joinInner(array(  //inner join com a tebela de categoria
    		'c' => 'categoria'
    	), 'p.idcategoria = c.idcategoria',
    	array('categoria'     //categoria da tabela categoria
    	));

    	return $select;   //retorna  um objeto select

    }

}
