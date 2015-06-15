<?php

/**
 * This is the model class for table "{{roles}}".
 *
 * The followings are the available columns in table '{{roles}}':
 * @property integer $id
 * @property string $name
 * @property integer $is_allow_login
 */
class Roles extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
//    public function getDbConnection() {
//        return Yii::app()->db2;
//    }

    public function tableName() {
        return '{{roles}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('is_allow_login,prefix', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, is_allow_login', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'is_allow_login' => 'Is Allow Login',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($type = 'user') {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('is_allow_login', $this->is_allow_login);

        if ($type == 'customer') {

            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_customer, true);
            $criteria->addInCondition('id', $sCriteria);
        } elseif ($type == 'user') {
//           $criteria->compare('id', $this->id);
            $criteria->compare('is_allow_login', '1', true);
        } elseif ($type == 'contact') {
            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_contact, true);
            $criteria->addInCondition('id', $sCriteria);
        } elseif ($type == 'supplier') {
            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_supplier, true);
            $criteria->addInCondition('id', $sCriteria);
        } elseif ($type == 'employment') {
            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_employment, true);
            $criteria->addInCondition('id', $sCriteria);
        } elseif ($type == 'guest') {
            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_guest, true);
            $criteria->addInCondition('id', $sCriteria);
        } elseif ($type == 'teacher') {
            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_teacher, true);
            $criteria->addInCondition('id', $sCriteria);
        } elseif ($type == 'student') {
            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_student, true);
            $criteria->addInCondition('id', $sCriteria);
        } elseif ($type == 'client') {
            $criteria->alias = "u";
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_client, true);
            $criteria->addInCondition('id', $sCriteria);
        }else{
            $criteria->compare('is_allow_login', '1', true);
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Roles the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function listRoles() {
        if (!app()->session['listRoles']) {
            $result = array();
            $roles = $this->findAll(array('index' => 'id'));
            app()->session['listRoles'] = $roles;
        }

        return app()->session['listRoles'];
    }

    public function listRole($type = '') {
        $sResult = "";
        $siteConfig = SiteConfig::model()->listSiteConfig();
        if ($type == 'supplier') {
            $sCriteria = json_decode($siteConfig->roles_supplier, true);
            if(!empty($sCriteria)){
            $list = '';
            foreach ($sCriteria as $o) {
                $list .= '"' . $o . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
//            $sResult = 'ghjghj';
            $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            }else{
                $sResult='';
            }
        } elseif ($type == 'customer') {
            $sCriteria = json_decode($siteConfig->roles_customer, true);
            if(!empty($sCriteria)){
            $list = '';
            foreach ($sCriteria as $o) {
                $list .= '"' . $o . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            }else{
                $sResult='';
            }
        } elseif ($type == 'guest') {
            $sCriteria = json_decode($siteConfig->roles_guest, true);
            if (!empty($sCriteria)) {
                $list = '';
                foreach ($sCriteria as $o) {
                    $list .= '"' . $o . '",';
                }
                $list = substr($list, 0, strlen($list) - 1);
                $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            } else {
                $sResult = '';
            }
        } elseif ($type == 'user') {
            $sResult = Roles::model()->findAll(array('condition' => 'is_allow_login=1'));
        } elseif ($type == 'employment') {
            $sCriteria = json_decode($siteConfig->roles_employment, true);
            if (!empty($sCriteria)) {
                $list = '';
                foreach ($sCriteria as $o) {
                    $list .= '"' . $o . '",';
                }
                $list = substr($list, 0, strlen($list) - 1);
                $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            } else {
                $sResult = '';
            }
        } elseif ($type == 'client') {
            $sCriteria = json_decode($siteConfig->roles_client, true);
            if (!empty($sCriteria)) {
                $list = '';
                foreach ($sCriteria as $o) {
                    $list .= '"' . $o . '",';
                }
                $list = substr($list, 0, strlen($list) - 1);
                $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            } else {
                $sResult = '';
            }
        } elseif ($type == 'contact') {
            $sCriteria = json_decode($siteConfig->roles_contact, true);
            if (!empty($sCriteria)) {
                $list = '';
                foreach ($sCriteria as $o) {
                    $list .= '"' . $o . '",';
                }
                $list = substr($list, 0, strlen($list) - 1);
                $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            } else {
                $sResult = '';
            }
        } elseif ($type == 'student') {
            $sCriteria = json_decode($siteConfig->roles_student, true);
            if (!empty($sCriteria)) {
                $list = '';
                foreach ($sCriteria as $o) {
                    $list .= '"' . $o . '",';
                }
                $list = substr($list, 0, strlen($list) - 1);
                $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            } else {
                $sResult = '';
            }
        }elseif ($type == 'teacher') {
            $sCriteria = json_decode($siteConfig->roles_teacher, true);
            if (!empty($sCriteria)) {
                $list = '';
                foreach ($sCriteria as $o) {
                    $list .= '"' . $o . '",';
                }
                $list = substr($list, 0, strlen($list) - 1);
                $sResult = Roles::model()->findAll(array('condition' => 'id in(' . $list . ')'));
            } else {
                $sResult = '';
            }
        }
        return $sResult;
    }

    public function getStatus() {
        $status = ($this->is_allow_login == 0) ? "<span class=\"label label-important\">No</span>" :
                "<span class=\"label label-info\">Yes</span>";
        return $status;
    }
    public function getTextPrefix() {
        $status = ($this->prefix == 0) ? "<span class=\"label label-important\">No</span>" :
                "<span class=\"label label-info\">Yes</span>";
        return $status;
    }

}
