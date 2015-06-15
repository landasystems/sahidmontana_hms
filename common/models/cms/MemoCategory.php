<?php

/**
 * This is the model class for table "{{ticket_category}}".
 *
 * The followings are the available columns in table '{{ticket_category}}':
 * @property integer $id
 * @property string $name
 * @property string $created
 * @property string $modified
 * @property integer $level
 * @property integer $lft
 * @property integer $rgt
 * @property integer $root
 */
class MemoCategory extends CActiveRecord {

//    public function getDbConnection() {
//        return Yii::app()->db2;
//    }
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TicketCategory the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{ticket_category}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('level, lft, rgt, root', 'numerical', 'integerOnly' => true),
            array('name, created, modified', 'length', 'max' => 45),
            array('parent_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, created, modified, level, lft, rgt, root, parent_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Ticket' => array(self::HAS_MANY, 'Memo', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'created' => 'Created',
            'modified' => 'Modified',
            'level' => 'Level',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'root' => 'Root',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('level', $this->level);
        $criteria->compare('lft', $this->lft);
        $criteria->compare('rgt', $this->rgt);
        $criteria->compare('root', $this->root);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'root, lft',
            ),
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
            'nestedSetBehavior' => array(
                'class' => 'common.extensions.NestedSetBehavior.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'hasManyRoots' => true,
            ),
        );
    }

    public function getNestedName() {
        return ($this->level == 1) ? $this->name : str_repeat("|—", $this->level - 1) . $this->name;
    }

}
