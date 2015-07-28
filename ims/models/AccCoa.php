<?php

/**
 * This is the model class for table "{{acc_coa}}".
 *
 * The followings are the available columns in table '{{acc_coa}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $created_user_id
 * @property string $modified
 * @property string $created
 * @property integer $level
 * @property integer $lft
 * @property integer $rgt
 * @property integer $root
 * @property integer $parent_id
 */
class AccCoa extends CActiveRecord {
    public $filee;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{acc_coa}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, created_user_id, level, lft, rgt, root, parent_id', 'numerical', 'integerOnly' => true),
            array('code, type_sub_ledger', 'length', 'max' => 15),
            array('code', 'unique'),
            array('name', 'length', 'max' => 100),
            array('description', 'length', 'max' => 255),
            array('modified, created, group', 'safe'),
//            array('filee','file','types'=>'xls'),
            array('type, code, name', 'required'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, code, name, group, description, created_user_id, modified, created, level, lft, rgt, root, parent_id', 'safe', 'on' => array('search', 'importExcel')),
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
            'AccCashIn' => array(self::HAS_MANY, 'AccCashIn', 'id'),
            'AccCashOut' => array(self::HAS_MANY, 'AccCashOut', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'code' => 'Kode Rekening',
            'name' => 'Nama Rekening',
            'description' => 'Description',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
            'created' => 'Tanggal',
            'level' => 'Level',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'root' => 'Root',
            'parent_id' => 'Parent',
            'type' => 'Type',
            'type_sub_ledger' => 'Type Sub Ledger',
            'group' => 'Group',
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
        $criteria->compare('code', $this->code, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
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
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AccCoa the static model class
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

    public function checkAccess() {
        throw new CHttpException(400, 'Anda tidak mempunyai Akses');
    }

    public function getSpaceName() {
        if ($this->type == 'general')
            $results = '<b>' . $this->code . ' - ' . $this->name . '</b>';
        else
            $results = $this->code . ' - ' . $this->name;

        return ($this->level == 1) ? $results : str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $this->level - 1) . $results;
    }

    public function getNestedName() {
        $results = ($this->level == 1) ? $this->code . ' - ' . $this->name : str_repeat("|--", $this->level - 1) . $this->code . ' - ' . $this->name;

        if ($this->type == 'general')
            $results = '<b>' . $results . '</b>';

        return $results;
    }

    public function getUrl() {
        return url('category/' . $this->id . '/' . $this->alias);
    }

    /*
     * $debet = array('id'=>value,'code'=>value, 'date_coa'=>value, 'description'=>value, 'total'=>value, 'acc_coa_id'=>value);
     * $credit = array('id'=>value,'code'=>value, 'date_coa'=>value, 'description'=>value, 'total'=>value, 'acc_coa_id'=>value);
     */

    public function trans($debet, $credit) {
//        t($credit);
//        $inDebet = array();
//        $inCredit = array();
        foreach ($debet as $valDebet) {
//            $lastBalance = AccCoaDet::model()->beginingBalance($valDebet->date_trans, $valDebet->acc_coa_id);
            $model = new AccCoaDet;
            $model->acc_coa_id = $valDebet->acc_coa_id;
            $model->invoice_det_id = $valDebet->invoice_det_id;
            $model->date_coa = $valDebet->date_trans;
            $model->code = $valDebet->code;
            $model->description = $valDebet->description;
            $model->debet = $valDebet->total;
            $model->credit = 0;
//            $model->balance = $lastBalance + $valDebet->total;
            $model->reff_id = $valDebet->id;
            $model->reff_type = $valDebet->reff_type;
            $model->save();
//            $inDebet[] = (object) array("balance" => $model->balance, "date_trans" => $model->date_coa, "acc_coa_id" => $model->acc_coa_id);
        }
        foreach ($credit as $valCredit) {
//            $lastBalance = AccCoaDet::model()->beginingBalance($valCredit->date_trans, $valCredit->acc_coa_id);
            $model = new AccCoaDet;
            $model->acc_coa_id = $valCredit->acc_coa_id;
            $model->invoice_det_id = $valCredit->invoice_det_id;
            $model->date_coa = $valCredit->date_trans;
            $model->code = $valCredit->code;
            $model->description = $valCredit->description;
            $model->debet = 0;
            $model->credit = $valCredit->total;
//            $model->balance = $lastBalance - $valCredit->total;
            $model->reff_id = $valCredit->id;
            $model->reff_type = $valCredit->reff_type;
            $model->save();
//            $inCredit[] = (object) array("balance" => $model->balance, "date_trans" => $model->date_coa, "acc_coa_id" => $model->acc_coa_id);
        }

//        AccCoaDet::model()->updateAfter($inDebet);
//        AccCoaDet::model()->updateAfter($inCredit);
    }

    public function transLedger($debet = array(), $credit = array()) {
        $subDebet = array();
        $subCredit = array();

        foreach ($debet as $valDebet) {
//            $lastBalance = AccCoaDet::model()->beginingBalanceSubLedger($valDebet->date_trans, $valDebet->acc_coa_id);
            $model = new AccCoaSub;
            $model->acc_coa_id = $valDebet->acc_coa_id;
            $model->date_coa = $valDebet->date_trans;
            $model->code = $valDebet->code;
            $model->description = $valDebet->description;
            $model->credit = 0;
            $model->debet = $valDebet->debet;
//            $model->balance = $lastBalance + $valDebet->debet;
            $model->reff_id = $valDebet->id;
            $model->reff_type = $valDebet->reff_type;
            $model->ar_id = $valDebet->ar;
            $model->as_id = $valDebet->as;
            $model->ap_id = $valDebet->ap;
            $model->save();
            $subDebet[] = (object) array("ar_id" => $model->ar_id, "as_id" => $model->as_id, "ap_id" => $model->ap_id, "balance" => $model->balance, "date_trans" => $model->date_coa, "acc_coa_id" => $model->acc_coa_id);
        }foreach ($credit as $valCredit) {
//            $lastBalance = AccCoaDet::model()->beginingBalanceSubLedger($valCredit->date_trans, $valCredit->acc_coa_id);
            $model = new AccCoaSub;
            $model->acc_coa_id = $valCredit->acc_coa_id;
            $model->date_coa = $valCredit->date_trans;
            $model->code = $valCredit->code;
            $model->description = $valCredit->description;
            $model->debet = 0;
            $model->credit = $valCredit->credit;
//            $model->balance = $lastBalance - $valCredit->credit;
            $model->reff_id = $valCredit->id;
            $model->reff_type = $valCredit->reff_type;
            $model->ar_id = $valCredit->ar;
            $model->as_id = $valCredit->as;
            $model->ap_id = $valCredit->ap;
            $model->save();
            $subCredit[] = (object) array("ar_id" => $model->ar_id, "as_id" => $model->as_id, "ap_id" => $model->ap_id, "balance" => $model->balance, "date_trans" => $model->date_coa, "acc_coa_id" => $model->acc_coa_id);
        }

//        if (!empty($subDebet))
//            AccCoaDet::model()->updateAfterSubLedger($subDebet);
//        if (!empty($subCredit))
//            AccCoaDet::model()->updateAfterSubLedger($subCredit);
    }

    public function angkaTerbilang($number) {
        if (!is_numeric($number))
            return $number;
        else
            $string = '';
        $angka = array('', ' satu', ' dua', ' tiga', ' empat', ' lima', ' enam', ' tujuh', ' delapan', ' sembilan');
        $level = array('', ' ribu', ' juta', ' milyar', ' trilyun', ' bilyun', ' quartilyun', ' quintilyun');
        $number = strrev($number);
        for ($i = 0; $i < intval(ceil(strlen($number) / 3)); $i++) {
            $char = substr($number, $i * 3, 3);
            if ($char == 0)
                continue;
            elseif ((strrev($char) == 1) && ($i == 1))
                $string = ' seribu' . $string;
            else {
                $string = $level[$i] . $string;
                $satu = intval(substr($char, 0, 1));
                $dua = intval(substr($char, 1, 1));
                $tiga = intval(substr($char, 2, 1));
                if (($dua == 1) && ($satu == 0))
                    $string = ' sepuluh' . $string;
                elseif (($dua == 1) && ($satu == 1))
                    $string = ' sebelas' . $string;
                elseif ($dua == 1)
                    $string = $angka[$satu] . ' belas' . $string;
                else {
                    $string = $angka[$satu] . $string;
                    if ($dua > 1)
                        $string = $angka[$dua] . ' puluh' . $string;
                }
                if ($tiga == 1)
                    $string = ' seratus' . $string;
                elseif ($tiga > 1)
                    $string = $angka[$tiga] . ' ratus' . $string;
            }
        }
        return ucwords($string) . " rupiah";
    }

    public function typeSub() {
        return array("ks" => "Cash", "bk" => "Bank", "ar" => "Receivable", "as" => "Stock", "ap" => "Payable");
    }

    public function getSubLedger($subLedger) {
        if ($subLedger == "ks") {
            $val = "Cash";
        } else if ($subLedger == "bk") {
            $val = "Bank";
        } else if ($subLedger == "ar") {
            $val = "Receivable";
        } else if ($subLedger == "as") {
            $val = "Stock";
        } else if ($subLedger == "ap") {
            $val = "Payable";
        } else {
            $val = "-";
        }

        echo $val;
    }

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

    public function maxLevel() {
        $m = AccCoa::model()->find(array('order' => 'level DESC'));
        if (empty($m)) {
            return array(1 => '1');
        } else {
            $result = array();
            for ($i = 1; $i <= $m->level; $i++) {
                $result[$i] = $i;
            }
            return $result;
        }
    }

    public function accessCoa() {
        if (user()->roles_id == -1) {
            $sWhere = 'type_sub_ledger="ks" OR type_sub_ledger="bk"';
        } elseif (isset(user()->roles['accesskb'])) {
            $idData = user()->roles['accesskb']->crud;
            $sWhere = 'id IN (' . implode(',', json_decode($idData)) . ')';
        } elseif (empty(user()->roles['crud'])) {
            $sWhere = '';
        } else {
            $sWhere = 'id=0';
        }

        return $sWhere;
    }

}
