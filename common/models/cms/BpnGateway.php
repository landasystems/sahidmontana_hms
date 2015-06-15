<?php

/**
 * This is the model class for table "{{buku}}".
 *
 * The followings are the available columns in table '{{buku}}':
 * @property integer $id
 * @property string $doc_year
 * @property string $name
 * @property string $type
 * @property string $status_document
 * @property string $status_check
 * @property string $barcode
 */
class BpnGateway extends CActiveRecord {

    

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BpnDocument the static model class
     */
    public function getDbConnection() {
        return Yii::app()->db3;
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{gateway}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('BERKASYEAR, NAMA, PROSEDURDESC, KET, BERKASFLOTANGGAL, BERKASNOMOR', 'required'),
            array('BERKASYEAR', 'length', 'max' => 4),
            array('NAMA, keterangan', 'length', 'max' => 255),
            array('BERKASNOMOR', 'length', 'max' => 5),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('BERKASYEAR, NAMA, BERKASNOMNOR', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'BpnProfil' => array(self::BELONGS_TO, 'BpnProfil', 'PROFILEID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'BERKASYEAR' => 'Tahun',
            'NAMA' => 'Name',
            'PROSEDURDESC' => 'Jenis Permohonan',
            'KET' => 'Posisi Berkas',
            'BERKASFLOTANGGAL' => 'Tanggal Posisi',
            'BERKASNOMOR' => 'Nomor Berkas',
        );
    }

    public function getKeterangan() {
        $ket = "Data tidak ditemukan";
        if ($this->BERKASSTATUS == 1 && $this->BERKASFLOWNAME == 'KIRIM' && $this->PROFILEID == '400020') {
            $ket = "SUDAH SELESAI, SILAHKAN AMBIL DI BPN KOTA SURABAYA II";
        } elseif ($this->BERKASSTATUS == 0) {
            $ket = "SUDAH DIAMBIL";
        } elseif ($this->BERKASSTATUS == 1 && $this->BERKASFLOWNAME != 'SELESAI' && $this->PROFILEID != '400020') {
            $ket = "MASIH DALAM PROSES DI BAGIAN " . $this->BpnProfil->profilenama ;;
        } elseif ($this->BERKASSTATUS == 2) {
            $ket = "BERKAS TERTUNDA, SILAHKAN HUBUNGI BPN KOTA SURABAYA II";
        } elseif ($this->BERKASSTATUS == 3) {
            $ket = "BERKAS DIBATALKAN";
        } elseif ($this->BERKASSTATUS == 4) {
            $ket = "BERKAS DITUTUP";
        }
        
        return $ket;
    }
}