$(document).ready(function()
  {	  
  //Ajax запрос на чекбокс
	  $("#pure_site_link_chk").click(function(){
		$.ajax({
		  type: 'POST',
		  url: "../ajax/checkbox.php",
		  data: { pure_site_link_chk : $('#pure_site_link_chk')[0]["checked"] },
		  dataType: 'html',
		  success: function(data){
			  console.log(data);
		  }
		});
	  });
	  
	  //Ajax запрос на селектор
	  $("#select").change(function(){
		$.ajax({
		  type: 'POST',
		  url: "../ajax/select.php",
		  data: { site_id : $("#select option:selected").val(),
		  site_name : $("#select option:selected").text()},
		  dataType: 'html',
		  success: function(data){
			  console.log(data);
		  }
		}); 
	  });
  });