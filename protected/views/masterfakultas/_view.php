<?php
/* @var $this MasterfakultasController */
/* @var $data Masterfakultas */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kode_badan_hukum')); ?>:</b>
	<?php echo CHtml::encode($data->kode_badan_hukum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kode_pt')); ?>:</b>
	<?php echo CHtml::encode($data->kode_pt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kode_fakultas')); ?>:</b>
	<?php echo CHtml::encode($data->kode_fakultas); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nama_fakultas')); ?>:</b>
	<?php echo CHtml::encode($data->nama_fakultas); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tgl_pendirian')); ?>:</b>
	<?php echo CHtml::encode($data->tgl_pendirian); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pejabat')); ?>:</b>
	<?php echo CHtml::encode($data->pejabat); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('jabatan')); ?>:</b>
	<?php echo CHtml::encode($data->jabatan); ?>
	<br />

	*/ ?>

</div>