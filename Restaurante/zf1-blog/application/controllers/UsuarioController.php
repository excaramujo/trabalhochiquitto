<?php

class UsuarioController extends Prato_Controller_Action {

    public function indexAction() {
        $tb = new Application_Model_DbTable_Admin();
        $this->view->admins = $tb->fetchAll();
    }

    public function createAction() {
        $form = new Application_Form_Admin();

        if ($this->getRequest()->isPost()) {
            $values = $this->getAllParams();
            if ($form->isValid($values)) {
                $model = new Application_Model_Admin();

                $vo = new Application_Model_Vo_Admin();
                $vo->setNome($form->getValue('nome'));
                $vo->setEmail($form->getValue('email'));
                $vo->setSenha($form->getValue('senha'));
                $vo->setPapel($form->getValue('funcao'));

                $model->salvar($vo);

                $flashMessenger = $this->_helper->FlashMessenger;
                $flashMessenger->addMessage('Adm salvo!');

                $this->_helper->redirector->gotoSimpleAndExit('index');
            }
        }

        $this->view->form = $form;
    }

    public function deleteAction() {
        $idadmin = (int) $this->getParam('idadmin');
        $model = new Application_Model_Admin();

        $flashMessenger = $this->_helper->FlashMessenger;

        try {
            $model->apagar($idadmin);
            $flashMessenger->addMessage('Adm apagado!');
        } catch (Application_Model_Exception $exc) {
            $flashMessenger->addMessage($exc->getMessage());
        }

        $this->_helper->redirector->gotoSimpleAndExit('index');
    }

    public function updateAction() {
        $flashMessenger = $this->_helper->FlashMessenger;

        $idadmin = (int) $this->getParam('idadmin');
        $tb = new Application_Model_DbTable_Admin();
        $adminRow = $tb->fetchRow('idadmin = ' . $idadmin);

        if (!$adminRow) {
            $flashMessenger->addMessage('Adm inexistente!');
            $this->_helper->redirector->gotoSimpleAndExit('index');
        }

        $form = new Application_Form_Admin();
        $form->setIdadmin($idadmin);

        if ($this->getRequest()->isPost()) {
            $values = $this->getAllParams();
            if ($form->isValid($values)) {
                $model = new Application_Model_Admin();

                $vo = new Application_Model_Vo_Admin();
                $vo->setIdadmin($form->getValue('idadmin'));
                $vo->setNome($form->getValue('nome'));
                $vo->setEmail($form->getValue('email'));
                $vo->setSenha($form->getValue('senha'));
                $vo->setFuncao($form->getValue('funcao'));

                $model->atualizar($vo);

                $flashMessenger->addMessage('Adm atualizado!');

                $this->_helper->redirector->gotoSimpleAndExit('index');
            }
        } else {
            $form->populate(array(
                'idadmin' => $adminRow->idadmin,
                'nome' => $adminRow->nome,
                'email' => $adminRow->email,
                'senha' => $adminRow->senha,
                'funcao' => $adminRow->funcao,
            ));
        }

        $this->view->form = $form;
    }

}
