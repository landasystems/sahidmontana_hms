<?php

class NaDpController extends Controller
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
                    'actions' => array('create'),
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
		$model=new NaDp;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NaDp']))
		{
			$model->attributes=$_POST['NaDp'];
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

		if(isset($_POST['NaDp']))
		{
			$model->attributes=$_POST['NaDp'];
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

                $model=new NaDp('search');
                $model->unsetAttributes();  // clear any default values

                if(isset($_GET['NaDp']))
		{
                        $model->attributes=$_GET['NaDp'];
			
			
                   	
                       if (!empty($model->id)) $criteria->addCondition('id = "'.$model->id.'"');
                     
                    	
                       if (!empty($model->na_id)) $criteria->addCondition('na_id = "'.$model->na_id.'"');
                     
                    	
                       if (!empty($model->name)) $criteria->addCondition('name = "'.$model->name.'"');
                     
                    	
                       if (!empty($model->by_cash)) $criteria->addCondition('by_cash = "'.$model->by_cash.'"');
                     
                    	
                       if (!empty($model->by_cc)) $criteria->addCondition('by_cc = "'.$model->by_cc.'"');
                     
                    	
                       if (!empty($model->by_bank)) $criteria->addCondition('by_bank = "'.$model->by_bank.'"');
                     
                    	
                       if (!empty($model->by_gl)) $criteria->addCondition('by_gl = "'.$model->by_gl.'"');
                     
                    	
                       if (!empty($model->by_cl)) $criteria->addCondition('by_cl = "'.$model->by_cl.'"');
                     
                    			
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
		$model=new NaDp('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['NaDp']))
			$model->attributes=$_GET['NaDp'];

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
		$model=NaDp::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='na-dp-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
}
