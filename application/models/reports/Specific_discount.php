<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Report.php");

class Specific_discount extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function create(array $inputs)
	{
		//Create our temp tables to work with the data in our report
		$this->Sale->create_temp_table($inputs);
	}
	
	public function getDataColumns()
	{
		return array(
			'summary' => array(
				array('id' => $this->lang->line('reports_sale_id')),
				array('sale_date' => $this->lang->line('reports_date')),
				array('quantity' => $this->lang->line('reports_quantity')),
				array('customer_name' => $this->lang->line('reports_sold_to')),
				array('subtotal' => $this->lang->line('reports_subtotal'), 'sorter' => 'currency_sorter'),
				array('tax' => $this->lang->line('reports_tax'), 'sorter' => 'currency_sorter'),
				array('total' => $this->lang->line('reports_total'), 'sorter' => 'currency_sorter'),
				array('profit' => $this->lang->line('reports_profit'), 'sorter' => 'currency_sorter'),
				array('payment_type' => $this->lang->line('reports_payment_type')),
				array('comments' => $this->lang->line('reports_comments'))),
			'details' => array(
				$this->lang->line('reports_name'),
				$this->lang->line('reports_category'),
				$this->lang->line('reports_serial_number'),
				$this->lang->line('reports_description'),
				$this->lang->line('reports_quantity'),
				$this->lang->line('reports_subtotal'),
				$this->lang->line('reports_total'),
				$this->lang->line('reports_tax'),
				$this->lang->line('reports_profit'),
				$this->lang->line('reports_discount'))
		);		
	}
	
	public function getData(array $inputs)
	{
		$this->db->select('sale_id, sale_date, SUM(quantity_purchased) AS items_purchased, customer_name, SUM(subtotal) AS subtotal, SUM(tax) AS tax, SUM(total) AS total, SUM(cost) AS cost, SUM(profit) AS profit, payment_type, comment');
		$this->db->from('sales_items_temp');
		$this->db->where('discount_percent >=', $inputs['discount']);

		if ($inputs['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($inputs['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}

		$this->db->group_by('sale_id');
		$this->db->order_by('sale_date');

		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();
		
		foreach($data['summary'] as $key=>$value)
		{
			$this->db->select('name, serialnumber, category, description, quantity_purchased, subtotal, tax, total, cost, profit, discount_percent');
			$this->db->from('sales_items_temp');
			$this->db->where('sale_id', $value['sale_id']);
			$data['details'][$key] = $this->db->get()->result_array();
		}

		return $data;
	}
	
	public function getSummaryData(array $inputs)
	{
		$this->db->select('SUM(subtotal) AS subtotal, SUM(tax) AS tax, SUM(total) AS total, SUM(cost) AS cost, SUM(profit) AS profit');
		$this->db->from('sales_items_temp');
		$this->db->where('discount_percent >=', $inputs['discount']);
			
		if ($inputs['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($inputs['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}

		return $this->db->get()->row_array();
	}
}
?>