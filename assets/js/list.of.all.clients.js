/*
* Author : Richard Keep
* Email : r.kipsang@gmail.com
* Subject : Autocomplete using PHP/MySQL and jQuery
*/

// autocomplet : this function will be executed every time we change the text

 $('#products').hide();
 
    function auto() {

      var keyword = $('#manufacture_id').val();

      var url = 'http://localhost/warehouse/admin/payments/js_clients';
      $.ajax({
        url: url,
        type: 'POST',
        minLength: 1,
        cache: false,
        data: {keyword:keyword},
        success:function(data){

          var data = JSON.parse(data);
          //console.log(data);

          if(data == false){
            $('#manufacture_id').val('');
            $('#manufacture_list_id').hide();
          }

          var list = data.list;

          var user_id = data.id;
          
          $('#id').val(user_id);

            $('#manufacture_list_id').show();
            
            //get_products(url, user_id);
            
            
            $('#manufacture_list_id').html(list);
            
            //$('#radio').html('it is working');
            //alert(keyword);

          if(keyword == null || keyword == ""){
            $('#manufacture_list_id').hide();
          } 
        
        }
      });
}

// set_item : this function will be executed when we select an item
function set_item(item) {
  // change input value
  $('#manufacture_id').val(item).promise().done(function(){
    var id = $('#id').val();
     $('#products').show();
    $.ajax({
      url: 'http://localhost/warehouse/admin/payments/js_clients_pro',
      data: {id: id},
      type: 'POST',
      cache: false,
        success: function(d){

          var d = JSON.parse(d);

          for (var i in d.data){
            //console.log(d.data[i].description);
            var select = '<p><input type=radio name=product_id value='+d.data[i].id+' > '+d.data[i].description+' '+d.data[i].measurement_name+' ';
            $('#radio').append(select);
            //alert(d.data[i].description);
          }
        },
        error: function(){
          alert('failed');
        }
    })
  });

  // hide proposition list
  $('#manufacture_list_id').hide();
} 

function delete_product(){
  $('#radio').html('');
  //$('#products').hide();
  $('#products').hide();

}

function confirmDeleteClient(id){
               var r = confirm("Are you sure you want to delete this client?")
               var url = "http://localhost/warehouse/admin/clients/delete";
               if(r == true){
                //alert(url);
                $.ajax({
                  url: url,
                  data: "delete_id="+id,
                  type: "POST",
                  cache: false,
                  success: function(data){
                    if(data == 0){
                      alert('Client was not deleted because stock exists');
                    }
                    location.reload(true);
                  }
                });
              }
            }

            function confirmDeleteProduct(id){
               var r = confirm("Are you sure you want to delete this product?")
               var url = "http://localhost/warehouse/admin/products/delete";
               if(r == true){
                //alert(url);
                $.ajax({
                  url: url,
                  data: "delete_id="+id,
                  type: "POST",
                  cache: false,
                  success: function(data){
                    if(data == 0){
                      alert('Product was not deleted because stock exists');
                    }
                    location.reload(true);
                  }
                });
              }
            }

            function confirmDeleteStock(id){
               var r = confirm("Are you sure you want to delete this stock?")
               var url = "http://localhost/warehouse/admin/stock/delete";
               if(r == true){
                //alert(url);
                $.ajax({
                  url: url,
                  data: "delete_id="+id,
                  type: "POST",
                  cache: false,
                  success: function(data){
                    
                    location.reload(true);
                  }
                });
              }
            }

            function confirmDeleteRelease(id){
               var r = confirm("Are you sure you want to delete this release order?")
               var url = "http://localhost/warehouse/admin/release/delete";
               if(r == true){
                //alert(url);
                $.ajax({
                  url: url,
                  data: "delete_id="+id,
                  type: "POST",
                  cache: false,
                  success: function(data){
                    
                    location.reload(true);
                  }
                });
              }
            }

            function confirmDeletePayment(id){
               var r = confirm("Are you sure you want to delete this payment?")
               var url = "http://localhost/warehouse/admin/payments/delete";
               if(r == true){
                //alert(url);
                $.ajax({
                  url: url,
                  data: "delete_id="+id,
                  type: "POST",
                  cache: false,
                  success: function(data){
                    
                    location.reload(true);
                  }
                });
              }
            }


            function confirmDeleteExpense(id){
               var r = confirm("Are you sure you want to delete this expense?")
               var url = "http://localhost/warehouse/admin/expense/delete";
               if(r == true){
                //alert(url);
                $.ajax({
                  url: url,
                  data: "delete_id="+id,
                  type: "POST",
                  cache: false,
                  success: function(data){
                    
                    location.reload(true);
                  }
                });
              }
            }



            

