<?php

class FiltroController extends Zend_Controller_Action {

	public function digitsAction() {

		$filtro = new Zend_Filter_Digits();   //instancia o filtro

		echo $filtro ->filter(date('c'));   //vai filtrar somente os números - saída 201605070233390200  tira espaços, aspas, letras mostra só números referenciando a data, hora e fuso horário

		exit;
	}

	public function htmlAction() {

		$filtro = new Zend_Filter_HtmlEntities();

		$var = "<strong>Brasil</strong>";

		echo $filtro ->filter($var);   //saída: <strong>Brasil</strong>  - converte html p/ texto comum  - echo $var;  saída: Brasil com negrito

		exit;
	}

	public function stripAction() {

		$filtro = new Zend_Filter_StripTags();

		$var = "<strong>Brasil</strong>";

		echo $filtro ->filter($var);  //remove o html - saída: Brasil sem negrito (strong)

		exit;  //termina o código p/ não precisar criar a tela da ação (Action)
	}

	public function compressAction() {

		$filtro = new Zend_Filter_Compress(array(
			'adapter' => 'Zip',    //tipo zip (adaptador)
			'options' => array(
			'archive' => 'D:\\Zend.zip'    //arquivo compactado
		    )
		));

		$filtro ->filter('D:\\banco.txt');   //caminho e arquivo que vai ser compactado

		exit;
	}
}