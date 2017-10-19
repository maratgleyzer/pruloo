<?php

class Paging
{
	
	public function __construct() { }
		
	public function CalcPageParams($obj) {
				
		if (!isset($obj->search['go_to_page'])) $obj->search['go_to_page'] = 0;
		if (!isset($obj->search['show_per_page'])) $obj->search['show_per_page'] = 25;
		if (!isset($obj->search['limit_start'])) $obj->search['limit_start'] = 0;
		else $obj->search['limit_start'] = ($obj->search['go_to_page'] * $obj->search['show_per_page']);
		
        if (isset($obj->search['first_page'])) $obj->search['limit_start'] = 0;
        if (isset($obj->search['prev_page'])) $obj->search['limit_start'] -= $obj->search['show_per_page'];
        if (isset($obj->search['next_page'])) $obj->search['limit_start'] += $obj->search['show_per_page'];
        if (isset($obj->search['last_page'])) $obj->search['limit_start'] = $obj->search['total_records'] - $obj->search['show_per_page'];
        
        return $obj->search;
		
	}

	public function DrawDisplayOptions($obj) {
		
		$per_page = $obj->search['show_per_page'];
		$display_options[] = "<option value=\"25\"".(25 == $per_page ? " selected" : "").">25</option>";
		$display_options[] = "<option value=\"50\"".(50 == $per_page ? " selected" : "").">50</option>";
		$display_options[] = "<option value=\"100\"".(100 == $per_page ? " selected" : "").">100</option>";
		$display_options[] = "<option value=\"200\"".(200 == $per_page ? " selected" : "").">200</option>";

		return implode("",$display_options); 
		
	}
	
	public function DrawPageOptions($obj) {

		$page_options = array();
		
		$total_pages = floor($obj->search['total_records'] / $obj->search['show_per_page']);
		$current_page = floor($obj->search['limit_start'] / $obj->search['show_per_page']);
		$page_stop = ($current_page < 49 ? 49 : ($current_page + 49 > $total_pages ? $total_pages : $current_page + 49));
		$page_start = ($current_page > 49 ? $current_page - 49 : "0");
		
		for ($i = $page_start;$i <= $page_stop; ++$i)
			$page_options[] = "<option value=\"$i\"".($i == $obj->search['go_to_page'] ? " selected" : "").">".($i+1)."</option>"; 

		return implode("",$page_options);

	}
	
}


?>