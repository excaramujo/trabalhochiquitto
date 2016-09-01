<?php

class Application_Form_Categoria extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $val = new Zend_Validate_StringLength();    //instancia o validador
        $val ->setMin(5);    //coloca a quantidade mínima

        $categoria = new Zend_Form_Element_Text('categoria', array(   //instancia o elemento campo de texto  -  array de opções
        	'label' => 'Nome da categoria',    //nome que aparece em cima do campo
        	'required' => true  //campo obrigatório  

        	));

        $categoria ->addValidator($val);   //adiciona o validador no elemento da categoria (campo de texto)

        $categoria ->addFilter(new Zend_Filter_StringToUpper());   //filtra o valor, deixa os caracteres em maiúsculos, o filtro vem antes da validação

        $this ->addElement($categoria);   //vincula qual formulário  -  this é este form

        $submit = new Zend_Form_Element_Submit('submit', array(   //instancia o elemento botão
        	'label' => 'Salvar'

        	));

        $this ->addElement($submit);

    }

}
