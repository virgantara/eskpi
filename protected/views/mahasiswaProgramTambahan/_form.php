<?php
/* @var $this MahasiswaProgramTambahanController */
/* @var $model MahasiswaProgramTambahan */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mahasiswa-program-tambahan-form',
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
		<?php echo $form->labelEx($model,'nim', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'2')); ?>
		<div class="col-sm-9">
			<?php 
			$nama_mahasiswa = '';

		if(!empty($_POST['MahasiswaProgramTambahan']))
		{
			$mhs = Mastermahasiswa::model()->findByAttributes(['nim_mhs'=>$_POST['MahasiswaProgramTambahan']['nim']]);
			
			if(!empty($mhs))
				$nama_mahasiswa = $mhs->nama_mahasiswa;
		}
		
		if(!$model->isNewRecord)
		{
			$mhs = Mastermahasiswa::model()->findByAttributes(['nim_mhs'=>$model->nim]);
			$nama_mahasiswa = $mhs->nama_mahasiswa;
		}

		echo $form->hiddenField($model,'nim');
		echo CHtml::textField('nama_mahasiswa',$nama_mahasiswa,['size'=>60,'maxlength'=>60,'id'=>'nama_mahasiswa']);
			?>
		<?php echo $form->error($model,'nim'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'nama', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'3')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'nama',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'nama'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'nama_en', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'4')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'nama_en',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'nama_en'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'durasi', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'5')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'durasi',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'durasi'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'durasi_en', array ('class'=>'col-sm-3 control-label no-padding-right', 'tabindex'=>'5')); ?>
		<div class="col-sm-9">
		<?php echo $form->textField($model,'durasi_en',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'durasi_en'); ?>
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

<script type="text/javascript">
	$(document).ready(function(){
		$('#nama_mahasiswa').autocomplete({
	      minLength:3,
	      select:function(event, ui){
	       
	        $('#MahasiswaProgramTambahan_nim').val(ui.item.id);
	        $('#nama_mahasiswa').val(ui.item.value);
	      },
	      
	      focus: function (event, ui) {

	        $('#MahasiswaProgramTambahan_nim').val(ui.item.id);
	        $('#nama_mahasiswa').val(ui.item.value);
	      },
	      source:function(request, response) {
	        $.ajax({
	                url: "<?php echo Yii::app()->createUrl('mastermahasiswa/getListMahasiswa');?>",
	                // type: "POST",
	                data: {
	                    term: request.term,
	                    
	                },
	                success: function (data) {
	                    var parsed = JSON.parse(data);
		                var newArray = new Array(parsed.length);
		                var i = 0;

		                parsed.forEach(function (entry) {
		                    // var newObject = {
		                    // 	id : entry.id,
		                    //     value: entry.value
		                    // };
		                    newArray[i] = entry;
		                    i++;
		                });

		                response(newArray);
	                }
	            })
	        },
	       
	  }); 
	});
</script>