<?php

class CategoriaController extends Prato_Controller_Action {

    public function indexAction() {

        $tab = new Application_Model_DbTable_Categoria();
        $categorias = $tab ->fetchAll(null, 'idcategoria desc');  //pega os registros do banco, null(não vai passar where) e vai ordenar por id

        $this ->view ->categorias = $categorias;  //passa p/ view
        
    }

    public function createAction() {

    	$frm = new Application_Form_Categoria();  //instanciou o form

    	if($this ->getRequest() ->isPost())   //pega a requisição e testa se veio por post
    	{
    		$params = $this ->getAllParams();    //pega todos os paramentros passados pelo usuário

    		if($frm ->isValid($params))  //valida os parametros passados
    		{
    			$params = $frm ->getValues();   //pega os dados filtrados e validos de dentro do formulário

                $categoria = new Application_Model_Vo_Categoria();
                $categoria ->setCategoria($params['categoria']);

                $model = new Application_Model_Categoria();  //instancia o model
                $model ->salvar($categoria);    //acessa o método salvar

                $flashMessenger = $this ->_helper ->FlashMessenger;   //chama o objeto ajudante p/ ajudar o controller
                $flashMessenger ->addMessage("Categoria salva.");   //passa a mensagem p/ usuário

                $this ->_helper ->Redirector ->gotoSimpleAndExit('index');   //redireciona a tela e termina a execução do código - vai p/ index (Action) do mesmo controller que está
    		}
   
    	}

    	$this ->view ->frm = $frm;   //passou p/ tela
        
    }

    public function deleteAction() {

        $idcategoria = (int) $this ->getParam('idcategoria', 0);  //pega o parametro e converte para inteiro, se não tiver pega 0

        $model = new Application_Model_Categoria();   //instancia o model
        $flashMessenger = $this ->_helper ->FlashMessenger;    //chama a propriedade do helper

        try {  //tenta apagar
           $model ->apagar($idcategoria);    //chama o método do model Categoria.php
           $flashMessenger ->addMessage("Registro apagado");     //adiciona uma mensagem

      } catch (Exception $ex) {   //senão conseguir apagar pega a exceção
            $flashMessenger ->addMessage($ex ->getMessage());
      }

        $this ->_helper ->Redirector ->gotoSimpleAndExit('index');  //redireciona para a tela principal, listar
        
    }

    public function updateAction() {

    $idcategoria = (int) $this ->getParam('idcategoria', 0);  //converte o parametro da url para inteiro
    
    $tab = new Application_Model_DbTable_Categoria();   
    $row = $tab ->fetchRow('idcategoria = ' . $idcategoria);  //consulta para trazer só uma linha, um registro

    if($row === null)   //se o valor da url for nula
    {
        echo 'Categoria inexistente';
        exit;
    } 

    $frm = new Application_Form_Categoria();  //instanciou o form

        if($this ->getRequest() ->isPost())   //pega a requisição e testa se veio por post
        {
            $params = $this ->getAllParams();    //pega todos os paramentros passados pelo usuário

            if($frm ->isValid($params))  //valida os parametros passados
            {
                $params = $frm ->getValues();   //pega os dados filtrados e validos de dentro do formulário

                $categoria = new Application_Model_Vo_Categoria();
                $categoria ->setCategoria($params['categoria']);
                $categoria ->setIdCategoria($idcategoria);

                $model = new Application_Model_Categoria();  //instancia o model
                $model ->atualizar($categoria);    //acessa o método atualizar do model Categoria.php

                $flashMessenger = $this ->_helper ->FlashMessenger;   //chama o objeto ajudante p/ ajudar o controller
                $flashMessenger ->addMessage("Categoria salva.");   //passa a mensagem p/ usuário

                $this ->_helper ->Redirector ->gotoSimpleAndExit('index');   //redireciona a tela e termina a execução do código - vai p/ index (Action) do mesmo controller que está
            }        
        
    } else {   

         // $frm ->populate(array($row ->toArray()));   o nome do banco é o mesmo nome que está no formulário

         $frm ->populate(array(

            'categoria' => $row ->categoria

            ));    

    }

    $this ->view ->frm = $frm;

 }

}
