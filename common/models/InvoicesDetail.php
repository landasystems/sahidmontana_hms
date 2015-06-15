<?php

/**
 * This is the model class for table "{{invoices_detail}}".
 *
 * The followings are the available columns in table '{{invoices_detail}}':
 * @property integer $id
 * @property string $invoices_id
 * @property string $description
 * @property integer $price
 */
class InvoicesDetail extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InvoicesDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return CDbConnection database connection
	 */
//	public function getDbConnection()
//	{
//		return Yii::app()->db2;
//	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{invoices_detail}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('price', 'numerical', 'integerOnly'=>true),
			array('invoices_id', 'length', 'max'=>45),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, invoices_id, description, price', 'safe', 'on'=>'search'),
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
                    'Invoice' => array(self::HAS_MANY, 'Invoice', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'invoices_id' => 'Invoices',
			'description' => 'Description',
			'price' => 'Price',
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
		$criteria->compare('invoices_id',$this->invoices_id,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price',$this->price);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}