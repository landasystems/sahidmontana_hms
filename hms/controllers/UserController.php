<?php

class UserController extends Controller {

    public $breadcrumbs;

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
                'expression' => 'app()->controller->isValidAccess("User","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("User","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("User","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("User","d")'
            )
        );
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

    public function actionGetBillUser() {
        $name = $_GET["q"];
        $siteConfig = SiteConfig::model()->listSiteConfig();
        $sCriteria = json_decode($siteConfig->roles_guest, true);
        $data = array();
        if (!empty($sCriteria)) {
            $list = '';
            foreach ($sCriteria as $o) {
                $list .= '"' . $o . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = User::model()->findAll(array('condition' => 'name like "%' . $name . '%" and roles_id in(' . $list . ')'));
            if (empty($sResult)) {
                $data[] = array("id" => "0", "text" => "No Results Found..");
            } else {
                foreach ($sResult as $val) {
                    $data[] = array('id' => $val->id, 'text' => '[' . $val->Roles->name . '] ' . $val->name);
                }
            }
        } else {
            $data[] = array("id" => "0", "text" => "No Results Found..");
        }
        echo json_encode($data);
    }

    public function actionGetListUser() {
        $name = $_GET["q"];
        $list = array();
        $data = User::model()->findAll(array('condition' => 'name like "%' . $name . '%"'));
        if (empty($data)) {
            $list[] = array("id" => "0", "text" => "No Results Found..");
        } else {
            foreach ($data as $val) {
                $list[] = array("id" => $val->id, "text" => '[' . $val->Roles->name . '] - ' . $val->name);
            }
        }
        echo json_encode($list);
    }

    public function actionGetListGuestLedger() {
        $name = $_GET["q"];
        $list = array();
        $query = 'select acca_user.name as name, acca_room_bill.id as id, acca_room_bill.room_number as room_number from acca_user, acca_registration, acca_room_bill where acca_user.id = acca_registration.guest_user_id and acca_registration.id = acca_room_bill.registration_id and acca_room_bill.is_checkedout=0 and acca_room_bill.lead_room_bill_id=0 and acca_user.name like "%' . $name . '%" or acca_room_bill.room_number = "' . $name . '" order by acca_room_bill.room_number ASC';
        $data =  Yii::app()->db->createCommand($query)->queryAll();
        //$data = RoomBill::model()->with('Registration')->with('User')->findAll(array('condition' => 'User.name like "%' . $name . '%" User.id = Registration.guest_user_id and t.is_checkedout=0 and t.lead_room_bill_id=0', "order" => "t.room_number Asc"));
        if (empty($data)) {
            $list[] = array("id" => "0", "text" => "No Results Found..");
        } else {
            foreach ($data as $val) {
                $list[] = array("id" => $val['id'], "text" => '[' . $val['room_number'] . '] - ' . $val['name']);
            }
        }
        echo json_encode($list);
    }
    
//    public function actionGetListGlMove() {
//        $name = $_GET["q"];
//        $list = array();
//        $query = 'select acca_user.name as name, acca_room_bill.room_id as id, acca_room_bill.room_number as room_number from acca_user, acca_registration, acca_room_bill where acca_user.id = acca_registration.guest_user_id and acca_registration.id = acca_room_bill.registration_id and acca_room_bill.is_checkedout=0 and acca_room_bill.lead_room_bill_id=0 and acca_user.name like "%' . $name . '%" or acca_room_bill.room_number = "' . $name . '" order by acca_room_bill.room_number ASC limit 10';
//        $data =  Yii::app()->db->createCommand($query)->queryAll();
//        //$data = RoomBill::model()->with('Registration')->with('User')->findAll(array('condition' => 'User.name like "%' . $name . '%" User.id = Registration.guest_user_id and t.is_checkedout=0 and t.lead_room_bill_id=0', "order" => "t.room_number Asc"));
//        if (empty($data)) {
//            $list[] = array("id" => "0", "text" => "No Results Found..");
//        } else {
//            foreach ($data as $val) {
//                $list[] = array("id" => $val['id'], "text" => '[' . $val['room_number'] . '] - ' . $val['name']);
//            }
//        }
//        echo json_encode($list);
//    }

    /* public function actionMigrasiCompany() {
      $guest = User::model()->listUsers('guest');
      foreach ($guest as $val) {
      $other = json_decode($val->others, true);
      if (isset($other['company'])) {
      $company = $other['company'];
      $update = User::model()->findByPk($val->id);
      $update->company = $company;
      $update->save();
      }
      }
      $this->render(url('dashboard'));
      } */

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

    public function actionHistory() {
        $this->render('history', array(
        ));
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
            $model->attributes = $_POST['User'];
            if ($type != 'user') {
                $model->username = '';
                $model->password = '';
            }
            $model->birth = (!empty($_POST['User']['birth'])) ? date('Y/m/d', strtotime($_POST['User']['birth'])) : '';
            $model->password = sha1($model->password);

            //$company = (!empty($_POST['company'])) ? $_POST['company'] : '';
            //$other = json_encode(array('company' => $company));
            //$model->others = $other;
            $model->company = $_POST['User']['company'];

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
            $model->birth = (!empty($_POST['User']['birth'])) ? date('Y/m/d', strtotime($_POST['User']['birth'])) : '';
            $company = (!empty($_POST['company'])) ? $_POST['company'] : '';
            //$other = json_encode(array('company' => $company));
            //$model->others = $other;
            $model->company = $company;

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
        $id = user()->id;
        $listRoles = Roles::model()->listRoles();
        $model = $this->loadModel($id);
        $_GET['id'] = user()->id;
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
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        unset($model->password);
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

    public function actionGetDetail() {
        //  $id = $_POST['id'];
        //  $user = User::model()->findByPk($id);
        $id = $_POST['id'];
        $user = User::model()->find(array('condition' => 'id="' . $id . '"'));
        if (isset($user) and ! empty($user)) {
            $return['id'] = $user->id;
            $return['group'] = $user->roles_id;
            $return['name'] = $user->name;
            $return['email'] = $user->email;
            $return['city'] = $user->City->id;
            $return['province'] = $user->City->Province->id;
            $return['address'] = $user->address;
            $return['phone'] = $user->phone;
            $return['number'] = $user->code;
            $return['sex'] = $user->sex;
            $return['birth'] = $user->birth;
            $return['nationality'] = $user->nationality;
            $return['company'] = $user->company;
            echo json_encode($return);
        } else {
            $return['id'] = '';
            $return['group'] = '';
            $return['name'] = '';
            $return['email'] = '';
            $return['city'] = '';
            $return['province'] = '';
            $return['address'] = '';
            $return['phone'] = '';
            $return['number'] = '';
            $return['sex'] = '';
            $return['birth'] = '';
            $return['nationality'] = '';
            $return['company'] = '';
            echo json_encode($return);
        }
    }

    public function actionOption() {
        if (isset($_POST['delete']) && isset($_POST['ceckbox'])) {
            foreach ($_POST['ceckbox'] as $data) {
                $this->loadModel($data)->delete();
            }
        }
        if (isset($_POST['disabled']) && isset($_POST['ceckbox'])) {
            foreach ($_POST['ceckbox'] as $data) {
                $model = $this->loadModel($data);
                $model->enabled = 0;
                $model->save();
            }
        }
        if (isset($_POST['enabled']) && isset($_POST['ceckbox'])) {
            foreach ($_POST['ceckbox'] as $data) {
                $model = $this->loadModel($data);
                $model->enabled = 1;
                $model->save();
            }
        }
        $this->redirect(array('user/index'));
    }

    public function actionIndex() {

        $criteria = new CDbCriteria();

        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        $roles = "";
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
            $roles = (isset($_GET['User']['roles'])) ? $_GET['User']['roles'] : '';

            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->username))
                $criteria->addCondition('username = "' . $model->username . '"');


            if (!empty($model->email))
                $criteria->addCondition('email = "' . $model->email . '"');


            if (!empty($model->password))
                $criteria->addCondition('password = "' . $model->password . '"');


            if (!empty($model->code))
                $criteria->addCondition('code = "' . $model->code . '"');


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

            if (!empty($model->roles))
                $criteria->addCondition('roles = "' . $model->roles . '"');

            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');
        }



        $this->render('index', array(
            'model' => $model,
            'type' => 'user',
            'roles' => $roles,
        ));
    }

    public function actionUser() {
        $criteria = new CDbCriteria();

        $model = new User('search');
        $model->unsetAttributes();  // clear any default values   

        $roles = "";
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
            $roles = (isset($_GET['User']['roles'])) ? $_GET['User']['roles'] : '';

            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->username))
                $criteria->addCondition('username = "' . $model->username . '"');


            if (!empty($model->email))
                $criteria->addCondition('email = "' . $model->email . '"');


            if (!empty($model->password))
                $criteria->addCondition('password = "' . $model->password . '"');


            if (!empty($model->code))
                $criteria->addCondition('code = "' . $model->code . '"');


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

            if (!empty($model->roles))
                $criteria->addCondition('roles = "' . $model->roles . '"');

            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');
        }

        $this->render('index', array(
            'model' => $model,
            'type' => 'user',
            'roles' => $roles,
        ));
    }

    public function actionGuest() {
        $criteria = new CDbCriteria();

        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        $roles = "";
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
            $roles = (isset($_GET['User']['roles'])) ? $_GET['User']['roles'] : '';

            if (!empty($model->username))
                $criteria->addCondition('username = "' . $model->username . '"');


            if (!empty($model->code))
                $criteria->addCondition('code = "' . $model->code . '"');


            if (!empty($model->name))
                $criteria->addCondition('name = "' . $model->name . '"');


            if (!empty($model->city_id))
                $criteria->addCondition('city_id = "' . $model->city_id . '"');


            if (!empty($model->address))
                $criteria->addCondition('address = "' . $model->address . '"');


            if (!empty($model->phone))
                $criteria->addCondition('phone = "' . $model->phone . '"');
        }

        $this->render('index', array(
            'model' => $model,
            'type' => 'guest',
            'roles' => $roles,
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

    public function actionCheckAuthority() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = User::model()->findByAttributes(array('username' => $username, 'password' => sha1($password)));
        $return = array();
        if (empty($user)) {
            $return['message'] = 'Username and password do not match';
            $return['user_id'] = '';
            echo json_encode($return);
        } else {
            $authorized = landa()->checkAccess('RoomType', 'u', $user->roles_id);
            if ($authorized == true) {
                $return['message'] = '';
                $return['user_id'] = $user->id;
            } else {
                $return['message'] = 'User does not have the authority';
                $return['user_id'] = '';
            }
            echo json_encode($return);
        }
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

}
