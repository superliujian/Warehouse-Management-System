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
          <a href="#">Update</a>
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Updating Release Order
        </h2>
      </div>

 
      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> stock updated with success.';
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
      

      $options_measurement = array('' => "Select");
      foreach ($measurements as $measurement)
      {
        $options_measurement[$measurement['measurement_id']] = $measurement['measurement_name'];
      }

    
      //form validation
      echo validation_errors();

      echo form_open('admin/release/update/'.$this->uri->segment(4).'', $attributes);
      ?>
        <fieldset>

          <div class="control-group">
            <label for="manufacture_id" class="control-label">Client</label>
            <div class="controls">
             <?php echo form_input(array('value'=>$products[0]['manufacture_name'],'readonly'=>'readonly')); ?>
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Product</label>
            <div class="controls">
              <?php echo form_input(array('value'=>$products[0]['description'],'readonly'=>'readonly')); ?>
             <!--<span class="help-inline">Cost Price</span> -->
            </div>
          </div>

          <input type="hidden" name="product_id" value="<?php echo $products[0]['product_id']; ?>">

          <div class="control-group">
            <label for="inputError" class="control-label">Release Order No.</label>
            <div class="controls">
              <input type="text" id="" name="release_no" value="<?php echo $products[0]['release_no']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
                   
          <div class="control-group">
            <label for="inputError" class="control-label">Driver Name</label>
            <div class="controls">
              <input type="text" id="" name="driver_name" value="<?php echo $products[0]['driver_name']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Driver National ID</label>
            <div class="controls">
              <input type="text" id="" name="driver_id" value="<?php echo $products[0]['driver_id']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Truck Number Plate </label>
            <div class="controls">
              <input type="text" name="truck_number_plate" value="<?php echo $products[0]['truck_number_plate']; ?>">
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>
          
          <div class="control-group">
            <label for="inputError" class="control-label">Quantity Brought</label>
            <div class="controls">
              
              <?php echo form_input(array('name'=>'quantity','placeholder'=>'quantity of stock brought','value'=>$products[0]['quantity'])); ?>
               </div>
          </div>
        
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     