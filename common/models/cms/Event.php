<?php

/**
 * This is the model class for table "{{event}}".
 *
 * The followings are the available columns in table '{{event}}':
 * @property integer $id
 * @property string $title
 * @property string $date_event
 * @property string $image
 * @property string $content
 * @property string $created
 * @property integer $created_user_id
 * @property integer $modified
 */
class Event extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Event the static model class
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
		return '{{event}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_user_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>45),
			array('image', 'length', 'max'=>255),
			array('date_event, content, created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, date_event, image, content, created, created_user_id, modified', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'date_event' => 'Date Event',
			'image' => 'Image',
			'content' => 'Content',
			'created' => 'Created',
			'created_user_id' => 'Created User',
			'modified' => 'Modified',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('date_event',$this->date_event,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('modified',$this->modified);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
    
    public function getImg(){
        return app()->landa->urlImg('event/', $this->image, $this->id);
    }
}