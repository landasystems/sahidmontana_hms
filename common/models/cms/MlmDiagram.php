<?php

/**
 * This is the model class for table "{{mlm_diagram}}".
 *
 * The followings are the available columns in table '{{mlm_diagram}}':
 * @property integer $id
 * @property string $name
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 * @property integer $level
 * @property integer $lft
 * @property integer $rgt
 * @property integer $root
 * @property integer $parent_id
 * @property string $parent_ids
 * @property string $position
 */
class MlmDiagram extends CActiveRecord {

//    public $name;
//    public $link;
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{mlm_diagram}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_user_id, level, lft, rgt, root, parent_id', 'numerical', 'integerOnly' => true),
            array('', 'length', 'max' => 60),
            array('position', 'length', 'max' => 100),
            array('created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, created, created_user_id, modified, level, lft, rgt, root, parent_id, position', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
            'level' => 'Level',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'root' => 'Root',
            'parent_id' => 'Parent',
            'parent_ids' => 'Parent Ids',
            'position' => 'Position',
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
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('level', $this->level);
        $criteria->compare('lft', $this->lft);
        $criteria->compare('rgt', $this->rgt);
        $criteria->compare('root', $this->root);
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('parent_ids', $this->parent_ids, true);
        $criteria->compare('position', $this->position, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MlmDiagram the static model class
     */
    public static function model($className = __CLASS__) {
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

    public function create($user, $reff_id = null) {
        $arrOthers = json_decode($user->others, true);

        $oMlmDiagram = new MlmDiagram();
        $oMlmDiagram->created_user_id = $user->id;
        if (empty($reff_id)) { //tidak ada referensial, taruh di root
            $oMlmDiagram->saveNode();
        } else { // ada referensi, pengecekan penempatan kaki
            //pengecekan record referal apakah sudah ada di tabel diagram 
            $oReff = MlmDiagram::model()->find(array('condition' => 'created_user_id=' . $reff_id));
            if (empty($oReff)) {
                $oMlmDiagramReff = new MlmDiagram();
                $oMlmDiagramReff->created_user_id = $reff_id;
                $oMlmDiagramReff->saveNode();
            }

            $childParam = MlmDiagram::model()->findAll(array('condition' => 'created_user_id=' . $reff_id));
            $this->algorithm($childParam, $user);
        };
    }

    public function checkChild($mlm, $user, $mlmReff) {
        if (count($mlm) == 0) { //jika belum punya kaki letakkan di kiri
            $mlmToSave = new MlmDiagram();
            $mlmToSave->created_user_id = $user->id;
            $mlmToSave->position = 'left';
            $mlmToSave->parent_id = $mlmReff->id;
            $mlmToSave->appendTo($mlmReff);
            return true;
        } elseif (count($mlm) == 1) { //jika sudah punya satu kaki, berarti diletakkan di kanan            
            $mlmToSave = new MlmDiagram();
            $mlmToSave->created_user_id = $user->id;
            $mlmToSave->position = 'right';
            $mlmToSave->parent_id = $mlmReff->id;
            $mlmToSave->appendTo($mlmReff);
            return true;
        } else {
            return false;
        }
    }

    public function algorithm($child, $user) {
        $statusExit = false;
        $childParam = array();
        foreach ($child as $o) {

            $mlmReff = MlmDiagram::model()->find(array('condition' => 'created_user_id=' . $o->created_user_id));
            $mlm = $mlmReff->children()->findAll();

            //memberi nilai pada anak selanjutnya
            foreach ($mlm as $oMlm) {
                $childParam[] = (object) array('created_user_id'=>$oMlm->created_user_id);
            }

            $statusExit = $this->checkChild($mlm, $user, $mlmReff);
            if ($statusExit == 1)
                break;
        }

//        logs('bb');
        if ($statusExit == 0) {
//            $childParam = MlmDiagram::model()->findAll(array('condition' => 'created_user_id IN (' . implode(',', $pieces) . ')'));
//            logs('aaaaa');
//            print_r((object)$childParam);
            $this->algorithm((object)$childParam, $user);
        }
    }

    public function getName() {
        if (isset($this->User->code)) {
            return '<b>' . $this->User->code . '</b><br/>' . $this->User->name . '<br/>' . landa()->hp($this->User->phone);
        } else {
            return '';
        }
    }

    public function getLink() {
        return '#';
    }

}
