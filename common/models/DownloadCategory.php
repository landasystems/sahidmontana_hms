<?php

/**
 * This is the model class for table "{{download_category}}".
 *
 * The followings are the available columns in table '{{download_category}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $level
 * @property integer $lft
 * @property integer $rgt
 * @property integer $root
 * @property integer $path
 * @property integer $parent_id
 */
class DownloadCategory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DownloadCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{download_category}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('name', 'required'),
//                        array(' name','unique','message'=>'{attribute} : {value} already exists!'),
			array('level, lft, rgt, root, parent_id', 'numerical', 'integerOnly'=>true),
			array('name,path , description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, level, lft, rgt, root, path, parent_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	  public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Download' => array(self::HAS_MANY, 'Download', 'id'),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'level' => 'Level',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'root' => 'Root',
			'path' => 'Path',
			'parent_id' => 'Parent',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
        
        public function behaviors() {
        return array(   
            'nestedSetBehavior' => array(
                'class' => 'common.extensions.NestedSetBehavior.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'hasManyRoots' => true,
            ),
        );
    }

  
        
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('lft',$this->lft);
		$criteria->compare('rgt',$this->rgt);
		$criteria->compare('root',$this->root);
		$criteria->compare('path',$this->path);
		$criteria->compare('parent_id',$this->parent_id);

		return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'root, lft',
		)));
	}
        public function getNestedName() {
        return ($this->level == 1) ? $this->name : str_repeat("|—", $this->level - 1) . $this->name;
    }  
}