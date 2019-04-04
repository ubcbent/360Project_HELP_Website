$(document).ready(function(){

var cart = $("#add-to-cart");
cart.click(function(){

	var results = $.getJSON("addToCart.php",{id:getUrlVars()['id']});

	results.done(function(data){
		//on success, print php return to designated locations
		$("#cart-feedback").html(data.a);
		$("#shopping-cart-element-container").append(data.b);
		$("#shopping-cart-total").html("Total: $"+data.total);
		//set on click function to remove recently added cart items
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

		//set on change function to change quantities of recently added cart items
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

    results.fail(function(jqXHR) {
		console.log("Error: " + jqXHR.status);
	});

});
});

function getUrlVars(){
	//finds and splits the query string into its parts
	var vars=[];
	var hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?')+1).split('&');
	for(var i = 0;i<hashes.length;i++){
		hash=hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}
