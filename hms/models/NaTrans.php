<?php

/**
 * This is the model class for table "{{na_trans}}".
 *
 * The followings are the available columns in table '{{na_trans}}':
 * @property integer $id
 * @property integer $na_id
 * @property integer $charge_additional_category_id
 * @property string $name
 * @property integer $room_id
 * @property string $by
 * @property double $by_cc
 * @property double $by_cl
 * @property double $by_gl
 * @property double $by_bank
 * @property double $by_cash
 * @property integer $cashier_user_id
 */
class NaTrans extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{na_trans}}';
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
			array('id, na_id, charge_additional_category_id, room_id, cashier_user_id', 'numerical', 'integerOnly'=>true),
			array('by_cc, by_cl, by_gl, by_bank, by_cash', 'numerical'),
			array('name', 'length', 'max'=>255),
			array('by', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, na_id, charge_additional_category_id, name, room_id, by, by_cc, by_cl, by_gl, by_bank, by_cash, cashier_user_id', 'safe', 'on'=>'search'),
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
			'charge_additional_category_id' => 'Charge Additional Category',
			'name' => 'Name',
			'room_id' => 'Room',
			'by' => 'By',
			'by_cc' => 'By Cc',
			'by_cl' => 'By Cl',
			'by_gl' => 'By Gl',
			'by_bank' => 'By Bank',
			'by_cash' => 'By Cash',
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
		$criteria->compare('charge_additional_category_id',$this->charge_additional_category_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('room_id',$this->room_id);
		$criteria->compare('by',$this->by,true);
		$criteria->compare('by_cc',$this->by_cc);
		$criteria->compare('by_cl',$this->by_cl);
		$criteria->compare('by_gl',$this->by_gl);
		$criteria->compare('by_bank',$this->by_bank);
		$criteria->compare('by_cash',$this->by_cash);
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
	 * @return NaTrans the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
