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
           <span class="divider">/</span>
        </li>
        <li class="active">
          <?php echo '<i style="color:red;">'.ucfirst($profile[0]['name']).'</i>';?>
        </li>
      </ul>

      <div class="row">
        <div class="span12 columns">

          <b style="color:red;">STOCK</b>

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
                
                <th class="yellow header headerSortDown">Stock Name</th>
                <th>Remaining Stock</th>
             
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              foreach ($test as $item){
                $in = $item['sum_in'];
                $out = $item['sum_out'];

                $diff = $in - $out;

                      echo '<tr>';
                      echo '<td>'.$i++.'</td>';
                      echo '<td>'.$item['description'].' '.$item['measurement_name'].'</td>';
                      echo '<td>'.$diff.'</td>';
                      echo '</tr>';
                 
                } 
              
              ?>      
            </tbody>
          </table>

          <?php //echo $test; ?>

          <b style="color:red;">RELEASED STOCK</b>

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
                
                <th class="yellow header headerSortDown">Name of Stock</th>
                <th class="yellow header headerSortDown">Release Order No.</th>
                <th class="red header">Driver Name</th>
                <th class="red header">Driver ID</th>
                <th class="red header">Truck No. Plate</th>
                <th class="red header">Container No.</th>
                <th class="red header">Quantity</th>
                <th class="red header">Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $x = 1;
            // echo $in[0]['description'].' - '.$in[0]['quantity'];
              foreach($info as $row)
              {
                $type = ($row['type'] ==1 )? 'IN' : 'OUT';
                $date = new dateTime($row['created_at']);
                echo '<tr>';
                echo '<td>'.$x++.'</td>';
                //echo '<td>'.$row['manufacture_name'].'</td>';
                echo '<td>'.$row['description'].' '.$row['measurement_name'].'</td>';
                echo '<td>'.$row['release_no'].'</td>';
                echo '<td>'.$row['driver_name'].'</td>';
                echo '<td>'.$row['driver_id'].'</td>';
                echo '<td>'.$row['truck_number_plate'].'</td>';
                echo '<td>'.$row['container_number'].'</td>';
                echo '<td>'.$row['quantity'] .'</td>';
                echo '<td>'.$date->format('d/m/Y h:i a').'</td>';

              
              }
              ?>      
            </tbody>
          </table>

          <b style="color:red;">INCOMING STOCK</b>

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
                
                <th class="yellow header headerSortDown">Name of Stock</th>
                <th class="red header">Driver Name</th>
                <th class="red header">Driver ID</th>
                <th class="red header">Truck No. Plate</th>
                <th class="red header">Container No.</th>
                <th class="red header">Quantity</th>
                <th class="red header">Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $x = 1;
            // echo $in[0]['description'].' - '.$in[0]['quantity'];
              foreach($info_in as $row)
              {
                //$type = ($row['type'] ==1 )? 'IN' : 'OUT';
                $date = new dateTime($row['created_at']);
                echo '<tr>';
                echo '<td>'.$x++.'</td>';
                //echo '<td>'.$row['manufacture_name'].'</td>';
                echo '<td>'.$row['description'].' ' .$row['measurement_name'].'</td>';
                echo '<td>'.$row['driver_name'].'</td>';
                echo '<td>'.$row['driver_id'].'</td>';
                echo '<td>'.$row['truck_number_plate'].'</td>';
                echo '<td>'.$row['container_number'].'</td>';
                echo '<td>'.$row['quantity'] .'</td>';
                echo '<td>'.$date->format('d/m/Y h:i a').'</td>';

              
              }
              ?>      
            </tbody>
          </table>

          <?php 
              $check = 0; 

              $number = 0;
              
              $d = array();
              // echo 'Total Storage fee charged per item<br>';
              echo 'TOTAL';

              if($items){
                  foreach($items as $row) {

                  $test = $row['storage_charge'];

                  //echo $row['description'].' - '.$test.'<br>';

                  $d[] = $check +=$test;
                }
              

              echo '<p>-----------------------------<br>';
              

              $number = count($items) - 1;

              echo 'Total Storage Fees: Ksh. '.number_format($d[$number]).'<br>';
            }

              ?>

              <?php 


              $rep_fees = isset($repackaging[0]['fees']) ? $repackaging[0]['fees'] : 0;

                echo 'Total Repackaging Fees: Ksh. '.number_format($rep_fees).'<br>'; 

                $total_fees = $rep_fees + $d[$number];
              

              ?>
              ---------------------------------<br>
              <b>Total Charges: Ksh. <?php echo number_format($total_fees); ?> </b><br>

              ---------------------------------

              <?php 
            

              if(isset($paid)){
                  $p = array();
                  
                  foreach ($paid as $amount) {
                    $p[] = $amount_paid = $amount->sum_total_paid;

                    $amount_paid = isset($amount_paid) ? $amount_paid : 0;

                    echo '<p><b>Total Amount Paid: Ksh. '.number_format($amount_paid);
                  }

                  echo '<br>------------------------------<br>';
                  //echo '<p style="color:red;">';
                 echo strtoupper('Total Balance: <i style="color:red;">Ksh. ');



                 if($p){
                    $bal = $p[0] - $total_fees;
                    //echo -$bal;
                    echo number_format(-$bal);
                 }
             }
              ?>





          <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

      </div>
    </div>