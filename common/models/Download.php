<?php

/**
 * This is the model class for table "{{download}}".
 *
 * The followings are the available columns in table '{{download}}':
 * @property integer $id
 * @property integer $download_category_id
 * @property string $name
 * @property string $url
 * @property integer $public
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class Download extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Download the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{download}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array(' name', 'required'),
            array('download_category_id, public, created_user_id', 'numerical', 'integerOnly' => true),
            array('name, url', 'length', 'max' => 255),
            array('modified', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, download_category_id, name, url, public, created, created_user_id, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
   public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'DownloadCategory' => array(self::BELONGS_TO, 'DownloadCategory', 'download_category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'download_category_id' => 'Download Category',
            'name' => 'Name',
            'url' => 'Url',
            'public' => 'Public',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
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
        $criteria->compare('download_category_id', $this->download_category_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('public', $this->public);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => $this->getTableAlias(false, false) . '.id Desc')
        ));
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
    
    public function getUrlFull() {
        if (empty($this->DownloadCategory->path)) {
            return '#';
        } else {
            return param('urlImg') . $this->DownloadCategory->path . $this->url;
        }
    }
    
    public function getPublishdata(){
        if($this->publish == 1){
            echo'<span id="" class="label label-info">Publish</span>';
        }else{
            echo'<span id="" class="label label-warning">Unpublish</span>';
        }
    }
    
}