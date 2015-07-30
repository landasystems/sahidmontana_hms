<?php

/**
 * This is the model class for table "{{m_province}}".
 *
 * The followings are the available columns in table '{{m_province}}':
 * @property integer $id
 * @property string $name
 */
class Province extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Province the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{province}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 250),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'City' => array(self::HAS_MANY, 'City', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
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
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getNationalityList() {
        $result = array();
        $result['ID'] = 'Indonesia';
        $result['AU'] = 'Australia';
        $result['DE'] = 'Germany';
        $result['UK'] = 'UK';
        $result['FR'] = 'France';
        $result['US'] = 'USA';
        $result['LA'] = 'Latin America';
        $result['JP'] = 'Japan';
        $result['SG'] = 'Singapore';
        $result['KO'] = 'Korea';
        $result['CH'] = 'China';
        $result['HK'] = 'Hongkong';
        $result['MY'] = 'Malaysia';
        $result['TH'] = 'Thailand';
        $result['ET'] = 'East Timor';
        $result['OT'] = 'Others';
        return $result;
    }

    public function nationality() {
        $arrNation = $this->getNationalityList();

        if (isset($arrNation[$arg]))
            return $arrNation[$arg];
        else
            return '';
    }

//    public function getProvinceList() {
//        $t_data = $this->findAll();
//        $data = CHtml::listData($t_data, 'id', 'name');
//        return $data;
//    }
}
