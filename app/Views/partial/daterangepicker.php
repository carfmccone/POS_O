<?php
/**
 * @var array $config
 */

	if(empty($config['date_or_time_format']))
	{
?>
		$('#daterangepicker').css("width","180");
		var start_date = "<?= date('Y-m-d') ?>";
		var end_date   = "<?= date('Y-m-d') ?>";

		$('#daterangepicker').daterangepicker({
			"ranges": {
				"<?= lang('Datepicker.today') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d"),date("Y"))) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")+1,date("Y"))-1) ?>"
				],
				"<?= lang('Datepicker.today_last_year') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d"),date("Y")-1)) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")+1,date("Y")-1)-1) ?>"
				],
				"<?= lang('Datepicker.yesterday') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")-1,date("Y"))) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d"),date("Y"))-1) ?>"
				],
				"<?= lang('Datepicker.last_7') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")-6,date("Y"))) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")+1,date("Y"))-1) ?>"
				],
				"<?= lang('Datepicker.last_30') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")-29,date("Y"))) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")+1,date("Y"))-1) ?>"
				],
				"<?= lang('Datepicker.this_month') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),1,date("Y"))) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m")+1,1,date("Y"))-1) ?>"
				],
				"<?= lang('Datepicker.same_month_to_same_day_last_year') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),1,date("Y")-1)) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")+1,date("Y")-1)-1) ?>"
				],
				"<?= lang('Datepicker.same_month_last_year') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),1,date("Y")-1)) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m")+1,1,date("Y")-1)-1) ?>"
				],
				"<?= lang('Datepicker.last_month') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,date("m")-1,1,date("Y"))) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),1,date("Y"))-1) ?>"
				],
				"<?= lang('Datepicker.this_year') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,1,1,date("Y"))) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),1,date("Y")+1)-1) ?>"
				],
				"<?= lang('Datepicker.last_year') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,1,1,date("Y")-1)) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,1,1,date("Y"))-1) ?>"
				],
				"<?= lang('Datepicker.this_financial_year') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,$config['financial_year'],1,date("Y"))) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),1,date("Y")+1)-1) ?>"
				],
				"<?= lang('Datepicker.last_financial_year') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,$config['financial_year'],1,date("Y")-1)) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,$config['financial_year'],1,date("Y"))-1) ?>"
				],
				"<?= lang('Datepicker.all_time') ?>": [
					"<?= date($config['dateformat'], mktime(0,0,0,1,1,2010)) ?>",
					"<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")+1,date("Y"))-1) ?>"
				],
			},
			"locale": {
				"format": '<?= dateformat_momentjs($config['dateformat']) ?>',
				"separator": " - ",
				"applyLabel": "<?= lang('Datepicker.apply') ?>",
				"cancelLabel": "<?= lang('Datepicker.cancel') ?>",
				"fromLabel": "<?= lang('Datepicker.from') ?>",
				"toLabel": "<?= lang('Datepicker.to') ?>",
				"customRangeLabel": "<?= lang('Datepicker.custom') ?>",
				"daysOfWeek": [
					"<?= lang('Cal.su') ?>",
					"<?= lang('Cal.mo') ?>",
					"<?= lang('Cal.tu') ?>",
					"<?= lang('Cal.we') ?>",
					"<?= lang('Cal.th') ?>",
					"<?= lang('Cal.fr') ?>",
					"<?= lang('Cal.sa') ?>",
					"<?= lang('Cal.su') ?>"
				],
				"monthNames": [
					"<?= lang('Cal.january') ?>",
					"<?= lang('Cal.february') ?>",
					"<?= lang('Cal.march') ?>",
					"<?= lang('Cal.april') ?>",
					"<?= lang('Cal.may') ?>",
					"<?= lang('Cal.june') ?>",
					"<?= lang('Cal.july') ?>",
					"<?= lang('Cal.august') ?>",
					"<?= lang('Cal.september') ?>",
					"<?= lang('Cal.october') ?>",
					"<?= lang('Cal.november') ?>",
					"<?= lang('Cal.december') ?>"
				],
				"firstDay": <?= lang('Datepicker.weekstart') ?>
			},
			"alwaysShowCalendars": true,
			"startDate": "<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")+1,date("Y"))-1) ?>",
			"endDate": "<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")+1,date("Y"))-1) ?>",
			"minDate": "<?= date($config['dateformat'], mktime(0,0,0,01,01,2010)) ?>",
			"maxDate": "<?= date($config['dateformat'], mktime(0,0,0,date("m"),date("d")+1,date("Y"))-1) ?>"
		}, function(start, end, label) {
			start_date = start.format('YYYY-MM-DD');
			end_date = end.format('YYYY-MM-DD');
		});
<?php
	}
	else
	{
?>
		$('#daterangepicker').css("width","305");
		var start_date = "<?= date('Y-m-d H:i:s', mktime(0,0,0,date("m"),date("d"),date("Y"))) ?>";
		var end_date = "<?= date('Y-m-d H:i:s', mktime(23,59,59,date("m"),date("d"),date("Y"))) ?>";
		$('#daterangepicker').daterangepicker({
			"ranges": {
				"<?= lang('Datepicker.today') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['dateformat'], mktime(0,0,0,date("m"),date("d"),date("Y"))) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m"),date("d"),date("Y"))) ?>"
				],
				"<?= lang('Datepicker.today_last_year') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,date("m"),date("d"),date("Y")-1)) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m"),date("d"),date("Y")-1)) ?>"
				],
				"<?= lang('Datepicker.yesterday') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,date("m"),date("d")-1,date("Y"))) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m"),date("d")-1,date("Y"))) ?>"
				],
				"<?= lang('Datepicker.last_7') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,date("m"),date("d")-6,date("Y"))) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m"),date("d"),date("Y"))) ?>"
				],
				"<?= lang('Datepicker.last_30') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,date("m"),date("d")-29,date("Y"))) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m"),date("d"),date("Y"))) ?>"
				],
				"<?= lang('Datepicker.this_month') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,date("m"),1,date("Y"))) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m"),date("d"),date("Y"))) ?>"
				],
				"<?= lang('Datepicker.same_month_to_same_day_last_year') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,date("m"),1,date("Y")-1)) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m"),date("d"),date("Y")-1)) ?>"
				],
				"<?= lang('Datepicker.same_month_last_year') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,date("m"),1,date("Y")-1)) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m")+1,0,date("Y")-1)) ?>"
				],
				"<?= lang('Datepicker.last_month') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,date("m")-1,1,date("Y"))) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m"),0,date("Y"))) ?>"
				],
				"<?= lang('Datepicker.this_year') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,1,1,date("Y"))) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m")+1,0,date("Y"))) ?>"
				],
				"<?= lang('Datepicker.last_year') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,1,1,date("Y")-1)) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,12,31,date("Y")-1)) ?>"
				],
				"<?= lang('Datepicker.this_financial_year') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,$config['financial_year'],1,date("Y"))) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m")+1,0,date("Y"))) ?>"
				],
				"<?= lang('Datepicker.last_financial_year') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,$config['financial_year'],1,date("Y")-1)) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,$config['financial_year'],0,date("Y"))) ?>"
				],
				"<?= lang('Datepicker.all_time') ?>": [
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,1,1,2010)) ?>",
					"<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m"),date("d"),date("Y"))) ?>"
				],
			},
			"locale": {
				"format": '<?= dateformat_momentjs($config['dateformat'] . ' ' . $config['timeformat']) ?>',
				"separator": " - ",
				"applyLabel": "<?= lang('Datepicker.apply') ?>",
				"cancelLabel": "<?= lang('Datepicker.cancel') ?>",
				"fromLabel": "<?= lang('Datepicker.from') ?>",
				"toLabel": "<?= lang('Datepicker.to') ?>",
				"customRangeLabel": "<?= lang('Datepicker.custom') ?>",
				"daysOfWeek": [
					"<?= lang('Cal.su') ?>",
					"<?= lang('Cal.mo') ?>",
					"<?= lang('Cal.tu') ?>",
					"<?= lang('Cal.we') ?>",
					"<?= lang('Cal.th') ?>",
					"<?= lang('Cal.fr') ?>",
					"<?= lang('Cal.sa') ?>",
					"<?= lang('Cal.su') ?>"
				],
				"monthNames": [
					"<?= lang('Cal.january') ?>",
					"<?= lang('Cal.february') ?>",
					"<?= lang('Cal.march') ?>",
					"<?= lang('Cal.april') ?>",
					"<?= lang('Cal.may') ?>",
					"<?= lang('Cal.june') ?>",
					"<?= lang('Cal.july') ?>",
					"<?= lang('Cal.august') ?>",
					"<?= lang('Cal.september') ?>",
					"<?= lang('Cal.october') ?>",
					"<?= lang('Cal.november') ?>",
					"<?= lang('Cal.december') ?>"
				],
				"firstDay": <?= lang('Datepicker.weekstart') ?>
			},
		    "timePicker": true,
		    "timePickerSeconds": true,
			"alwaysShowCalendars": true,
			"startDate": "<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,date("m"),date("d"),date("Y"))) ?>",
			"endDate": "<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m"),date("d"),date("Y"))) ?>",
			"minDate": "<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(0,0,0,01,01,2010)) ?>",
			"maxDate": "<?= date($config['dateformat'] . ' ' . $config['timeformat'],mktime(23,59,59,date("m"),date("d"),date("Y"))) ?>"
		}, function(start, end, label) {
			start_date = start.format('YYYY-MM-DD HH:mm:ss');
			end_date = end.format('YYYY-MM-DD HH:mm:ss');
		});
<?php
	}
?>
