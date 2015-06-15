<?php

/**
 * This is the model class for table "{{sell_info}}".
 *
 * The followings are the available columns in table '{{sell_info}}':
 * @property integer $id
 * @property integer $sell_id
 * @property string $status
 * @property integer $city_id
 * @property string $address
 * @property string $note
 */
class SellInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sell_info}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sell_id, city_id', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>9),
			array('address, note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sell_id, status, name, phone,city_id, address, note', 'safe', 'on'=>'search'),
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
                    'City' => array(self::BELONGS_TO, 'City', 'city_id'),
                    'Sell' => array(self::BELONGS_TO, 'Sell', 'sell_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sell_id' => 'Sell',
			'status' => 'Status',
			'city_id' => 'City',
			'address' => 'Address',
			'note' => 'Note',
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
		$criteria->compare('sell_id',$this->sell_id);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SellInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
}
