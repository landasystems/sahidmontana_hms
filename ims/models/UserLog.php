<?php

/**
 * This is the model class for table "{{user_log}}".
 *
 * The followings are the available columns in table '{{user_log}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $created
 */
class UserLog extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id', 'numerical', 'integerOnly' => true),
            array('created', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, user_id, created', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'User' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'created' => 'Created',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('created', $this->created, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    protected function beforeValidate() {
        if (empty($this->user_id))
            $this->user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }
    
    public function getRoles(){
        $roles = (isset($this->User->Roles->name)) ? $this->User->Roles->name : 'Super User';
        return $roles;
    }
    
    public function getName(){
        $name = (isset($this->User->name)) ? $this->User->name : '-';
        return $name;
    }
    
    public function getTime(){
        $time = '<span class="label" style="margin-top: 6px;">' . landa()->ago($this->created) . '</span>';
        return $time;
    }

}