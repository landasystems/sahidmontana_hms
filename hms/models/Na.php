<?php

/**
 * This is the model class for table "{{na}}".
 *
 * The followings are the available columns in table '{{na}}':
 * @property integer $id
 * @property string $date_na
 * @property double $global_cash
 * @property double $global_cc
 * @property double $global_gl
 * @property double $global_cl
 * @property double $global_total
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class Na extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{na}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_user_id', 'numerical', 'integerOnly' => true),
            array('global_cash,rate_dollar, global_cc, global_gl, global_cl, global_total', 'numerical'),
            array('date_na, created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, date_na, global_cash, global_cc, global_gl, global_cl, global_total, created, created_user_id, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Cashier' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'Geographical' => array(self::HAS_MANY, 'ReportGeographical', 'na_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'date_na' => 'Date Na',
            'global_cash' => 'Global Cash',
            'global_cc' => 'Global Cc',
            'global_gl' => 'Global Gl',
            'global_cl' => 'Global Cl',
            'global_total' => 'Global Total',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('date_na', $this->date_na, true);
        $criteria->compare('global_cash', $this->global_cash);
        $criteria->compare('global_cc', $this->global_cc);
        $criteria->compare('global_gl', $this->global_gl);
        $criteria->compare('global_cl', $this->global_cl);
        $criteria->compare('global_total', $this->global_total);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Na the static model class
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
        );
    }

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

}
