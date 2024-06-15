<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_add_missing_config extends Migration
{
	public function up(): void
	{
		$image_values = [
			['key' => 'account_number', 'value' => ''],  // This has no current maintenance, but it's used in Sales
			['key' => 'category_dropdown', 'value' => ''],
			['key' => 'smtp_host', 'value' => ''],
			['key' => 'smtp_user', 'value' => ''],
			['key' => 'smtp_pass', 'value' => ''],
			['key' => 'login_form', 'value' => ''],
			['key' => 'receiving_calculate_average_price', 'value' => '']
		];

		$this->db->table('app_config')->ignore(true)->insertBatch($image_values);
	}

	public function down(): void
	{
		// no need to remove necessary config values.
	}
}
