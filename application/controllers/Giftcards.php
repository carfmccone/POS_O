<?php
require_once ("Secure_area.php");
require_once ("interfaces/Idata_controller.php");

class Giftcards extends Secure_area implements iData_controller
{
	function __construct()
	{
		parent::__construct('giftcards');
	}

	public function index($limit_from = 0)
	{
		$data['controller_name'] = $this->get_controller_name();
		$data['table_headers'] = get_giftcards_manage_table_headers();

		$data = $this->security->xss_clean($data);

		$this->load->view('giftcards/manage', $data);
	}

	/*
	Returns Giftcards table data rows. This will be called with AJAX.
	*/
	public function search()
	{
		$search = $this->input->get('search');
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort   = $this->input->get('sort');
		$order  = $this->input->get('order');

		$giftcards = $this->Giftcard->search($search, $limit, $offset, $sort, $order);
		$total_rows = $this->Giftcard->get_found_rows($search);

		$data_rows = array();
		foreach($giftcards->result() as $giftcard)
		{
			$data_rows[] = get_giftcard_data_row($giftcard, $this);
		}

		$data_rows = $this->security->xss_clean($data_rows);

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	public function suggest_search()
	{
		$suggestions = $this->Giftcard->get_search_suggestions($this->input->post('term'));

		$suggestions = $this->security->xss_clean($suggestions);

		echo json_encode($suggestions);
	}

	public function get_row($row_id)
	{
		$data_row = get_giftcard_data_row($this->Giftcard->get_info($row_id), $this);

		$data_row = $this->security->xss_clean($data_row);

		echo json_encode($data_row);
	}

	public function view($giftcard_id = -1)
	{
		$giftcard_info = $this->Giftcard->get_info($giftcard_id);

		$data['selected_person_name'] = ($giftcard_id > 0 && isset($giftcard_info->person_id)) ? $giftcard_info->first_name . ' ' . $giftcard_info->last_name : '';
		$data['selected_person_id']   = $giftcard_info->person_id;
		$data['giftcard_number']      = $giftcard_id > 0 ? $giftcard_info->giftcard_number : $this->Giftcard->get_max_number()->giftcard_number + 1;
		$data['giftcard_id']          = $giftcard_id;
		$data['giftcard_value']       = $giftcard_info->value;

		$data = $this->security->xss_clean($data);

		$this->load->view("giftcards/form", $data);
	}
	
	public function save($giftcard_id = -1)
	{
		$giftcard_data = array(
			'record_time' => date('Y-m-d H:i:s'),
			'giftcard_number' => $this->input->post('giftcard_number'),
			'value' => $this->input->post('value'),
			'person_id' => $this->input->post('person_id')
		);

		if($this->Giftcard->save($giftcard_data, $giftcard_id))
		{
			//New giftcard
			if($giftcard_id == -1)
			{
				echo json_encode(array('success'=>true, 'message'=>$this->lang->line('giftcards_successful_adding').' '.
								$giftcard_data['giftcard_number'], 'id'=>$giftcard_data['giftcard_id']));
			}
			else //Existing giftcard
			{
				echo json_encode(array('success'=>true, 'message'=>$this->lang->line('giftcards_successful_updating').' '.
								$giftcard_data['giftcard_number'], 'id'=>$giftcard_id));
			}
		}
		else //failure
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('giftcards_error_adding_updating').' '.
							$giftcard_data['giftcard_number'], 'id'=>-1));
		}
	}

	public function delete()
	{
		$giftcards_to_delete = $this->input->post('ids');

		$giftcards_to_delete = $this->security->xss_clean($giftcards_to_delete);

		if($this->Giftcard->delete_list($giftcards_to_delete))
		{
			echo json_encode(array('success'=>true, 'message'=>$this->lang->line('giftcards_successful_deleted').' '.
							count($giftcards_to_delete).' '.$this->lang->line('giftcards_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false, 'message'=>$this->lang->line('giftcards_cannot_be_deleted')));
		}
	}
}
?>
