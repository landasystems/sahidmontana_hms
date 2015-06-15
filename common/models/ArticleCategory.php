<?php

/**
 * This is the model class for table "{{m_article_category}}".
 *
 * The followings are the available columns in table '{{m_article_category}}':
 * @property integer $id
 * @property string $name
 * @property integer $created_user_id
 * @property string $modified
 * @property string $created
 * @property string $lft
 * @property string $rgt
 *
 * The followings are the available model relations:
 * @property Article[] $Articles
 */
class ArticleCategory extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ArticleCategory the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{article_category}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('created_user_id, lft, rgt, parent_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 60),
            array('modified, created, lft, rgt, alias', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, created_user_id, modified, created', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Article' => array(self::HAS_MANY, 'Article', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'created_user_id' => 'Created Userid',
            'modified' => 'Modified',
            'created' => 'Created',
            'lft' => 'lft',
            'rgt' => 'rgt',
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
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('created', $this->created, true);

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

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

//    public function getArticleCategoryList() {
//        $t_data = $this->findAll(array('order' => 'root, lft'));
//        print_r($t_data);
//        $data = CHtml::listData($t_data, 'id', 'name');
//        return $data;
//    }

    public function getNestedName() {
        return ($this->level == 1) ? $this->name : str_repeat("|—", $this->level - 1) . $this->name;
    }

}

