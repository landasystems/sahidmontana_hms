<?php

/**
 * Description of FileManagerController
 *
 * @author landa
 */
class FileManagerController extends Controller {

    public $breadcrumbs;
    public $layout = '//layouts/main';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', 
                'actions' => array('index'),
                'expression' => 'app()->controller->isValidAccess()'
            ),
        );
    }

    public function actions() {
        return array(
            'fileUploaderConnector' => "common.extensions.ezzeelfinder.ElFinderConnectorAction",
        );
    }

    public function actionIndex() {
        $this->render('index', array());
    }

    public function actionIndexBlank() {
        $this->layout = 'none';
        $this->render('index', array());
    }

}

?>
