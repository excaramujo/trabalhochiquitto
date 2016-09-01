<?php

class Application_Model_Categoria {

    public function apagar($idcategoria) {

        $tabpost = new Application_Model_DbTable_Prato();
        $prato = $tabpost ->fetchRow("idcategoria = $idcategoria");  //verifica na tabela post se tem a categoria e não pode apagar

        if($prato !== null)  //verifica se não veio categoria
        {
            throw new Exception("Categoria com Prato", 1);   //lançar exceção - 1 pode ser usuado para alguma comparação

        }

        $tab = new Application_Model_DbTable_Categoria();
        $tab ->delete("idcategoria = $idcategoria");   //chama o método delete e passa o where (idcategoria)

        return true;

    }

    public function atualizar(Application_Model_Vo_Categoria $categoria) {

        $tab = new Application_Model_DbTable_Categoria();   //instanciou a tabela para usar
        $tab ->update(array('categoria' => $categoria ->getCategoria()    //chama o método update
            ), 'idcategoria = ' . $categoria ->getIdCategoria());

    }

    public function salvar(Application_Model_Vo_Categoria $categoria) {

    	$tab = new Application_Model_DbTable_Categoria();
    	$tab ->insert(array(   //método insert, passando um array p/ gravar no banco
    		  'categoria' => $categoria ->getCategoria()   //pega o retorno do método getCategoria e grava no indice

    		));

    	$id = $tab ->getAdapter() ->lastInsertId();  //adapter adapta a conexao com o banco(meio) e pega o último código gerado
    	$categoria ->setIdCategoria($id);   //passa o código p/ variável

    	return true;

    }

}
