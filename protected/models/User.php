<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $username
 * @property string $password
 * @property integer $level
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Partner[] $partners
 */
class User extends MyActiveRecord
{

	public $SEARCH;
	public $PAGE_SIZE = 10;
	private $_identity;
	public $rememberMe;
	
	public $old_password;
    public $new_password;
    public $repeat_password;
	

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password,level,status', 'required','message' => Yii::t('yii', '{attribute} harus diisi')),
			array('level, status', 'numerical', 'integerOnly'=>true,'on'=>'form','message' => Yii::t('yii', '{attribute} harus diisi')),
			array('username', 'length', 'max'=>255),
			array('kode_prodi', 'checkKode'),
			array('username', 'unique','message'=>'{attribute} sudah dipakai.', 'on'=>'form'),
			array('password', 'length', 'max'=>100),
			array('repeat_password', 'compare', 'compareAttribute'=>'password', 'on'=>'repeatPwd','message' => Yii::t('yii', '{attribute} tidak sama dengan password')),
			
			//array('username', 'match',
              //  'pattern' => '/^[a-zA-Z\s\d]+$/',
               // 'message' => '{attribute} hanya bisa angka dan huruf'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('username, password, level, status,PARTNER_ID,kode_prodi,auth_key', 'safe', 'on'=>'search'),
		);
	}

	public function checkKode($attribute, $params)
    {
    	// print_r($this->kode_prodi);exit;
		if($this->level == 3 && empty($this->kode_prodi))
        	$this->addError($attribute, 'Prodi harus diisi.');
    }
	
	//matching the old password with your existing password.
    public function findpasswords($attribute, $params)
    {
		$username = Yii::app()->user->getState('username');
        $user = User::model()->findByPk($username);
        if ($user->password != md5($this->old_password))
            $this->addError($attribute, 'Old password is incorrect.');
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
			'username' => 'username',
			'password' => 'password',
			'level' => 'level',
			'status' => 'status',
			'kode_prodi' => 'Prodi',
			'repeat_password' => 'Ulangi password'
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

        $criteria->addSearchCondition('username',$this->SEARCH,true,'OR');
        $criteria->addSearchCondition('level',$this->SEARCH,true,'OR');
        $criteria->addSearchCondition('status',$this->SEARCH,true,'OR');
        $criteria->addSearchCondition('kode_prodi',$this->SEARCH,true,'OR');
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'pagination'=>array(
                'pageSize'=>$this->PAGE_SIZE,

            ),
        )); 
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,md5($this->password));
			if(!$this->_identity->authenticate())
				$this->addError('password','username atau password salah');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{


			$this->_identity=new UserIdentity($this->username,md5($this->password));
			$this->_identity->authenticate();
		}

		$errCode = $this->_identity->errorCode;


		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*1 : 0; // 1 day
			Yii::app()->user->login($this->_identity,$duration);
			
			
		}


		return $errCode;
	}

	protected function beforeSave()
	{
		$this->password = md5($this->password);
		return parent::beforeSave();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
