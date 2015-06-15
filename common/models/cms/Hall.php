<?php

/**
 * This is the model class for table "{{hall}}".
 *
 * The followings are the available columns in table '{{hall}}':
 * @property integer $id
 * @property integer $hall_category_id
 * @property string $name
 * @property integer $amount_player
 * @property integer $amount_player_max
 * @property integer $amount_viewer
 * @property double $amount_jackpot
 * @property integer $amount_played
 * @property double $min
 * @property double $max
 * @property string $chair0
 * @property string $chair1
 * @property string $chair2
 * @property string $chair3
 * @property string $chair4
 * @property string $chair5
 * @property string $chair6
 * @property string $chair7
 * @property string $chair8
 */
class Hall extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{hall}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hall_category_id, amount_player, amount_player_max, amount_viewer, amount_played', 'numerical', 'integerOnly'=>true),
			array('amount_jackpot, min, max, buta_kecil, buta_besar', 'numerical'),
			array('name', 'length', 'max'=>45),
//			array('chair0, chair1, chair2, chair3, chair4, chair5, chair6, chair7, chair8', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, hall_category_id, name, amount_player, amount_player_max, amount_viewer, amount_jackpot, amount_played, min, max, chair0, chair1, chair2, chair3, chair4, chair5, chair6, chair7, chair8', 'safe', 'on'=>'search'),
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
			'hall_category_id' => 'Hall Category',
			'name' => 'Name',
			'amount_player' => 'Amount Player',
			'amount_player_max' => 'Player Max',
			'amount_viewer' => 'Amount Viewer',
			'amount_jackpot' => 'Amount Jackpot',
			'amount_played' => 'Amount Played',
			'min' => 'Min',
			'max' => 'Max',
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
		$criteria->compare('hall_category_id',$this->hall_category_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('amount_player',$this->amount_player);
		$criteria->compare('amount_player_max',$this->amount_player_max);
		$criteria->compare('amount_viewer',$this->amount_viewer);
		$criteria->compare('amount_jackpot',$this->amount_jackpot);
		$criteria->compare('amount_played',$this->amount_played);
		$criteria->compare('min',$this->min);
		$criteria->compare('max',$this->max);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort' => array('defaultOrder' => 'id DESC')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Hall the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getCategory(){
            $result="";
            if($this->hall_category_id == 1){
                $result = 'Pemula';
            }elseif($this->hall_category_id == 2){
                $result = 'Dasar';
            }elseif($this->hall_category_id == 3){
                $result ='Lanjut';
            }elseif ($this->hall_category_id == 4) {
                $result = 'Ahli';
            }else{
                $result = 'Cepat';
            }
            return $result;
            
        }
        
}
