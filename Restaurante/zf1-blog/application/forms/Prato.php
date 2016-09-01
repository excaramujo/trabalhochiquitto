<?php

class Application_Form_Prato extends Zend_Form {

    const CADASTRO_OR_UPDATE = 1;
    const UPDATE_VALOR = 2;

    public function __construct($form) {
       parent::__construct();
        switch ($form) {
            case 1:
                $this->updateCadastrar();
                break;
            case 2:
                $this->updateValor();
                break;
            default :
                break;
        }
    }

    public function updateCadastrar() {
        $this->setMethod('post');

        $nomeprato = new Zend_Form_Element_Text('nomeprato', array(//elemento de text - edit
            'label' => 'Nome do Prato: ',
            'required' => true   //é obrigatório colocar o título
        ));

        $this->addElement($nomeprato);

        $categoria = new Zend_Form_Element_Select('idcategoria', array(
            'label' => 'Categoria',
            'required' => true
        ));

        $this->addElement($categoria);

//        $login = new Zend_Form_Element_Select('idadmin', array(
//                'label' => 'Usuario',
//                'required' => true
//            ));
//        $this ->addElement($login);

        $categoria->setMultiOptions($this->pegarCategorias());  //passa as opções do laço para o elemento categoria
//        $login->setMultiOptions($this ->pegarLogin());

        $f = new Zend_Filter_Null();   //instancia o filtro
        $categoria->addFilter($f);   //adiciona o filtro ao elemento de categoria (select)
//        $preco = new Zend_Form_Element_Text('preco', array(   //elemento textarea
//        	    'label' => 'Preço do Prato R$ :',
//        	    'required' => true
//        	));
//
//        $this ->addElement($preco);   //adicionar o elemento ao formulário

        $botao = new Zend_Form_Element_Submit('botao', array(//elemento botão
            'label' => 'Salvar'
        ));

        $this->addElement($botao);
    }

    public function pegarCategorias() {

        $tab = new Application_Model_DbTable_Categoria();   //instancia a tabela de categoria

        $categorias = $tab->fetchAll()->toArray();   //pega todos os registros de categoria e transforma a variável em array

        $options = array();
        $options[0] = 'Selecione uma Categoria';
        foreach ($categorias as $item) {   //iterar sobre o array  - transforma em array associativo
            $idcategoria = $item['idcategoria'];   //recebe o código da categoria, índice
            $nomeCategoria = $item['categoria'];  //recebe o nome da categoria

            $options[$idcategoria] = $nomeCategoria;   //o índice(idcategoria) recebe o nome da categoria
        }

        return $options;
    }

    public function pegarLogin() {

        $tabela = new Application_Model_DbTable_Admin();

        $loginAdmin = $tabela->fetchAll()->toArray();

        $options = array();
        $options[0] = 'Selecione';
        foreach ($loginAdmin as $item) {
            $idadmin = $item['idadmin'];
            $nome = $item['nome'];

            $options[$idadmin] = $nome;
        }

        return $options;
    }

    public function updateValor() {
        $this->setMethod('post');
        $preco = new Zend_Form_Element_Text('preco', array(//elemento textarea
            'label' => 'Preço do Prato R$ :',
            'required' => true
        ));

        $this->addElement($preco);   //adicionar o elemento ao formulário
        
        $botao = new Zend_Form_Element_Submit('botao', array(//elemento botão
            'label' => 'Salvar'
        ));

        $this->addElement($botao);
    }

}
