<?php

class RoomBillDpController extends Controller
{
        
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='main';

	/**
	 * @return array action filters
	 */
	public function filters() {
            return array(
                'accessControl', // perform access control for CRUD operations
            );
        }

        public function accessRules() {
            return array(
                array('allow', // c
                    'actions' => array('create'),
                    'expression' => 'app()->controller->isValidAccess("RoomBill","c")'
                ),
                array('allow', // r
                    'actions' => array('index', 'view'),
                    'expression' => 'app()->controller->isValidAccess("RoomBill","r")'
                ),
                array('allow', // u
                    'actions' => array('update'),
                    'expression' => 'app()->controller->isValidAccess("RoomBill","u")'
                ),
                array('allow', // d
                    'actions' => array('delete'),
                    'expression' => 'app()->controller->isValidAccess("RoomBill","d")'
                )
            );
        }

	public function actionGetRoomBill() {
            $id = $_POST['roomId'];
            $roomBills = RoomBill::model()->find(array('condition' => 'room_id=' . $id . ' and is_checkedout=0 and processed=1 and date_bill="'.date("Y-m-d").'"'));
            $return=array();
            if (!empty($roomBills)){
                $return['room_bill_id']= $roomBills->id;
                $return['name']= $roomBills->Registration->Guest->name;
            }else{
                $return['room_bill_id']= '';
                $return['name']= '';
            }
            echo json_encode($return);
        }
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new RoomBillDp;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RoomBillDp']))
		{
			$model->attributes=$_POST['RoomBillDp'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RoomBillDp']))
		{
			$model->attributes=$_POST['RoomBillDp'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{		
            $criteria = new CDbCriteria();            

                $model=new RoomBillDp('search');
                $model->unsetAttributes();  // clear any default values

                if(isset($_GET['RoomBillDp']))
		{
                        $model->attributes=$_GET['RoomBillDp'];
			
			
                   	
                       if (!empty($model->id)) $criteria->addCondition('id = "'.$model->id.'"');
                     
                    	
                       if (!empty($model->room_bill_id)) $criteria->addCondition('room_bill_id = "'.$model->room_bill_id.'"');
                     
                    	
                       if (!empty($model->amount)) $criteria->addCondition('amount = "'.$model->amount.'"');
                     
                    	
                       if (!empty($model->cc_number)) $criteria->addCondition('cc_number = "'.$model->cc_number.'"');
                     
                    	
                       if (!empty($model->created)) $criteria->addCondition('created = "'.$model->created.'"');
                     
                    	
                       if (!empty($model->created_user_id)) $criteria->addCondition('created_user_id = "'.$model->created_user_id.'"');
                     
                    	
                       if (!empty($model->modified)) $criteria->addCondition('modified = "'.$model->modified.'"');
                     
                    			
		}
                
       

                $this->render('index',array(
			'model'=>$model,
		));

	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new RoomBillDp('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['RoomBillDp']))
			$model->attributes=$_GET['RoomBillDp'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=RoomBillDp::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='room-bill-dp-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
}
