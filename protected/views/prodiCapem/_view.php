<?php
/* @var $this ProdiCapemController */
/* @var $data ProdiCapem */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prodi_id')); ?>:</b>
	<?php echo CHtml::encode($data->prodi_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('capem_kategori_id')); ?>:</b>
	<?php echo CHtml::encode($data->capem_kategori_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('label')); ?>:</b>
	<?php echo CHtml::encode($data->label); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('label_en')); ?>:</b>
	<?php echo CHtml::encode($data->label_en); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
	<?php echo CHtml::encode($data->updated_at); ?>
	<br />


</div>