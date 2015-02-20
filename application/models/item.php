<?php
class Item extends CI_Model
{
	/*
	Determines if a given item_id is an item
	*/
	function exists($item_id)
	{
		$this->db->from('items');
		$this->db->where('item_id',$item_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
	
	function item_number_exists($item_number,$item_id='')
	{
		$this->db->from('items');
		$this->db->where('item_number', $item_number);
		if (!empty($item_id))
		{
			$this->db->where('item_id !=', $item_id);
		}
		$query=$this->db->get();
		return ($query->num_rows()==1);
	}
	
	function get_total_rows()
	{
		return $this->db->count_all('items');
	}
	
	function get_found_rows($search,$stock_location_id=-1,$low_inventory=0,$is_serialized=0,$no_description)
	{
		
		$this->db->from("items");
		if ($stock_location_id > -1)
		{
			$this->db->join('item_quantities','item_quantities.item_id=items.item_id');
			$this->db->where('location_id',$stock_location_id);
		}
		// open parentheses
		$this->db->where("(name LIKE '%" . $search . "%' OR " .
				"item_number LIKE '" . $search . "%' OR " .
				$this->db->dbprefix('items').".item_id LIKE '" . $search . "%' OR " .
				"category LIKE '%" . $search . "%')");
		// close parentheses
		$this->db->where('deleted', 0);
		if ($low_inventory !=0 )
		{
			$this->db->where('quantity <=', 'reorder_level');
		}
		if ($is_serialized !=0 )
		{
			$this->db->where('serialized', 1);
		}
		if ($no_description!=0 )
		{
			$this->db->where('description','');
		}
		return $this->db->get()->num_rows();
	}

	/*
	Returns all the items
	*/
	function get_all($stock_location_id=-1, $rows = 0, $limit_from = 0)
	{
		$this->db->from('items');
		if ($stock_location_id > -1)
		{
			$this->db->join('item_quantities','item_quantities.item_id=items.item_id');
			$this->db->where('location_id',$stock_location_id);
		}
		$this->db->where('deleted',0);
		$this->db->order_by("name","asc");
		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}
		return $this->db->get();
	}
	
	function count_all()
	{
		$this->db->from('items');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}

	/*
	Gets information about a particular item
	*/
	function get_info($item_id)
	{
		$this->db->from('items');
		$this->db->where('item_id',$item_id);
		
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj=new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('items');

			foreach ($fields as $field)
			{
				$item_obj->$field='';
			}

			return $item_obj;
		}
	}

	/*
	Get an item id given an item number
	*/
	function get_item_id($item_number)
	{
		$this->db->from('items');
		$this->db->where('item_number',$item_number);
        $this->db->where('deleted',0); // Parq 131226
        
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row()->item_id;
		}

		return false;
	}

	/*
	Gets information about multiple items
	*/
	function get_multiple_info($item_ids)
	{
		$this->db->from('items');
		$this->db->where_in('item_id',$item_ids);
		$this->db->order_by('item_id', 'asc');
		return $this->db->get();
	}

	/*
	Inserts or updates a item
	*/
	function save(&$item_data,$item_id=false)
	{
		if (!$item_id or !$this->exists($item_id))
		{
			if($this->db->insert('items',$item_data))
			{
				$item_data['item_id']=$this->db->insert_id();
				return true;
			}
			return false;
		}

		$this->db->where('item_id', $item_id);
		return $this->db->update('items',$item_data);
	}

	/*
	Updates multiple items at once
	*/
	function update_multiple($item_data,$item_ids)
	{
		$this->db->where_in('item_id',$item_ids);
		return $this->db->update('items',$item_data);
	}

	/*
	Deletes one item
	*/
	function delete($item_id)
	{
		$this->db->where('item_id', $item_id);
		return $this->db->update('items', array('deleted' => 1));
	}

	/*
	Deletes a list of items
	*/
	function delete_list($item_ids)
	{
		$this->db->where_in('item_id',$item_ids);
		return $this->db->update('items', array('deleted' => 1));
 	}

 	/*
	Get search suggestions to find items
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('items');
		$this->db->like('name', $search);
		$this->db->where('deleted',0);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->name;
		}

		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->like('category', $search);
		$this->db->order_by("category", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->category;
		}

		$this->db->from('items');
		$this->db->like('item_number', $search);
		$this->db->where('deleted',0);
		$this->db->order_by("item_number", "asc");
		$by_item_number = $this->db->get();
		foreach($by_item_number->result() as $row)
		{
			$suggestions[]=$row->item_number;
		}
/** GARRISON ADDED 4/21/2013 **/
	//Search by description
		$this->db->from('items');
		$this->db->like('description', $search);
		$this->db->where('deleted',0);
		$this->db->order_by("description", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->name;
		}
/** END GARRISON ADDED **/

/** GARRISON ADDED 4/22/2013 **/
	//Search by custom fields
		$this->db->from('items');
		$this->db->like('custom1', $search);
		$this->db->or_like('custom2', $search);
		$this->db->or_like('custom3', $search);
		$this->db->or_like('custom4', $search);
		$this->db->or_like('custom5', $search);
		$this->db->or_like('custom6', $search);
		$this->db->or_like('custom7', $search);
		$this->db->or_like('custom8', $search);
		$this->db->or_like('custom9', $search);
		$this->db->or_like('custom10', $search);
		$this->db->where('deleted',0);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->name;
		}
/** END GARRISON ADDED **/		
		
	//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}

	function get_item_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->like('name', $search);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->item_id.'|'.$row->name;
		}

		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->like('item_number', $search);
		$this->db->order_by("item_number", "asc");
		$by_item_number = $this->db->get();
		foreach($by_item_number->result() as $row)
		{
			$suggestions[]=$row->item_id.'|'.$row->item_number;
		}
/** GARRISON ADDED 4/21/2013 **/
	//Search by description
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->like('description', $search);
		$this->db->order_by("description", "asc");
		$by_description = $this->db->get();
		foreach($by_description->result() as $row)
		{
			$suggestions[]=$row->item_id.'|'.$row->name;
		}
/** END GARRISON ADDED **/	
		/** GARRISON ADDED 4/22/2013 **/		
	//Search by custom fields
		/*$this->db->from('items');
		$this->db->where('deleted',0);
        $this->db->where('stock_type',$stock_type);
		$this->db->like('custom1', $search);
		$this->db->or_like('custom2', $search);
		$this->db->or_like('custom3', $search);
		$this->db->or_like('custom4', $search);
		$this->db->or_like('custom5', $search);
		$this->db->or_like('custom6', $search);
		$this->db->or_like('custom7', $search);
		$this->db->or_like('custom8', $search);
		$this->db->or_like('custom9', $search);
		$this->db->or_like('custom10', $search);
		$this->db->order_by("name", "asc");
		$by_description = $this->db->get();
		foreach($by_description->result() as $row)
		{
			$suggestions[]=$row->item_id.'|'.$row->name;
		}*/
		/** END GARRISON ADDED **/
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	}

	function get_category_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('category');
		$this->db->from('items');
		$this->db->like('category', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("category", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->category;
		}

		return $suggestions;
	}

/** GARRISON ADDED 5/18/2013 **/	
	function get_location_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('location');
		$this->db->from('items');
		$this->db->like('location', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("location", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->location;
		}
	
		return $suggestions;
	}

	function get_custom1_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('custom1');
		$this->db->from('items');
		$this->db->like('custom1', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("custom1", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->custom1;
		}
	
		return $suggestions;
	}
	
	function get_custom2_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('custom2');
		$this->db->from('items');
		$this->db->like('custom2', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("custom2", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->custom2;
		}
	
		return $suggestions;
	}
	
	function get_custom3_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('custom3');
		$this->db->from('items');
		$this->db->like('custom3', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("custom3", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->custom3;
		}
	
		return $suggestions;
	}
	
	function get_custom4_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('custom4');
		$this->db->from('items');
		$this->db->like('custom4', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("custom4", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->custom4;
		}
	
		return $suggestions;
	}
	
	function get_custom5_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('custom5');
		$this->db->from('items');
		$this->db->like('custom5', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("custom5", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->custom5;
		}
	
		return $suggestions;
	}
	
	function get_custom6_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('custom6');
		$this->db->from('items');
		$this->db->like('custom6', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("custom6", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->custom6;
		}
	
		return $suggestions;
	}
	
	function get_custom7_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('custom7');
		$this->db->from('items');
		$this->db->like('custom7', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("custom7", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->custom7;
		}
	
		return $suggestions;
	}
	
	function get_custom8_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('custom8');
		$this->db->from('items');
		$this->db->like('custom8', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("custom8", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->custom8;
		}
	
		return $suggestions;
	}
	
	function get_custom9_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('custom9');
		$this->db->from('items');
		$this->db->like('custom9', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("custom9", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->custom9;
		}
	
		return $suggestions;
	}
	
	function get_custom10_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('custom10');
		$this->db->from('items');
		$this->db->like('custom10', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("custom10", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->custom10;
		}
	
		return $suggestions;
	}
/** END GARRISON ADDED **/	

	/*
	 Persform a search on items
	*/
	function search($search,$stock_location_id=-1,$low_inventory=0,$is_serialized=0,$no_description,$rows = 0,$limit_from = 0)
	{
		$this->db->from("items");
		if ($stock_location_id > -1)
		{
			$this->db->join('item_quantities','item_quantities.item_id=items.item_id');
			$this->db->where('location_id',$stock_location_id);
		}
		// open parentheses
		$this->db->where("(name LIKE '%" . $search . "%' OR " .
				"item_number LIKE '" . $search . "%' OR " .
				$this->db->dbprefix("items").".item_id LIKE '" . $search . "%' OR " .
				"category LIKE '%" . $search . "%')");
		// close parentheses
		$this->db->where('deleted', 0);
		if ($low_inventory !=0 )
		{
			$this->db->where('quantity <=', 'reorder_level');
		}
		if ($is_serialized !=0 )
		{
			$this->db->where('serialized', 1);
		}
		if ($no_description!=0 )
		{
			$this->db->where('description','');
		}
		$this->db->order_by('name', "asc");
		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}
		return $this->db->get();
	}

	function get_categories()
	{
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->order_by("category", "asc");

		return $this->db->get();
	}
	
	/*
	 * changes the cost price of a given item
	 * calculates the average price between received items and items on stock
	 * $item_id : the item which price should be changed
	 * $items_received : the amount of new items received
	 * $new_price : the cost-price for the newly received items
	 * $old_price (optional) : the current-cost-price
	 * 
	 * used in receiving-process to update cost-price if changed
	 * caution: must be used there before item_quantities gets updated, otherwise average price is wrong!
	 * 
	 */
	function change_cost_price($item_id, $items_received, $new_price, $old_price = null)
	{
		if($old_price === null)
		{
			$item_info = $this->get_info($item['item_id']);
			$old_price = $item_info->cost_price;
		}

		$this->db->from('item_quantities');
		$this->db->select_sum('quantity');
        $this->db->where('item_id',$item_id);
		$this->db->join('stock_locations','stock_locations.location_id=item_quantities.location_id');
        $this->db->where('stock_locations.deleted',0);
		$old_total_quantity = $this->db->get()->row()->quantity;

		$total_quantity = $old_total_quantity + $items_received;
		$average_price = bcdiv(bcadd(bcmul($items_received, $new_price), bcmul($old_total_quantity, $old_price)), $total_quantity);

		$data = array('cost_price' => $average_price);
		
		return $this->save($data, $item_id);
	}
    
}
?>