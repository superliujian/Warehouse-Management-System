<?php

/**
 * Admin_reports class file.
 *
 * @author Richard Keep <r.kipsang@gmail.com>
 * @copyright Copyright &copy; 2014 
 */


class Admin_reports extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reports_model');
        $this->load->model('payments_model');
        $this->load->model('manufacturers_model');

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }
 
    /**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function display_client_report()
    {

        $manufacture_id = $this->uri->segment(3);

        //load transactions
        $data['info'] = $this->reports_model->get_client_report($manufacture_id);
        $data['info_in'] = $this->reports_model->get_client_report_in($manufacture_id);

        $data['profile'] = $this->manufacturers_model->get_manufacture_by_id($manufacture_id);


         $data['test'] = $this->reports_model->array_add_by_key( $this->reports_model->getSum_in($manufacture_id), $this->reports_model->getSum_out($manufacture_id) );

        //$data['all_out'] = $this->reports_model->getSum_out($manufacture_id);

        //$data['final'] = array($data['all_in'], $data['all_out']);


        $data['items'] = $this->reports_model->multiply_product_by_storage_fee($manufacture_id);

        $data['paid'] = $this->payments_model->getTotalPayments($manufacture_id);

        $data['repackaging'] = $this->reports_model->repackage_fees($manufacture_id);


        //load views
        $data['main_content'] = 'admin/reports/list';
        $this->load->view('includes/template', $data);
    }


}