<?php

/**
 * This is the model class for table "{{bill_charge_det}}".
 *
 * The followings are the available columns in table '{{bill_charge_det}}':
 * @property integer $id
 * @property integer $bill_charge_id
 * @property integer $charge_additional_id
 * @property double $amount
 * @property double $charge
 */
class BillChargeDet extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public $qty;
    public $total;

    public function tableName() {
        return '{{bill_charge_det}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('bill_charge_id,deposite_id,deposite_amount, charge_additional_id', 'numerical', 'integerOnly' => true),
            array('amount, charge', 'numerical'),
            array('discount,qty', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, bill_charge_id, charge_additional_id, amount, charge', 'safe', 'on' => 'search'),
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
            'bill_charge_id' => 'Bill Charge',
            'charge_additional_id' => 'Charge Additional',
            'amount' => 'Amount',
            'charge' => 'Charge',
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
        $criteria->compare('bill_charge_id', $this->bill_charge_id);
        $criteria->compare('charge_additional_id', $this->charge_additional_id);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('charge', $this->charge);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BillChargeDet the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getNetTotal() {
        $total = ($this->charge - (($this->discount / 100) * $this->charge)) * $this->amount;
        return $total;
    }

    public function getNetCharge() {
        $total = $this->charge - (($this->discount / 100) * $this->charge);
        return $total;
    }

}
