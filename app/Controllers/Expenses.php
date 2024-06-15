<?php

namespace App\Controllers;

use app\Models\Expense;
use app\Models\Expense_category;

/**
 * @property expense expense
 * @property expense_category expense_category
 */
class Expenses extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('expenses');

		$this->expense = model('Expense');
		$this->expense_category = model('Expense_category');
	}

	public function index(): void
	{
		$data['table_headers'] = get_expenses_manage_table_headers();

		// filters that will be loaded in the multiselect dropdown
		$data['filters'] = [
			'only_cash' => lang('Expenses.cash_filter'),
			'only_due' => lang('Expenses.due_filter'),
			'only_check' => lang('Expenses.check_filter'),
			'only_credit' => lang('Expenses.credit_filter'),
			'only_debit' => lang('Expenses.debit_filter'),
			'is_deleted' => lang('Expenses.is_deleted')
		];

		echo view('expenses/manage', $data);
	}

	public function search(): void
	{
		$search   = $this->request->getGet('search', FILTER_SANITIZE_STRING);
		$limit    = $this->request->getGet('limit', FILTER_SANITIZE_NUMBER_INT);
		$offset   = $this->request->getGet('offset', FILTER_SANITIZE_NUMBER_INT);
		$sort     = $this->request->getGet('sort', FILTER_SANITIZE_STRING);
		$order    = $this->request->getGet('order', FILTER_SANITIZE_STRING);
		$filters  = [
			'start_date' => $this->request->getGet('start_date', FILTER_SANITIZE_STRING),
			'end_date' => $this->request->getGet('end_date', FILTER_SANITIZE_STRING),
			'only_cash' => FALSE,
			'only_due' => FALSE,
			'only_check' => FALSE,
			'only_credit' => FALSE,
			'only_debit' => FALSE,
			'is_deleted' => FALSE
		];

		// check if any filter is set in the multiselect dropdown
		$filledup = array_fill_keys($this->request->getGet('filters', FILTER_SANITIZE_STRING), TRUE);	//TODO: variable naming does not match standard
		$filters = array_merge($filters, $filledup);
		$expenses = $this->expense->search($search, $filters, $limit, $offset, $sort, $order);
		$total_rows = $this->expense->get_found_rows($search, $filters);
		$payments = $this->expense->get_payments_summary($search, $filters);
		$payment_summary = get_expenses_manage_payments_summary($payments, $expenses);
		$data_rows = [];

		foreach($expenses->getResult() as $expense)
		{
			$data_rows[] = get_expenses_data_row($expense);
		}

		if($total_rows > 0)
		{
			$data_rows[] = get_expenses_data_last_row($expenses);
		}

		echo json_encode (['total' => $total_rows, 'rows' => $data_rows, 'payment_summary' => $payment_summary]);
	}

	public function view(int $expense_id = -1): void	//TODO: Replace -1 with a constant
	{
		$data = [];	//TODO: Duplicated code

		$data['employees'] = [];
		foreach($this->employee->get_all()->getResult() as $employee)
		{
			foreach(get_object_vars($employee) as $property => $value)
			{
				$employee->$property = $value;
			}

			$data['employees'][$employee->person_id] = $employee->first_name . ' ' . $employee->last_name;
		}

		$data['expenses_info'] = $this->expense->get_info($expense_id);

		$expense_categories = [];
		foreach($this->expense_category->get_all(0, 0, TRUE)->getResultArray() as $row)
		{
			$expense_categories[$row['expense_category_id']] = $row['category_name'];
		}
		$data['expense_categories'] = $expense_categories;

		$expense_id = $data['expenses_info']->expense_id;

		if(empty($expense_id))
		{
			$data['expenses_info']->date = date('Y-m-d H:i:s');
			$data['expenses_info']->employee_id = $this->employee->get_logged_in_employee_info()->person_id;
		}

		$data['payments'] = [];
		foreach($this->expense->get_expense_payment($expense_id)->getResult() as $payment)
		{
			foreach(get_object_vars($payment) as $property => $value)
			{
				$payment->$property = $value;
			}

			$data['payments'][] = $payment;
		}

		// don't allow gift card to be a payment option in a sale transaction edit because it's a complex change
		$data['payment_options'] = $this->expense->get_payment_options();

		echo view("expenses/form", $data);
	}

	public function get_row(int $row_id)
	{
		$expense_info = $this->expense->get_info($row_id);
		$data_row = get_expenses_data_row($expense_info);

		echo json_encode($data_row);
	}

	public function save(int $expense_id = -1): void	//TODO: Replace -1 with a constant
	{
		$newdate = $this->request->getPost('date', FILTER_SANITIZE_STRING);

		$date_formatter = date_create_from_format(config('OSPOS')->dateformat . ' ' . config('OSPOS')->timeformat, $newdate);

		$expense_data = [
			'date' => $date_formatter->format('Y-m-d H:i:s'),
			'supplier_id' => $this->request->getPost('supplier_id') == '' ? NULL : $this->request->getPost('supplier_id', FILTER_SANITIZE_NUMBER_INT),
			'supplier_tax_code' => $this->request->getPost('supplier_tax_code', FILTER_SANITIZE_STRING),
			'amount' => parse_decimals($this->request->getPost('amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)),
			'tax_amount' => parse_decimals($this->request->getPost('tax_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)),
			'payment_type' => $this->request->getPost('payment_type', FILTER_SANITIZE_STRING),
			'expense_category_id' => $this->request->getPost('expense_category_id', FILTER_SANITIZE_NUMBER_INT),
			'description' => $this->request->getPost('description', FILTER_SANITIZE_STRING),
			'employee_id' => $this->request->getPost('employee_id', FILTER_SANITIZE_NUMBER_INT),
			'deleted' => $this->request->getPost('deleted') != NULL
		];

		if($this->expense->save_value($expense_data, $expense_id))
		{
			//New Expense
			if($expense_id == -1)
			{
				echo json_encode (['success' => TRUE, 'message' => lang('Expenses.successful_adding'), 'id' => $expense_data['expense_id']]);
			}
			else // Existing Expense
			{
				echo json_encode (['success' => TRUE, 'message' => lang('Expenses.successful_updating'), 'id' => $expense_id]);
			}
		}
		else//failure
		{
			echo json_encode (['success' => FALSE, 'message' => lang('Expenses.error_adding_updating'), 'id' => -1]);	//TODO: Need to replace -1 with a constant
		}
	}

	public function ajax_check_amount(): void
	{
		$value = $this->request->getPost(NULL, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$parsed_value = parse_decimals(array_pop($value));
		echo json_encode (['success' => $parsed_value !== FALSE]);
	}

	public function delete(): void
	{
		$expenses_to_delete = $this->request->getPost('ids', FILTER_SANITIZE_STRING);

		if($this->expense->delete_list($expenses_to_delete))
		{
			echo json_encode (['success' => TRUE, 'message' => lang('Expenses.successful_deleted') . ' ' . count($expenses_to_delete) . ' ' . lang('Expenses.one_or_multiple'), 'ids' => $expenses_to_delete]);
		}
		else
		{
			echo json_encode (['success' => FALSE, 'message' => lang('Expenses.cannot_be_deleted'), 'ids' => $expenses_to_delete]);
		}
	}
}