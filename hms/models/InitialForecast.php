<?php

/**
 * This is the model class for table "{{initial_forecast}}".
 *
 * The followings are the available columns in table '{{initial_forecast}}':
 * @property integer $id
 * @property string $dsr
 * @property string $statistik
 * @property string $food_analysis
 * @property string $food_percover
 * @property string $beverage_analysis
 */
class InitialForecast extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{initial_forecast}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id,dsr, statistik, food_analysis, food_percover, beverage_analysis', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dsr, statistik, food_analysis, food_percover, beverage_analysis', 'safe', 'on'=>'search'),
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
			'dsr' => 'Dsr',
			'statistik' => 'Statistik',
			'food_analysis' => 'Food Analysis',
			'food_percover' => 'Food Percover',
			'beverage_analysis' => 'Beverage Analysis',
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
		$criteria->compare('dsr',$this->dsr,true);
		$criteria->compare('statistik',$this->statistik,true);
		$criteria->compare('food_analysis',$this->food_analysis,true);
		$criteria->compare('food_percover',$this->food_percover,true);
		$criteria->compare('beverage_analysis',$this->beverage_analysis,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort' => array('defaultOrder' => 'id DESC')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InitialForecast the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
