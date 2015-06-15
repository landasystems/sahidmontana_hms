<?php

/**
 * This is the model class for table "{{kapal}}".
 *
 * The followings are the available columns in table '{{kapal}}':
 * @property integer $id
 * @property integer $paket_id
 * @property integer $hotel_id
 * @property integer $kamar_id
 * @property string $jadwal
 * @property string $harga
 */
class Kapal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{kapal}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('paket_id, hotel_id, kamar_id', 'numerical', 'integerOnly'=>true),
			array('jadwal, harga', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, paket_id, hotel_id, kamar_id, jadwal, harga', 'safe', 'on'=>'search'),
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
			'paket_id' => 'Paket',
			'hotel_id' => 'Hotel',
			'kamar_id' => 'Kamar',
			'jadwal' => 'Jadwal',
			'harga' => 'Harga',
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
		$criteria->compare('paket_id',$this->paket_id);
		$criteria->compare('hotel_id',$this->hotel_id);
		$criteria->compare('kamar_id',$this->kamar_id);
		$criteria->compare('jadwal',$this->jadwal,true);
		$criteria->compare('harga',$this->harga,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort' => array('defaultOrder' => 'id DESC')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Kapal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
