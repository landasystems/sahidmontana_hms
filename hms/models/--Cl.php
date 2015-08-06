<?php

/**
 * This is the model class for table "{{cl}}".
 *
 * The followings are the available columns in table '{{cl}}':
 * @property integer $id
 * @property integer $na_id
 * @property string $name
 * @property integer $cl_user_id
 * @property double $charge
 * @property integer $cashier_user_id
 */
class Cl extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{cl}}';
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
			array('id, na_id, cl_user_id, cashier_user_id', 'numerical', 'integerOnly'=>true),
			array('charge', 'numerical'),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, na_id, name, cl_user_id, charge, cashier_user_id', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'cl_user_id' => 'Cl User',
			'charge' => 'Charge',
			'cashier_user_id' => 'Cashier User',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('cl_user_id',$this->cl_user_id);
		$criteria->compare('charge',$this->charge);
		$criteria->compare('cashier_user_id',$this->cashier_user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'sort' => array('defaultOrder' => 't.id DESC')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
