<?php

/**
 * This is the model class for table "{{hotel_trans}}".
 *
 * The followings are the available columns in table '{{hotel_trans}}':
 * @property integer $id
 * @property string $registration_id
 * @property string $created
 * @property integer $created_user_id
 * @property integer $total
 * @property integer $refund
 * @property string $barcode
 */
class HotelTrans extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return HotelTrans the static model class
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
		return '{{hotel_trans}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_user_id, total, refund', 'numerical', 'integerOnly'=>true),
			array('registration_id, barcode', 'length', 'max'=>45),
			array('created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, registration_id, created, created_user_id, total, refund, barcode', 'safe', 'on'=>'search'),
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
			'registration_id' => 'Registration',
			'created' => 'Created',
			'created_user_id' => 'Created User',
			'total' => 'Total',
			'refund' => 'Refund',
			'barcode' => 'Barcode',
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
		$criteria->compare('registration_id',$this->registration_id,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('total',$this->total);
		$criteria->compare('refund',$this->refund);
		$criteria->compare('barcode',$this->barcode,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'sort' => array('defaultOrder' => 't.id DESC')
		));
	}
}