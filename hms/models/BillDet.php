<?php

/**
 * This is the model class for table "{{bill_det}}".
 *
 * The followings are the available columns in table '{{bill_det}}':
 * @property integer $id
 * @property integer $bill_id
 * @property integer $room_bill_id
 */
class BillDet extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{bill_det}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('bill_id, room_bill_id_leader, deposite_id,room_bill_id,deposite_amount', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, bill_id, room_bill_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'RoomBill' => array(self::BELONGS_TO, 'RoomBill', 'room_bill_id'),
            'Bill' => array(self::BELONGS_TO, 'Bill', 'bill_id'),
            'BillCharge' => array(self::BELONGS_TO, 'BillCharge', 'bill_charge_id'),
            'Deposite' => array(self::BELONGS_TO, 'Deposite', 'deposite_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'bill_id' => 'Bill',
            'room_bill_id' => 'Room Bill',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('bill_id', $this->bill_id);
        $criteria->compare('room_bill_id', $this->room_bill_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BillDet the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
