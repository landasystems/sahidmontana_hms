<?php

class HelpController extends Controller {

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
                'actions' => array('view','index'),
                'expression' => 'app()->controller->isValidAccess()'
            ),
        );
    }
    
    public function actionIndex(){
        $appName = app()->name;
        if ($appName == 'Content Management Systems') {
            $this->render('cms');
        } elseif ($appName == 'Inventory Management Systems') {
            $this->render('ims');
        } elseif ($appName == 'Hotel Management Systems') {
           $this->render('hms');
        } elseif ($appName == 'Academic Management Systems') {
            $this->render('ams');
        }
    }
    
    public function actionAboutUs() {
        $model = $this->loadModel(3);
        $this->render('view', array(
            'model' => $model,
        ));
    }
    
    public function actionView($id) {
        $model = $this->loadModel($id);
        $this->render('view', array(
            'model' => $model,
        ));
    }

//    public function actionViewTOC($id) {
//        $model = $this->loadModel($id);
//        $this->render('viewTOC', array(
//            'model' => $model,
//        ));
//    }
//
//    public function actionTableOfContent($id) {
//        $this->render('viewListTOC', array(
//            'model' => LandaArticle::model()->findAll(array('condition' => 'article_category_id=' . $id, 'order' => 'title')),
////            'modelCategory' => LandaArticleCategory::model()->findAll('parent_id=:parent_id', array(':parent_id' => $id)),
//        ));
//    }
//
    public function loadModel($id) {
        $model = LandaArticle::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
