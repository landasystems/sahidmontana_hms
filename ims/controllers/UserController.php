<?php

class UserController extends Controller {

    public $breadcrumbs;

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';

//    public function filters() {
//        return array(
//            'accessControl', // perform access control for CRUD operations
//        );
//    }

    public function accessRules() {
//        return array(
//            array('allow', // c
//                'actions' => array('create'),
//                'expression' => 'app()->controller->isValidAccess("User","r") || app()->controller->isValidAccess("CLient","r") ||
//                    app()->controller->isValidAccess("CLient","r") || app()->controller->isValidAccess("Guest","r") ||
//                    app()->controller->isValidAccess("Supplier","r") || app()->controller->isValidAccess("Employment","r") ||
//                    app()->controller->isValidAccess("Contact","r") || app()->controller->isValidAccess("Customer","r")',
//            ),
//            array('allow', // r
//                'actions' => array('index', 'view'),
//                'expression' => 'app()->controller->isValidAccess("User","r") || app()->controller->isValidAccess("CLient","r") ||
//                    app()->controller->isValidAccess("CLient","r") || app()->controller->isValidAccess("Guest","r") ||
//                    app()->controller->isValidAccess("Supplier","r") || app()->controller->isValidAccess("Employment","r") ||
//                    app()->controller->isValidAccess("Contact","r") || app()->controller->isValidAccess("Customer","r")',
//            ),
//            array('allow', // u
//                'actions' => array('update'),
//                'expression' => 'app()->controller->isValidAccess("User","r") || app()->controller->isValidAccess("CLient","r") ||
//                    app()->controller->isValidAccess("CLient","r") || app()->controller->isValidAccess("Guest","r") ||
//                    app()->controller->isValidAccess("Supplier","r") || app()->controller->isValidAccess("Employment","r") ||
//                    app()->controller->isValidAccess("Contact","r") || app()->controller->isValidAccess("Customer","r")',
//            ),
//            array('allow', // d
//                'actions' => array('delete'),
//                'expression' => 'app()->controller->isValidAccess("User","r") || app()->controller->isValidAccess("CLient","r") ||
//                    app()->controller->isValidAccess("CLient","r") || app()->controller->isValidAccess("Guest","r") ||
//                    app()->controller->isValidAccess("Supplier","r") || app()->controller->isValidAccess("Employment","r") ||
//                    app()->controller->isValidAccess("Contact","r") || app()->controller->isValidAccess("Customer","r")',
//            )
//        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $type = 'user';
        if (!empty($_GET['type']))
            $type = $_GET['type'];
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'type' => $type,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionAllowLogin() {
//        logs($_POST);
        if (!empty($_POST['User']['roles_id'])) {
            $listRoles = Roles::model()->listRoles();
            if (isset($listRoles[$_POST['User']['roles_id']]))
                echo $listRoles[$_POST['User']['roles_id']]['is_allow_login'];
            elseif ($_POST['User']['roles_id'] == -1)
                echo '-1';
            else
                echo '0';
        }
    }

    public function actionRemovephoto($id) {
        User::model()->updateByPk($id, array('avatar_img' => NULL));
    }

    public function actionCreate() {
        $model = new User;
        $listRoles = Roles::model()->listRoles();
        cs()->registerScript('tab', '$("#myTab a").click(function(e) {
                                        e.preventDefault();
                                        $(this).tab("show");
                                    })');

        $type = 'user';
        $model->scenario = 'allow';
        if (!empty($_GET['type']))
            $type = $_GET['type'];
        if ($type != 'user')
            $model->scenario = 'notAllow';

        if (isset($_POST['User'])) {
            if (!empty($_POST['User']['roles'])) {
                $model->scenario = 'allow';
                if (isset($listRoles[$_POST['User']['roles']])) {
                    if ($listRoles[$_POST['User']['roles']]['is_allow_login'] == '0')
                        $model->scenario = 'notAllow';
                }
            }

            $model->attributes = $_POST['User'];
            $model->password = sha1($model->password);

            $file = CUploadedFile::getInstance($model, 'avatar_img');
            if (is_object($file)) {
                $model->avatar_img = Yii::app()->landa->urlParsing($model->name) . '.' . $file->extensionName;
            } else {
                unset($model->avatar_img);
            }

            if ($model->save()) {

                //create image if any file
                if (is_object($file)) {
                    $file->saveAs('images/avatar/' . $model->avatar_img);
                    Yii::app()->landa->createImg('avatar/', $model->avatar_img, $model->id);
                }

                //assign a roles
//                $model->assignRoles($model->roles_id, $model->id);
                unset(Yii::app()->session['listUser']);

                $this->redirect(array('view', 'id' => $model->id, 'type' => $type));
            }
        }


        $this->render('create', array(
            'model' => $model,
            'type' => $type,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $listRoles = Roles::model()->listRoles();
        $model = $this->loadModel($id);
        $type = 'user';
        $model->scenario == 'allow';
        if (!empty($_GET['type']))
            $type = $_GET['type'];
        if ($type != 'user')
            $model->scenario == 'notAllow';

        $tempRoles = $model->roles_id;
        $tempPass = $model->password;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        cs()->registerScript('tab', '$("#myTab a").click(function(e) {
                                        e.preventDefault();
                                        $(this).tab("show");
                                    })');

        if (isset($listRoles[$model->roles_id])) {
            if ($listRoles[$model->roles_id]['is_allow_login'] == '0')
                $model->scenario = 'notAllow';
            else
                $model->scenario = 'allow';
        }


        if (isset($_POST['User'])) {
            if (!empty($_POST['User']['roles_id'])) {
                $model->scenario = 'allow';
                if (isset($listRoles[$_POST['User']['roles_id']])) {
                    if ($listRoles[$_POST['User']['roles_id']]['is_allow_login'] == '0')
                        $model->scenario = 'notAllow';
                }
            }

            $model->attributes = $_POST['User'];

            if (!empty($model->password)) { //not empty, change the password
                $model->password = sha1($model->password);
            } else {
                $model->password = $tempPass;
            }

            $file = CUploadedFile::getInstance($model, 'avatar_img');
            if (is_object($file)) {
                $model->avatar_img = Yii::app()->landa->urlParsing($model->name) . '.' . $file->extensionName;
                $file->saveAs('images/avatar/' . $model->avatar_img);
                Yii::app()->landa->createImg('avatar/', $model->avatar_img, $model->id);
            }

            if ($model->save()) {
                //check if any change in roles, revoke then assign new role
//                if ($tempRoles != $model->roles) {
//                    $model->revokeRoles($tempRoles, $model->id);
//                    $model->assignRoles($model->roles, $model->id);
//                }
                //clear session user
                unset(Yii::app()->session['listUser']);
                unset(Yii::app()->session['listUserPhone']);

                $this->redirect(array('view', 'id' => $model->id, 'type' => $type));
            }
        }
        unset($model->password);
        if ($type != 'user')
            $model->scenario == 'allow';

        $this->render('update', array(
            'model' => $model,
            'type' => $type,
        ));
    }

    public function actionUpdateProfile() {
        $_GET['id'] = user()->id;
        $id = user()->id;

        $listRoles = Roles::model()->listRoles();
        $model = $this->loadModel($id);
        $type = 'user';
        $model->scenario == 'allow';
        if (!empty($_GET['type']))
            $type = $_GET['type'];
        if ($type != 'user')
            $model->scenario == 'notAllow';

        $tempRoles = $model->roles_id;
        $tempPass = $model->password;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        cs()->registerScript('tab', '$("#myTab a").click(function(e) {
                                        e.preventDefault();
                                        $(this).tab("show");
                                    })');

        if (isset($listRoles[$model->roles_id])) {
            if ($listRoles[$model->roles_id]['is_allow_login'] == '0')
                $model->scenario = 'notAllow';
            else
                $model->scenario = 'allow';
        }


        if (isset($_POST['User'])) {
            if (!empty($_POST['User']['roles_id'])) {
                $model->scenario = 'allow';
                if (isset($listRoles[$_POST['User']['roles_id']])) {
                    if ($listRoles[$_POST['User']['roles_id']]['is_allow_login'] == '0')
                        $model->scenario = 'notAllow';
                }
            }

            $model->attributes = $_POST['User'];

            if (!empty($model->password)) { //not empty, change the password
                $model->password = sha1($model->password);
            } else {
                $model->password = $tempPass;
            }

            $file = CUploadedFile::getInstance($model, 'avatar_img');
            if (is_object($file)) {
                $model->avatar_img = Yii::app()->landa->urlParsing($model->name) . '.' . $file->extensionName;
                $file->saveAs('images/avatar/' . $model->avatar_img);
                Yii::app()->landa->createImg('avatar/', $model->avatar_img, $model->id);
            }

            if ($model->save()) {
                //check if any change in roles, revoke then assign new role
//                if ($tempRoles != $model->roles) {
//                    $model->revokeRoles($tempRoles, $model->id);
//                    $model->assignRoles($model->roles, $model->id);
//                }
                //clear session user
                unset(Yii::app()->session['listUser']);
                unset(Yii::app()->session['listUserPhone']);

                $this->redirect(array('view', 'id' => $model->id, 'type' => $type));
            }
        }
        unset($model->password);
        if ($type != 'user')
            $model->scenario == 'allow';

        $this->render('update', array(
            'model' => $model,
            'type' => $type,
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
        $session = new CHttpSession;
        $session->open();
        $criteria = new CDbCriteria();

        $model = new User('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];

            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->username))
                $criteria->addCondition('username = "' . $model->username . '"');


            if (!empty($model->email))
                $criteria->addCondition('email = "' . $model->email . '"');


            if (!empty($model->password))
                $criteria->addCondition('password = "' . $model->password . '"');


            if (!empty($model->employeenum))
                $criteria->addCondition('employeenum = "' . $model->employeenum . '"');


            if (!empty($model->name))
                $criteria->addCondition('name = "' . $model->name . '"');


            if (!empty($model->city_id))
                $criteria->addCondition('city_id = "' . $model->city_id . '"');


            if (!empty($model->address))
                $criteria->addCondition('address = "' . $model->address . '"');


            if (!empty($model->phone))
                $criteria->addCondition('phone = "' . $model->phone . '"');


            if (!empty($model->created))
                $criteria->addCondition('created = "' . $model->created . '"');

            if (!empty($model->roles_id)) {
//                $criteria->alias = "u";
                $criteria->addCondition('roles_id = "' . $model->roles_id . '"');
            }

            if (!empty($model->created_user_id))
                $criteria->addCondition('created_user_id = "' . $model->created_user_id . '"');


            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');
        }
        $session['User_records'] = User::model()->findAll($criteria);


        $this->render('index', array(
            'model' => $model,
            'type' => 'user'
        ));
    }

    public function actionUser() {
        $session = new CHttpSession;
        $session->open();
        $criteria = new CDbCriteria();

        $model = new User('search');
        $model->unsetAttributes();  // clear any default values        
        $this->render('index', array(
            'model' => $model,
            'type' => 'user'
        ));
    }

    public function actionCustomer() {
        $session = new CHttpSession;
        $session->open();
        $criteria = new CDbCriteria();

        $model = new User('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];



            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->username))
                $criteria->addCondition('username = "' . $model->username . '"');


            if (!empty($model->email))
                $criteria->addCondition('email = "' . $model->email . '"');


            if (!empty($model->password))
                $criteria->addCondition('password = "' . $model->password . '"');




            if (!empty($model->employeenum))
                $criteria->addCondition('employeenum = "' . $model->employeenum . '"');


            if (!empty($model->name))
                $criteria->addCondition('name = "' . $model->name . '"');


            if (!empty($model->city_id))
                $criteria->addCondition('city_id = "' . $model->city_id . '"');


            if (!empty($model->address))
                $criteria->addCondition('address = "' . $model->address . '"');


            if (!empty($model->phone))
                $criteria->addCondition('phone = "' . $model->phone . '"');


            if (!empty($model->created))
                $criteria->addCondition('created = "' . $model->created . '"');


            if (!empty($model->created_user_id))
                $criteria->addCondition('created_user_id = "' . $model->created_user_id . '"');


            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');
        }
        $session['User_records'] = User::model()->findAll($criteria);


        $this->render('index', array(
            'model' => $model,
            'type' => 'customer'
        ));
    }

    public function actionSupplier() {
        $session = new CHttpSession;
        $session->open();
        $criteria = new CDbCriteria();

        $model = new User('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];

            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->username))
                $criteria->addCondition('username = "' . $model->username . '"');


            if (!empty($model->email))
                $criteria->addCondition('email = "' . $model->email . '"');


            if (!empty($model->password))
                $criteria->addCondition('password = "' . $model->password . '"');

            if (!empty($model->employeenum))
                $criteria->addCondition('employeenum = "' . $model->employeenum . '"');


            if (!empty($model->name))
                $criteria->addCondition('name = "' . $model->name . '"');


            if (!empty($model->city_id))
                $criteria->addCondition('city_id = "' . $model->city_id . '"');


            if (!empty($model->address))
                $criteria->addCondition('address = "' . $model->address . '"');


            if (!empty($model->phone))
                $criteria->addCondition('phone = "' . $model->phone . '"');


            if (!empty($model->created))
                $criteria->addCondition('created = "' . $model->created . '"');


            if (!empty($model->created_user_id))
                $criteria->addCondition('created_user_id = "' . $model->created_user_id . '"');


            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');
        }
        $session['User_records'] = User::model()->findAll($criteria);


        $this->render('index', array(
            'model' => $model,
            'type' => 'supplier'
        ));
    }

    public function actionEmployment() {
        $session = new CHttpSession;
        $session->open();
        $criteria = new CDbCriteria();

        $model = new User('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];



            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->username))
                $criteria->addCondition('username = "' . $model->username . '"');


            if (!empty($model->email))
                $criteria->addCondition('email = "' . $model->email . '"');


            if (!empty($model->password))
                $criteria->addCondition('password = "' . $model->password . '"');




            if (!empty($model->employeenum))
                $criteria->addCondition('employeenum = "' . $model->employeenum . '"');


            if (!empty($model->name))
                $criteria->addCondition('name = "' . $model->name . '"');


            if (!empty($model->city_id))
                $criteria->addCondition('city_id = "' . $model->city_id . '"');


            if (!empty($model->address))
                $criteria->addCondition('address = "' . $model->address . '"');


            if (!empty($model->phone))
                $criteria->addCondition('phone = "' . $model->phone . '"');


            if (!empty($model->created))
                $criteria->addCondition('created = "' . $model->created . '"');


            if (!empty($model->created_user_id))
                $criteria->addCondition('created_user_id = "' . $model->created_user_id . '"');


            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');
        }
        $session['User_records'] = User::model()->findAll($criteria);


        $this->render('index', array(
            'model' => $model,
            'type' => 'employment'
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

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
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'User-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGenerateExcel() {
        $model = User::model()->findAll(array());
        Yii::app()->request->sendFile('List user.xls', $this->renderPartial('excelReport', array(
                    'model' => $model
                        ), true)
        );
    }

    public function actionSearchJson() {
        $user = User::model()->findAll(array('condition' => 'name like "%' . $_POST['queryString'] . '%" OR phone like "%' . $_POST['queryString'] . '%"', 'limit' => 7));
        $results = array();
        foreach ($user as $no => $o) {
            $results[$no]['url'] = url('user/' . $o->id);
            $results[$no]['img'] = $o->imgUrl['small'];
            $results[$no]['title'] = $o->name;
            $results[$no]['description'] = $o->Roles->name . '<br/>' . landa()->hp($o->phone) . '<br/>' . $o->address;
        }
        echo json_encode($results);
    }

    public function actionAuditUser() {
        $this->layout = 'main';

        $this->render('auditUser', array(
//            'oUserLogs' => $oUserLogs,
//            'listUser' => $listUser,
        ));
    }

    public function actionUserInvoice() {
        $header = User::model()->findAll(array(
            'with' => 'Roles',
            'order' => 't.name',
            'condition' => 'Roles.type ="' . $_GET['type'] . '"'
        ));
        $alert = false;
        if (isset($_POST['code'])) {
//            logs($_POST['code']);
            for ($i = 0; $i < count($_POST['code']); $i++) {
                if (empty($_POST['id'][$i])) {
                    $payment = new InvoiceDet();
                } else {
                    $payment = InvoiceDet::model()->findByPk($_POST['id'][$i]);
                }
                $payment->code = $_POST['code'][$i];
                $payment->description = $_POST['description'][$i];
                $payment->user_id = $_POST['user_id'][$i];
                $payment->payment = $_POST['payment'][$i];
                $payment->type = $_GET['type'];
                $payment->term_date = date('Y-m-d', strtotime($_POST['term_date'][$i]));
//                $payment->save();
                if ($payment->save()) {
                    if (empty($_POST['id_coaDet'][$i])) {
                        $coaDet = new AccCoaDet();
                    } else {
                        $coaDet = AccCoaDet::model()->findByPk($_POST['id_coaDet'][$i]);
                    }
                    $coaDet->reff_type = "invoice";
                    if ($payment->is_new_invoice == 1) {
                        $coaDet->credit = 0;
                        $coaDet->debet = 0;
                    }else{
                        if ($_GET['type'] == "customer") {
                            $coaDet->debet = $_POST['payment'][$i];
                        } else {
                            $coaDet->credit = $_POST['payment'][$i];
                        }
                    }
                    $coaDet->invoice_det_id = $payment->id;
                    $coaDet->date_coa = date('Y-m-d', strtotime($_POST['date_coa'][$i]));
                    $coaDet->save();
//                    logs($coaDet->getErrors());
                }
//                logs($payment->getErrors());
                $alert = true;
            }
        }
        $this->render('userInvoice', array(
            'header' => $header,
            'alert' => $alert
        ));
    }

    public function actionAddRow() {
        if (!empty($_POST['code'])) {
            echo '<tr>'
            . '<td>'
            . '<input type="text" class="code span1" name="code[]" value="' . $_POST['code'] . '">'
            . '</td>'
            . '<td>'
            . '<input type="text" readonly="readonly" class="dateStart" style="width:95%" name="date_coa[]" value="' . $_POST['date_coa'] . '">'
            . '</td>'
            . '<td>'
            . '<input type="text" readonly="readonly" class="term" style="width:95%" name="term_date[]" value="' . $_POST['terms'] . '">'
            . '</td>'
            . '<td>'
            . '<input type="text" class=" span4" name="description[]" value="' . $_POST['desc'] . '">'
            . '</td>'
            . '<td style="text-align:center">
                <div class="input-prepend">
                    <span class="add-on">Rp.</span>
                    <input type="text" class="angka charge nilai" name="payment[]" value="' . $_POST['payment'] . '">
                </div>
            </td>'
            . '<td>'
            . '<span style="width:12px" class="btn delInv"><i class="cut-icon-trashcan"></i></span>'
            . '<input type="hidden" class="user" name="user_id[]" value="' . $_POST['sup_id'] . '">'
            . '<input type="hidden" class="id_invoice" name="id[]" value="">'
            . '<input type="hidden" class="id_coaDet" name="id_coaDet[]" value="">'
            . '</td>'
            . '</tr>'
            . '<tr class="addRows" style="display:none;">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>';
        }
    }

    public function actionInvoiceDetail() {
        $userInvoice = AccCoaDet::model()->findAll(array(
            'with' => array('InvoiceDet'),
            'condition' => 'InvoiceDet.user_id=' . $_POST['id'] . ' AND reff_type="invoice"'
        ));
        $ambil = ($_POST['id'] == 0) ? false : true;
        $balance = InvoiceDet::model()->findAllByAttributes(array('user_id' => $_POST['id']));
        echo $this->renderPartial('_userInvoice', array(
            'userInvoice' => $userInvoice,
            'ambil' => $ambil,
            'alert' => false,
            'balance' => $balance
                ), true);
    }

}
