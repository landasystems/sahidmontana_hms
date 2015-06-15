<?php

class GlController extends Controller
{
        public $breadcrumbs;
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
                    'actions' => array('index', 'create'),
                    'expression' => 'app()->controller->isValidAccess(1,"c")'
                ),
                array('allow', // r
                    'actions' => array('index', 'view'),
                    'expression' => 'app()->controller->isValidAccess(1,"r")'
                ),
                array('allow', // u
                    'actions' => array('index', 'update'),
                    'expression' => 'app()->controller->isValidAccess(1,"u")'
                ),
                array('allow', // d
                    'actions' => array('index', 'delete'),
                    'expression' => 'app()->controller->isValidAccess(1,"d")'
                )
            );
        }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
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
		$model=new Gl;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Gl']))
		{
			$model->attributes=$_POST['Gl'];
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

		if(isset($_POST['Gl']))
		{
			$model->attributes=$_POST['Gl'];
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

                $model=new Gl('search');
                $model->unsetAttributes();  // clear any default values

                if(isset($_GET['Gl']))
		{
                        $model->attributes=$_GET['Gl'];
			
			
                   	
                       if (!empty($model->id)) $criteria->addCondition('id = "'.$model->id.'"');
                     
                    	
                       if (!empty($model->na_id)) $criteria->addCondition('na_id = "'.$model->na_id.'"');
                     
                    	
                       if (!empty($model->room_id)) $criteria->addCondition('room_id = "'.$model->room_id.'"');
                     
                    	
                       if (!empty($model->guest_user_id)) $criteria->addCondition('guest_user_id = "'.$model->guest_user_id.'"');
                     
                    	
                       if (!empty($model->charge_previous)) $criteria->addCondition('charge_previous = "'.$model->charge_previous.'"');
                     
                    	
                       if (!empty($model->charge)) $criteria->addCondition('charge = "'.$model->charge.'"');
                     
                    	
                       if (!empty($model->charge_settle)) $criteria->addCondition('charge_settle = "'.$model->charge_settle.'"');
                     
                    	
                       if (!empty($model->charge_balance)) $criteria->addCondition('charge_balance = "'.$model->charge_balance.'"');
                     
                    			
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
		$model=new Gl('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Gl']))
			$model->attributes=$_GET['Gl'];

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
		$model=Gl::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='gl-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
}
