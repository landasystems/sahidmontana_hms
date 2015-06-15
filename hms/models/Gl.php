<?php

/**
 * This is the model class for table "{{gl}}".
 *
 * The followings are the available columns in table '{{gl}}':
 * @property integer $id
 * @property integer $na_id
 * @property integer $room_id
 * @property integer $guest_user_id
 * @property double $charge_previous
 * @property double $charge
 * @property double $charge_settle
 * @property double $charge_balance
 */
class Gl extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{gl}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id, na_id, room_id, guest_user_id', 'numerical', 'integerOnly'=>true),
			array('charge_previous, charge, charge_settle, charge_balance', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, na_id, room_id, guest_user_id, charge_previous, charge, charge_settle, charge_balance', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'na_id' => 'Na',
			'room_id' => 'Room',
			'guest_user_id' => 'Guest User',
			'charge_previous' => 'Charge Previous',
			'charge' => 'Charge',
			'charge_settle' => 'Charge Settle',
			'charge_balance' => 'Charge Balance',
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
		$criteria->compare('na_id',$this->na_id);
		$criteria->compare('room_id',$this->room_id);
		$criteria->compare('guest_user_id',$this->guest_user_id);
		$criteria->compare('charge_previous',$this->charge_previous);
		$criteria->compare('charge',$this->charge);
		$criteria->compare('charge_settle',$this->charge_settle);
		$criteria->compare('charge_balance',$this->charge_balance);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'sort' => array('defaultOrder' => 't.id DESC')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Gl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
