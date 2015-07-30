<?php

/**
 * This is the model class for table "{{acc_coa_det}}".
 *
 * The followings are the available columns in table '{{acc_coa_det}}':
 * @property integer $id
 * @property integer $acc_coa_id
 * @property string $date_coa
 * @property string $description
 * @property double $amount
 * @property double $amount_beginning
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 * @property string $reff_type
 * @property integer $reff_id
 */
class AccCoaDet extends CActiveRecord {

    public $sumDebet, $sumCredit, $sumBalance, $sumSaldo;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{acc_coa_det}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('acc_coa_id,invoice_det_id, created_user_id, reff_id', 'numerical', 'integerOnly' => true),
//            array('debet, credit', 'numerical'),
//            array('description', 'length', 'max' => 255),
            array('reff_type', 'length', 'max' => 50),
            array('date_coa,debet, balance, credit, created, modified, sumDebet, sumCredit', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, acc_coa_id,invoice_det_id, date_coa, description, debet, credit, balance, created, created_user_id, modified, reff_type, reff_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'AccCoa' => array(self::BELONGS_TO, 'AccCoa', 'acc_coa_id'),
            'InvoiceDet' => array(self::BELONGS_TO, 'InvoiceDet', 'invoice_det_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'acc_coa_id' => 'Acc Coa',
            'invoice_det_id' => 'Invoice Det',
            'date_coa' => 'Date Coa',
            'description' => 'Description',
            'debet' => 'Debet',
            'credit' => 'Credit',
            'balance' => 'Balance',
            'created' => 'Tanggal',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
            'reff_type' => 'Reff Type',
            'reff_id' => 'Reff',
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
        $criteria->compare('acc_coa_id', $this->acc_coa_id);
        $criteria->compare('invoice_det_id', $this->invoice_det_id);
        $criteria->compare('date_coa', $this->date_coa, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('balance', $this->balance);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('reff_type', $this->reff_type, true);
        $criteria->compare('reff_id', $this->reff_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AccCoaDet the static model class
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

    public function beginingBalance($date_coa, $id_coa, $saldoAkhir = true) {
        $tanda = ($saldoAkhir) ? '<=' : '<';


        $balance = $this->find(array('condition' => 'reff_type="balance" AND date_coa <= "' . $date_coa . '" AND acc_coa_id="' . $id_coa . '"', 'order' => 'date_coa DESC'));
        if (empty($balance)) { //jika balance tidak ada, jumlah kan semua trans sebelumnya2
            $model = $this->find(array('select' => 'sum(debet) as sumDebet, sum(credit) as sumCredit', 'condition' => 'date_coa ' . $tanda . ' "' . $date_coa . '" AND acc_coa_id="' . $id_coa . '"'));
        } elseif ($balance->date_coa == $date_coa) { //jika tanggal balance sama dengan filter, maka saldo balance yang ditampilkan
            return $balance->debet - $balance->credit;
        } else {
            $model = $this->find(array('select' => 'sum(debet) as sumDebet, sum(credit) as sumCredit', 'condition' => 'date_coa >= "' . $balance->date_coa . '" AND date_coa ' . $tanda . ' "' . $date_coa . '" AND acc_coa_id="' . $id_coa . '"'));
        }

        if (empty($model)) {
            $results = 0;
        } else {
            $results = $model->sumDebet - $model->sumCredit;
        }
        return $results;
    }

//    public function beginingBalance($date_coa, $id_coa, $thisDay = true) {
//        $siteConfig = SiteConfig::model()->listSiteConfig();
//
//        if ($thisDay) {
//            $model = $this->find(array('select' => 'sum(debet) as sumDebet, sum(credit) as sumCredit', 'condition' => 'date_coa <= "' . $date_coa . '" AND acc_coa_id="' . $id_coa . '"'));
//        } else {
//            $model = $this->find(array('select' => 'sum(debet) as sumDebet, sum(credit) as sumCredit', 'condition' => 'date_coa <"' . $date_coa . '" AND acc_coa_id="' . $id_coa . '"'));
//        }
//        if (empty($model)) {
//            $results = 0;
//        } else {
//            $results = $model->sumDebet - $model->sumCredit;
//        }
//        return $results;
//    }

    public function beginingBalanceSubLedger($date_coa, $id_coa) {
        $model = AccCoaSub::model()->find(array('condition' => 'date_coa <= "' . $date_coa . '" and acc_coa_id="' . $id_coa . '"', 'order' => 'date_coa DESC, id DESC'));
        if (empty($model)) {
            $results = 0;
        } else {
            $results = $model->balance;
        }
        return $results;
    }

    public function listProfitLoss($start, $end) {
        $results = $this->findAll(array('with' => 'AccCoa', 'index' => 'acc_coa_id', 'select' => 'acc_coa_id,sum(debet) as sumDebet, sum(credit) as sumCredit', 'group' => 'acc_coa_id', 'condition' => '(AccCoa.group="receivable" OR AccCoa.group="cost") AND date_coa >= "' . $start . '" AND date_coa <= "' . $end . '"', 'order' => 'acc_coa_id'));
        return $results;
    }

    public function totalSaldo($start, $end, $id_coa) {
        $model = $this->find(array('select' => 'sum(debet) as sumDebet, sum(credit) as sumCredit', 'condition' => 'reff_type<>"balance" AND (date_coa <= "' . $end . '" AND date_coa >= "' . $start . '") AND acc_coa_id="' . $id_coa . '"'));
        if (isset($model))
            $result = $model;
        else
            $result = (object) array('sumDebet' => 0, 'sumCredit' => 0);

        return $result;
    }

    public function sumDet($id, $start, $end) {
        $sumtotal = $this->findAll(array(
            'with' => 'AccCoa',
            'index' => 'acc_coa_id',
            'select' => 'sum(debet) as sumDebet, sum(credit) as sumCredit, (sum(debet)-sum(credit)) as saldo',
            'condition' => 'AccCoa.parent_id =' . $id . ' AND (AccCoa.group="receivable" OR AccCoa.group="cost") AND date_coa >= "' . $start . '" AND date_coa <= "' . $end . '"',
            'order' => 'date_coa DESC'
        ));
        if (isset($sumtotal))
            $result = $sumtotal;
        else
            $result = (object) array('sumDebet' => 0, 'sumCredit' => 0, 'saldo' => 0);


        return $result;
    }

//    public function balanceInvoice($id, $start) {
//        $balance = $this->findAll(array(
//            'with' => 'InvoiceDet',
//            'select' => '(sum(debet) - sum(credit)) as sumBalance',
//            'condition' => 'InvoiceDet.user_id=' . $id . ' AND t.date_coa < "' . date('Y-m-d', strtotime($start)) . '"',
//        ));
//
//        foreach ($balance as $a) {
//            return $a->sumBalance;
//        }
//    }

    public function total($end, $start, $id) {
        $sType = 'user_id=' . $id;
        $model = $this->with('InvoiceDet')->find(array('select' => 'sum(debet) as sumDebet, sum(credit) as sumCredit', 'condition' => '(date_coa <= "' . $end . '" AND date_coa >= "' . $start . '") AND ' . $sType));
        if (isset($model))
            $result = $model;
        else
            $result = (object) array('sumDebet' => 0, 'sumCredit' => 0);

        return $result;
    }

    public function saldoKartu($start, $id) {
        $sType = 'user_id=' . $id;
        $model = $this->with('InvoiceDet')->find(array(
            'select' => 'sum(debet) as sumDebet, sum(credit) as sumCredit',
            'condition' => $sType . ' AND date_coa <"' . date('Y-m-d', strtotime($start)) . '"',
            'order' => 'date_coa DESC'
        ));
        if (isset($model))
            $saldo = $model->sumDebet - $model->sumCredit;
        else
            $saldo = 0;
        
        return $saldo;
    }
    public function balanceInvoice($invoice_id) {
        $model = $this->find(array(
            'select' => 'sum(debet) as sumDebet, sum(credit) as sumCredit',
            'condition' => 'invoice_det_id =' . $invoice_id,
        ));
        if (isset($model))
            $saldo = $model->sumDebet - $model->sumCredit;
        else
            $saldo = 0;
        
        return ($saldo<0) ? $saldo * -1 : $saldo;
    }

}
