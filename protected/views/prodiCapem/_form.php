<?php
/* @var $this ProdiCapemController */
/* @var $model ProdiCapem */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'prodi-capem-form',
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
		<?php echo $form->labelEx($model,'capem_kategori_id', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'3')); ?>
		<div class="col-sm-9">
		<?php echo $form->dropDownList($model,'capem_kategori_id',CHtml::listData($params['listKategori'],'id','nama'),['prompt'=>'- Pilih Kategori-']); ?>
		<?php echo $form->error($model,'capem_kategori_id'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'prodi_id', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'2')); ?>
		<div class="col-sm-9">
		<?php echo $form->dropDownList($model,'prodi_id',CHtml::listData($params['listProdi'],'kode_prodi','nama_prodi'),['prompt'=>'- Pilih Prodi-']); ?>
		<?php echo $form->error($model,'prodi_id'); ?>
		</div>
	</div>

	

	<div class="form-group">
		<?php echo $form->labelEx($model,'label', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'4')); ?>
		<div class="col-sm-9">
		<?php echo $form->textArea($model,'label',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'label'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'label_en', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'5')); ?>
		<div class="col-sm-9">
		<?php echo $form->textArea($model,'label_en',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'label_en'); ?>
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
    	CKEDITOR.replace( 'ProdiCapem_label' );
    	CKEDITOR.replace( 'ProdiCapem_label_en' );	
    });
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
    
</script>