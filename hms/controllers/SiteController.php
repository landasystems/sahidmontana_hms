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
                'actions' => array('error', 'login', 'logout', 'icons', 'saveChargeAdditional', 'setup', 'saveChargeAdditionalCategory', 'delChargeAdditionalCategory', 'delChargeAdditional'),
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
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
//        Yii::import("xupload.models.XUploadForm");
//        $model = new XUploadForm;
//        $this -> render('index', array('model' => $model, ));

        $this->layout = 'main';
        $this->render('index');
    }

    public function actionIcons() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
//        Yii::import("xupload.models.XUploadForm");
//        $model = new XUploadForm;
//        $this -> render('index', array('model' => $model, ));
        $this->layout = 'main';
        $this->render('themes/icons');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        $this->layout = 'blankHeader';
        cs()->registerScript('error', '
                $(".errorContainer").hide();
                $(".errorContainer").fadeIn(1000).animate({
                    "top": "50%", "margin-top": +($(".errorContainer").height() / -2 - 30)
                }, {duration: 750, queue: false}, function () {
                    // Animation complete.
                });
            ');
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
    public function actionSaveChargeAdditional() {
        $chargeAdditional = new ChargeAdditional;
        $chargeAdditional->charge_additional_category_id = $_POST['additionalDepartment'];
        $chargeAdditional->account_id = $_POST['additionalAccount'];
        $chargeAdditional->type_transaction = $_POST['additionalTypeTransaction'];
        $chargeAdditional->charge = $_POST['additionalCharge'];
        $chargeAdditional->name = $_POST['additionalName'];
        $chargeAdditional->description = $_POST['additionalDescription'];
        $chargeAdditional->discount = $_POST['additionalDiscount'];
        if ($chargeAdditional->save()) {
            $data['ket'] = 'success';
        } else {
            $data['ket'] = 'error';
        }

        //select2
        $data['additional'] = '';
        $select2 = !empty(ChargeAdditionalCategory::model()->findAll()) ? RoomBill::model()->getAdditional() : array(0 => 'please insert charge additional before');
        foreach ($select2 as $key => $val) {
            $data['additional'] .= '<option value="' . $key . '">' . $val . '</option>';
        }

        //table
        $list = '';
        $charge = ChargeAdditional::model()->findAll();
        foreach ($charge as $value) {
            $list .= '<tr id="charge' . $value->id . '" class="' . $value->id . '">
                        <td>' . $value->name . '</td>
                        <td>' . $value->ChargeAdditionalCategory->name . '</td>
                        <td>' . landa()->rp($value->charge) . '</td>
                        <td>' . $value->discount . '%</td>
                        <td>' . landa()->rp($value->charge - (($value->charge * $value->discount) / 100)) . '</td>
                        <td align="center"><a href="#" class="btn" onclick="delRowCharge(' . $value->id . ')"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></a></td>
                      </tr>';
        }
        $data['list'] = '<table class="table table-bordered" id="listCharge">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Charge</th>
                                    <th>Discount</th>
                                    <th>Total Charge</th>
                                    <th width="50">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . $list . '
                            </tbody>
                        </table>';
        echo json_encode($data);
    }

    public function actionSaveChargeAdditionalCategory() {
        $model = new ChargeAdditionalCategory;
        if ($_POST['additionalCategoriParent']) {
            $child = new ChargeAdditionalCategory;
            $child->code = $_POST['additionalCategoriCode'];
            $child->name = $_POST['additionalCategoriName'];
            $root = ChargeAdditionalCategory::model()->findByPk($_POST['additionalCategoriParent']);
            if ($child->appendTo($root)) {
                $data['ket'] = 'success';
            } else {
                $data['ket'] = 'error';
            }
        } else {
            $model->code = $_POST['additionalCategoriCode'];
            $model->name = $_POST['additionalCategoriName'];
            if ($model->saveNode()) {
                $data['ket'] = 'success';
            } else {
                $data['ket'] = 'error';
            }
        }

        //combobox
        $parent = ChargeAdditionalCategory::model()->findAll();
        $data['parent'] = '<option value>root</option>';
        foreach ($parent as $val) {
            $data['parent'] .= '<option value="' . $val->id . '">' . $val->name . '</option>';
        }

        //select2
        $data['additional'] = '';
        $select2 = !empty(ChargeAdditionalCategory::model()->findAll()) ? RoomBill::model()->getAdditional() : array(0 => 'please insert charge additional before');
        foreach ($select2 as $key => $val) {
            $data['additional'] .= '<option value="' . $key . '">' . $val . '</option>';
        }

        //table
        $departement = ChargeAdditionalCategory::model()->findAll();
        $data['list'] = '<div id="">
                            <table class="table table-bordered" id="listDepartement">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Department</th>
                                    <th width="50">#</th>
                                </tr>
                            </thead>
                            <tbody>';
        foreach ($departement as $val) {
            $data['list'] .= '<tr id="' . $val->id . '" class="' . $val->id . '">
                                <td>' . $val->code . '</td>
                                <td>' . $val->name . '</td>
                                <td align="center"><a href="#" class="btn" onclick="delRow(' . $val->id . ')"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></a></td>
                              </tr>
                            ';
        }
        $data['list'] .= '</body>
                          </table>
                          </div>';
        echo json_encode($data);
    }

    public function actionDelChargeAdditionalCategory() {
        $id = $_POST['id'];
        $model = ChargeAdditionalCategory::model()->findByPk($id);
        $descendants = $model->children()->findAll();
        $sWhere[] = $id;
        foreach ($descendants as $o) {
            $sWhere[] = $o->id;
        }
        ChargeAdditional::model()->deleteAll(array('condition' => 'charge_additional_category_id IN (' . implode(',', $sWhere) . ')'));
        $model->deleteNode();

        //combobox
        $parent = ChargeAdditionalCategory::model()->findAll();
        $data['parent'] = '<option>Root</option>';
        foreach ($parent as $val) {
            $data['parent'] .= '<option value="' . $val->id . '">' . $val->name . '</option>';
        }

        //select2
        $data['additional'] = '';
        $select2 = !empty(ChargeAdditionalCategory::model()->findAll()) ? RoomBill::model()->getAdditional() : array(0 => 'please insert charge additional before');
        foreach ($select2 as $key => $val) {
            $data['additional'] .= '<option value="' . $key . '">' . $val . '</option>';
        }

        echo json_encode($data);
    }

    public function actionDelChargeAdditional() {
        $id = $_POST['id'];
        $additional = ChargeAdditional::model()->findByPk($id);
        $additional->delete();

        //select2
        $data['additional'] = '';
        $select2 = !empty(ChargeAdditionalCategory::model()->findAll()) ? RoomBill::model()->getAdditional() : array(0 => 'please insert charge additional before');
        foreach ($select2 as $key => $val) {
            $data['additional'] .= '<option value="' . $key . '">' . $val . '</option>';
        }

        echo json_encode($data);
    }

    public function actionLogin() {
        //disable login page if logged
//        if (isset(user()->id)) {
//            $this->redirect(Yii::app()->user->returnUrl);
//        }
//        $siteConfig = SiteConfig::model()->findByPk(1);
//        if ($siteConfig->is_setup == 0) {
//            $this->redirect('setup');
//        } else {
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
//        }
    }

    public function actionSetup() {
        $siteConfig = SiteConfig::model()->findByPk(1);
        $this->layout = 'blankHeader';
        if ($siteConfig->is_setup == 0) {
            $this->render('setup', array('model' => $siteConfig));
            if (isset($_POST['SiteConfig'])) {
                $model = $siteConfig;
                $model->attributes = $_POST['SiteConfig'];
                $model->others_include = "";
                $model->acc_cash_id = $_POST['SiteConfig']['acc_cash_id'];
                $model->acc_city_ledger_id = $_POST['SiteConfig']['acc_city_ledger_id'];
                $model->acc_service_charge_id = $_POST['SiteConfig']['acc_service_charge_id'];
                $model->acc_tax_id = $_POST['SiteConfig']['acc_tax_id'];
                $model->acc_clearance_id = $_POST['SiteConfig']['acc_clearance_id'];
                $model->is_setup = 1;

                if (isset($_POST['others_include'])) {
                    $model->others_include = json_encode($_POST['others_include']);
                }

                if (!empty($_POST['SiteConfig']['roles_guest'])) {
                    $model->roles_guest = json_encode($_POST['SiteConfig']['roles_guest']);
                }

                $file = CUploadedFile::getInstance($model, 'client_logo');
                if (is_object($file)) {
                    $model->client_logo = Yii::app()->landa->urlParsing($model->client_name) . '.' . $file->extensionName;
                } else {
                    unset($model->client_logo);
                }

                $settings = array();

                $settings['fb_charge'] = $_POST['fnb'];
                $settings['extrabed_charge'] = $_POST['extrabed'];
                $settings['fb_account'] = $_POST['breakfastAccount'];
                $settings['room_account'] = $_POST['roomAccount'];
                $settings['rate'] = $_POST['rate'];

                $model->settings = json_encode($settings);

                if ($model->save()) {
                    if (is_object($file)) {
                        $file->saveAs('images/site/' . $model->client_logo);
                        app()->landa->createImg('site/', $model->client_logo, $model->id, false);
                    }

                    $cekForecast = Forecast::model()->findAll();
                    if (empty($cekForecast)) {
                        $forecast = new Forecast;
                        $forecast->tahun = date("Y", strtotime($_POST['SiteConfig']['date_system']));
                        $forecast->save();
                    }

                    $cekInitialForecast = InitialForecast::model()->findAll();
                    if (empty($cekInitialForecast)) {
                        $initialForecast = new InitialForecast;
                        $initialForecast->id = 1;
                        $initialForecast->save();
                    }

                    $account = Account::model()->findAll();
                    $newForecast = array();
                    foreach ($account as $valAccount) {
                        $departement = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $valAccount->id, 'group' => 'charge_additional_category_id'));
                        $month = array();
                        if (empty($departement)) {
                            for ($i = 1; $i <= 12; $i++) {
                                $newForecast[$valAccount->id][$i] = 0;
                            }
                        } else {
                            foreach ($departement as $valDepartement) {
                                for ($i = 1; $i <= 12; $i++) {
                                    $newForecast[$valAccount->id][$valDepartement->charge_additional_category_id][$i] = 0;
                                }
                            }
                        }
                    }

                    $forecast = Forecast::model()->find(array('condition' => 'tahun = ' . date("Y", strtotime($_POST['SiteConfig']['date_system']))));
                    $forecast->forecast = json_encode($newForecast);
                    $forecast->save();


                    echo '<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">
					document.location.href="' . Yii::app()->baseUrl . '/site/login.html";
                            </SCRIPT>';
                }
            }
            if (!empty($model->roles_guest)) {
                $model->roles_guest = json_decode($model->roles_guest);
            }
        } else {
            $this->redirect(Yii::app()->baseUrl . '/site/login.html');
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        app()->cache->flush();
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->baseUrl . '/site/login.html');
    }

}
