<?php

/**
 * This is the model class for table "{{date_config}}".
 *
 * The followings are the available columns in table '{{date_config}}':
 * @property integer $id
 * @property integer $year
 * @property integer $cash_in
 * @property integer $cash_out
 * @property integer $jurnal
 */
class DateConfig extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{date_config}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('year, cash_in, cash_out, bk_in, bk_out, jurnal', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, year, cash_in, cash_out, bk_in, bk_out, jurnal', 'safe', 'on' => 'search'),
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
            'year' => 'Tahun',
            'cash_in' => 'Kas Masuk',
            'cash_out' => 'Kas Keluar',
            'bk_in' => 'Bank Masuk',
            'bk_out' => 'Bank Keluar',
            'jurnal' => 'Jurnal',
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
        $criteria->compare('year', $this->year);
        $criteria->compare('cash_in', $this->cash_in);
        $criteria->compare('cash_out', $this->cash_out);
        $criteria->compare('bk_in', $this->bk_in);
        $criteria->compare('bk_out', $this->bk_out);
        $criteria->compare('jurnal', $this->jurnal);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DateConfig the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function addYear($years, $type) {
        $year = date('Y',strtotime($years));
        $model = $this->model()->find(array('condition' => 'year=' . $year.' AND departement_id='.User()->departement_id));
        if (!empty($model)) {
            if ($type == 'cash_in') {
                $model->cash_in++;
            } elseif ($type == 'cash_out') {
                $model->cash_out++;
            } elseif ($type == 'bk_in') {
                $model->bk_in++;
            } elseif ($type == 'bk_out') {
                $model->bk_out++;
            } elseif ($type == 'jurnal') {
                $model->jurnal++;
            }


            $model->save();
        } else {
            $newYear = new DateConfig();
            $newYear->year = $year;
            $newYear->departement_id = User()->departement_id;
            if ($type == 'cash_in') {
                $newYear->cash_in = 1;
            } elseif ($type == 'cash_out') {
                $newYear->cash_out = 1;
            } elseif ($type == 'jurnal') {
                $newYear->jurnal = 1;
            } elseif ($type == 'bk_in') {
                $newYear->bk_in = 1;
            } elseif ($type == 'bk_out') {
                $newYear->bk_out = 1;
            }
            $newYear->save();
        }
    }

}
