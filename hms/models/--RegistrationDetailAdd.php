<?php

/**
 * This is the model class for table "{{registration_detail_add}}".
 *
 * The followings are the available columns in table '{{registration_detail_add}}':
 * @property integer $id
 * @property integer $registration_detail_id
 * @property integer $charge_additional_id
 * @property integer $price
 * @property string $created
 * @property integer $created_user_id
 */
class RegistrationDetailAdd extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RegistrationDetailAdd the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{registration_detail_add}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('registration_detail_id, charge_additional_id, price, created_user_id', 'numerical', 'integerOnly' => true),
            array('created,charge', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, registration_detail_id, charge_additional_id, price, created, created_user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Room' => array(self::BELONGS_TO, 'Room', 'room_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'registration_detail_id' => 'Registration Detail',
            'charge_additional_id' => 'Charge Additional',
            'price' => 'Price',
            'created' => 'Created',
            'created_user_id' => 'Created User',
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
        $criteria->compare('registration_detail_id', $this->registration_detail_id);
        $criteria->compare('charge_additional_id', $this->charge_additional_id);
        $criteria->compare('price', $this->price);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

}
