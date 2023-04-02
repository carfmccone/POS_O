<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IntToTinyint extends Migration
{
	public function up(): void
	{
		$this->db->query('ALTER TABLE ' . $this->db->prefixTable('customers') . ' MODIFY `consent` tinyint NOT NULL DEFAULT 0');
		$this->db->query('ALTER TABLE ' . $this->db->prefixTable('cash_up') . ' MODIFY `note` tinyint NOT NULL DEFAULT 0');
	}

	public function down()
	{
		$this->db->query('ALTER TABLE ' . $this->db->prefixTable('customers') . ' MODIFY `consent` int NOT NULL DEFAULT 0');
		$this->db->query('ALTER TABLE ' . $this->db->prefixTable('cash_up') . ' MODIFY `note` int NOT NULL DEFAULT 0');

	}
}
