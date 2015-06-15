<?php

/**
 * This is the model class for table "{{registration_detail}}".
 *
 * The followings are the available columns in table '{{registration_detail}}':
 * @property integer $id
 * @property integer $registration_id
 * @property integer $room_id
 * @property integer $pax
 * @property integer $price
 * @property string $date_to
 * @property string $date_from
 */
class RegistrationDetail extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RegistrationDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{registration_detail}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('registration_id, room_id' , 'numerical', 'integerOnly'=>true),
			array('charge,guest_user_names,others_include,extrabed,pax,guest_user_names,room_price,extrabed_price,fnb_price', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, is_moved,registration_id, room_id,  date_to, date_from', 'safe', 'on'=>'search'),
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
                    'Room' => array(self::BELONGS_TO, 'Room', 'room_id'),
                    'Registration' => array(self::BELONGS_TO, 'Registration', 'registration_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'registration_id' => 'Registration',
			'room_id' => 'Room',				
			'date_to' => 'Date To',
			'date_from' => 'Date From',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('registration_id',$this->registration_id);
		$criteria->compare('room_id',$this->room_id);		
		$criteria->compare('date_to',$this->date_to,true);
		$criteria->compare('date_from',$this->date_from,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'sort' => array('defaultOrder' => 't.id DESC')
		));
	}
}
