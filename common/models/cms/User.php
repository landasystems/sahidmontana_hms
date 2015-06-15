<?php

/**
 * This is the model class for table "{{m_user}}".
 *
 * The followings are the available columns in table '{{m_user}}':
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $employeenum
 * @property string $name
 * @property integer $city_id
 * @property string $address
 * @property string $phone
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class User extends CActiveRecord {

    public $cache;
    public $verifyCode;

//    public function __construct() {
//        $this->cache = Yii::app()->cache;
//    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
//    public function getDbConnection() {
//        return Yii::app()->db2;
//    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('roles_id', 'required'),
            array('city_id,saldo, created_user_id, modal', 'numerical', 'integerOnly' => true),
            array('username, phone', 'length', 'max' => 20),
            array('', 'length', 'max' => 100),
            array('name, password, name,description, address', 'length', 'max' => 255),
            array('code', 'length', 'max' => 25),
            array('modified, enabled', 'safe'),
            array('username, email', 'unique', 'message' => '{attribute} : {value} already exists!', 'on' => array('allow','register')),
            array('email', 'email', 'on' => array('allow','register')),
            array('username, email, password', 'required', 'on' => array('allow','register')),
            array('username, email', 'safe', 'message' => '{attribute} : {value} already exists!', 'on' => 'notAllow'),
            array('id, username, email,saldo, password, code, name, city_id, address, phone, created, created_user_id, modified,description', 'safe', 'on' => 'search'),
            array('avatar_img', 'unsafe'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'register'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'code' => 'Identity Number (KTP/SIM/Passport)',
            'name' => 'Name',
            'city_id' => 'city_id',
            'address' => 'Address',
            'phone' => 'Phone',
            'created' => 'Created',
            'created_user_id' => 'Created Userid',
            'modified' => 'Modified',
            'verifyCode' => 'Verification Code',
        );
    }

    public function search($type = 'user') {
        $criteria = new CDbCriteria;
        $criteria->with = array('Roles');
        $criteria->together = true;

        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
//        $criteria->compare('password', $this->password, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('city_id', $this->city_id, true);
        $criteria->compare('phone', $this->phone, true);
//        $criteria->compare('roles_id', $this->roles_id, true);
        if ($type == 'customer') {
//            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_customer, true);
            $criteria->addInCondition('roles_id', $sCriteria);
        } elseif ($type == 'contact') {
//            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_contact, true);
            $criteria->compare('roles_id', $sCriteria);
            if (!empty($roles))
                $criteria->compare('roles_id', $roles);
        } elseif ($type == 'user') {
            $criteria->compare('roles_id', $this->roles_id);
        } elseif ($type == 'supplier') {
//            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_supplier, true);
            $criteria->addInCondition('roles_id', $sCriteria);
        } elseif ($type == 'employment') {
//            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_employment, true);
            $criteria->addInCondition('roles_id', $sCriteria);
        } elseif ($type == 'client') {
//            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_client, true);
            $criteria->addInCondition('roles_id', $sCriteria);
        }elseif ($type == 'student') {
//            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_student, true);
            $criteria->addInCondition('roles_id', $sCriteria);
        } elseif ($type == 'teacher') {
//            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_teacher, true);
            $criteria->addInCondition('roles_id', $sCriteria);
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort'  =>array('defaultOrder'=>'t.id DESC') 
        ));
    }

    public function listUser() {
        if (!app()->session['listUser']) {
            $result = array();
            $users = $this->findAll(array('index' => 'id'));
            app()->session['listUser'] = $users;
        }

        return app()->session['listUser'];
    }

    public function listUserPhone() {
        if (!app()->session['listUserPhone']) {
            $result = array();
            $users = $this->findAll(array('index' => 'phone'));
            app()->session['listUserPhone'] = $users;
        }

        return app()->session['listUserPhone'];
    }

    public function roles() {
        $result = Roles::model()->findAll();
        return $result;
    }

    public function role($user_id) {
        $role = User::model()->findByPk($user_id);

        if (isset($role->Roles->name)) {
            $result = $role->Roles->name;
        } else {
            $result = '';
        }

        return $result;
    }

    public function listUsers($type = '') {
        $siteConfig = SiteConfig::model()->listSiteConfig();
        if ($type == 'user') {
//             $criteria->with = array('Roles');
//            $criteria->together = true;
            $sResult = User::model()->with('Roles')->findAll(array('condition' => 'Roles.is_allow_login=1'));
        } elseif ($type == 'supplier') {
            $sCriteria = json_decode($siteConfig->roles_supplier, true);
            if(!empty($sCriteria)){
            $list = '';
            foreach ($sCriteria as $o) {
                $list .= '"' . $o . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = User::model()->findAll(array('condition' => 'roles_id in(' . $list . ')'));
            }else{
               $sResult=array();
            }
        } elseif ($type == 'customer') {
            $sCriteria = json_decode($siteConfig->roles_customer, true);
            if(!empty($sCriteria)){
            $list = '';
            foreach ($sCriteria as $o) {
                $list .= '"' . $o . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = User::model()->findAll(array('condition' => 'roles_id in(' . $list . ')'));
            }else{
                $sResult=array();
            }
        } elseif ($type == 'contact') {
            $sCriteria = json_decode($siteConfig->roles_contact, true);
            if(!empty($sCriteria)){
            $list = '';
            foreach ($sCriteria as $o) {
                $list .= '"' . $o . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = User::model()->findAll(array('condition' => 'roles_id in(' . $list . ')'));
            }else{
                $sResult=array();
            }
        } elseif ($type == 'client') {
            $sCriteria = json_decode($siteConfig->roles_client, true);
            if(!empty($sCriteria)){
            $list = '';
            foreach ($sCriteria as $o) {
                $list .= '"' . $o . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = User::model()->findAll(array('condition' => 'roles_id in(' . $list . ')'));
            }else{
                $sResult=array();
            }
        } elseif ($type == 'guest') {
            $sCriteria = json_decode($siteConfig->roles_guest, true);
            if(!empty($sCriteria)){
            $list = '';
            foreach ($sCriteria as $o) {
                $list .= '"' . $o . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = User::model()->findAll(array('condition' => 'roles_id in(' . $list . ')'));
            }else{
                $sResult=array();
            }
        }
        return $sResult;
    }

    public function typeRoles($sType = 'user') {
        $siteConfig = SiteConfig::model()->listSiteConfig();
        $result = array();

        if ($sType == 'user') {
            if (Yii::app()->user->roles_id == -1) {
                $array = array(-1 => 'Super User');
            } else {
                $array = array();
            }

            $sResult = Roles::model()->findAll(array('condition' => 'is_allow_login=1'));
            $result = $array + Chtml::listdata($sResult, 'id', 'name');
        } elseif ($sType == 'customer') {
            $customers = json_decode($siteConfig->roles_customer, true);
            $list = '';
            foreach ($customers as $customer) {
                $list .= '"' . $customer . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            $result = Chtml::listdata($sResult, 'id', 'name');
        } elseif ($sType == 'supplier') {
            $customers = json_decode($siteConfig->roles_supplier, true);
            $list = '';
            foreach ($customers as $customer) {
                $list .= '"' . $customer . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            $result = Chtml::listdata($sResult, 'id', 'name');
        } elseif ($sType == 'employment') {
            $customers = json_decode($siteConfig->roles_employment, true);
            $list = '';
            foreach ($customers as $customer) {
                $list .= '"' . $customer . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            $result = Chtml::listdata($sResult, 'id', 'name');
        } elseif ($sType == 'contact') {
            $contact = json_decode($siteConfig->roles_contact, true);
            $list = '';
            foreach ($contact as $contact) {
                $list .= '"' . $contact . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            $result = Chtml::listdata($sResult, 'id', 'name');
        } elseif ($sType == 'client') {
            $client = json_decode($siteConfig->roles_client, true);
            $list = '';
            foreach ($client as $client) {
                $list .= '"' . $client . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            $result = Chtml::listdata($sResult, 'id', 'name');
        }


        return $result;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'City' => array(self::BELONGS_TO, 'City', 'city_id'),
            'Payment' => array(self::HAS_MANY, 'Payment', 'id'),
            'SaldoDeposit' => array(self::HAS_MANY, 'SaldoDeposit', 'id'),
            'SaldoWithdrawal' => array(self::HAS_MANY, 'SaldoWithdrawal', 'id'),
            'FormBuilder' => array(self::HAS_MANY, 'FormBuilder', 'id'),
            'Roles' => array(self::BELONGS_TO, 'Roles', 'roles_id'),
            'UserLog' => array(self::HAS_MANY, 'UserLog', 'id'),
            'MlmDiagram' => array(self::HAS_MANY, 'MlmDiagram', 'id'),
            'MlmPrize' => array(self::HAS_MANY, 'MlmPrize', 'id'),
        );
    }

    /**
     * Checks if the given password is correct.
     * @param string the password to be validated
     * @return boolean whether the password is valid
     */
    public function validatePassword($password) {
        return $this->hashPassword($password) === $this->password;
    }

    public function behaviors() {
        return array(
            'timestamps' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    /**
     * Generates the password hash.
     * @param string password
     * @param string salt
     * @return string hash
     */
    public function hashPassword($password) {
        return sha1($password);
    }

    public function getUrlFull() {
        return param('urlImg') . $this->DownloadCategory->path . $this->url;
    }

    public function getUrlDel() {
        return createUrl('download/' . $this->Download->id);
    }

    public function getImgUrl() {
        return landa()->urlImg('avatar/', $this->avatar_img, $this->id);
    }

    public function getUrl() {

        return url('user/' . $this->id);
    }

    public function getTagImg() {
        return '<img src="' . $this->imgUrl['small'] . '" class="img-polaroid img-rounded"/><br>';
    }

    public function getMediumImage() {
        return '<img src="' . $this->imgUrl['medium'] . '" class="img-polaroid"/><br>';
    }

    public function getTagBiodata() {
        $sSaldo = (isset($this->saldo)) ? landa()->rp($this->saldo) : '';
        echo '<div class="row-fluid">
                    <div class="span3">
                        <b>Nama</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $this->name . '
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <b>Provinsi</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $this->City->Province->name . '
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <b>Kota/Kab</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $this->City->name . '
                    </div>
                </div>
                     <div class="row-fluid">
                    <div class="span3">
                        <b>Telepon</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        +62' . $this->phone . '
                    </div>
                </div>
                ';
        if (in_array('saldo', param('menu'))) {
            echo'<div class="row-fluid">
                    <div class="span3">
                        <b>Saldo</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $sSaldo . '
                    </div>
                </div>';
        }
        if (param('client')=='sumbangcoin') {
            
        }
        if (param('client')=='mlt') {
            $others = json_decode($this->others, true);
            
            echo'<div class="row-fluid">
                    <div class="span3">
                        <b>Bonus</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . landa()->rp($this->saldoPrize) . '
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <b>Tagihan</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . landa()->rp($this->saldoCredit) . '
                    </div>
                </div>';
        }
    }

    function getFullName() {
        return '[' . $this->roles . '] ' . $this->name;
    }

    function getFullContact() {
        return $this->name . ' (0' . $this->phone . ')';
    }
    function getNamePhone() {
        return $this->name . ' (0' . $this->phone . ')';
    }

    public function getTagAccess() {

        $username = (!empty($this->username)) ? "
            <div class=\"row-fluid\">
                    <div class=\"span3\">
                        <b>Username</b>
                    </div>
                    <div class=\"span1\">:</div>
                    <div class=\"span8\" style=\"text-align:left\">
                         $this->username 
                    </div>
                </div>" : '';
        $email = (!empty($this->email)) ? "
            <div class=\"row-fluid\">
                    <div class=\"span3\">
                        <b>E-mail</b>
                    </div>
                    <div class=\"span1\">:</div>
                    <div class=\"span8\" style=\"text-align:left\">
                         $this->email 
                    </div>
                </div>" : "";


        $enabled = ($this->enabled == 0) ? "<span class=\"label label-important\">No</span>" :
                "<span class=\"label label-info\">Yes</span>";
        $sRolesName = (isset($this->Roles->name)) ? $this->Roles->name : '-';
        $sRoles = ($this->roles_id == -1) ? 'Super user' : $sRolesName;

        return '' . $username . '
                <div class="row-fluid">
                    <div class="span3">
                        <b>Permission</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $sRoles . '
                    </div>
                </div>
                ' . $email . '
                <div class="row-fluid">
                    <div class="span3">
                        <b>Enabled</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $enabled . '
                    </div>
                </div>';
    }

    public function getDecodeOthers() {
        $result = json_decode($this->others,true);
        return $result;
    }
    
    public function getSaldoMlt(){
        $others = json_decode($this->others,true);
        $saldoCredit = (isset($others['saldo_credit'])) ? $others['saldo_credit'] : 0;
        $saldoPrize = (isset($others['saldo_prize'])) ? $others['saldo_prize'] : 0;
        return 'Saldo: ' . landa()->rp($this->saldo) . ', Bonus:'.landa()->rp($saldoPrize).', Tagihan:' . landa()->rp($saldoCredit);
    }
    public function getSaldoPlay(){
        return $this->saldo + $this->saldoPrize;
    }
    public function getSaldoPrize(){
        $others = json_decode($this->others,true);
        $saldoPrize = (isset($others['saldo_prize'])) ? $others['saldo_prize'] : 0;
        return $saldoPrize;
    }
    public function getSaldoCredit(){
        $others = json_decode($this->others,true);
        $saldoCredit = (isset($others['saldo_credit'])) ? $others['saldo_credit'] : 0;
        return $saldoCredit;
    }

    

//    public function getEnable(){
//        return '$this->enabled == 1 ? 'badge badge-success' : (($model->result >= 80) ? 'badge badge-warning' : 'badge badge-important');
//$result = '<span class="' . $resultColor . '"><h1>' . $model->result . '</h1></span>';';
//    } 
}
