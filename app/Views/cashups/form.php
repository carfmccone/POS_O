<?php
/**
 * @var object $cash_ups_info
 * @var array $employees
 * @var string $controller_name
 */
?>
<div id="required_fields_message"><?php echo lang('Common.fields_required_message') ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open(esc('cashups/save/'.$cash_ups_info->cashup_id, 'attr'), ['id' => 'cashups_edit_form', 'class' => 'form-horizontal']) //TODO: String Interpolation ?>
	<fieldset id="item_basic_info">
		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.info'), 'cash_ups_info', ['class' => 'control-label col-xs-3']) ?>
			<?php echo form_label(!empty($cash_ups_info->cashup_id) ? lang('Cashups.id') . ' ' . $cash_ups_info->cashup_id : '', 'cashup_id', ['class' => 'control-label col-xs-8', 'style' => 'text-align:left']) ?>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.open_date'), 'open_date', ['class' => 'required control-label col-xs-3']) ?>
			<div class='col-xs-6'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
					<?php echo form_input ([
							'name' => 'open_date',
							'id' => 'open_date',
							'class' => 'form-control input-sm datepicker',
							'value'=>to_datetime(strtotime($cash_ups_info->open_date))]
							) ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.open_employee'), 'open_employee', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('open_employee_id', esc($employees, 'attr'), $cash_ups_info->open_employee_id, 'id="open_employee_id" class="form-control"') ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.open_amount_cash'), 'open_amount_cash', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
					<?php echo form_input ([
						'name' => 'open_amount_cash',
						'id' => 'open_amount_cash',
						'class' => 'form-control input-sm',
						'value' => to_currency_no_money($cash_ups_info->open_amount_cash)
					]) ?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.transfer_amount_cash'), 'transfer_amount_cash', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
					<?php echo form_input ([
							'name' => 'transfer_amount_cash',
							'id' => 'transfer_amount_cash',
							'class' => 'form-control input-sm',
							'value'=>to_currency_no_money($cash_ups_info->transfer_amount_cash)
						])
					?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.close_date'), 'close_date', ['class' => 'required control-label col-xs-3']) ?>
			<div class='col-xs-6'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
					<?php echo form_input ([
							'name' => 'close_date',
							'id' => 'close_date',
							'class' => 'form-control input-sm datepicker',
							'value'=>to_datetime(strtotime($cash_ups_info->close_date))]
							) ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.close_employee'), 'close_employee', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('close_employee_id', esc($employees, 'attr'), $cash_ups_info->close_employee_id, 'id="close_employee_id" class="form-control"') ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.closed_amount_cash'), 'closed_amount_cash', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
					<?php echo form_input ([
							'name' => 'closed_amount_cash',
							'id' => 'closed_amount_cash',
							'class' => 'form-control input-sm',
							'value'=>to_currency_no_money($cash_ups_info->closed_amount_cash)]
							) ?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.note'), 'note', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-6'>
				<?php echo form_checkbox ([
					'name' => 'note',
					'id' => 'note',
					'value'=>0,
					'checked'=>($cash_ups_info->note) ? 1 : 0]
				) ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.closed_amount_due'), 'closed_amount_due', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
					<?php echo form_input ([
							'name' => 'closed_amount_due',
							'id' => 'closed_amount_due',
							'class' => 'form-control input-sm',
							'value'=>to_currency_no_money($cash_ups_info->closed_amount_due)]
							) ?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.closed_amount_card'), 'closed_amount_card', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
					<?php echo form_input ([
							'name' => 'closed_amount_card',
							'id' => 'closed_amount_card',
							'class' => 'form-control input-sm',
							'value'=>to_currency_no_money($cash_ups_info->closed_amount_card)]
							) ?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.closed_amount_check'), 'closed_amount_check', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
					<?php echo form_input ([
							'name' => 'closed_amount_check',
							'id' => 'closed_amount_check',
							'class' => 'form-control input-sm',
							'value'=>to_currency_no_money($cash_ups_info->closed_amount_check)]
							) ?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.closed_amount_total'), 'closed_amount_total', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
					<?php echo form_input ([
							'name' => 'closed_amount_total',
							'id' => 'closed_amount_total',
							'readonly' => 'true',
							'class' => 'form-control input-sm',
							'value'=>to_currency_no_money($cash_ups_info->closed_amount_total)
						]
					) ?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo esc(config('OSPOS')->currency_symbol) ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label(lang('Cashups.description'), 'description', ['class' => 'control-label col-xs-3']) ?>
			<div class='col-xs-6'>
				<?php echo form_textarea ([
					'name' => 'description',
					'id' => 'description',
					'class' => 'form-control input-sm',
					'value'=>esc($cash_ups_info->description)
					]
				) ?>
			</div>
		</div>

		<?php
		if(!empty($cash_ups_info->cashup_id))
		{
		?>
			<div class="form-group form-group-sm">
				<?php echo form_label(lang('Cashups.is_deleted').':', 'deleted', ['class' => 'control-label col-xs-3']) ?>
				<div class='col-xs-5'>
					<?php echo form_checkbox ([
						'name' => 'deleted',
						'id' => 'deleted',
						'value'=>1,
						'checked'=>($cash_ups_info->deleted) ? 1 : 0]
					) ?>
				</div>
			</div>
		<?php
		}
		?>
	</fieldset>
<?php echo form_close() ?>

<script type='text/javascript'>
//validation and submit handling
$(document).ready(function()
{
	<?php echo view('partial/datepicker_locale') ?>

	$('#open_date').datetimepicker({
		format: "<?php echo dateformat_bootstrap(config('OSPOS')->dateformat) . ' ' . dateformat_bootstrap(config('OSPOS')->timeformat) ?>",
		startDate: "<?php echo date(config('OSPOS')->dateformat . ' ' . esc(config('OSPOS')->timeformat, 'js'), mktime(0, 0, 0, 1, 1, 2010)) ?>",
		<?php
		$t = config('OSPOS')->timeformat;
		$m = $t[strlen($t)-1];
		if( strpos(config('OSPOS')->timeformat, 'a') !== false || strpos(config('OSPOS')->timeformat, 'A') !== false )
		{
		?>
			showMeridian: true,
		<?php
		}
		else
		{
		?>
			showMeridian: false,
		<?php
		}
		?>
		minuteStep: 1,
		autoclose: true,
		todayBtn: true,
		todayHighlight: true,
		bootcssVer: 3,
		language: '<?php echo current_language_code() ?>'
	});

	$('#close_date').datetimepicker({
		format: "<?php echo dateformat_bootstrap(config('OSPOS')->dateformat) . ' ' . dateformat_bootstrap(config('OSPOS')->timeformat) ?>",
		startDate: "<?php echo date(config('OSPOS')->dateformat . ' ' . esc(config('OSPOS')->timeformat, 'js'), mktime(0, 0, 0, 1, 1, 2010)) ?>",
		<?php
		$t = config('OSPOS')->timeformat;
		$m = $t[strlen($t)-1];
		if( strpos(config('OSPOS')->timeformat, 'a') !== false || strpos(config('OSPOS')->timeformat, 'A') !== false )
		{
		?>
			showMeridian: true,
		<?php
		}
		else
		{
		?>
			showMeridian: false,
		<?php
		}
		?>
		minuteStep: 1,
		autoclose: true,
		todayBtn: true,
		todayHighlight: true,
		bootcssVer: 3,
		language: '<?php echo current_language_code() ?>'
	});

	$('#open_amount_cash, #transfer_amount_cash, #closed_amount_cash, #closed_amount_due, #closed_amount_card, #closed_amount_check').keyup(function() {
		$.post("<?php echo esc(site_url("$controller_name/ajax_cashup_total"), 'url') ?>", {
				'open_amount_cash': $('#open_amount_cash').val(),
				'transfer_amount_cash': $('#transfer_amount_cash').val(),
				'closed_amount_due': $('#closed_amount_due').val(),
				'closed_amount_cash': $('#closed_amount_cash').val(),
				'closed_amount_card': $('#closed_amount_card').val(),
				'closed_amount_check': $('#closed_amount_check').val()
			},
			function(response) {
				$('#closed_amount_total').val(response.total);
			},
			'json'
		);
	});

	var submit_form = function()
	{
		$(this).ajaxSubmit(
		{
			success: function(response)
			{
				dialog_support.hide();
				table_support.handle_submit('<?php echo esc(site_url('cashups'), 'url') ?>', response);
			},
			dataType: 'json'
		});
	};

	$('#cashups_edit_form').validate($.extend(
	{
		submitHandler: function(form)
		{
			submit_form.call(form);
		},
		rules:
		{

		},
		messages:
		{
			open_date:
			{
				required: '<?php echo lang('Cashups.date_required') ?>'

			},
			close_date:
			{
				required: '<?php echo lang('Cashups.date_required') ?>'

			},
			amount:
			{
				required: '<?php echo lang('Cashups.amount_required') ?>',
				number: '<?php echo lang('Cashups.amount_number') ?>'
			}
		}
	}, form_support.error));
});
</script>
