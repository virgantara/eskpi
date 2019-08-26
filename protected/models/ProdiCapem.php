<?php

/**
 * This is the model class for table "{{prodi_capem}}".
 *
 * The followings are the available columns in table '{{prodi_capem}}':
 * @property integer $id
 * @property string $prodi_id
 * @property integer $capem_kategori_id
 * @property string $label
 * @property string $label_en
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Masterprogramstudi $prodi
 * @property ProdiCapem $capemKategori
 * @property ProdiCapem[] $prodiCapems
 */
class ProdiCapem extends CActiveRecord
{

	public $SEARCH;
	public $PAGE_SIZE = 10;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{prodi_capem}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prodi_id, capem_kategori_id, label, label_en', 'required'),
			array('capem_kategori_id', 'numerical', 'integerOnly'=>true),
			array('prodi_id', 'length', 'max'=>15),
			array('created_at, updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, prodi_id, capem_kategori_id, label, label_en, created_at, updated_at', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'prodi' => array(self::BELONGS_TO, 'Masterprogramstudi', 'prodi_id'),
			'capemKategori' => array(self::BELONGS_TO, 'ProdiCapem', 'capem_kategori_id'),
			'prodiCapems' => array(self::HAS_MANY, 'ProdiCapem', 'capem_kategori_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'prodi_id' => 'Prodi',
			'capem_kategori_id' => 'Capem Kategori',
			'label' => 'Label',
			'label_en' => 'Label En',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$sort = new CSort;

		$criteria->addSearchCondition('id',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('prodi_id',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('capem_kategori_id',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('label',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('label_en',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('created_at',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('updated_at',$this->SEARCH,true,'OR');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
			'pagination'=>array(
				'pageSize'=>$this->PAGE_SIZE,

			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProdiCapem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
