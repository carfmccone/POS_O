<?php
/**
 * @var object $item_info
 * @var array $categories
 * @var int $selected_category
 * @var bool $standard_item_locked
 * @var bool $item_kit_disabled
 * @var int $allow_temp_item
 * @var array $suppliers
 * @var int $selected_supplier
 * @var bool $use_destination_based_tax
 * @var float $default_tax_1_rate
 * @var float $default_tax_2_rate
 * @var string $tax_category
 * @var int $tax_category_id
 * @var bool $include_hsn
 * @var string $hsn_code
 * @var array $stock_locations
 * @var bool $logo_exists
 * @var string $image_path
 * @var string $selected_low_sell_item
 * @var int $selected_low_sell_item_id
 * @var string $controller_name
 */
?>
<div id="required_fields_message"><?php echo lang('Common.fields_required_message') ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open("items/save/$item_info->item_id", ['id' => 'item_form', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) ?>
	<fieldset id="item_basic_info">
		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.item_number'), 'item_number', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-8'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-barcode"></span></span>
					<?php echo form_input ([
						'name' => 'item_number',
						'id' => 'item_number',
						'class' => 'form-control input-sm',
						'value' => esc($item_info->item_number, 'attr')
					]) ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.name'), 'name', ['class' => 'required control-label col-xs-3']) ?>
			<div class='col-xs-8'>
				<?php echo form_input ([
					'name' => 'name',
					'id' => 'name',
					'class' => 'form-control input-sm',
					'value' => esc($item_info->name, 'attr')
				]) ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.category'), 'category', ['class' => 'required control-label col-xs-3']) ?>
			<div class='col-xs-8'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-tag"></span></span>
					<?php
						if(config('OSPOS')->settings['category_dropdown'])
						{
							echo form_dropdown('category', esc($categories, 'attr'), $selected_category, ['class' => 'form-control']);
						}
						else
						{
							echo form_input ([
								'name' => 'category',
								'id' => 'category',
								'class' => 'form-control input-sm',
								'value' => esc($item_info->category, 'attr')
							]);
						}
					?>
				</div>
			</div>
		</div>

		<div id="attributes">
			<script type="text/javascript">
				$('#attributes').load('<?php echo site_url("items/attributes/$item_info->item_id") ?>');
			</script>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.stock_type'), 'stock_type', !empty($basic_version) ? ['class' => 'required control-label col-xs-3'] : ['class' => 'control-label col-xs-3']) ?>
			<div class="col-xs-8">
				<label class="radio-inline">
					<?php echo form_radio ([
						'name' => 'stock_type',
						'type' => 'radio',
						'id' => 'stock_type',
						'value' => 0,
						'checked' => $item_info->stock_type == HAS_STOCK
					]) ?> <?php echo lang('Items.stock') ?>
				</label>
				<label class="radio-inline">
					<?php echo form_radio ([
						'name' => 'stock_type',
						'type' => 'radio',
						'id' => 'stock_type',
						'value' => 1,
						'checked' => $item_info->stock_type == HAS_NO_STOCK
					]) ?><?php echo lang('Items.nonstock') ?>
				</label>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.type'), 'item_type', !empty($basic_version) ? ['class' => 'required control-label col-xs-3'] : ['class' => 'control-label col-xs-3']) ?>
			<div class="col-xs-8">
				<label class="radio-inline">
					<?php
						$radio_button = [
							'name' => 'item_type',
							'type' => 'radio',
							'id' => 'item_type',
							'value' => 0,
							'checked' => $item_info->item_type == ITEM
						];

						if($standard_item_locked)
						{
							$radio_button['disabled'] = TRUE;
						}
						echo form_radio($radio_button) ?> <?php echo lang('Items.standard') ?>
				</label>
				<label class="radio-inline">
					<?php
						$radio_button = [
							'name' => 'item_type',
							'type' => 'radio',
							'id' => 'item_type',
							'value' => 1,
							'checked' => $item_info->item_type == ITEM_KIT
						];

						if($item_kit_disabled)
						{
							$radio_button['disabled'] = TRUE;
						}
						echo form_radio($radio_button) ?> <?php echo lang('Items.kit') ?>
				</label>
				<?php
				if(config('OSPOS')->settings['derive_sale_quantity'] == '1')
				{
				?>
					<label class="radio-inline">
						<?php echo form_radio ([
							'name' => 'item_type',
							'type' => 'radio',
							'id' => 'item_type',
							'value' => 2,
							'checked' => $item_info->item_type == ITEM_AMOUNT_ENTRY
						]) ?><?php echo lang('Items.amount_entry') ?>
					</label>
				<?php
				}
				?>
				<?php
				if($allow_temp_item == 1)
				{
				?>
					<label class="radio-inline">
						<?php echo form_radio ([
							'name' => 'item_type',
							'type' => 'radio',
							'id' => 'item_type',
							'value' => 3,
							'checked' => $item_info->item_type == ITEM_TEMP
						]) ?> <?php echo lang('Items.temp') ?>
					</label>
				<?php
				}
				?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.supplier'), 'supplier', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-8'>
				<?php echo form_dropdown('supplier_id', esc($suppliers, 'attr'), $selected_supplier, ['class' => 'form-control']) ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.cost_price'), 'cost_price', ['class' => 'required control-label col-xs-3']) ?>
			<div class="col-xs-4">
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->settings['currency_symbol']) ?></b></span>
					<?php endif; ?>
					<?php echo form_input ([
						'name' => 'cost_price',
						'id' => 'cost_price',
						'class' => 'form-control input-sm',
						'onClick' => 'this.select();',
						'value' => to_currency_no_money($item_info->cost_price)
					]) ?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->settings['currency_symbol']) ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.unit_price'), 'unit_price', ['class' => 'required control-label col-xs-3']) ?>
			<div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->settings['currency_symbol']) ?></b></span>
					<?php endif; ?>
					<?php echo form_input ([
						'name' => 'unit_price',
						'id' => 'unit_price',
						'class' => 'form-control input-sm',
						'onClick' => 'this.select();',
						'value' => to_currency_no_money($item_info->unit_price)
					]) ?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->settings['currency_symbol']) ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php
		if(!$use_destination_based_tax)
		{
		?>
			<div class="form-group form-group-sm">
				<?php echo form_label(lang('Items.tax_1'), 'tax_percent_1', ['class' => 'control-label col-xs-3']) ?>
				<div class='col-xs-4'>
					<?php echo form_input ([
						'name' => 'tax_names[]',
						'id' => 'tax_name_1',
						'class' => 'form-control input-sm',
						'value' => isset($item_tax_info[0]['name']) ? esc($item_tax_info[0]['name'], 'attr') : esc(config('OSPOS')->settings['default_tax_1_name'], 'attr')
					]) ?>
				</div>
				<div class="col-xs-4">
					<div class="input-group input-group-sm">
						<?php echo form_input ([
							'name' => 'tax_percents[]',
							'id' => 'tax_percent_name_1',
							'class' => 'form-control input-sm',
							'value' => isset($item_tax_info[0]['percent']) ? to_tax_decimals($item_tax_info[0]['percent']) : to_tax_decimals($default_tax_1_rate)
						]) ?>
						<span class="input-group-addon input-sm"><b>%</b></span>
					</div>
				</div>
			</div>

			<div class="form-group form-group-sm">
				<?php echo form_label(lang('Items.tax_2'), 'tax_percent_2', ['class' => 'control-label col-xs-3']) ?>
				<div class='col-xs-4'>
					<?php echo form_input ([
						'name' => 'tax_names[]',
						'id' => 'tax_name_2',
						'class' => 'form-control input-sm',
						'value' => isset($item_tax_info[1]['name']) ? esc($item_tax_info[1]['name'], 'attr') : esc(config('OSPOS')->settings['default_tax_2_name'], 'attr')
					]) ?>
				</div>
				<div class="col-xs-4">
					<div class="input-group input-group-sm">
						<?php echo form_input ([
							'name' => 'tax_percents[]',
							'class' => 'form-control input-sm',
							'id' => 'tax_percent_name_2',
							'value' => isset($item_tax_info[1]['percent']) ? to_tax_decimals($item_tax_info[1]['percent']) : to_tax_decimals($default_tax_2_rate)
						]) ?>
						<span class="input-group-addon input-sm"><b>%</b></span>
					</div>
				</div>
			</div>
		<?php
		}
		?>

		<?php if($use_destination_based_tax): ?>
			<div class="form-group form-group-sm">
				<?php echo form_label(lang('Taxes.tax_category'), 'tax_category', ['class' => 'control-label col-xs-3']) ?>
				<div class='col-xs-8'>
					<div class="input-group input-group-sm">
						<?php echo form_input ([
							'name' => 'tax_category',
							'id' => 'tax_category',
							'class' => 'form-control input-sm',
							'size' => '50',
							'value' => esc($tax_category, 'attr')
						]) ?><?php echo form_hidden('tax_category_id', $tax_category_id) ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if($include_hsn): ?>
			<div class="form-group form-group-sm">
				<?php echo form_label(lang('Items.hsn_code'), 'category', ['class' => 'control-label col-xs-3']) ?>
				<div class='col-xs-8'>
					<div class="input-group">
						<?php echo form_input ([
							'name' => 'hsn_code',
							'id' => 'hsn_code',
							'class' => 'form-control input-sm',
							'value' => esc($hsn_code, 'attr')
						]) ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php
		foreach($stock_locations as $key => $location_detail)
		{
		?>
			<div class="form-group form-group-sm">
				<?php echo form_label(lang('Items.quantity') . ' ' . $location_detail['location_name'], "quantity_$key", ['class' => 'required control-label col-xs-3']) ?>
				<div class='col-xs-4'>
					<?php echo form_input ([
						'name' => "quantity_$key",
						'id' => "quantity_$key",
						'class' => 'required quantity form-control',
						'onClick' => 'this.select();',
						'value' => isset($item_info->item_id) ? to_quantity_decimals($location_detail['quantity']) : to_quantity_decimals(0)
					]) ?>
				</div>
			</div>
		<?php
		}
		?>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.receiving_quantity'), 'receiving_quantity', ['class' => 'required control-label col-xs-3']) ?>
			<div class='col-xs-4'>
				<?php echo form_input ([
					'name' => 'receiving_quantity',
					'id' => 'receiving_quantity',
					'class' => 'required form-control input-sm',
					'onClick' => 'this.select();',
					'value' => isset($item_info->item_id) ? to_quantity_decimals($item_info->receiving_quantity) : to_quantity_decimals(0)
				]) ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.reorder_level'), 'reorder_level', ['class' => 'required control-label col-xs-3']) ?>
			<div class='col-xs-4'>
				<?php echo form_input ([
					'name' => 'reorder_level',
					'id' => 'reorder_level',
					'class' => 'form-control input-sm',
					'onClick' => 'this.select();',
					'value' => isset($item_info->item_id) ? to_quantity_decimals($item_info->reorder_level) : to_quantity_decimals(0)
				]) ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.description'), 'description', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-8'>
				<?php echo form_textarea ([
						'name' => 'description',
						'id' => 'description',
						'class' => 'form-control input-sm',
						'value' => esc($item_info->description, 'attr')
				]) ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.image'), 'items_image', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-8'>
				<div class="fileinput <?php echo $logo_exists ? 'fileinput-exists' : 'fileinput-new' ?>" data-provides="fileinput">
					<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;"></div>
					<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100px; max-height: 100px;">
						<img data-src="holder.js/100%x100%" alt="<?php echo lang('Items.image') ?>"
							 src="<?php echo esc($image_path, 'url') ?>"
							 style="max-height: 100%; max-width: 100%;">
					</div>
					<div>
						<span class="btn btn-default btn-sm btn-file">
							<span class="fileinput-new"><?php echo lang('Items.select_image') ?></span>
							<span class="fileinput-exists"><?php echo lang('Items.change_image') ?></span>
							<input type="file" name="item_image" accept="image/*">
						</span>
						<a href="#" class="btn btn-default btn-sm fileinput-exists" data-dismiss="fileinput"><?php echo lang('Items.remove_image') ?></a>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.allow_alt_description'), 'allow_alt_description', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-1'>
				<?php echo form_checkbox ([
					'name' => 'allow_alt_description',
					'id' => 'allow_alt_description',
					'value' => 1,
					'checked' => ($item_info->allow_alt_description) ? 1 : 0
				]) ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.is_serialized'), 'is_serialized', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-1'>
				<?php echo form_checkbox ([
					'name' => 'is_serialized',
					'id' => 'is_serialized',
					'value' => 1,
					'checked' => ($item_info->is_serialized) ? 1 : 0
				]) ?>
			</div>
		</div>

		<?php
		if(config('OSPOS')->settings['multi_pack_enabled'] == '1')
		{
			?>
			<div class="form-group form-group-sm">
				<?php echo form_label(lang('Items.qty_per_pack'), 'qty_per_pack', ['class' => 'control-label col-xs-3']) ?>
				<div class='col-xs-4'>
					<?php echo form_input ([
						'name' => 'qty_per_pack',
						'id' => 'qty_per_pack',
						'class' => 'form-control input-sm',
						'value' => isset($item_info->item_id) ? to_quantity_decimals($item_info->qty_per_pack) : to_quantity_decimals(0)
					]) ?>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<?php echo form_label(lang('Items.pack_name'), 'name', ['class' => 'control-label col-xs-3']) ?>
				<div class='col-xs-8'>
					<?php echo form_input ([
						'name' => 'pack_name',
						'id' => 'pack_name',
						'class' => 'form-control input-sm',
						'value' => esc($item_info->pack_name, 'attr')
					]) ?>
				</div>
			</div>
			<div class="form-group  form-group-sm">
				<?php echo form_label(lang('Items.low_sell_item'), 'low_sell_item_name', ['class' => 'control-label col-xs-3']) ?>
				<div class='col-xs-8'>
					<div class="input-group input-group-sm">
						<?php echo form_input ([
							'name' => 'low_sell_item_name',
							'id' => 'low_sell_item_name',
							'class' => 'form-control input-sm',
							'value' => esc($selected_low_sell_item, 'attr')
						]) ?><?php echo form_hidden('low_sell_item_id', $selected_low_sell_item_id) ?>
					</div>
				</div>
			</div>
			<?php
		}
		?>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Items.is_deleted'), 'is_deleted', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-1'>
				<?php echo form_checkbox ([
					'name' => 'is_deleted',
					'id' => 'is_deleted',
					'value'=>1,
					'checked' => ($item_info->deleted) ? 1 : 0
				]) ?>
			</div>
		</div>

	</fieldset>
<?php echo form_close() ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	$('#new').click(function() {
		stay_open = true;
		$('#item_form').submit();
	});

	$('#submit').click(function() {
		stay_open = false;
	});

	$("input[name='tax_category']").change(function() {
		!$(this).val() && $(this).val('');
	});

	var fill_value = function(event, ui) {
		event.preventDefault();
		$("input[name='tax_category_id']").val(ui.item.value);
		$("input[name='tax_category']").val(ui.item.label);
	};

	$('#tax_category').autocomplete({
		source: "<?php echo site_url('taxes/suggest_tax_categories') ?>",
		minChars: 0,
		delay: 15,
		cacheLength: 1,
		appendTo: '.modal-content',
		select: fill_value,
		focus: fill_value
	});

	var fill_value = function(event, ui) {
		event.preventDefault();
		$("input[name='low_sell_item_id']").val(ui.item.value);
		$("input[name='low_sell_item_name']").val(ui.item.label);
	};

	$('#low_sell_item_name').autocomplete({
		source: "<?php echo site_url('items/suggest_low_sell') ?>",
		minChars: 0,
		delay: 15,
		cacheLength: 1,
		appendTo: '.modal-content',
		select: fill_value,
		focus: fill_value
	});

	$('#category').autocomplete({
		source: "<?php echo site_url('items/suggest_category') ?>",
		delay: 10,
		appendTo: '.modal-content'
	});

	$('a.fileinput-exists').click(function() {
		$.ajax({
			type: 'GET',
			url: '<?php echo esc(site_url("$controller_name/remove_logo/$item_info->item_id"), 'url') ?>',
			dataType: 'json'
		})
	});

	$.validator.addMethod('valid_chars', function(value, element) {
		return value.match(/(\||_)/g) == null;
	}, "<?php echo lang('Attributes.attribute_value_invalid_chars') ?>");

	var init_validation = function() {
		$('#item_form').validate($.extend({
			submitHandler: function(form, event) {
				$(form).ajaxSubmit({
					success: function(response) {
						var stay_open = dialog_support.clicked_id() != 'submit';
						if(stay_open)
						{
							// set action of item_form to url without item id, so a new one can be created
							$('#item_form').attr('action', "<?php echo site_url('items/save/') ?>");
							// use a whitelist of fields to minimize unintended side effects
							$(':text, :password, :file, #description, #item_form').not('.quantity, #reorder_level, #tax_name_1, #receiving_quantity, ' +
								'#tax_percent_name_1, #category, #reference_number, #name, #cost_price, #unit_price, #taxed_cost_price, #taxed_unit_price, #definition_name, [name^="attribute_links"]').val('');
							// de-select any checkboxes, radios and drop-down menus
							$(':input', '#item_form').removeAttr('checked').removeAttr('selected');
						}
						else
						{
							dialog_support.hide();
						}
						table_support.handle_submit('<?php echo site_url('items') ?>', response, stay_open);
						init_validation();
					},
					dataType: 'json'
				});
			},

			errorLabelContainer: '#error_message_box',

			rules:
			{
				name: 'required',
				category: 'required',
				item_number:
				{
					required: false,
					remote:
					{
						url: "<?php echo esc(site_url("$controller_name/check_item_number"), 'url') ?>",
						type: 'POST',
						data: {
							'item_id' : "<?php echo $item_info->item_id ?>",
							'item_number' : function()
							{
								return $('#item_number').val();
							},
						}
					}
				},
				cost_price:
				{
					required: true,
					remote: "<?php echo esc(site_url("$controller_name/check_numeric"), 'url') ?>"
				},
				unit_price:
				{
					required: true,
					remote: "<?php echo esc(site_url("$controller_name/check_numeric"), 'url') ?>"
				},
				<?php
				foreach($stock_locations as $key=>$location_detail)
				{
				?>
				<?php echo 'quantity_' . $key ?>:
					{
						required: true,
						remote: "<?php echo esc(site_url("$controller_name/check_numeric"), 'url') ?>"
					},
				<?php
				}
				?>
				receiving_quantity:
				{
					required: true,
					remote: "<?php echo esc(site_url("$controller_name/check_numeric"), 'url') ?>"
				},
				reorder_level:
				{
					required: true,
					remote: "<?php echo esc(site_url("$controller_name/check_numeric"), 'url') ?>"
				},
				tax_percent:
				{
					required: true,
					remote: "<?php echo esc(site_url("$controller_name/check_numeric"), 'url') ?>"
				}
			},

			messages:
			{
				name: "<?php echo lang('Items.name_required') ?>",
				item_number: "<?php echo lang('Items.item_number_duplicate') ?>",
				category: "<?php echo lang('Items.category_required') ?>",
				cost_price:
				{
					required: "<?php echo lang('Items.cost_price_required') ?>",
					number: "<?php echo lang('Items.cost_price_number') ?>"
				},
				unit_price:
				{
					required: "<?php echo lang('Items.unit_price_required') ?>",
					number: "<?php echo lang('Items.unit_price_number') ?>"
				},
				<?php
				foreach($stock_locations as $key => $location_detail)
				{
				?>
				<?php echo esc("quantity_$key", 'js') ?>:
					{
						required: "<?php echo lang('Items.quantity_required') ?>",
						number: "<?php echo lang('Items.quantity_number') ?>"
					},
				<?php
				}
				?>
				receiving_quantity:
				{
					required: "<?php echo lang('Items.quantity_required') ?>",
					number: "<?php echo lang('Items.quantity_number') ?>"
				},
				reorder_level:
				{
					required: "<?php echo lang('Items.reorder_level_required') ?>",
					number: "<?php echo lang('Items.reorder_level_number') ?>"
				},
				tax_percent:
				{
					required: "<?php echo lang('Items.tax_percent_required') ?>",
					number: "<?php echo lang('Items.tax_percent_number') ?>"
				}
			}
		}, form_support.error));
	};

	init_validation();
});
</script>

