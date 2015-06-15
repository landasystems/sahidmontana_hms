<?php

/**
 * This is the model class for table "{{na_dp}}".
 *
 * The followings are the available columns in table '{{na_dp}}':
 * @property integer $id
 * @property integer $na_id
 * @property string $name
 * @property double $by_cash
 * @property double $by_cc
 * @property double $by_bank
 * @property double $by_gl
 * @property double $by_cl
 */
class NaDp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{na_dp}}';
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
			array('id, na_id', 'numerical', 'integerOnly'=>true),
			array('by_cash, by_cc, by_bank, by_gl, by_cl', 'numerical'),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, na_id, name, by_cash, by_cc, by_bank, by_gl, by_cl', 'safe', 'on'=>'search'),
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
			'by_cash' => 'By Cash',
			'by_cc' => 'By Cc',
			'by_bank' => 'By Bank',
			'by_gl' => 'By Gl',
			'by_cl' => 'By Cl',
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
		$criteria->compare('by_cash',$this->by_cash);
		$criteria->compare('by_cc',$this->by_cc);
		$criteria->compare('by_bank',$this->by_bank);
		$criteria->compare('by_gl',$this->by_gl);
		$criteria->compare('by_cl',$this->by_cl);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'sort' => array('defaultOrder' => 't.id DESC')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NaDp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
