<?php
/**
 * @var string $controller_name
 * @var object $person_info
 * @var array $categories
 */
?>
<div id="required_fields_message"><?php echo lang('Common.fields_required_message') ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open(esc("$controller_name/save/$person_info->person_id", 'url'), ['id' => 'supplier_form', 'class' => 'form-horizontal']) ?>
	<fieldset id="supplier_basic_info">
		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Suppliers.company_name'), 'company_name', ['class' => 'required control-label col-xs-3']) ?>
			<div class='col-xs-8'>
				<?php echo form_input([
					'name' => 'company_name',
					'id' => 'company_name_input',
					'class' => 'form-control input-sm',
					'value' => esc($person_info->company_name)
					])
				?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Suppliers.category'), 'category', ['class' => 'required control-label col-xs-3']) ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('category', esc($categories), esc($person_info->category), ['class' => 'form-control', 'id' => 'category']) ?>
			</div>
		</div>

		<div class="form-group form-group-sm">	
			<?php echo form_label(lang('Suppliers.agency_name'), 'agency_name', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-8'>
				<?php echo form_input ([
					'name' => 'agency_name',
					'id' => 'agency_name_input',
					'class' => 'form-control input-sm',
					'value' => esc($person_info->agency_name)
					])
				?>
			</div>
		</div>

		<?php echo view('people/form_basic_info') ?>

		<div class="form-group form-group-sm">	
			<?php echo form_label(lang('Suppliers.account_number'), 'account_number', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-8'>
				<?php echo form_input ([
					'name' => 'account_number',
					'id' => 'account_number',
					'class' => 'form-control input-sm',
					'value' => esc($person_info->account_number)
					])
				?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Suppliers.tax_id'), 'tax_id', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-8'>
				<?php echo form_input ([
						'name' => 'tax_id',
						'id' => 'tax_id',
						'class' => 'form-control input-sm',
						'value' => esc($person_info->tax_id)
					])
				?>
			</div>
		</div>
	</fieldset>
<?php echo form_close() ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	$('#supplier_form').validate($.extend({
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				success: function(response)
				{
					dialog_support.hide();
					table_support.handle_submit("<?php echo esc($controller_name) ?>", response);
				},
				dataType: 'json'
			});
		},

		errorLabelContainer: '#error_message_box',
 
		rules:
		{
			company_name: 'required',
			first_name: 'required',
			last_name: 'required',
			email: 'email'
   		},

		messages: 
		{
			company_name: "<?php echo lang('Suppliers.company_name_required') ?>",
			first_name: "<?php echo lang('Common.first_name_required') ?>",
			last_name: "<?php echo lang('Common.last_name_required') ?>",
			email: "<?php echo lang('Common.email_invalid_format') ?>"
		}
	}, form_support.error));
});
</script>
