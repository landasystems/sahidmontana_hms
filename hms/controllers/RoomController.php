<?php

class RoomController extends Controller {

    

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // c
                'actions' => array('create'),
                'expression' => 'app()->controller->isValidAccess("Room","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view','linkedRoom','updateLinkedRoom'),
                'expression' => 'app()->controller->isValidAccess("Room","r")'
            ),
            array('allow', // r
                'actions' => array('index', 'view','linkedRoom','updateLinkedRoom'),
                'expression' => 'app()->controller->isValidAccess("LinkedRoom","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("Room","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("Room","d")'
            )
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionChangeToVacant($id) {
        RoomSchedule::model()->updateAll(array('status' => 'vacant'), 'room_id=' . $id.' and date_schedule="'.$_GET['date'].'"');
        Room::model()->updateAll(array('status' => 'vacant'), 'id=' . $id);
        $this->redirect(array('index'));
    }

    public function actionCreate() {
        $model = new Room;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Room'])) {
            $model->attributes = $_POST['Room'];
            $model->id = $model->number;
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Room'])) {
            $model->attributes = $_POST['Room'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $criteria = new CDbCriteria();

        $model = new Room('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Room'])) {
            $model->attributes = $_GET['Room'];



            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->number))
                $criteria->addCondition('number = "' . $model->number . '"');


            if (!empty($model->room_type_id))
                $criteria->addCondition('room_type_id = "' . $model->room_type_id . '"');


            if (!empty($model->floor))
                $criteria->addCondition('floor = "' . $model->floor . '"');


            if (!empty($model->bed))
                $criteria->addCondition('bed = "' . $model->bed . '"');


            if (!empty($model->linked_room_id))
                $criteria->addCondition('linked_room_id = "' . $model->linked_room_id . '"');
        }
       


        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionLinkedRoom() {
        $model = Room::model()->findAll(array('condition' => '(status="occupied"  or status="house use")  and linked_room_id =0'));
        $this->render('linkedRoom', array(
            'model' => $model,
        ));
    }

    public function actionUpdateLinkedRoom($id) {
        $model = $this->loadModel($id);
        if ($model->status != "occupied" && $model->status != "house use")
            throw new CHttpException(404, 'The requested page does not exist.');
        if (!empty($model->linked_room_id))
//            throw new CHttpException(404, 'The requested page does not exist.');
            $model = $this->loadModel($model->linked_room_id);

        if (isset($_POST['id'])) {
            Room::model()->updateAll(array('linked_room_id' => '0'), 'linked_room_id=' . $_POST['id']);
            if (!empty($_POST['linkedRoom'])) {
                foreach ($_POST['linkedRoom'] as $child) {
                    $linked = Room::model()->findByPk($child);
                    $linked->linked_room_id = $id;
                    $linked->save();
                }
            }
            $this->redirect(array('linkedRoom'));
        }

        $room = Room::model()->findAll(array('condition' => 'status="occupied" and linked_room_id=' . $model->id));
        $list = Room::model()->findAll(array('condition' => 'status="occupied" and id<>' . $model->id));
        $this->render('updateLinkedRoom', array(
            'model' => $model,
            'room' => $room,
            'list' => $list,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Room('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Room']))
            $model->attributes = $_GET['Room'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Room::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'room-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    

}
