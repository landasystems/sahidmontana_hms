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

//    public function __construct() {
//        $this->cache = Yii::app()->cache;
//    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
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
            array('name, city_id, roles_id', 'required'),
            array('city_id, created_user_id', 'numerical', 'integerOnly' => true),
            array('username, phone', 'length', 'max' => 20),
            array('password, name,description,company, address', 'length', 'max' => 255),
            array('code', 'length', 'max' => 100),
            array('modified,sex,nationality,birth, enabled', 'safe'),
            array('username, email', 'unique', 'message' => '{attribute} : {value} already exists!', 'on' => 'allow'),
            array('email', 'email', 'on' => 'allow'),
            array('username, email', 'required', 'on' => 'allow'),
            array('username, email', 'safe', 'message' => '{attribute} : {value} already exists!', 'on' => 'notAllow'),
            array('company, id, username, email, password, code, name, city_id, address, phone, created, created_user_id, modified,description', 'safe', 'on' => 'search'),
            array('avatar_img', 'unsafe'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'code' => 'KTP/ SIM/ Passport',
            'name' => 'Name',
            'city_id' => 'city_id',
            'address' => 'Address',
            'phone' => 'Phone',
            'created' => 'Created',
            'created_user_id' => 'Created Userid',
            'modified' => 'Modified',
            'roles_id' => 'Roles',
        );
    }

    public function search($type = 'user') {
        $criteria = new CDbCriteria;
        $criteria->with = array('Roles');
        $criteria->together = true;

        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('roles_id', $this->roles_id, true);
        
        if ($type == 'user'){
            $criteria->compare('Roles.is_allow_login', 1);
        }else{
            $criteria->compare('Roles.is_allow_login', 0);
        }
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    public function listUser() {
        return $this->findAll(array('index' => 'id'));
    }

    public function listUserPhone() {
        return $this->findAll(array('index' => 'phone'));
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
        if ($type == 'user') {
            $sResult = User::model()->with('Roles')->findAll(array('condition' => 'Roles.is_allow_login=1'));
        } elseif ($type == 'guest') {
            $sResult = User::model()->with('Roles')->findAll(array('condition' => 'Roles.is_allow_login=0'));
        }
        return $sResult;
    }

//    public function getBillUser() {
//        $name = $_GET["q"];
//        $siteConfig = SiteConfig::model()->listSiteConfig();
//        $sCriteria = json_decode($siteConfig->roles_guest, true);
//        if (!empty($sCriteria)) {
//            $list = '';
//            foreach ($sCriteria as $o) {
//                $list .= '"' . $o . '",';
//            }
//            $list = substr($list, 0, strlen($list) - 1);
//            $sResult = User::model()->findAll(array('condition' => 'name like "%$name%" and roles_id in(' . $list . ')', 'limit' => '10'));
//        } else {
//            $sResult = '';
//        }
//        return $sResult;
//    }

    public function typeRoles($sType = 'user') {
        $result = array();
        if ($sType == 'user') {
            $sResult = Roles::model()->user();
            $result = $array + Chtml::listdata($sResult, 'id', 'name');
        } elseif ($sType == 'guest') {
            $sResult = Roles::model()->guest();
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
            'FormBuilder' => array(self::HAS_MANY, 'FormBuilder', 'id'),
            'Roles' => array(self::BELONGS_TO, 'Roles', 'roles_id'),
            'UserLog' => array(self::HAS_MANY, 'UserLog', 'id'),
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

    /**
     * Generates the password hash.
     * @param string password
     * @param string salt
     * @return string hash
     */
    public function hashPassword($password) {
        return sha1($password);
    }

    public function getImgUrl() {
        return landa()->urlImg('avatar/', $this->avatar_img, $this->id);
    }

    public function getUrl() {

        return url('user/' . $this->id);
    }

    public function getTagImg() {
        return '<img src="' . $this->imgUrl['small'] . '" class="img-polaroid" style="width:30px"/><br>';
    }

    public function getMediumImage() {
        return '<img src="' . $this->imgUrl['medium'] . '" class="img-polaroid" style=""/><br>';
    }

    public function getTagBiodata() {
        $code = (!empty($this->code)) ? '' : '';
        $phone = (isset($this->phone)) ? $this->phone : '-';
        return '<div class="row-fluid">
                    <div class="span3" style="text-align:left">
                        <b>ID/Passport</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $this->code . '
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3" style="text-align:left">
                        <b>Name</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $this->guestName . $this->company . '
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3" style="text-align:left">
                        <b>Telphone</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $phone . '
                    </div>
                </div>
                ';
    }

    function getFullName() {
        $roles = (isset($this->Roles->name)) ? $this->Roles->name : '-';
        if (isset($this->Roles->is_allow_login) and $this->Roles->is_allow_login == 1) {
            $title = ($this->sex == 'f') ? 'Mrs. ' : 'Mr. ';
        } else {
            $title = '';
        }
        if ($this->roles_id != -1)
            return '[' . $roles . '] ' . strtoupper($title . ucwords($this->name));
    }

    function getGuestName() {
        if (isset($this->Roles->is_allow_login) && $this->Roles->is_allow_login == 1) {
            $title = ($this->sex == 'f') ? 'Mrs. ' : 'Mr. ';
        } else {
            $title = '';
        }
        return strtoupper($title . ucwords($this->name));
    }

    public function getDetailGuest($id) {
        $guest = User::model()->findByPk($id);
        $detail = '';
        if (!empty($guest)) {
            $detail = "<center><b>DETAIL GUEST</b></center><hr>";
            $detail .= "<div class=\\'span1\\'>Name</div><div class=\\'span3\\'>: " . ucwords($guest->guestName) . "</div><br>";
            $detail .= "<div class=\\'span1\\'>Province</div><div class=\\'span3\\'>: " . ucwords($guest->City->Province->name) . "</div><br>";
            $detail .= "<div class=\\'span1\\'>City</div><div class=\\'span3\\'>: " . ucwords($guest->City->name) . "</div><br>";
            $detail .= "<div class=\\'span1\\'>Address</div><div class=\\'span3\\'>: " . ucwords($guest->address) . "</div><br>";
            $detail .= "<div class=\\'span1\\'>Phone</div><div class=\\'span3\\'>: " . ucwords($guest->phone) . "</div><br>";
        }
        return $detail;
    }

    public function getTagAccess() {
        $rolesName = ($this->roles_id != -1) ? $this->Roles->name : "Super User";
        $enabled = ($this->enabled == 0) ? "<span class=\"label label-important\">No</span>" : "<span class=\"label label-info\">Yes</span>";
        $address = (isset($this->address)) ? $this->address : '-';
        $city = (isset($this->City->name)) ? ucwords($this->City->name) : '';
        $prov = (isset($this->City->name)) ? ucwords($this->City->Province->name) : '';
        $nat = (empty($this->nationality)) ? 'Indonesia' : $this->nationality;
        return '<div class="row-fluid">
                    <div class="span3" style="text-align:left">
                        <b>Nationality</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $nat . '
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3" style="text-align:left">
                        <b>Address</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $prov . $city . $address . '
                    </div>
                </div>
                ';
    }

//    public function getEnable(){
//        return '$this->enabled == 1 ? 'badge badge-success' : (($model->result >= 80) ? 'badge badge-warning' : 'badge badge-important');
//$result = '<span class="' . $resultColor . '"><h1>' . $model->result . '</h1></span>';';
//    } 
}
