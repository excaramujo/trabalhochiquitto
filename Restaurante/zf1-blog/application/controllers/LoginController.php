<?php

class LoginController extends Prato_Controller_Action {

    public function indexAction() {
        $form = new Application_Form_Login();
        
        if ($this->getRequest()->isPost()) {
            $values = $this->getAllParams();
            if ($form->isValid($values)) {
                // autenticacao do usuario

                $adapter = new Zend_Auth_Adapter_DbTable();
                $adapter ->setTableName('admin');  //nome da tabela
                $adapter ->setIdentityColumn('email');  //coluna de identificação do usuário
                $adapter ->setCredentialColumn('senha');  //credencial do usuário

                $adapter ->setIdentity($form ->getValue('email'));  //$form ->getValue() pega um valor
                $adapter ->setCredential($form ->getValue('senha'));  //pega os valores passados no formulário

                $auth = Zend_Auth::getInstance();
                $resultado = $auth ->authenticate($adapter);  //autentica o usuário

                if ($resultado ->isValid())  //se a autenticação está válida
                {
                    $dados = $adapter ->getResultRowObject(null, array('senha'));  //pega os dados do usuário, pega tudo menos a senha
                    $auth ->getStorage() ->write($dados);  //grava os dados em um lugar temporário
                    
                    $this ->_helper ->redirector ->gotoSimpleAndExit('index', 'index');  //redireciona para o index do controller index
                }
                else
                {
                    $form ->getElement('email') ->addError('Login ou Senha estao incorretos');  //marca o elemento do formulário e dá a mensagem de erro
                }
            }
        }
        
        $this->view->form = $form;
    }

    public function logoutAction() {

        Zend_Auth::getInstance() ->clearIdentity();   //pega a instância só uma vez e acessa o método limpar

        $this ->_helper ->redirector ->gotoSimpleAndExit('index');  //vai para o login
        
    }

}
