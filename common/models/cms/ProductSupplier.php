<?php

/**
 * This is the model class for table "{{product_supplier}}".
 *
 * The followings are the available columns in table '{{product_supplier}}':
 * @property integer $id
 * @property integer $city_id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $fax
 * @property string $acc_number
 * @property string $acc_number_name
 * @property string $acc_bank
 */
class ProductSupplier extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductSupplier the static model class
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
		return '{{product_supplier}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, address, phone, email', 'required'),
			array('city_id', 'numerical', 'integerOnly'=>true),
			array('name, address, phone, fax', 'length', 'max'=>100),
			array('email', 'length', 'max'=>250),
			array('acc_number, acc_number_name, acc_bank', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, city_id, name, address, phone, email, fax, acc_number, acc_number_name, acc_bank', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'city_id' => 'City',
			'name' => 'Name',
			'address' => 'Address',
			'phone' => 'Phone',
                        'province_id' => 'Province',
			'email' => 'Email',
			'fax' => 'Fax',
			'acc_number' => 'Account Number',
			'acc_number_name' => 'Account Number Name',
			'acc_bank' => 'Account Bank',
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
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('acc_number',$this->acc_number,true);
		$criteria->compare('acc_number_name',$this->acc_number_name,true);
		$criteria->compare('acc_bank',$this->acc_bank,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}