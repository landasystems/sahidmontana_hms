<?php

class User extends CActiveRecord {

    public $cache;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{user}}';
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, city_id, roles_id', 'required'),
            array('city_id, departement_id', 'numerical', 'integerOnly' => true),
            array('username, phone', 'length', 'max' => 20),
            array('', 'length', 'max' => 100),
            array('password, name, address', 'length', 'max' => 255),
            array('code', 'length', 'max' => 25),
            array('enabled', 'safe'),
            array('username, email', 'unique', 'message' => '{attribute} : {value} already exists!', 'on' => 'allow'),
            array('email', 'email', 'on' => 'allow'),
            array('username, email', 'required', 'on' => 'allow'),
            array('username, email', 'safe', 'message' => '{attribute} : {value} already exists!', 'on' => 'notAllow'),
            array('id, username, email, password, code, name, city_id, address, phone, created, created_user_id, modified,description', 'safe', 'on' => 'search'),
            array('avatar_img', 'unsafe'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'code' => 'Kode',
            'name' => 'Nama',
            'city_id' => 'Kota',
            'description' => 'Catatan',
            'address' => 'Alamat',
            'phone' => 'Telephone',
            'province_id' => 'Provinsi',
            'created' => 'Created',
            'created_user_id' => 'Created Userid',
            'modified' => 'Modified',
            'departement_id' => 'Departement Id',
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
        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('roles_id', $this->roles_id, true);
        $criteria->compare('departement_id', $this->departement_id, true);
        if ($type == 'customer') {
            $criteria->compare('Roles.type', 'customer');
        } elseif ($type == 'user') {
            $criteria->compare('Roles.is_allow_login', '1', true);
        } elseif ($type == 'supplier') {
            $criteria->compare('Roles.type', 'supplier');
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
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
            $sResult = User::model()->with('Roles')->findAll(array('condition' => 'Roles.is_allow_login=1'));
        } elseif ($type == 'supplier') {
            $sResult = User::model()->with('Roles')->findAll(array('condition' => 'Roles.type="supplier"','order'=>'t.name'));
        } elseif ($type == 'customer') {
            $sResult = User::model()->with('Roles')->findAll(array('condition' => 'Roles.type="customer"','order'=>'t.name'));
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
            $sResult = Roles::model()->findAll(array('condition' => 'type="customer"','order'=>'t.name'));
            $result = Chtml::listdata($sResult, 'id', 'name');
        } elseif ($sType == 'supplier') {
            $sResult = Roles::model()->findAll(array('condition' => 'type="supplier"','order'=>'t.name'));
            $result = Chtml::listdata($sResult, 'id', 'name');
        }


        return $result;
    }

    public function relations() {
        return array(
            'City' => array(self::BELONGS_TO, 'City', 'city_id'),
            'Payment' => array(self::HAS_MANY, 'Payment', 'id'),
            'FormBuilder' => array(self::HAS_MANY, 'FormBuilder', 'id'),
            'Roles' => array(self::BELONGS_TO, 'Roles', 'roles_id'),
            'UserLog' => array(self::HAS_MANY, 'UserLog', 'id'),
            'Departement' => array(self::BELONGS_TO, 'Departement', 'departement_id'),
        );
    }

    public function validatePassword($password) {
        return $this->hashPassword($password) === $this->password;
    }

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
        return '<img src="' . $this->imgUrl['small'] . '" class="img-polaroid" width="50"/><br>';
    }

    public function getMediumImage() {
        return '<img src="' . $this->imgUrl['medium'] . '" class="img-polaroid"/><br>';
    }

    public function getTagBiodata() {
        return '<div class="row-fluid">
                    <div class="span3">
                        <b>Identity Number</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $this->code . '
                    </div>
                </div>
                <div class="row-fluid">
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
        $departement = (!empty($this->departement_id)) ? '
                    <div class="row-fluid">
                    <div class="span3">
                        <b>Departement</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $this->Departement->name . '
                    </div>
                ' : '';
        $enabled = ($this->enabled == 0) ? "<span class=\"label label-important\">No</span>" :
                "<span class=\"label label-info\">Yes</span>";
        return '' . $username . '
                <div class="row-fluid">
                    <div class="span3">
                        <b>Permission</b>
                    </div>
                    <div class="span1">:</div>
                    <div class="span8" style="text-align:left">
                        ' . $this->Roles->name . '
                    </div>
                </div>
                ' . $departement . $email . '
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

//    public function getEnable(){
//        return '$this->enabled == 1 ? 'badge badge-success' : (($model->result >= 80) ? 'badge badge-warning' : 'badge badge-important');
//$result = '<span class="' . $resultColor . '"><h1>' . $model->result . '</h1></span>';';
//    } 
}
