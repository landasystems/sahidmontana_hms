<?php

class LandaArticleController extends Controller {

    

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';
    
    public function actionViewFull($id) {       
        $model = $this->loadModel($id);
        $this->render('viewFull', array(
            'model' => $model,
        ));
    }
    public function actionViewList($id) {
        $this->render('viewList', array(
            'model' => LandaArticle::model()->findAll('article_category_id=:article_category_id', array(':article_category_id' => $id)),
            'modelCategory' => LandaArticleCategory::model()->findAll('parent_id=:parent_id', array(':parent_id' => $id)),
        ));
    }
    
    public function loadModel($id) {
        $model = LandaArticle::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    
}
