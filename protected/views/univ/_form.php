<?php
/* @var $this UnivController */
/* @var $model Univ */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'univ-form',
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
		<?php echo $form->labelEx($model,'kode', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'2')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'kode'); ?>
		<?php echo $form->error($model,'kode'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'nama', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'2')); ?>
		<div class="col-sm-9">
		<?php echo $form->textArea($model,'nama',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'nama'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'nama_en', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'3')); ?>
		<div class="col-sm-9">
		<?php echo $form->textArea($model,'nama_en',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'nama_en'); ?>
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

<script src="https://cdn.ckeditor.com/4.12.1/full/ckeditor.js"></script>
<script>
    $(document).ready(function(){
    	CKEDITOR.addCss('.cke_editable p { margin: 0 !important; }');
    	CKEDITOR.replace( 'Univ_nama' );
    	CKEDITOR.replace( 'Univ_nama_en' );	
    });
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
    
</script>