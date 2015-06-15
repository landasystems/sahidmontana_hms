<?php

/**
 * This is the model class for table "{{room_schedule}}".
 *
 * The followings are the available columns in table '{{room_schedule}}':
 * @property integer $id
 * @property integer $room_id
 * @property string $date_schedule
 * @property string $status
 * @property integer $reservation_id
 * @property integer $registration_id
 */
class RoomSchedule extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{room_schedule}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('id', 'required'),
			array('id, room_id, reservation_id, registration_id', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>25),
			array('date_schedule', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, room_id, date_schedule, status, reservation_id, registration_id', 'safe', 'on'=>'search'),
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
                    'Reservation' => array(self::BELONGS_TO, 'Reservation', 'reservation_id'),
                    'Room' => array(self::BELONGS_TO, 'Room', 'room_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'room_id' => 'Room',
			'date_schedule' => 'Date Schedule',
			'status' => 'Status',
			'reservation_id' => 'Reservation',
			'registration_id' => 'Registration',
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
		$criteria->compare('room_id',$this->room_id);
		$criteria->compare('date_schedule',$this->date_schedule,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('reservation_id',$this->reservation_id);
		$criteria->compare('registration_id',$this->registration_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'sort' => array('defaultOrder' => 't.id DESC')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RoomSchedule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
         public function getDaySchedule(){
            return date('j',strtotime($this->date_schedule));
        }
}
