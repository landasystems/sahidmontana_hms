<?php

/**
 * This is the model class for table "{{opname_detail}}".
 *
 * The followings are the available columns in table '{{opname_detail}}':
 * @property integer $id
 * @property integer $opname_id
 * @property integer $product_id
 * @property double $qty
 * @property double $qty_opname
 */
class OpnameDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{opname_detail}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('opname_id, product_id', 'numerical', 'integerOnly'=>true),
			array('qty, qty_opname', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, opname_id, product_id, qty, qty_opname', 'safe', 'on'=>'search'),
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
                    'Product' => array(self::BELONGS_TO, 'Product', 'product_id'),
                    'Opname' => array(self::BELONGS_TO, 'Opname', 'opname_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'opname_id' => 'Opname',
			'product_id' => 'Product',
			'qty' => 'Qty',
			'qty_opname' => 'Qty Opname',
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
		$criteria->compare('opname_id',$this->opname_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('qty_opname',$this->qty_opname);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OpnameDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
