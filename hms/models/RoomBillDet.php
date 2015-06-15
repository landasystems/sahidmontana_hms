<?php

/**
 * This is the model class for table "{{room_bill_det}}".
 *
 * The followings are the available columns in table '{{room_bill_det}}':
 * @property integer $id
 * @property integer $room_bill_id
 * @property integer $charge_additional_id
 * @property integer $amount
 * @property integer $charge
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class RoomBillDet extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{room_bill_det}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('room_bill_id, charge_additional_id, amount, charge, created_user_id', 'numerical', 'integerOnly' => true),
            array('created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, room_bill_id, charge_additional_id, amount, charge, created, created_user_id, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Additional' => array(self::BELONGS_TO, 'ChargeAdditional', 'charge_additional_id'),
            'Cashier' => array(self::BELONGS_TO, 'User', 'created_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'room_bill_id' => 'Room Bill',
            'charge_additional_id' => 'Charge Additional',
            'amount' => 'Amount',
            'charge' => 'Charge',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
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
        $criteria->compare('room_bill_id', $this->room_bill_id);
        $criteria->compare('charge_additional_id', $this->charge_additional_id);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('charge', $this->charge);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RoomBillDet the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
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

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

}
