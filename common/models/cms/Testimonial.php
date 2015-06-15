<?php

/**
 * This is the model class for table "{{testimonial}}".
 *
 * The followings are the available columns in table '{{testimonial}}':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $img_avatar
 * @property string $testimonial
 */
class Testimonial extends CActiveRecord {

    public $verifyCode;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Testimonial the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{testimonial}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, email,corporate, img_avatar', 'length', 'max' => 100),
            array('name, email', 'required',),
            array('testimonial', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, email, img_avatar, testimonial', 'safe', 'on' => 'search'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
            
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
            'name' => 'Name',
            'email' => 'Email',
            'img_avatar' => 'Img Avatar',
            'testimonial' => 'Testimonial',
            'verifyCode'=>'Verification Code',
        );
    }
    /////model image
public function getImg(){
        return landa()->urlImg('testimonial/', $this->img_avatar, $this->id);
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
        $criteria->compare('email', $this->email, true);
        $criteria->compare('img_avatar', $this->img_avatar, true);
        $criteria->compare('testimonial', $this->testimonial, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}