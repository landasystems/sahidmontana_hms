<?php

class RoomChartingController extends Controller {

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
            array('allow', // r
                'actions' => array('index', 'view', 'stay'),
                'expression' => 'app()->controller->isValidAccess("RoomCharting","r")'
            ),
            array('allow', // r
                'actions' => array('houseKeeping'),
                'expression' => 'app()->controller->isValidAccess("HouseKeeping","r")'
            ),
        );
    }

    public function CSS() {
        cs()->registerCss('', '
                #results td a{
                color: #FFFFFF;
                font-size: 9px;
                text-align: center;
                }
                .tombol {                
                    width: 100%;        
                    height: 20px;    
                    overflow: hidden;
                }
                .tombol:hover{
                    background: darkorange;
                    cursor: pointer;
                    cursor: hand;
                } 
               .vacant{
                    background: #468847;
                }
                .vacantinspect{
                    background: #468847;
                }
                .reservation{
                    background: skyblue;
                }
                .reserved{
                    background: #3a87ad;
                }
                .occupied{
                    background: #b94a48;
                }
                .dirty{
                    background: #f89406;
                }
                .houseuse{
                    background: #999999;
                }
                .compliment{
                    background: slategray;
                }
                .outoforder{
                    background: #353535;
                }
                
                .label-vci{
                    background: green;        
                }    
                .label-vc{
                    background: yellowgreen;   
                }
                .label-vd{
                    background: #f89406;
                }
                .label-o{
                    background: salmon;
                }
                .label-onl{
                    background: blueviolet;
                }
                .label-dd{
                    background: darkred;
                }
                .label-so{
                    background: saddlebrown;
                }
                .label-hu{
                    background: darkgray;
                }
                .label-ooo{
                    background: #333333;                    
                }
                .taro a {color:white !important;border-bottom:dashed 1px white !important;}
                .taro {color:white !important;border-bottom:none !important;}
            ');
    }

    public function actionIndex() {
        $this->layout = 'mainWide';
        $this->CSS();
        cs()->registerScript('', '$(".pop").popover();
                $( document ).ready(function() {
                     $.ajax({
                        url: "' . url('roomCharting/dataResult') . '",
                        data: $("form").serialize(),
                        type: "post",
                        success: function (data) {
                            $("#results").html(data);
                            fixed();
                        }
                    });
                });
                 ');
        $this->render('index', array());
    }

    public function actionDataResult() {
        $siteConfig = SiteConfig::model()->findByPk(1);
        $modelRoom = Room::model()->findAll(array('order' => 'number'));
        $arrSchedule = array();
        $month = isset($_POST['month']) ? $_POST['month'] : date("m");
        $year = isset($_POST['year']) ? $_POST['year'] : date("Y");
        $jumHari = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (isset($_POST['page'])) {
            $page = $_POST['page'];
        } else {
            if (strtotime($year . "-" . $month . "-" . date("d")) >= strtotime($year . "-" . $month . "-01") and strtotime($year . "-" . $month . "-" . date("d")) <= strtotime($year . "-" . $month . "-10")) {
                $page = 1;
            } else if (strtotime($year . "-" . $month . "-" . date("d")) >= strtotime($year . "-" . $month . "-11") and strtotime($year . "-" . $month . "-" . date("d")) <= strtotime($year . "-" . $month . "-20")) {
                $page = 2;
            } else if (strtotime($year . "-" . $month . "-" . date("d")) >= strtotime($year . "-" . $month . "-21") and strtotime($year . "-" . $month . "-" . date("d")) <= strtotime($year . "-" . $month . "-" . $jumHari)) {
                $page = 3;
            }
        }

        if (isset($month) && isset($year)) {
            $filter = '';
            $type = isset($_POST['type']) ? $_POST['type'] : '';
            $bed = isset($_POST['bed']) ? $_POST['bed'] : '';
            if (!empty($type))
                $filter.= 'room_type_id=' . $type;
            if (!empty($bed) && !empty($type))
                $filter.= ' and bed="' . $bed . '"';
            if (!empty($bed) && empty($type))
                $filter.= 'bed="' . $bed . '"';

            if ($page == "1") {
                $start = date("Y-m-d", strtotime($year . "-" . $month . "-1"));
                $end = date("Y-m-d", strtotime($year . "-" . $month . "-10"));
                $amountDay = 10;
            } else if ($page == "2") {
                $start = date("Y-m-d", strtotime($year . "-" . $month . "-11"));
                $end = date("Y-m-d", strtotime($year . "-" . $month . "-20"));
                $amountDay = 10;
            } else if ($page == "3") {
                $start = date("Y-m-d", strtotime($year . "-" . $month . "-21"));
                $end = date("Y-m-d", strtotime($year . "-" . $month . "-" . $jumHari));
                $amountDay = $jumHari - 20;
            }
            //$amountDay = landa()->daysInMonth($_POST['month'], $_POST['year']);
            $modelRoom = Room::model()->findAll(array('condition' => $filter, 'order' => ' number'));
            $mSchedule = RoomSchedule::model()->findAll(array('order' => 'id ASC', 'condition' => 'date_schedule >= "' . $start . '" and date_schedule <= "' . $end . '"'));
            foreach ($mSchedule as $o) {
                $arrSchedule[$o->daySchedule][$o->room_id] = $o;
            }
        } else {
            $amountDay = 0;
        }

        echo $this->renderPartial('_results', array(
            'modelRoom' => $modelRoom,
            'amountDay' => $amountDay,
            'mSchedule' => $arrSchedule,
            'siteConfig' => $siteConfig,
            'page' => $page
                ), true);
    }

    public function actionStay() {
        $this->layout = 'mainWide';
        $siteConfig = SiteConfig::model()->findByPk(1);
        $this->CSS();
        if (!empty($_POST)) {
            $user = (!empty($_POST['user'])) ? $_POST['user'] : '';
            $status = (!empty($_POST['status'])) ? $_POST['status'] : '';
            $type = (!empty($_POST['type'])) ? $_POST['type'] : '';
            $bed = (!empty($_POST['bed'])) ? $_POST['bed'] : '';
            $filter = '';
            if (!empty($_POST['status']))
                $filter .= 'status_housekeeping="' . $_POST['status'] . '"';
            if (!empty($_POST['status']) && $_POST['status'] == 'vacant')
                $filter .= ' or status_housekeeping is null';

            if (!empty($_POST['type'])) {
                if (!empty($filter))
                    $filter .= ' and ';
                $filter .= 'room_type_id=' . $_POST['type'];
            }
            if (!empty($_POST['bed'])) {
                if (!empty($filter))
                    $filter .= ' and ';
                $filter .= 'bed="' . $_POST['bed'] . '"';
            }
            if (!empty($_POST['user'])) {
                if (!empty($filter))
                    $filter .= ' and ';
                $filter .= 'Registration.guest_user_id="' . $_POST['user'] . '"';
            }

            $modelRoom = Room::model()->with(array('Registration'))->findAll(array('condition' => $filter, 'order' => 'number'));
        } else {
            $modelRoom = Room::model()->findAll(array('order' => 'number'));
        }
        $mSchedule = RoomSchedule::model()->findAll(array('index' => 'room_id', 'condition' => 'date_schedule="' . date('Y-m-d', strtotime($siteConfig->date_system)) . '"'));
        $this->render('stay', array(
            'modelRoom' => $modelRoom,
            'mSchedule' => $mSchedule,
        ));
    }

    public function actionHouseKeeping() {
        $siteConfig = SiteConfig::model()->findByPk(1);
        $this->CSS();
        $modelRoom = Room::model()->findAll(array('order' => 'number,room_type_id'));
        $mSchedule = RoomSchedule::model()->findAll(array('index' => 'room_id', 'condition' => 'date_schedule="' . date('Y-m-d', strtotime($siteConfig->date_system)) . '"'));
        $this->render('housekeeping', array(
            'modelRoom' => $modelRoom,
            'mSchedule' => $mSchedule,
        ));
    }

    public function actionUpdateGuestNames() {
        if (Yii::app()->request->isPostRequest) {
            $model = RegistrationDetail::model()->findByPk($_POST['pk']);
            $model->$_POST['name'] = $_POST['value'];
            $model->save();
        } else {
            throw new CHttpException(400, 'Invalid request');
        }
    }

    public function actionUpdateGuestReservation() {
        if (Yii::app()->request->isPostRequest) {
            $model = ReservationDetail::model()->findByPk($_POST['pk']);
            $model->$_POST['name'] = $_POST['value'];
            $model->save();
        } else {
            throw new CHttpException(400, 'Invalid request');
        }
    }

    public function actionUpdateNotes() {
        if (Yii::app()->request->isPostRequest) {
            $model = Room::model()->findByPk($_POST['pk']);
            $model->$_POST['name'] = $_POST['value'];
            $model->save();
        } else {
            throw new CHttpException(400, 'Invalid request');
        }
    }

    public function actionUpdateStatus() {
        if (Yii::app()->request->isPostRequest) {
            $model = Room::model()->findByPk($_POST['pk']);
            $model->$_POST['name'] = $_POST['value'];
            $model->modified = date('Y-m-d H:i:s');
            $model->modified_user_id = user()->id;
            $_POST['value'] = ($_POST['value'] == "vacant inspect") ? 'vacant' : $_POST['value'];
            if ($_POST['value'] == 'dirty' || $_POST['value'] == 'vacant' || $_POST['value'] == 'out of order') {
                $model->status = $_POST['value'];
            }
            $model->save();
        } else {
            throw new CHttpException(400, 'Invalid request');
        }
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
