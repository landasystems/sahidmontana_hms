<?php

/**
 * Description of FileManagerController
 *
 * @author landa
 */
class FileManagerController extends Controller {

    public $breadcrumbs;
    public $layout = 'main';

//    public function filters() {
//        return array(
//            'rights', // perform access control for CRUD operations
//        );
//    }

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
