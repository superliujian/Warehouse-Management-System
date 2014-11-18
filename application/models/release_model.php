<?php


/**
 * Release_model class file.
 *
 * @author Richard Keep <r.kipsang@gmail.com>
 * @copyright Copyright &copy; 2014 
 */



class Release_model extends CI_Model {
 
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
    public function get_release_by_id($id=null)
    {
		/*$this->db->select('*');
		$this->db->from('release');
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

		//$this->db->where('stock.type', 0);

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

		$this->db->group_by('products.id');

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('products.id', $order_type);
		}


		//$this->db->limit($limit_start, $limit_end);
		//$this->db->limit('4', '4');

		//$this->db->where('stock.type', 0);


		$query = $this->db->get();
		
		return $query->result_array(); 	
    }

    public function get_release($manufacture_id=null, $search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('*');
		$this->db->select('stock.id as id');
		$this->db->select('stock.manufacture_id');
		$this->db->select('manufacturers.name as manufacture_name');
		$this->db->from('stock');
		$this->db->where('type', 0);
		$this->db->where('quantity !=', 0);
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

		//$this->db->where('type', 0);


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

    function all_stock_by_id_in($product_id){
    	$this->db->select('sum(quantity) as quantity');
    	$this->db->where('type', 1);
    	$this->db->where('product_id', $product_id);
    	$this->db->from('stock');

    	$query = $this->db->get();

    	$x = array();

    	foreach ($query->result() as $row)
            {
               $x[] =  $row->quantity;

            }
            return $x[0];
    }

    function all_stock_by_id_out($product_id){
    	$this->db->select('sum(quantity) as quantity');
    	$this->db->where('type', 0);
    	$this->db->where('product_id', $product_id);
    	$this->db->from('stock');

    	$query = $this->db->get();

    	$x = array();

    	foreach ($query->result() as $row)
            {
               $x[] =  $row->quantity;
            }
            return $x[0];
    }

    function all_remaining_stock_by_id($product_id)
    {
    	return $this->all_stock_by_id_in($product_id) - $this->all_stock_by_id_out($product_id);
    }

    function count_release($manufacture_id=null, $search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->where('type', 0);
		$this->db->from('stock');
		if($manufacture_id){
			$this->db->where('manufacture_id', $manufacture_id);
		}
		if($search_string){
			$this->db->like('product_id', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('stock.id', 'Asc');
		}

		$query = $this->db->get();
		
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_release($data)
    {
		$insert = $this->db->insert('stock', $data);
	    return $insert;
	}

    /**
    * Update product
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_release($id, $data)
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
	function delete_release($id){
		$this->db->where('id', $id);
		$this->db->delete('stock'); 
	}
 
}
?>	
