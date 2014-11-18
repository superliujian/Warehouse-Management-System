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
          <a href="#">Repackage</a>
        </li>
      </ul>
      
      <div class="page-header users-header">
        <h2>
          Repackaging Product
          <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2);?>" class="btn btn-success">Go Back</a>
       
        </h2>
      </div>

 
      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> product repackaged successfully.'.$undo;
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> something went wrong.';
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
        $options_measurement[$measurement['measurement_name']] = $measurement['measurement_name'];
      }

      if ($rem_stock == 0) {
        //die('No stock exists'. anchor('admin/products', ' Go back'));
      }

      //form validation
      echo validation_errors();

      echo form_open('admin/products/repackage', $attributes);
      ?>
        <fieldset>

          <div class="control-group">
            <label for="manufacture_id" class="control-label">Client</label>
            <div class="controls">

              <input type="text" id="manufacture_id"  readonly="readonly" onkeyup="auto()" value="<?php echo $product[0]['manufacture_name']; ?>" autocomplete="off">
                
                  <ul id="manufacture_list_id"></ul>
              
              <?php //echo form_dropdown('manufacture_id', $options_manufacture, $product[0]['manufacture_id'], 'class="span2"'); ?>

            </div>
          </div>

          <input type="hidden" id="id" name="manufacture_id" value="<?php echo $product[0]['manufacture_id']; ?>">
          <input type="hidden" id="id" name="from_unit" value="<?php echo $product[0]['measurement_name']; ?>">
          <input type="hidden" id="id" name="repackaging_fee" value="<?php echo $product[0]['repackaging_fee']; ?>">
          <input type="hidden" id="id" name="storage_fee" value="<?php echo $product[0]['storage_fee']; ?>">
          <input type="hidden" id="id" name="penalty_fee" value="<?php echo $product[0]['penalty_fee']; ?>">
          <input type="hidden" id="id" name="description" value="<?php echo $product[0]['description']; ?>">
          <input type="hidden" id="id" name="product_id" value="<?php echo $product[0]['id']; ?>">

          <div class="control-group">
            <label for="inputError" class="control-label">Description</label>
            <div class="controls">
              <input type="text" id="" name="" readonly="readonly" value="<?php echo $product[0]['description'].' '.$product[0]['measurement_name']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>

           <div class="control-group">
            <label for="inputError" class="control-label">Remaining Stock: </label>
            <div class="controls">
              <input type="text" id="rem_quantity" name="rem_quantity" readonly="readonly" value="<?php echo $rem_stock; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Quantity to repackage: </label>
            <div class="controls">
              <input type="text" id="quantity" name="quantity" value="" >
              <span class="help-inline">e.g 200, 500, 800 etc</span>
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Repackage to: </label>
            <div class="controls">
              <?php echo form_dropdown('to_unit', $options_measurement, set_value('measurement_name'), 'class="span2"'); ?>
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>
         
          <div class="form-actions">
            <button class="btn btn-primary" type="submit" id="submit"  >Repackage</button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     