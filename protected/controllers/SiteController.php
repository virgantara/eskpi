<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	

	public function actionMaster()
	{
		$this->render('master');
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->render('home');
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		// $this->actionLogin();
	}

	public function actionContact()
	{
		$model = new ContactForm;
		if(isset($_POST['ContactForm']))

		{

			$model->attributes=$_POST['ContactForm'];

			if($model->validate())
			{
				$message = 'Hello World!';
				Yii::app()->mailer->Host = 'smtp.gmail.com';
				Yii::app()->mailer->IsSMTP();
				Yii::app()->mailer->From = 'skpi@unida.gontor.ac.id';
				Yii::app()->mailer->FromName = 'Admin SKPI';
				Yii::app()->mailer->AddReplyTo($model->email);
				// Yii::app()->mailer->AddAddress('qian@yiiframework.com');
				Yii::app()->mailer->Subject = $model->subject;
				Yii::app()->mailer->Body = $message;
				Yii::app()->mailer->Send();
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');

				$this->refresh();

			}

		}

		$this->render('contact',[
			'model' => $model
		]);
	}

	public function actionJadwal()
	{
		$this->render('jadwal');
	}

	public function actionAbout()
	{
		$this->render('pages/about');
	}

	public function actionHome()
	{
		$this->render('home');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	public function actionLogin()
	{
	
		$model=new User;
		$this->layout = '//layouts/main_login';

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['User']))
		{
		
			
			$model->attributes=$_POST['User'];
			$result = $model->login();
			// validate user input and redirect to the previous page if valid
			switch($result)
			{
				case UserIdentity::ERROR_NONE:
					
					$time_expiration = time()+60*60*24*7; 
					$tahunaktif = Tahunakademik::model()->findByAttributes(array('buka'=> 'Y'));	
					$cookie = new CHttpCookie('tahunaktif', $tahunaktif->tahun_id);
					$cookie->expire = $time_expiration; 
					Yii::app()->request->cookies['tahunaktif'] = $cookie;	
					$this->redirect(Yii::app()->createUrl('site/home'));
					
					break;
				case UserIdentity::ERROR_USERNAME_INVALID:
				case UserIdentity::ERROR_PASSWORD_INVALID:
					$model->addError('username','Incorrect username or password.');
					
					break;
				case UserIdentity::ERROR_USER_INACTIVE:

					$model->addError('username','Akun Anda belum aktif. Silakan menghubungi Administrator.');
					
					break;
			}
			
		}
		
		
		
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('site/index'));
	}
	
}