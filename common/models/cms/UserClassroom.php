<?php

/**
 * This is the model class for table "{{user_classroom}}".
 *
 * The followings are the available columns in table '{{user_classroom}}':
 * @property integer $id
 * @property integer $classroom_id
 * @property string $user_id
 */
class UserClassroom extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserClassroom the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_classroom}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('classroom_id', 'numerical', 'integerOnly'=>true),
			array('user_id', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, classroom_id, user_id', 'safe', 'on'=>'search'),
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
                    'User' => array(self::BELONGS_TO, 'User', 'user_id'),
                    'Classroom'=>array(self::BELONGS_TO,'Classroom','classroom_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'classroom_id' => 'Classroom',
			'user_id' => 'User',
		);
	}
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('classroom_id',$this->classroom_id);
		$criteria->compare('user_id',$this->user_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        public function getFieldClassroom_id(){
            $model = $this->find('user_id='. user()->id);
            if (empty($model)){
                $results = 0;
            }else{
                $results = $model->classroom_id;
            }
            return $results;
        }
        
        public function className($id){
            $model = $this->find(array('condition' => 'user_id="' . $id . '"'));
            if (empty($model)){
                $results = 0;
            }else{
                $results = $model->Classroom->name . ', ' . $model->Classroom->description ;
            }
            return $results;
        }
}