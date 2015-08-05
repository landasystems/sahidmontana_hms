<?php

/**
 * This is the model class for table "{{room_type}}".
 *
 * The followings are the available columns in table '{{room_type}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $standart_rate
 * @property integer $max_pax
 */
class RoomType extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RoomType the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{room_type}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name,', 'required'),
            array('name', 'length', 'max' => 45),
            array('description', 'length', 'max' => 10000),
            array('charge_additional_ids,pax,is_package,fnb_charge', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Room' => array(self::HAS_MANY, 'Room', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'pax' => 'Default Pax',
            'description' => 'Description',
            'rate' => 'Standart Rate',
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
        $criteria->compare('description', $this->description, true);
        $criteria->compare('rate', $this->rate, true);
        $criteria->compare('is_package', $this->is_package, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    public function getPrice() {
        return landa()->rp($this->rate);
    }

    public function getShortDesc() {
        $package = json_decode($this->charge_additional_ids);
        $listPackage = '';
        if (!empty($package)) {
            foreach ($package as $data) {
                $additional = ChargeAdditional::model()->findByPk($data->id);
                $listPackage .= '- [ ' . $data->amount . ' ] ' . $additional->name . '<br>';
            }
        }
        $is_package = ($this->is_package == 1) ? 'Yes' : 'No';

        return'
                <div class="row-fluid">
                    <div class="span3">
                        <b> Name </b>
                    </div>
                    <div class="span1" style="width:3px">
                        :
                    </div>
                    
                    <div class="span8" style="text-align:left"> 
                       ' . $this->name . '
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <b> Default Pax  </b>
                    </div>
                    <div class="span1" style="width:3px">
                        :
                    </div>
                    <div class="span8" style="text-align:left">
                        ' . $this->pax . ' 
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <b> Is Package  </b>
                    </div>
                    <div class="span1" style="width:3px">
                        :
                    </div>
                    <div class="span8" style="text-align:left">
                        ' . $is_package . ' 
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                       <b> Extra Package </b>
                    </div>
                    <div class="span1" style="width:3px">
                        :
                    </div>
                    <div class="span8" style="text-align:left">
                        ' . $listPackage . '
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <b>Description</b>
                    </div>
                    <div class="span1" style="width:3px">
                        :
                    </div>
                    <div class="span8" style="text-align:left"> 
                        ' . $this->description . '
                    </div>
                </div>';
    }

    public function getHasil() {
        echo'<table class="table table-striped table-bordered">
                                <thead>
                                  <tr>
                                    <th>User Type</th>
                                    <th>Min</th>
                                    <th>Max</th>
                                    <th>Default</th>
                                  </tr>
                                </thead><tbody>';
        $rates = json_decode($this->rate);
        $roles = Roles::model()->guest();
        
        foreach ($rates as $key => $value) {
            //jika ada user groupnya
            if (isset($roles[$key])) {
                $min = (empty($value->min)) ? '<td style="width:150px;">-</td>' : '<td style="width:150px;">' . landa()->rp($value->min) . '</td>';
                $max = (empty($value->max)) ? '<td style="width:150px;">-</td>' : '<td style="width:150px;">' . landa()->rp($value->max) . '</td>';
                $default = (empty($value->default)) ? '<td style="width:150px;">-</td>' : '<td style="width:150px;">' . landa()->rp($value->default) . '</td>';
                echo'<tr>
                                    <td style="width:130px;">' . $roles[$key]->name . '</td>
                                    ' . $min . '
                                    ' . $max . '
                                    ' . $default . '
                                  </tr>';
            }
//            }
        }
        echo'</tbody></table>';
    }

}
