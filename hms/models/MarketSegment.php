<?php

/**
 * This is the model class for table "{{market_segment}}".
 *
 * The followings are the available columns in table '{{market_segment}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $level
 * @property integer $lft
 * @property integer $rgt
 * @property string $root
 * @property integer $parent_id
 */
class MarketSegment extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{market_segment}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('level, lft, rgt, parent_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('root', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, description, level, lft, rgt, root, parent_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Note',
            'level' => 'Level',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'root' => 'Root',
            'parent_id' => 'Parent',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('level', $this->level);
        $criteria->compare('lft', $this->lft);
        $criteria->compare('rgt', $this->rgt);
        $criteria->compare('root', $this->root, true);
        $criteria->compare('parent_id', $this->parent_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'root,lft',),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MarketSegment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function behaviors() {
        return array(
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
        if ($this->level == 1) {
            return '<b>' . $this->name . '</b>';
        } else {
            return str_repeat("|— ", $this->level - 1) . $this->name;
        }
    }

    public function getNestedNameSob() {
        if ($this->level == 1) {
            return '<b>' . $this->name . '</b>';
        } else {
            return ($this->level <= 2) ? '&nbsp;&nbsp;&nbsp;' . $this->name : str_repeat("&nbsp;&nbsp;&nbsp;|— ", $this->level - 2) . $this->name;
        }
    }

    public function Select2() {
        $all = MarketSegment::model()->findAll(array('condition' => 'level=1'));
        $array = array();
        foreach ($all as $ms) {
            $child = MarketSegment::model()->findAll(array('condition' => 'root=' . $ms->id . ' and id !=' . $ms->id));
            foreach ($child as $anak) {
                $array[$ms->name][$anak->id] = $anak->nestedName;
            }
        }
        return $array;
    }

}
