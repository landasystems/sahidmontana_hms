<?php

/**
 * This is the model class for table "acc_cash_in_det".
 *
 * The followings are the available columns in table 'acc_cash_in_det':
 * @property integer $id
 * @property integer $acc_cash_in_id
 * @property integer $acc_coa_id
 * @property double $amount
 */
class AccCashInDet extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{acc_cash_in_det}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('acc_cash_in_id, acc_coa_id, invoice_det_id', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, acc_cash_in_id, invoice_det_id, acc_coa_id, amount', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                   'AccCoa' => array(self::BELONGS_TO, 'AccCoa', 'acc_coa_id'),
                   'AccCashIn' => array(self::BELONGS_TO, 'AccCashIn', 'acc_cash_in_id'),
                   'InvoiceDet' => array(self::BELONGS_TO, 'InvoiceDet', 'invoice_det_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'acc_cash_in_id' => 'Acc Cash In',
			'acc_coa_id' => 'Acc Coa',
			'amount' => 'Amount',
			'invoice_det_id' => 'Invoice Det',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('acc_cash_in_id',$this->acc_cash_in_id);
		$criteria->compare('acc_coa_id',$this->acc_coa_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('invoice_det_id',$this->invoice_det_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccCashInDet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
