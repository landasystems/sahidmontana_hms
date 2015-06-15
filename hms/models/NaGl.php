<?php

/**
 * This is the model class for table "{{na_gl}}".
 *
 * The followings are the available columns in table '{{na_gl}}':
 * @property integer $id
 * @property integer $na_id
 * @property integer $registration_id
 * @property double $prev
 * @property double $charge
 * @property integer $balance
 */
class NaGl extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{na_gl}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('na_id, registration_id, prev, charge, balance', 'required'),
			array('na_id, bill_id, registration_id, balance', 'numerical', 'integerOnly'=>true),
			array('prev, charge,deposite,tunai,creditcard,cityledger,refund', 'numerical'),
			array('room_number, guest_user_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, na_id, registration_id, prev, charge, balance', 'safe', 'on'=>'search'),
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
                    'Registration' => array(self::BELONGS_TO, 'Registration', 'registration_id'),
                    'Bill' => array(self::BELONGS_TO, 'Bill', 'bill_id'),
                    'Guest' => array(self::BELONGS_TO, 'User', 'guest_user_id'),
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
			'registration_id' => 'Registration',
			'prev' => 'Prev',
			'charge' => 'Charge',
			'balance' => 'Balance',
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
		$criteria->compare('registration_id',$this->registration_id);
		$criteria->compare('prev',$this->prev);
		$criteria->compare('charge',$this->charge);
		$criteria->compare('balance',$this->balance);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'sort' => array('defaultOrder' => 't.id DESC')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NaGl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
