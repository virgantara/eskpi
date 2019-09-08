<?php

/**
 * This is the model class for table "{{mahasiswa_program_tambahan}}".
 *
 * The followings are the available columns in table '{{mahasiswa_program_tambahan}}':
 * @property string $id
 * @property string $nim
 * @property string $nama
 * @property string $nama_en
 * @property string $durasi
 * @property string $durasi_en
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Mastermahasiswa $nim0
 */
class MahasiswaProgramTambahan extends CActiveRecord
{

	public $SEARCH;
	public $PAGE_SIZE = 10;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mahasiswa_program_tambahan}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, nim, nama, nama_en, durasi, durasi_en', 'required'),
			array('id, nama, nama_en, durasi, durasi_en', 'length', 'max'=>255),
			array('nim', 'length', 'max'=>25),
			array('created_at, updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nim, nama, nama_en, durasi, durasi_en, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'nim0' => array(self::BELONGS_TO, 'Mastermahasiswa', 'nim'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nim' => 'Nim',
			'nama' => 'Nama',
			'nama_en' => 'Nama En',
			'durasi' => 'Durasi',
			'durasi_en' => 'Durasi En',
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
		$criteria->addSearchCondition('nim',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('nama',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('nama_en',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('durasi',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('durasi_en',$this->SEARCH,true,'OR');
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
	 * @return MahasiswaProgramTambahan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getListProgram($nim)
	{

		$list = MahasiswaProgramTambahan::model()->findAllByAttributes(['nim'=>$nim]);
		return $list;
	}
}
