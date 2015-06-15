<?php

/**
 * This is the model class for table "{{m_city}}".
 *
 * The followings are the available columns in table '{{m_city}}':
 * @property integer $id
 * @property string $name
 * @property integer $province_id
 */
class City extends CActiveRecord {

    public function getDbConnection() {
        return Yii::app()->db2;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return City the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{city}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, province_id', 'required'),
            array('province_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 250),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, province_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Province' => array(self::BELONGS_TO, 'Province', 'province_id'),
            'Departement' => array(self::HAS_MANY, 'Departement', 'id'),
            'SiteConfig' => array(self::HAS_MANY, 'SiteConfig', 'id'),
            'Contact' => array(self::HAS_MANY, 'Contact', 'id'),
            'User' => array(self::HAS_MANY, 'User', 'id'),
            'Agent' => array(self::HAS_MANY, 'Agent', 'id'),
        );
    }

     public function getFullName() {
         $province = (isset($this->Province->name))?$this->Province->name.' - ':''; 
         return ucwords(strtolower($province . $this->name));
     }
     public function getFullNameCut() {
         $province = (isset($this->Province->name))?$this->Province->name.' - ':''; 
         return ucwords(strtolower($province . $this->name));
     }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'City Name',
            'province_id' => 'province_id',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('province_id', $this->province_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
