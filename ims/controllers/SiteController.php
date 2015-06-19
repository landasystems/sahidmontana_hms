<?php

class SiteController extends Controller {

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('error', 'login', 'logout', 'icons'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $this->layout = 'main';
        $this->render('index');
    }

    public function actionIcons() {
        $this->layout = 'main';
        $this->render('themes/icons');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        $this->layout = 'blank';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
         cs()->registerScript('login', '
                    $("#LoginForm_username").bind("cut copy paste", function(e) {         
                    e.preventDefault();     
                    });
                    $("#LoginForm_username").keyup(function(){
                        var count = $(this).val().length;
                        var id=$("#LoginForm_username").val();
                       if( count >= 7){
                        $.ajax({
                            url : "' . url('manufacturing/checkUser') . '",
                            data : "id="+id,
                            cache : false,
                            success : function(data){ 
                                if (data=="0"){
                                    $("#info").show();
                                    
                                }else{
                                    obj = JSON.parse(data);                                        
                                    $("#info-name").html(obj["name"]); 
                                    $("#info-img").attr("src",obj["avatar_img"]); 
                                    $("#cont-username").hide(); 
                                    $("#cont-password").show(); 
                                }
                            }
                        });
                        }
                    });
                    ');

        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $userLog = new UserLog;
                $userLog->save();

                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        // display the login form
        $this->layout = 'blankHeader';
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        app()->cache->flush();
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->baseUrl . '/site/login.html');
    }

    public function actionProcess() {
        $this->render('process');
    }
    
}
