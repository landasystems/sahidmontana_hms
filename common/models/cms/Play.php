<?php

/**
 * This is the model class for table "{{play}}".
 *
 * The followings are the available columns in table '{{play}}':
 * @property integer $id
 * @property integer $play_result_id
 * @property string $number
 * @property string $description
 * @property string $output
 * @property string $type
 * @property string $status
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class Play extends CActiveRecord
{
        public $sum_amount;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{play}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('play_result_id, created_user_id', 'numerical', 'integerOnly'=>true),
			array('number, status', 'length', 'max'=>4),
			array('description', 'length', 'max'=>255),
			array('output', 'length', 'max'=>3),
			array('type', 'length', 'max'=>20),
			array('created, modified, sum_amount', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, play_result_id, number, description, output, type, status, created, created_user_id, modified', 'safe', 'on'=>'search'),
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
                    'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
                    'PlayResult' => array(self::BELONGS_TO, 'PlayResult', 'play_result_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'play_result_id' => 'Play Result',
			'number' => 'Number',
			'description' => 'Description',
			'output' => 'Output',
			'type' => 'Type',
			'status' => 'Status',
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
		$criteria->compare('play_result_id',$this->play_result_id);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('output',$this->output,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('created_user_id',$this->created_user_id);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'  =>array('defaultOrder'=>'id Desc')
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Play the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        public function behaviors() {
        return array(
            'timestamps' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }
    
    public function getAmountUser(){
        return landa()->rp($this->amount);
    }
    
    public function getStatusUser(){
        if($this->status == 'win'){
            return '<span class="label label-success">Menang</span>';
        }elseif($this->status == 'lose'){
           return '<span class="label label-danger">Kalah</span>';
        }else{
            return '<span class="label label-warning">Proses</span>';
        }
    }
    
    public function getPlayRes(){
        if(isset($this->PlayResult->number)){
            $this->PlayResult->number;
        }else{
            echo'Number is not yet out.';
        }
    }
    
    public function type() {
        $result = array();
        $result['2d'] = '2D';
        $result['3d'] = '3D';
        $result['4d'] = '4D';
        $result['cj_satuan'] = 'Colok Jitu Satuan';
        $result['cj_puluhan'] = 'Colok Jitu Puluhan';
        $result['cj_ratusan'] = 'Colok Jitu Ratusan';
        $result['cj_ribuan'] = 'Colok Jitu Ribuan';
        $result['cr'] = 'Colok Raun';
      
        return $result;
    }
    
    public function output() {
        $result = array();
        $result['s'] = 'Singapore';
        $result['h'] = 'Hongkong';
        return $result;
    }
    
    public function status() {
        $result = array();
        $result['lose'] = 'Kalah';
        $result['win'] = 'Menang';
        return $result;
    }
}
