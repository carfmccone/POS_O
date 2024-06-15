<?php
/**
 * @var string $title
 * @var string $subtitle
 * @var array $overall_summary_data
 * @var array $details_data
 * @var array $headers
 * @var array $summary_data
 */
?>
<?php echo view('partial/header') ?>

<div id="page_title"><?php echo esc($title) ?></div>

<div id="page_subtitle"><?php echo esc($subtitle) ?></div>

<div id="table_holder">
	<table id="table"></table>
</div>

<div id="report_summary">
	<?php
		foreach($overall_summary_data as $name => $value)
		{
		?>
			<div class="summary_row"><?php echo lang("Reports.$name") . ': ' . to_currency($value) ?></div>
		<?php
		}
	?>
</div>

<script type="text/javascript">
	$(document).ready(function()
	{
	 	<?php echo view('partial/bootstrap_tables_locale') ?>

		var details_data = <?php echo json_encode(esc($details_data, 'js')) ?>;
		<?php
		if($config['customer_reward_enable'] == TRUE && !empty($details_data_rewards))	//TODO: === ?
		{
		?>
			var details_data_rewards = <?php echo json_encode(esc($details_data_rewards, 'js')) ?>;
		<?php
		}
		?>
		var init_dialog = function() {
			<?php
			if(isset($editable))
			{
			?>
				table_support.submit_handler('<?php echo esc(site_url("reports/get_detailed_$editable" . '_row'), 'url') ?>');
				dialog_support.init("a.modal-dlg");
			<?php
			}
			?>
		};

		$('#table')
			.addClass("table-striped")
			.addClass("table-bordered")
			.bootstrapTable({
				columns: <?php echo transform_headers(esc($headers['summary'], 'js'), TRUE) ?>,
				stickyHeader: true,
				stickyHeaderOffsetLeft: $('#table').offset().left + 'px',
				stickyHeaderOffsetRight: $('#table').offset().right + 'px',
				pageSize: <?php echo $config['lines_per_page'] ?>,
				pagination: true,
				sortable: true,
				showColumns: true,
				uniqueId: 'id',
				showExport: true,
				exportDataType: 'all',
				exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
				data: <?php echo json_encode(esc($summary_data, 'js')) ?>,
				iconSize: 'sm',
				paginationVAlign: 'bottom',
				detailView: true,
				escape: false,
				search: true,
				onPageChange: init_dialog,
				onPostBody: function() {
					dialog_support.init("a.modal-dlg");
				},
				onExpandRow: function (index, row, $detail) {
					$detail.html('<table></table>').find("table").bootstrapTable({
						columns: <?php echo transform_headers_readonly(esc($headers['details'], 'js')) ?>,
						data: details_data[(!isNaN(row.id) && row.id) || $(row[0] || row.id).text().replace(/(POS|RECV)\s*/g, '')]
					});

					<?php
					if($config['customer_reward_enable'] == TRUE && !empty($details_data_rewards))
					{
					?>
						$detail.append('<table></table>').find("table").bootstrapTable({
							columns: <?php echo transform_headers_readonly(esc($headers['details_rewards'], 'js')) ?>,
							data: details_data_rewards[(!isNaN(row.id) && row.id) || $(row[0] || row.id).text().replace(/(POS|RECV)\s*/g, '')]
						});
					<?php
					}
					?>
				}
		});

		init_dialog();
	});
</script>

<?php echo view('partial/footer') ?>
