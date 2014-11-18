/*
* Author : Ali Aboussebaba
* Email : bewebdeveloper@gmail.com
* Website : http://www.bewebdeveloper.com
* Subject : Autocomplete using PHP/MySQL and jQuery
*/

// autocomplet : this function will be executed every time we change the text

    function autocomplet() {
      alert('working');
      var keyword = $('#client_id').val();
      alert(keyword);
      var url = 'http://localhost/edenways/admin/payments/js';
      $.ajax({
        url: url,
        type: 'POST',
        minLength: 2,
        cache: false,
        data: {keyword:keyword},
        success:function(data){

          if(data == false){
            $('#client_id').val('');
            $('#client_list_id').hide();
          }

          var data = JSON.parse(data);

            $('#client_list_id').show();
            $('#client_list_id').html(data.list);
            $('#id').val(data.id);

          if(keyword == null || keyword == ""){
            $('#client_list_id').hide();
          } 
        
        }
      });
}

// set_item : this function will be executed when we select an item
function set_item(item) {
  // change input value
  $('#client_id').val(item);

  
  // hide proposition list
  $('#client_list_id').hide();
} 

