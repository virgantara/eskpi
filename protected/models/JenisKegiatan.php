<?php

/**
 * This is the model class for table "{{jenis_kegiatan}}".
 *
 * The followings are the available columns in table '{{jenis_kegiatan}}':
 * @property integer $id
 * @property string $nama_jenis_kegiatan
 * @property integer $nilai_minimal
 * @property integer $nilai_maximal
 */
class JenisKegiatan extends CActiveRecord
{

	public $SEARCH;
	public $PAGE_SIZE = 10;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{jenis_kegiatan}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nama_jenis_kegiatan, nilai_minimal, nilai_maximal', 'required'),
			array('nilai_minimal, nilai_maximal', 'numerical', 'integerOnly'=>true),
			array('nama_jenis_kegiatan', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nama_jenis_kegiatan, nilai_minimal, nilai_maximal', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nama_jenis_kegiatan' => 'Nama Jenis Kegiatan',
			'nilai_minimal' => 'Nilai Minimal',
			'nilai_maximal' => 'Nilai Maximal',
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
		$criteria->addSearchCondition('nama_jenis_kegiatan',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('nilai_minimal',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('nilai_maximal',$this->SEARCH,true,'OR');

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
	 * @return JenisKegiatan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
