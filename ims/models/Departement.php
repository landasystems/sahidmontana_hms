<?php

/**
 * This is the model class for table "{{m_dept}}".
 *
 * The followings are the available columns in table '{{m_dept}}':
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property integer $city_id
 * @property string $phone
 * @property string $email
 * @property string $fax
 */
class Departement extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Departement the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{departement}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('city_id, name, address, phone, email, fax', 'required'),
            array('city_id', 'numerical', 'integerOnly' => true),
            array('name, address, phone, fax', 'length', 'max' => 100),
            array('email', 'length', 'max' => 250),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, address, city_id, phone, email, fax', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'City'=>array(self::BELONGS_TO, 'City', 'city_id'),
            'Opname'=>array(self::HAS_MANY, 'Opname', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Nama',
            'address' => 'Alamat',
            'city_id' => 'Kota',
            'phone' => 'Telephone',
            'email' => 'Email',
            'fax' => 'Fax',
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
        //$criteria->with = array('City');
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('fax', $this->fax, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}