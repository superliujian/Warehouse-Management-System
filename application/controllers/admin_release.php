<?php

/**
 * Admin_release class file.
 *
 * @author Richard Keep <r.kipsang@gmail.com>
 * @copyright Copyright &copy; 2014 
 */


class Admin_release extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
        $this->load->model('release_model');
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
        $config['base_url'] = base_url().'admin/release';
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

            $data['count_products']= $this->release_model->count_release($manufacture_id, $search_string, $order);
            $config['total_rows'] = $data['count_products'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['products'] = $this->release_model->get_release($manufacture_id, $search_string, $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['products'] = $this->release_model->get_release('', $search_string, '', $order_type, $config['per_page'],$limit_end);           
                }
            }else{
                if($order){
                    $data['products'] = $this->release_model->get_release($manufacture_id, '', $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['products'] = $this->release_model->get_release($manufacture_id, '', '', $order_type, $config['per_page'],$limit_end);        
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
            $data['order'] = 'stock.id';

            //fetch sql data into arrays
            $data['manufactures'] = $this->manufacturers_model->get_manufacturers();
            $data['count_products']= $this->release_model->count_release();
            $data['products'] = $this->release_model->get_release('', $search_string, '', $order_type, $config['per_page'],$limit_end);        
            $config['total_rows'] = $data['count_products'];

        }//!isset($manufacture_id) && !isset($search_string) && !isset($order)

        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/release/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function excess($str){

        $y = $this->release_model->all_remaining_stock_by_id($this->input->post('product_id'));

        if($str > $y ){
                $errormessage = 'There is no insufficient stock to issue: '.$str.' units. Remaining stock is: '.$y.' units';
                    $this->form_validation->set_message('excess', $errormessage);
                    return false;
        } 
    }

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
           
            $this->form_validation->set_rules('driver_name', 'Driver Name', 'required|alpha_dash');
            $this->form_validation->set_rules('driver_id', 'Driver National ID', 'required|numeric|max_length[8]');
            $this->form_validation->set_rules('release_no', 'Release Number', 'required|xss_clean');
            $this->form_validation->set_rules('product_id', 'Product', 'required|is_natural_no_zero');
            $this->form_validation->set_rules('id', 'Client', 'required|is_natural_no_zero');
            $this->form_validation->set_rules('truck_number_plate', 'Truck Number Plate', 'required');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|is_natural_no_zero|callback_excess');
            //$this->form_validation->set_rules('measurement_id', 'Unit of measurement', 'required|is_natural_no_zero');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(
                    'manufacture_id' => $this->input->post('id'),
                    'driver_name' => $this->input->post('driver_name'),
                    'driver_id' => $this->input->post('driver_id'),
                    'release_no' => $this->input->post('release_no'), 
                    'type' => 0, // This stands for out. Never mind         
                    'product_id' => $this->input->post('product_id'),
                    'truck_number_plate' => $this->input->post('truck_number_plate'),
                    'quantity' => $this->input->post('quantity'),
                    'measurement_id' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'user' => 1

                );
                //if the insert has returned true then we show the flash message
                if($this->release_model->store_release($data_to_store)){
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
        $data['products'] = $this->release_model->get_products();


        //load the view
        $data['main_content'] = 'admin/release/add';
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

            $this->form_validation->set_rules('driver_name', 'Driver Name', 'required|alpha_dash');
            $this->form_validation->set_rules('driver_id', 'Driver National ID', 'required|numeric|max_length[8]');
            $this->form_validation->set_rules('release_no', 'Release Number', 'required|xss_clean');
            $this->form_validation->set_rules('product_id', 'Product', 'required|is_natural_no_zero');
            //$this->form_validation->set_rules('id', 'Client', 'required|is_natural_no_zero');
            $this->form_validation->set_rules('truck_number_plate', 'Truck Number Plate', 'required');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|is_natural_no_zero|callback_excess');
            //$this->form_validation->set_rules('measurement_id', 'Unit of measurement', 'required|is_natural_no_zero');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(
                    'release_no' => $this->input->post('release_no'),
                    'driver_name' => $this->input->post('driver_name'),
                    'driver_id' => $this->input->post('driver_id'),  
                    'truck_number_plate' => $this->input->post('truck_number_plate'),
                    'quantity' => $this->input->post('quantity'),
                    'measurement_id' => ''
                );

                //if the insert has returned true then we show the flash message
                if($this->release_model->update_release($id, $data_to_store) == TRUE){
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE; 
                }
                //redirect('admin/products');

            }//validation run

        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data

        //release data 
        $data['products'] = $this->release_model->get_release_by_id($id);
        //fetch manufactures data to populate the select field
        $data['manufactures'] = $this->manufacturers_model->get_manufacturers();

        //fetch measurements data to populate the select field
        $data['measurements'] = $this->measurements_model->get_measurements();

        //load the view
        $data['main_content'] = 'admin/release/edit';
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
        $this->release_model->delete_release($id);
        //redirect('admin/products
        $this->index();
    }

}