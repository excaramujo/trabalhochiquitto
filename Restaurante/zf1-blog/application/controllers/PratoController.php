<?php

class PratoController extends Prato_Controller_Action {

    public function indexAction() {

        
        $tab = new Application_Model_DbTable_Prato();
        $consulta = $tab->getAdapter()->select();
        $consulta->from(array(
            "p" => "prato"
                ), array(
            "idprato", "nomeprato", "preco"
        ));
        $consulta->joinInner(array(
            "c" => "categoria"
                ), "c.idcategoria = p.idcategoria", array(
            "categoria"
        ));
        $consulta->where("p.idcategoria > ?", "0", Zend_Db::INT_TYPE);
        $consultaBd = $consulta->query()->fetchAll();
        $this->view->pratos = $consultaBd;
        $this->view->podeApagar = $this->aclIsAllowed('prato', 'delete');
        $this->view->podeAtualizarValor = $this->aclIsAllowed('prato', 'update-valor');
    }

    public function createAction() {
        $auth = Zend_Auth::getInstance();
        $dados = $auth->getStorage()->read();
        $frm = new Application_Form_Prato(Application_Form_Prato::CADASTRO_OR_UPDATE);   //instancia o formulário de post

        if ($this->getRequest()->isPost()) {
            $params = $this->getAllParams();

            if ($frm->isValid($params)) {
                $params = $frm->getValues();

                $prato = new Application_Model_Vo_Prato();
                $prato->setIdcategoria($params['idcategoria']);
                $prato->setIdadmin($dados->idadmin);
                $prato->setNomeprato($params['nomeprato']);
                $prato->setPreco(0);

                $model = new Application_Model_Prato();
                $model->salvar($prato);

                $flashMessenger = $this->_helper->FlashMessenger;
                $flashMessenger->addMessage("Prato cadastrado com sucesso");

                $this->_helper->Redirector->gotoSimpleAndExit('index');
            }
        }

        $this->view->frm = $frm;   //passa para a view
    }

    public function deleteAction() {

        $idprato = (int) $this->getParam('idprato', 0);

        $model = new Application_Model_Prato();
        $model->apagar($idprato);

        $flashMessenger = $this->_helper->FlashMessenger;
        $flashMessenger->addMessage("Prato apagado com sucesso");

        $this->_helper->Redirector->gotoSimpleAndExit('index');
    }

    public function updateAction() {
        $auth = Zend_Auth::getInstance();
        $dados = $auth->getStorage()->read();
        $idprato = (int) $this->getParam('idprato', 0);

        $tabela = new Application_Model_DbTable_Prato();
        $linha = $tabela->fetchRow('idprato = ' . $idprato);
        if ($linha === null) {
            echo 'Prato inexistente';
            exit;
        }

        $frm = new Application_Form_Prato(Application_Form_Prato::CADASTRO_OR_UPDATE);

        if ($this->getRequest()->isPost()) {
            $params = $this->getAllParams();  //pega os dados do usuário

            if ($frm->isValid($params)) {  //vai atribuir os valores para cada elemento e validar
                $params = $frm->getValues();   //pega os dados do formulário

                $prato = new Application_Model_Vo_Prato();
                $prato->setIdcategoria($params['idcategoria']);
                $prato->setIdadmin($dados->idadmin);
                $prato->setNomeprato($params['nomeprato']);
                $prato->setPreco(null);
                $prato->setIdprato($idprato);



                $model = new Application_Model_Prato();
                $model->atualizar($prato);


                $flashMessenger = $this->_helper->FlashMessenger;
                $flashMessenger->addMessage("Prato atualizado com sucesso.");

                $this->_helper->Redirector->gotoSimpleAndExit('index');
            }
        } else {   //se não veio por post pega os dados do banco
            $frm->populate(array(//passar informações para a tela do usuário

                'idcategoria' => $linha->idcategoria,
                'idadmin' => $linha->idadmin,
                'nomeprato' => $linha->nomeprato,
                'preco' => $linha->preco
            ));
        }

        $this->view->frm = $frm;
    }

    public function updateValorAction() {
        $auth = Zend_Auth::getInstance();
        $dados = $auth->getStorage()->read();
        $idprato = (int) $this->getParam('idprato', 0);

        $tabela = new Application_Model_DbTable_Prato();
        $linha = $tabela->fetchRow('idprato = ' . $idprato);
        if ($linha === null) {
            echo 'Prato inexistente';
            exit;
        }

        $frm = new Application_Form_Prato(Application_Form_Prato::UPDATE_VALOR);

        if ($this->getRequest()->isPost()) {
            $params = $this->getAllParams();  //pega os dados do usuário

            if ($frm->isValid($params)) {  //vai atribuir os valores para cada elemento e validar
                $params = $frm->getValues();   //pega os dados do formulário

                $prato = new Application_Model_Vo_Prato();
                $prato->setPreco($params['preco']);
                $prato->setIdprato($idprato);



                $model = new Application_Model_Prato();
                $model->atualizarValor($prato);


                $flashMessenger = $this->_helper->FlashMessenger;
                $flashMessenger->addMessage("Prato atualizado com sucesso.");

                $this->_helper->Redirector->gotoSimpleAndExit('index');
            }
            $this->view->prato = $linha;
        } else {   //se não veio por post pega os dados do banco
            $frm->populate(array(//passar informações para a tela do usuário

                'idcategoria' => $linha->idcategoria,
                'idadmin' => $linha->idadmin,
                'nomeprato' => $linha->nomeprato,
                'preco' => $linha->preco
            ));
        }

        $this->view->frm = $frm;
        $this->view->prato = $linha;
    }

}
