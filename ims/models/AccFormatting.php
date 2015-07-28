<?php

/**
 * This is the model class for table "{{acc_formatting}}".
 *
 * The followings are the available columns in table '{{acc_formatting}}':
 * @property integer $id
 * @property integer $departement_id
 * @property string $cash_in
 * @property string $cash_in_approval
 * @property string $bank_in_approval
 * @property string $cash_out
 * @property string $cash_out_approval
 * @property string $bank_out_approval
 * @property string $journal
 * @property string $journal_approval
 */
class AccFormatting extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{acc_formatting}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('departement_id', 'numerical', 'integerOnly' => true),
            array('cash_in, cash_in_approval, bank_in_approval, cash_out, cash_out_approval, bank_out_approval, journal, journal_approval', 'length', 'max' => 11),
            array('departement_id, cash_in, cash_in_approval, bank_in_approval, cash_out, cash_out_approval, bank_out_approval, journal, journal_approval', 'required'),
            array('departement_id', 'unique'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, departement_id, cash_in, cash_in_approval, bank_in_approval, cash_out, cash_out_approval, bank_out_approval, journal, journal_approval', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Departement' => array(self::BELONGS_TO, 'Departement', 'departement_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'departement_id' => 'Departement',
            'cash_in' => 'Kas Masuk',
            'cash_in_approval' => 'Kas Masuk Approval',
            'bank_in_approval' => 'Bank Masuk Approval',
            'cash_out' => 'Kas Keluar',
            'cash_out_approval' => 'Kas Keluar Approval',
            'bank_out_approval' => 'Bank Keluar Approval',
            'journal' => 'Jurnal',
            'journal_approval' => 'Jurnal Approval',
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
        $criteria->compare('departement_id', $this->departement_id);
        $criteria->compare('cash_in', $this->cash_in, true);
        $criteria->compare('cash_in_approval', $this->cash_in_approval, true);
        $criteria->compare('bank_in_approval', $this->bank_in_approval, true);
        $criteria->compare('cash_out', $this->cash_out, true);
        $criteria->compare('cash_out_approval', $this->cash_out_approval, true);
        $criteria->compare('bank_out_approval', $this->bank_out_approval, true);
        $criteria->compare('journal', $this->journal, true);
        $criteria->compare('journal_approval', $this->journal_approval, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AccFormatting the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
