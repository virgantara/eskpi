<?php
/* @var $this MasterfakultasController */
/* @var $model Masterfakultas */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kode_badan_hukum'); ?>
		<?php echo $form->textField($model,'kode_badan_hukum',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kode_pt'); ?>
		<?php echo $form->textField($model,'kode_pt',array('size'=>6,'maxlength'=>6)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kode_fakultas'); ?>
		<?php echo $form->textField($model,'kode_fakultas',array('size'=>5,'maxlength'=>5)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nama_fakultas'); ?>
		<?php echo $form->textField($model,'nama_fakultas',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tgl_pendirian'); ?>
		<?php echo $form->textField($model,'tgl_pendirian'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pejabat'); ?>
		<?php echo $form->textField($model,'pejabat',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jabatan'); ?>
		<?php echo $form->textField($model,'jabatan',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->