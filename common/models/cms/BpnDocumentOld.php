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
class BpnDocumentOld extends CActiveRecord {

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
        return '{{databerkas}}';
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
			array('NAMA', 'length', 'max' => 255),
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

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('BERKASYEAR', $this->berkasyear, true);
        $criteria->compare('NAMA', $this->nama, true);
        $criteria->compare('PROSEDURDESC', $this->PROSEDURDESC, true);
        $criteria->compare('KET', $this->KET, true);
        $criteria->compare('BERKASFLOTANGGAL', $this->BERKASFLOTANGGAL, true);
        $criteria->compare('BERKASNOMOR', $this->noberkas, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}