<?php

/**
 * Stock_model class file.
 *
 * @author Richard Keep <r.kipsang@gmail.com>
 * @copyright Copyright &copy; 2014 
 */


class Stock_model extends CI_Model {
 
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    /**
    * Get product by his is
    * @param int $product_id 
    * @return array
    */
    public function get_stock_by_id($id=null)
    {
		/*$this->db->select('*');
		$this->db->from('stock');
		$this->db->where('id', $id); */

		$this->db->select('*');
		$this->db->select('stock.id as id');
		$this->db->select('stock.manufacture_id');
		$this->db->select('manufacturers.name as manufacture_name');
		$this->db->from('stock');

		$this->db->join('manufacturers', 'stock.manufacture_id = manufacturers.id', 'left');
		$this->db->join('measurements', 'stock.measurement_id = measurements.measurement_id', 'left');
		$this->db->join('products', 'stock.product_id = products.id', 'left');


		if($id){
			$this->db->where('stock.id', $id);
		}

		//$this->db->where('stock.type', 1);

		$query = $this->db->get();
		return $query->result_array(); 
    }

    /**
    * Fetch products data from the database
    * possibility to mix search, filter and order
    * @param int $manufacuture_id 
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_products($manufacture_id=null, $search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('*');
		//$this->db->select('products.manufacture_id');
		//$this->db->select('manufacturers.name as manufacture_name');
		$this->db->from('products');
		//if($manufacture_id != null && $manufacture_id != 0){
			//$this->db->where('manufacture_id', $manufacture_id);
		//}
		if($search_string){
			$this->db->like('description', $search_string);
		}

		//$this->db->join('manufacturers', 'products.manufacture_id = manufacturers.id', 'left');

		$this->db->group_by('id');

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('id', $order_type);
		}


		//$this->db->limit($limit_start, $limit_end);
		//$this->db->limit('4', '4');

		//$this->db->where('stock.type', 1);


		$query = $this->db->get();
		
		return $query->result_array(); 	
    }

    public function get_stock($manufacture_id=null, $search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('*');
		$this->db->select('stock.id as id');
		$this->db->select('stock.manufacture_id');
		$this->db->select('manufacturers.name as manufacture_name');
		$this->db->from('stock');
		if($manufacture_id != null && $manufacture_id != 0){
			$this->db->where('manufacture_id', $manufacture_id);
		}
		if($search_string){
			$this->db->like('products.description', $search_string);
			$this->db->or_like('manufacturers.name', $search_string);
			$this->db->or_like('stock.driver_name', $search_string);
		}

		$this->db->join('manufacturers', 'stock.manufacture_id = manufacturers.id', 'left');
		$this->db->join('measurements', 'stock.measurement_id = measurements.measurement_id', 'left');
		$this->db->join('products', 'stock.product_id = products.id', 'left');
		
		$this->db->group_by('stock.id');

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('stock.id', $order_type);
		}


		$this->db->limit($limit_start, $limit_end);
		//$this->db->limit('4', '4');

		$this->db->where('type', 1);


		$query = $this->db->get();
		
		return $query->result_array(); 	
    }

    /**
    * Count the number of rows
    * @param int $manufacture_id
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_stock($manufacture_id=null, $search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('stock');
		if($manufacture_id != null && $manufacture_id != 0){
			$this->db->where('manufacture_id', $manufacture_id);
		}
		if($search_string){
			//$this->db->like('description', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('id', 'Asc');
		}

		$this->db->where('type', 1);

		$query = $this->db->get();
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_stock($data)
    {
		$insert = $this->db->insert('stock', $data);
	    return $insert;
	}

    /**
    * Update product
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_stock($id, $data)
    {
		$this->db->where('id', $id);
		
		if($this->db->update('stock', $data)){

			return true;
		}else{
			return false;
		}
	}

    /**
    * Delete product
    * @param int $id - product id
    * @return boolean
    */
	function delete_stock($id){
		$this->db->where('id', $id);
		$this->db->delete('stock'); 
	}

	function autocomplete($id, $keyword){

        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('manufacture_id', $id);
        //$this->db->like('description', $keyword);
        $query = $this->db->get();
        return $query->result_array(); 
    }
 
}
?>	
