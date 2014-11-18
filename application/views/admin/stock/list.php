    <div class="container top">

      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url("admin"); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          <?php echo ucfirst($this->uri->segment(2));?>
        </li>
      </ul>

      <div class="page-header users-header">
        <h2>
          <?php echo ucfirst($this->uri->segment(2));?> 
          <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/add" class="btn btn-success">Add a new stock</a>
        </h2>
      </div>
      
      <div class="row">
        <div class="span12 columns">
          <div class="well">
           
            <?php
           
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');
           
            $options_manufacture = array(0 => "all");
            foreach ($manufactures as $row)
            {
              $options_manufacture[$row['id']] = $row['name'];
            }
            //save the columns names in a array that we will use as filter         
            $options_products = array();    
            foreach ($products as $array) {
              foreach ($array as $key => $value) {
                $options_products[$key] = $key;
              }
              break;
            }

            echo form_open('admin/stock', $attributes);
     
              echo form_label('Search:', 'search_string');
              echo form_input('search_string', $search_string_selected, 'style="width: 170px;
height: 26px;"');

              echo ' ';

              //echo form_label('Filter by manufacturer:', 'manufacture_id');
              //echo form_dropdown('manufacture_id', $options_manufacture, $manufacture_selected, 'class="span2"');

              //echo form_label('Order by:', 'order');
              //echo form_dropdown('order', $options_products, $order, 'class="span2"');

              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');

              //$options_order_type = array('Asc' => 'Asc', 'Desc' => 'Desc');
              //echo form_dropdown('order_type', $options_order_type, $order_type_selected, 'class="span1"');

              echo form_submit($data_submit);

            echo form_close();
            ?>

          </div>

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
                <th class="yellow header headerSortDown">Client</th>
                <th class="yellow header headerSortDown">Name of Stock</th>
                <th class="red header">Driver Name</th>
                <th class="red header">Driver ID</th>
                <th class="red header">Truck No. Plate</th>
                <th class="red header">Container No.</th>
                <th class="red header">Quantity</th>
                <th class="red header">Date</th>
                <th class="red header">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              //$x = 1;
              foreach($products as $row)
              {
                $date = new dateTime($row['created_at']);
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td><a href="'.site_url("admin").'/reports/'.$row['manufacture_id'].'">'.$row['manufacture_name'].'</a></td>';
                
                echo '<td>'.$row['description'].' '.$row['measurement_name'].'</td>';
                echo '<td>'.$row['driver_name'].'</td>';
                echo '<td>'.$row['driver_id'].'</td>';
                echo '<td>'.$row['truck_number_plate'].'</td>';
                echo '<td>'.$row['container_number'].'</td>';
                echo '<td>'.$row['quantity'] .'</td>';
                echo '<td>'.$date->format('d/m/Y h:i a').'</td>';

                echo '<td class="crud-actions">
                  <a href="'.site_url("admin").'/stock/update/'.$row['id'].'" class="btn btn-info">view & edit</a>  
                  <a id="delete_id" onclick="confirmDeleteStock('.$row['id'].')" class="btn btn-danger">delete</a>
                </td>';
                echo '</tr>';
              }
              ?>      
            </tbody>
          </table>

          <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

      </div>
    </div>