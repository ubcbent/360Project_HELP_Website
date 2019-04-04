$(document).ready(function(){

var cartNums = $("#shopping-cart-element-container input");
cartNums.change(function(){
	var input = $(this);
	var num = input.val();
	var id = input.parent().parent().attr("id");
	
	var results = $.getJSON("changeInCart.php",{id:id,val:num});
	
	results.done(function(data){
		//on success, set quantity to one if user entered invalid number, also update total price
		input.val(data.qty);
		$("#"+id+" #shopping-cart-item-price").html("$"+data.subtotal);
		$("#shopping-cart-total").html("Total: $"+data.total);
    });

    results.fail(function(jqXHR) {
		console.log("Error: " + jqXHR.status);
	});
	
});
});