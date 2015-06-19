<?php

class TicketDetailController extends Controller {

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

    public function actionCreate() {
        $model = new TicketDetail;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        //if (isset($_POST['Ticket'])) {

        $model->message = $_POST['TicketDetail']['message'];
        //$model->status = 'OPEN';


        if ($model->save()) {


//                save table ticket detail
//                $modelTicketDetail->ticket_id = $model->id;
//                $model->message = $_POST['TicketDetail'];
//                $modelTicketDetail->save();
            //redirect
            $this->redirect(array('view', 'id' => $model->id));
        }
        //}
    }

    public function actionCreateDetail() {
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['TicketDetail'])) {


            //insert new record user message detail
            $model = new TicketDetail;
            $model->attributes = $_POST['TicketDetail'];
            $model->save();

            //render new li
//            $model = $this->loadModel($model->id);
//            $listUser = User::model()->listUser();
//            $type = 'user';
//            $name = $listUser[app()->user->id]['name'];
//            $img = Yii::app()->landa->urlImg('avatar/', $listUser[app()->user->id]['avatar_img'], app()->user->id);
//            $model = $this->loadModel($model->id);
            //$listUser = User::model()->listUser();
            $model = $this->loadModel($model->id);
            $type = 'user';
            $name = $model->created_user_name;
            $img = '';

            echo '<div class="nextMessage"></div>';
            $this->renderPartial('/ticket/_viewDetailLi', array('type' => $type, 'ticketDetail' => $model, 'img' => $img, 'name' => $name));
        }
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//            if (!isset($_GET['ajax']))
//                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function loadModel($id) {
        $model = TicketDetail::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
