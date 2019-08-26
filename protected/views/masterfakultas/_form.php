<?php
/* @var $this MasterfakultasController */
/* @var $model Masterfakultas */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'masterfakultas-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'class'=>'form-horizontal'
	)
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">Silakan perbaiki beberapa kesalahan berikut:','</div>'); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'kode_badan_hukum', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'2')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'kode_badan_hukum',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'kode_badan_hukum'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'kode_pt', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'3')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'kode_pt',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'kode_pt'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'kode_fakultas', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'4')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'kode_fakultas',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'kode_fakultas'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'nama_fakultas', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'5')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'nama_fakultas',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'nama_fakultas'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'tgl_pendirian', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'6')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'tgl_pendirian'); ?>
		<?php echo $form->error($model,'tgl_pendirian'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'pejabat', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'7')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'pejabat',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'pejabat'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'jabatan', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'8')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'jabatan',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'jabatan'); ?>
		</div>
	</div>

	<div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">
		<button class="btn btn-info" type="submit">
            <i class="ace-icon fa fa-check bigger-110"></i>
            Simpan
          </button>
	  </div>
      </div>
             

<?php $this->endWidget(); ?>
