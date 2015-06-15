<?php

/**
 * This is the model class for table "{{saldo}}".
 *
 * The followings are the available columns in table '{{saldo}}':
 * @property integer $id
 * @property integer $to_user_id
 * @property string $type
 * @property integer $amount
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class Saldo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{saldo}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('to_user_id, amount, created_user_id', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>4),
			array('created, modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, to_user_id, type, amount, created, created_user_id, modified', 'safe', 'on'=>'search'),
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
                    'UserTo' => array(self::BELONGS_TO, 'User', 'to_user_id'),
                    'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'to_user_id' => 'To User',
			'type' => 'Type',
			'amount' => 'Amount',
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
		$criteria->compare('to_user_id',$this->to_user_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Saldo the static model class
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
        if (empty($this->created_user_id)) {
            $this->created_user_id = Yii::app()->user->id;
        }
        return parent::beforeValidate();
    }
    
    public function getCreate(){
        return $this->User->name;
    }
    
    public function getAmountSaldo(){
        return landa()->rp($this->amount);
    }
    
    public function getTanggal(){
        return date('d F Y', strtotime($this->created));
    }
    
    
    
    public function getTypeSaldo(){
        if($this->type == 'add'){
            echo'<span class="label label-info">Add</span>';
            
        }else{
            echo'<span class="label label-important">Less</span>';
        }
    }
}
