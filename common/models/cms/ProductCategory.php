<?php

/**
 * This is the model class for table "{{product_category}}".
 *
 * The followings are the available columns in table '{{product_category}}':
 * @property integer $id
 * @property string $name
 * @property integer $created_user_id
 * @property string $modified
 * @property string $created
 * @property integer $level
 * @property integer $lft
 * @property integer $rgt
 * @property integer $root
 * @property integer $parent_id
 */
class ProductCategory extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductCategory the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{product_category}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_user_id, level, lft, rgt, root, parent_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 60),
            array('modified, created', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, created_user_id, modified, created, level, lft, rgt, root, parent_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Product' => array(self::HAS_MANY, 'Product', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
            'created' => 'Created',
            'level' => 'Level',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'root' => 'Root',
            'parent_id' => 'Parent',
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
        $criteria->compare('level', $this->level);
        $criteria->compare('lft', $this->lft);
        $criteria->compare('rgt', $this->rgt);
        $criteria->compare('root', $this->root);
        $criteria->compare('parent_id', $this->parent_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'root, lft',
        )));
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

    public function getUrl() {
        return url('category/' . $this->id . '/' . $this->alias);
    }

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

    public function listProductCategory($product_category_id = 0) {
        if (!app()->session['listProductCategory']) {
            if ($product_category_id == 0) {
                app()->session['listProductCategory'] = $this->findAll(array('order' => 'root, lft'));
            } else {
                $category = $this->findByPk($product_category_id);
                app()->session['listProductCategory'] = $category->descendants()->findAll();

//            app()->session['listProductCategory'] = $this->findAll(array('order' => 'root, lft'));
            }
        }
        return app()->session['listProductCategory'];
    }

}