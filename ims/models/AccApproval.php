<?php

/**
 * This is the model class for table "{{acc_approval}}".
 *
 * The followings are the available columns in table '{{acc_approval}}':
 * @property integer $id
 * @property string $status
 * @property string $description
 * @property integer $acc_cash_in_id
 * @property integer $acc_cash_out_id
 * @property integer $acc_jurnal_id
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class AccApproval extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{acc_approval}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('acc_cash_in_id, acc_cash_out_id, acc_jurnal_id, created_user_id', 'numerical', 'integerOnly' => true),
            array('status', 'length', 'max' => 7),
            array('description', 'length', 'max' => 255),
            array('created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, status, description, acc_cash_in_id, acc_cash_out_id, acc_jurnal_id, created, created_user_id, modified', 'safe', 'on' => 'search'),
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
            'status' => 'Status',
            'description' => 'Description',
            'acc_cash_in_id' => 'Acc Cash In',
            'acc_cash_out_id' => 'Acc Cash Out',
            'acc_jurnal_id' => 'Acc Jurnal',
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
        $criteria->compare('status', $this->status, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('acc_cash_in_id', $this->acc_cash_in_id);
        $criteria->compare('acc_cash_out_id', $this->acc_cash_out_id);
        $criteria->compare('acc_jurnal_id', $this->acc_jurnal_id);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AccApproval the static model class
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

    public function getStatus($id) {
        if (!empty($id)) {
            $status = $this->findByPk($id);
            if ($status->status == "open") {
                echo '<span class="label">Open</span>';
            } elseif ($status->status == "pending") {
                echo '<span class="label label-info">Pending</span>';
            } elseif ($status->status == "reject") {
                echo '<span class="label label-important">Reject</span>';
            } elseif ($status->status == "confirm") {
                echo '<span class="label label-success">Confirm</span>';
            }
        } else {
            echo '<span class="label">Open</span>';
        }
    }

    public function saveApproval($admin, $manager, $getmodel) {
        $approve = new AccApproval;
        $model = new $getmodel->table;
        $field = (strtolower($getmodel->field));

        $model = $model->findByPk($getmodel->id);

        if (!empty($admin)) {
            $approve->status = $admin->status;
            $approve->description = $admin->description;
            $approve->$field = $getmodel->id;
            $approve->save();
            $model->acc_approval_admin_id = $approve->id;
        } elseif (!empty($manager)) {
            $approve->status = $manager->status;
            $approve->description = $manager->description;
            $approve->$field = $getmodel->id;
            $approve->save();
            $model->acc_approval_id = $approve->id;
            if ($manager->status == "reject") {
                $idAdmin = $approve->findByPk($model->acc_approval_admin_id);
                $idAdmin->status = "open";
                $idAdmin->save();
            }
        }
        $model->save();
    }

}
