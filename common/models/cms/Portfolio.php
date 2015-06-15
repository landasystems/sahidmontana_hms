<?php

/**
 * This is the model class for table "portfolio".
 *
 * The followings are the available columns in table 'portfolio':
 * @property integer $id
 * @property string $title
 * @property string $created
 * @property string $description
 * @property string $image
 * @property integer $portfolio_category_id
 */
class Portfolio extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Portfolio the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{portfolio}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('portfolio_category_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 45),
            array('description', 'length', 'max' => 255),
            array('created, project_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, created, description, portfolio_category_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'PortfolioCategory' => array(self::BELONGS_TO, 'PortfolioCategory', 'portfolio_category_id'),
            'PortfolioImage' => array(self::HAS_MANY, 'PortfolioImage', 'portfolio_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'created' => 'Created',
            'description' => 'Description',
            'image' => 'Image',
            'portfolio_category_id' => 'Portfolio Category',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('portfolio_category_id', $this->portfolio_category_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    

}