<?php

/**
 * This is the model class for table "{{na_food_analys}}".
 *
 * The followings are the available columns in table '{{na_food_analys}}':
 * @property integer $id
 * @property integer $na_id
 * @property string $today
 * @property string $mtd_actual
 * @property string $mtd_forecast
 * @property string $mtd_last_month
 * @property string $ytd_actual
 * @property string $ytd_forecast
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class NaFoodAnalys extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{na_food_analys}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('na_id, today, mtd_actual, mtd_forecast, mtd_last_month, ytd_actual, ytd_forecast, created, created_user_id, modified', 'safe'),
			array('na_id, created_user_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, na_id, today, mtd_actual, mtd_forecast, mtd_last_month, ytd_actual, ytd_forecast, created, created_user_id, modified', 'safe', 'on'=>'search'),
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
                    'Na' => array(self::BELONGS_TO, 'Na', 'na_id'),
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
			'today' => 'Today',
			'mtd_actual' => 'Mtd Actual',
			'mtd_forecast' => 'Mtd Forecast',
			'mtd_last_month' => 'Mtd Last Month',
			'ytd_actual' => 'Ytd Actual',
			'ytd_forecast' => 'Ytd Forecast',
			'created' => 'Created',
			'created_user_id' => 'Created User',
			'modified' => 'Modified',
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
		$criteria->compare('today',$this->today,true);
		$criteria->compare('mtd_actual',$this->mtd_actual,true);
		$criteria->compare('mtd_forecast',$this->mtd_forecast,true);
		$criteria->compare('mtd_last_month',$this->mtd_last_month,true);
		$criteria->compare('ytd_actual',$this->ytd_actual,true);
		$criteria->compare('ytd_forecast',$this->ytd_forecast,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort' => array('defaultOrder' => 'id DESC')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NaFoodAnalys the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}












}
