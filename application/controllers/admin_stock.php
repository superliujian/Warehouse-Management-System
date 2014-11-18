<?php

/**
 * Admin_stock class file.
 *
 * @author Richard Keep <r.kipsang@gmail.com>
 * @copyright Copyright &copy; 2014 
 */

class Admin_stock extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
        $this->load->model('release_model');
        $this->load->model('stock_model');
        $this->load->model('manufacturers_model');
        $this->load->model('measurements_model');

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }
 
    /**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function index()
    {

        //all the posts sent by the view
        $manufacture_id = $this->input->post('manufacture_id');        
        $search_string = $this->input->post('search_string');        
        $order = $this->input->post('order'); 
        $order_type = $this->input->post('order_type'); 

        //pagination settings
        $config['per_page'] = 20;
        $config['base_url'] = base_url().'admin/stock';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 

        //if order type was changed
        if($order_type){
            $filter_session_data['order_type'] = $order_type;
        }
        else{
            //we have something stored in the session? 
            if($this->session->userdata('order_type')){
                $order_type = $this->session->userdata('order_type');    
            }else{
                //if we have nothing inside session, so it's the default "Asc"
                $order_type = 'Asc';    
            }
        }
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;        


        //we must avoid a page reload with the previous session data
        //if any filter post was sent, then it's the first time we load the content
        //in this case we clean the session filter data
        //if any filter post was sent but we are in some page, we must load the session data

        //filtered && || paginated
        if($manufacture_id !== false && $search_string !== false && $order !== false || $this->uri->segment(3) == true){ 
           
            /*
            The comments here are the same for line 79 until 99

            if post is not null, we store it in session data array
            if is null, we use the session data already stored
            we save order into the the var to load the view with the param already selected       
            */

            if($manufacture_id !== 0){
                $filter_session_data['manufacture_selected'] = $manufacture_id;
            }else{
                $manufacture_id = $this->session->userdata('manufacture_selected');
            }
            $data['manufacture_selected'] = $manufacture_id;

            if($search_string){
                $filter_session_data['search_string_selected'] = $search_string;
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

            if($order){
                $filter_session_data['order'] = $order;
            }
            else{
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;

            //save session data into the session
            $this->session->set_userdata($filter_session_data);

            //fetch manufacturers data into arrays
            $data['manufactures'] = $this->manufacturers_model->get_manufacturers();

            $data['count_products']= $this->stock_model->count_stock($manufacture_id, $search_string, $order);
            $config['total_rows'] = $data['count_products'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['products'] = $this->stock_model->get_stock($manufacture_id, $search_string, $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['products'] = $this->stock_model->get_stock($manufacture_id, $search_string, '', $order_type, $config['per_page'],$limit_end);           
                }
            }else{
                if($order){
                    $data['products'] = $this->stock_model->get_stock($manufacture_id, '', $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['products'] = $this->stock_model->get_stock($manufacture_id, '', '', $order_type, $config['per_page'],$limit_end);        
                }
            }

        }else{

            //clean filter data inside section
            $filter_session_data['manufacture_selected'] = null;
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['manufacture_selected'] = 0;
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['manufactures'] = $this->manufacturers_model->get_manufacturers();
            $data['count_products']= $this->stock_model->count_stock();
            $data['products'] = $this->stock_model->get_stock('', $search_string, '', $order_type, $config['per_page'],$limit_end);        
            $config['total_rows'] = $data['count_products'];

        }//!isset($manufacture_id) && !isset($search_string) && !isset($order)

        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/stock/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
           

            $this->form_validation->set_rules('driver_name', 'Driver Name', 'required');
            $this->form_validation->set_rules('driver_id', 'Driver National ID', 'required|numeric|max_length[8]');
            //$this->form_validation->set_rules('release_no', 'Release Number', 'required|xss_clean');
            $this->form_validation->set_rules('product_id', 'Product', 'required|is_natural_no_zero');
            $this->form_validation->set_rules('id', 'Client', 'required|is_natural_no_zero');
            $this->form_validation->set_rules('truck_number_plate', 'Truck Number Plate', 'required');
            $this->form_validation->set_rules('container_number', 'Container Number', '');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|is_natural_no_zero');
           // $this->form_validation->set_rules('measurement_id', 'Unit of measurement', 'required|is_natural_no_zero');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(
                    'manufacture_id' => $this->input->post('id'),
                    'driver_name' => $this->input->post('driver_name'),
                    'driver_id' => $this->input->post('driver_id'),  
                    'type' => 1, // This stands for in. Never mind       
                    'product_id' => $this->input->post('product_id'),
                    'truck_number_plate' => $this->input->post('truck_number_plate'),
                    'container_number' => $this->input->post('container_number'),
                    'quantity' => $this->input->post('quantity'),
                    //'measurement_id' => $this->input->post('measurement_id'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'user' => 1

                );

                $data_to_store_release = array(
                    'manufacture_id' => $this->input->post('id'),
                    'driver_name' => '',
                    'driver_id' => '',  
                    'type' => 0, // This stands for in. Never mind 
                    'release_no' => '',      
                    'product_id' => $this->input->post('product_id'),
                    'truck_number_plate' =>'',
                    'container_number' => '',
                    'quantity' => 0,
                    'measurement_id' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'user' => 1

                ); 

                //if the insert has returned true then we show the flash message
                if($this->stock_model->store_stock($data_to_store)){
                    $this->release_model->store_release($data_to_store_release);
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE; 
                }

            }

        }
        //fetch manufactures data to populate the select field
        $data['manufactures'] = $this->manufacturers_model->get_manufacturers();

        //fetch measurements data to populate the select field
        $data['measurements'] = $this->measurements_model->get_measurements();

        //fetch products data to populate the select field
        $data['products'] = $this->stock_model->get_products();


        //load the view
        $data['main_content'] = 'admin/stock/add';
        $this->load->view('includes/template', $data);  
    }       

    /**
    * Update item by his id
    * @return void
    */
    public function update()
    {
        //product id 
        $id = $this->uri->segment(4);
  
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('driver_name', 'Driver Name', 'required');
            //$this->form_validation->set_rules('id', 'Client', 'required');
            //$this->form_validation->set_rules('product_id', 'Stock', 'required');
            $this->form_validation->set_rules('driver_id', 'Driver National ID', 'required|numeric');
            $this->form_validation->set_rules('truck_number_plate', 'Truck Number Plate', 'required');
            $this->form_validation->set_rules('container_number', 'Container Number', '');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|required');
            //$this->form_validation->set_rules('measurement_id', 'Unit of measurement', 'required|numeric');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(
                    'driver_name' => $this->input->post('driver_name'),
                    'driver_id' => $this->input->post('driver_id'),  
                    //'product_id' => $this->input->post('product_id'),
                    'truck_number_plate' => $this->input->post('truck_number_plate'),
                    'container_number' => $this->input->post('container_number'),
                    'quantity' => $this->input->post('quantity'),
                    //'measurement_id' => $this->input->post('measurement_id'),
                    //'manufacture_id' => $this->input->post('id')
                );

                //if the insert has returned true then we show the flash message
                if($this->stock_model->update_stock($id, $data_to_store) == TRUE){
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE; 
                }
                //redirect('admin/products');

            }//validation run

        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data

        //stock data 
        $data['products'] = $this->stock_model->get_stock_by_id($id);
        //fetch manufactures data to populate the select field
        $data['manufactures'] = $this->manufacturers_model->get_manufacturers();

        //fetch measurements data to populate the select field
        $data['measurements'] = $this->measurements_model->get_measurements();

        //load the view
        $data['main_content'] = 'admin/stock/edit';
        $this->load->view('includes/template', $data);            

    }//update

    /**
    * Delete product by his id
    * @return void
    */


    public function delete()
    {

        //product id 
        $id = $this->input->post('delete_id');

        $this->stock_model->delete_stock($id);
             
        return true;
      
    }//edit


    public function js_clients_products(){

        $data = $this->stock_model->autocomplete(3);

        if(!count($data)){ return false; die; }

            foreach ($data as $rs) {
                // put in bold the written text
                //$client_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['description']);
                // add new option
                $id = $rs['id'];
                $name = $rs['description'];
                $test = array('list' => '<input type=radio name=product_id value='.$id.'> '.$name.'<br>'
                    
                    );

                echo json_encode($test);
            }     
    }

}