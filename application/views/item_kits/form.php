<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('item_kits/save/'.$item_kit_info->item_kit_id, array('id'=>'item_kit_form', 'class' => 'form-horizontal')); ?>
	<fieldset id="item_kit_info">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('item_kits_name').':', 'name', array('class'=>'control-label col-xs-3 required')); ?>
			<div class='col-xs-6'>
				<?php echo form_input(array(
					'name'=>'name',
					'id'=>'name',
					'class'=>'form-control input-sm',
					'value'=>$item_kit_info->name)
					);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('item_kits_description').':', 'description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_textarea(array(
					'name'=>'description',
					'id'=>'description',
					'class'=>'form-control input-sm',
					'value'=>$item_kit_info->description)
					);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('item_kits_add_item').':', 'item', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_input(array(
					'name'=>'item',
					'id'=>'item',
					'class'=>'form-control input-sm')
					);?>
			</div>
		</div>

		<table id="item_kit_items" class="table table-striped table-hover">
			<thead>
				<tr bgcolor="#CCC">
					<th><?php echo $this->lang->line('common_delete'); ?></th>
					<th><?php echo $this->lang->line('item_kits_item'); ?></th>
					<th><?php echo $this->lang->line('item_kits_quantity'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($this->Item_kit_items->get_info($item_kit_info->item_kit_id) as $item_kit_item)
				{
				?>
					<tr>
						<?php $item_info = $this->Item->get_info($item_kit_item['item_id']); ?>
						<td><a href='#' onclick='return deleteItemKitRow(this);'><span class='glyphicon glyphicon-trash'></span></a></td>
						<td><?php echo $item_info->name; ?></td>
						<td><input class='quantity form-control input-sm' id='item_kit_item_<?php echo $item_kit_item['item_id'] ?>' name=item_kit_item[<?php echo $item_kit_item['item_id'] ?>] value='<?php echo $item_kit_item['quantity'] ?>'/></td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</fieldset>
<?php echo form_close(); ?>

<script type='text/javascript'>
$("#item").autocomplete('<?php echo site_url("items/item_search"); ?>',
{
	minChars:0,
	max:100,
	selectFirst: false,
   	delay:10,
	formatItem: function(row) {
		return row[1];
	}
});

$("#item").result(function(event, data, formatted)
{
	$("#item").val("");
	
	if ($("#item_kit_item_"+data[0]).length ==1)
	{
		$("#item_kit_item_"+data[0]).val(parseFloat($("#item_kit_item_"+data[0]).val()) + 1);
	}
	else
	{
		$("#item_kit_items").append("<tr><td><a href='#' onclick='return deleteItemKitRow(this);'><span class='glyphicon glyphicon-trash'></span></a></td><td>"+data[1]+"</td><td><input class='quantity form-control input-sm' id='item_kit_item_"+data[0]+"' type='text' name=item_kit_item["+data[0]+"] value='1'/></td></tr>");
	}
});


//validation and submit handling
$(document).ready(function()
{
	$('#item_kit_form').validate($.extend({
		submitHandler:function(form)
		{
			$(form).ajaxSubmit({
			success:function(response)
			{
				dialog_support.hide();
				post_item_kit_form_submit(response);
			},
			dataType:'json'
		});

		},
		rules:
		{
			name:"required",
			category:"required"
		},
		messages:
		{
			name:"<?php echo $this->lang->line('items_name_required'); ?>",
			category:"<?php echo $this->lang->line('items_category_required'); ?>"
		}
	}, dialog_support.error));
});

function deleteItemKitRow(link)
{
	$(link).parent().parent().remove();
	return false;
}
</script>