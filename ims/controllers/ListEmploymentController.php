<?php
class ListUserController extends Controller{
    public $breadcrumbs;
    public $layout = 'mainPublic';
    
    public function actionIndex(){
        $this->render('index',array());
    }
    
    public function actionEmployment(){
        $criteria = new CDbCriteria();

        $model = new User('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
        }

        $this->render('employment', array(
            'model' => $model,
        ));
    }
    public function actionViewEmployment($id) {
        $model = User::model()->findByPk($_POST['Sell']['customer_user_id']);
        echo $this->renderPartial('user/view', array('model' => $model));
    }
}
?>
