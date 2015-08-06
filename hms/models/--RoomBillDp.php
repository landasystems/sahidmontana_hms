<?php

/**
 * This is the model class for table "{{room_bill_dp}}".
 *
 * The followings are the available columns in table '{{room_bill_dp}}':
 * @property integer $id
 * @property integer $room_bill_id
 * @property double $amount
 * @property string $cc_number
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class RoomBillDp extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{room_bill_dp}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('room_bill_id, created_user_id', 'numerical', 'integerOnly' => true),
            array('amount', 'numerical'),
            array('amount,room_bill_id', 'required'),
            array('cc_number', 'length', 'max' => 45),
            array('created, modified,is_cashier', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, room_bill_id, amount, cc_number, created, created_user_id, modified', 'safe', 'on' => 'search'),
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
            'amount' => 'Amount',
            'cc_number' => 'Credit Card Number',
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
        $criteria->compare('amount', $this->amount);
        $criteria->compare('cc_number', $this->cc_number, true);
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
     * @return RoomBillDp the static model class
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
