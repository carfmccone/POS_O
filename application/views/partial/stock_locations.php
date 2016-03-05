<?php
$i = 0;

foreach($stock_locations as $location=>$location_data)
{
	$location_id = $location_data['location_id'];
	$location_name = $location_data['location_name'];
?>
	<div class="form-group form-group-sm" style="<?php echo $location_data['deleted'] ? 'display:none;' : 'display:block;' ?>">
		<?php echo form_label($this->lang->line('config_stock_location') . ' ' . ++$i, 'stock_location_' . $i, array('class'=>'control-label col-xs-2 required')); ?>
			<div class='col-xs-2'>
				<?php $form_data = array(
						'name'=>'stock_location_' . $location_id,
						'id'=>'stock_location_' . $location_id,
						'class'=>'stock_location valid_chars form-control input-sm required',
						'value'=>$location_name
					); 
					$location_data['deleted'] && $form_data['disabled'] = 'disabled';
					echo form_input($form_data);
				?>
			</div>
		<img class="add_stock_location" src="<?php echo base_url('images/plus.png'); ?>" />
		<img class="remove_stock_location" src="<?php echo base_url('images/minus.png'); ?>" />
	</div>
<?php
}
?>
