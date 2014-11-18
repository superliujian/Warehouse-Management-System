    <div class="container top">
      
      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url("admin"); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li>
          <a href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>">
            <?php echo ucfirst($this->uri->segment(2));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          <a href="#">New</a>
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Releasing Stock
        </h2>
      </div>
 
      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> new stock created with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
          echo '</div>';          
        }
      }
      ?>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal', 'id' => '');
      $options_manufacture = array('' => "Select");
      foreach ($manufactures as $row)
      {
        $options_manufacture[$row['id']] = $row['name'];
      }

      $options_measurement = array('' => "Select");
      foreach ($measurements as $measurement)
      {
        $options_measurement[$measurement['measurement_id']] = $measurement['measurement_name'];
      }

      $options_product = array('' => "Select");

      foreach ($products as $product)
      {
        $options_product[$product['id']] = $product['description'];
      }

      //form validation
      echo validation_errors();
      
      echo form_open('admin/release/add', $attributes);
      ?>
        <fieldset>

          <div class="control-group">
            <label for="manufacture_id" class="control-label">Client</label>
            <div class="controls">
              <?php //echo form_dropdown('manufacture_id', $options_manufacture, set_value('manufacture_id'), 'class="span2"'); ?>
            
            <input type="text" id="manufacture_id" name="manufacture_id" onkeydown="delete_product()" onkeyup="auto()" autocomplete="off">
                
                  <ul id="manufacture_list_id"></ul>
            </div>
          </div>

          <input type="hidden" id="id" name="id" value="">

          <div  class="control-group">
            <label for="inputError" id="products" class="control-label">Product</label>
            <div class="controls">
              <?php //echo form_dropdown('product_id', $options_product, set_value('product_id'), 'class="span2"'); ?>
             <!--<span class="help-inline">Cost Price</span> -->
             
             <div id="radio"></div>

            </div>
          </div>

        

          <div class="control-group">
            <label for="inputError" class="control-label">Release Order No.</label>
            <div class="controls">
              <input type="text" id="" name="release_no" value="<?php echo set_value('release_no'); ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
                   
          <div class="control-group">
            <label for="inputError" class="control-label">Driver Name</label>
            <div class="controls">
              <input type="text" id="" name="driver_name" value="<?php echo set_value('driver_name'); ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Driver National ID</label>
            <div class="controls">
              <input type="text" id="" name="driver_id" value="<?php echo set_value('driver_id'); ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Truck Number Plate </label>
            <div class="controls">
              <input type="text" name="truck_number_plate" value="<?php echo set_value('truck_number_plate'); ?>">
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Quantity Taken</label>
            <div class="controls">
              
              <?php echo form_input(array('name'=>'quantity','placeholder'=>'','value'=>set_value('quantity'))); ?>
               </div>
          </div>

          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save </button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     