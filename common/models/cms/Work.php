<?php

/**
 * This is the model class for table "{{work}}".
 *
 * The followings are the available columns in table '{{work}}':
 * @property integer $id
 * @property string $user_id
 * @property string $status
 * @property integer $time_total
 * @property integer $time_session
 * @property double $time_elapsed
 */
class Work extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Work the static model class
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
		return '{{work}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time_total, time_session', 'numerical', 'integerOnly'=>true),
			array('time_elapsed', 'numerical'),
			array('user_id, status', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, status, time_total, time_session, time_elapsed', 'safe', 'on'=>'search'),
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
                    'Portfolio'=>array(self::HAS_MANY, 'WorkDetail', 'id'),
                    'User' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'status' => 'Status',
			'time_total' => 'Time Total',
			'time_session' => 'Time Session',
			'time_elapsed' => 'Time Elapsed',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('time_total',$this->time_total);
		$criteria->compare('time_session',$this->time_session);
		$criteria->compare('time_elapsed',$this->time_elapsed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}