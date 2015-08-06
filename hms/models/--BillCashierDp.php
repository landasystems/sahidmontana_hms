<?php

/**
 * This is the model class for table "{{bill_cashier_dp}}".
 *
 * The followings are the available columns in table '{{bill_cashier_dp}}':
 * @property integer $id
 * @property integer $bill_cashier_id
 * @property integer $registration_id
 * @property integer $reservation_id
 * @property double $charge
 */
class BillCashierDp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{bill_cashier_dp}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bill_cashier_id, registration_id, reservation_id', 'numerical', 'integerOnly'=>true),
			array('charge', 'numerical'),
			array('cc_number,registration_dp_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, bill_cashier_id, registration_id, reservation_id, charge', 'safe', 'on'=>'search'),
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
                    'Reservation' => array(self::BELONGS_TO, 'Reservation', 'reservation_id'),
                    'Registration' => array(self::BELONGS_TO, 'Registration', 'registration_id'),
                    'RegistrationDp' => array(self::BELONGS_TO, 'RegistrationDp', 'registration_dp_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'bill_cashier_id' => 'Bill Cashier',
			'registration_id' => 'Registration',
			'reservation_id' => 'Reservation',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('bill_cashier_id',$this->bill_cashier_id);
		$criteria->compare('registration_id',$this->registration_id);
		$criteria->compare('reservation_id',$this->reservation_id);
		$criteria->compare('charge',$this->charge);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'sort' => array('defaultOrder' => 't.id DESC')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BillCashierDp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
