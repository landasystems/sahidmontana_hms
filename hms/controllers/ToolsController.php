<?php

class ToolsController extends Controller {

    

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';
    
    public function actionView($id) {       
        $model = $this->loadModel($id);
        $this->render('view', array(
            'model' => $model,
        ));
    }
    public function actionViewTOC($id) {       
        $model = $this->loadModel($id);
        $this->render('viewTOC', array(
            'model' => $model,
        ));
    }
    
    public function actionTableOfContent($id) {
        $this->render('viewListTOC', array(
            'model' => LandaArticle::model()->findAll(array('condition'=>'article_category_id='.$id, 'order'=>'title')),
//            'modelCategory' => LandaArticleCategory::model()->findAll('parent_id=:parent_id', array(':parent_id' => $id)),
        ));
    }
    
    public function loadModel($id) {
        $model = LandaArticle::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    
}
