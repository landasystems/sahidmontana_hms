<?php

class NaTransController extends Controller
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
                    'actions' => array( 'create'),
                    'expression' => 'app()->controller->isValidAccess("NightAudit","c")'
                ),
                array('allow', // r
                    'actions' => array('index', 'view'),
                    'expression' => 'app()->controller->isValidAccess("NightAudit","r")'
                ),
                array('allow', // u
                    'actions' => array( 'update'),
                    'expression' => 'app()->controller->isValidAccess("NightAudit","u")'
                ),
                array('allow', // d
                    'actions' => array('delete'),
                    'expression' => 'app()->controller->isValidAccess("NightAudit","d")'
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
		$model=new NaTrans;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NaTrans']))
		{
			$model->attributes=$_POST['NaTrans'];
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

		if(isset($_POST['NaTrans']))
		{
			$model->attributes=$_POST['NaTrans'];
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
                $model=new NaTrans('search');
                $model->unsetAttributes();  // clear any default values

                if(isset($_GET['NaTrans']))
		{
                        $model->attributes=$_GET['NaTrans'];	
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
		$model=new NaTrans('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['NaTrans']))
			$model->attributes=$_GET['NaTrans'];

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
		$model=NaTrans::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='na-trans-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
}
