<?php

/**
 * This is the model class for table "acc_cash_in".
 *
 * The followings are the available columns in table 'acc_cash_in':
 * @property integer $id
 * @property string $code
 * @property integer $acc_coa_id
 * @property string $description
 * @property integer $total
 * @property integer $is_acc_admin
 * @property integer $is_acc
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class AccCashIn extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{acc_cash_in}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('acc_coa_id, date_trans', 'required'),
            array('acc_coa_id, created_user_id', 'numerical', 'integerOnly' => true),
            array('code, code_acc, description, description_to, description_giro_an', 'length', 'max' => 255),
            array('created, modified, total, date_posting', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, code, acc_coa_id, description, total, date_trans, created, created_user_id, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'AccCasInDet' => array(self::HAS_MANY, 'AccCashInDet', 'id'),
            'AccCoa' => array(self::BELONGS_TO, 'AccCoa', 'acc_coa_id'),
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
            'code_acc' => 'No Posting',
            'acc_coa_id' => 'Acc Coa',
            'description' => 'Keterangan',
            'description_to' => 'Diterima Dari',
            'description_giro_an' => 'Giro A.N',
            'total' => 'Total',
            'date_trans' => 'Tgl Pembuatan',
            'date_posting' => 'Tgl Posting',
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
    public function search($export = false) {
        // @todo Please modify the following code to remove attributes that should not be searched.


        $criteria = new CDbCriteria;

        $criteria->compare('code', $this->code, true);
        $criteria->compare('code_acc', $this->code_acc, true);
        if (!empty($this->date_posting)) {
            $exDate = explode('-', $this->date_posting);
            $criteria->condition = 'date_posting >="' . date('Y-m-d', strtotime($exDate[0])) . '" AND date_posting <="' . date('Y-m-d', strtotime($exDate[1])) . '"';
        }
        if (!empty($this->acc_coa_id))
            $criteria->compare('acc_coa_id', $this->acc_coa_id);
        $criteria->compare('description', $this->description, true);
//        $criteria->compare('total', $this->total);

        if (user()->roles_id == -1) {
//            do nothing
        } elseif (isset(user()->roles['accesskb'])) {
            $idData = user()->roles['accesskb']->crud;
            $sWhere = json_decode($idData);
            $sWhere[] = 0;
            $criteria->addInCondition('acc_coa_id', $sWhere);
        } else {
            $criteria->compare('acc_coa_id', 0);
        }
        if ($export == false) {
            $data = new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => array('defaultOrder' => 'id DESC')
            ));
        } else {
            $data = AccCashIn::model()->findAll($criteria);
        }
        return $data;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AccCashIn the static model class
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

    protected function saldoAwal($start, $id_coa) {
        $model = $this->model()->findAll(array('condition' => 'date_trans<="' . $start . '" AND acc_coa_id=' . $id_coa));
        if (empty($model)) {
            $results = 0;
        } else {
            $results = $model->total;
        }
        return $results;
    }

}
