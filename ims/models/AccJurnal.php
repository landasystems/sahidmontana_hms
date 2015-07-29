<?php

/**
 * This is the model class for table "{{acc_jurnal}}".
 *
 * The followings are the available columns in table '{{acc_jurnal}}':
 * @property integer $id
 * @property string $code
 * @property string $date_trans
 * @property string $description
 * @property double $total_debet
 * @property double $total_credit
 * @property integer $acc_admin_user_id
 * @property integer $acc_user_id
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class AccJurnal extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{acc_jurnal}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('date_trans', 'required'),
            array('created_user_id', 'numerical', 'integerOnly' => true),
            array('total_debet, total_credit', 'numerical'),
            array('code, code_acc, description', 'length', 'max' => 255),
            array('date_trans,total_debet,total_credit, created, modified, date_posting', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, code, date_trans, description, total_debet, total_credit, created, created_user_id, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'AccCoaDet' => array(self::HAS_MANY, 'AccCoaDet', 'id'),
            'AccJurnalDet' => array(self::HAS_MANY, 'AccJurnalDet', 'id'),
            'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'AccAdmin' => array(self::BELONGS_TO, 'AccApproval', 'acc_approval_admin_id'),
            'AccManager' => array(self::BELONGS_TO, 'AccApproval', 'acc_approval_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'code' => 'No Transaksi',
            'code_acc' => 'No Approval',
            'date_trans' => 'Tgl Pembuatan',
            'date_posting' => 'Tgl Posting',
            'description' => 'Keterangan',
            'total_debet' => 'Total Debet',
            'total_credit' => 'Total Credit',
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
    public function search($export=false) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('code', $this->code, true);
        $criteria->compare('code_acc', $this->code_acc, true);
        if(!empty($this->date_posting)){
            $exDate = explode('-', $this->date_posting);
            $criteria->addCondition('date_posting >="'.date('Y-m-d', strtotime($exDate[0])).'" AND date_posting <="'.date('Y-m-d', strtotime($exDate[1])).'"');
        }
        $criteria->compare('description', $this->description, true);
//        $criteria->compare('total_debet', $this->total_debet);
//        $criteria->compare('total_credit', $this->total_credit);
        
        if ($export == false) {
            $data = new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => array('defaultOrder' => 'id DESC')
            ));
        } else {
            $data = AccJurnal::model()->findAll($criteria);
        }
        return $data;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AccJurnal the static model class
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
