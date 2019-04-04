$(document).ready(function(){

var x = $(".shopping-cart-element span");

x.click(function(){
	var id = $(this).parent().parent().attr('id');
	
	var results = $.get("removeFromCart.php",{id:id});

	results.done(function(data){
		//on success
		$("#"+id).remove();
		$("#shopping-cart-total").html("Total: $"+data);
    });

    results.fail(function(jqXHR) {
		console.log("Error: " + jqXHR.status);
	});
	
});
});