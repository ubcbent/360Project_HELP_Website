var page = 1;

$(document).ready(function(){
//next and previous buttons
var next = $("#next-page");
next.click(function(){
	page++;
	queryServer(true);

	//Added a scroll to top when the user changes the listings of items
	$("html, body").animate({ scrollTop: 0 }, "slow");
	window.scrollTo(0,0);
});

var prev = $("#prev-page");
prev.click(function(){
	if(page>1){
		page--;
		queryServer(false);

		//Added a scroll to top when the user changes the listings of items
		$("html, body").animate({ scrollTop: 0 }, "slow");
		window.scrollTo(0,0);
	}
});

var filter = $("#filter-fieldset");
filter.change(function(){
	page = 1;
	queryServer(false)

	//Added a scroll to top when the user changes the listings of items
	$("html, body").animate({ scrollTop: 0 }, "slow");
	window.scrollTo(0,0);
});
//Change search results in real time when filtered
function queryServer(checkdata){
	//returns all checked categories
	var categories = $("#filter-fieldset input:checked");
	//grabs the names of the categories for the query string
	var catenames = [];
	categories.each(function(i){
		catenames.push(categories.eq(i).attr("name"));
	});
	//grabs the numerical values entered
	var minprice = $("#filter-fieldset #minprice");
	var maxprice = $("#filter-fieldset #maxprice");
	var ratings = $("#filter-fieldset #reviews");

	//validate the fields
	if(minprice.val()<0){
		minprice.val(0);
	}else if(minprice.val()>9999999.99){
		minprice.val(9999999.99);
	}
	if(maxprice.val()<0){
		maxprice.val(0);
	}else if(maxprice.val()>9999999.99){
		maxprice.val(9999999.99);
	}
	if(ratings.val()<0){
		ratings.val(0);
	}else if(ratings.val()>10){
		ratings.val(10);
	}
	//get search term and build category string
	var query = getUrlVars();
	var catenamesstring = JSON.stringify(catenames);

	var results = $.get("admin-listProd.php",{"search":query['search'],"page":page,"minprice":minprice.val(),"maxprice":maxprice.val(),"ratings":ratings.val(),"category":catenamesstring});

	results.done(function(data){
      //on success
	  if(checkdata && data.length===93){ //check for empty data since we are moving forwards
		  page--;
	  }else{
      $("#items").html(data);}

	  //re-define our buttons for new page
	  var next = $("#next-page");
		next.click(function(){
			page++;
			//added a scroll to top when the user changes the listings of items
			$("html, body").animate({ scrollTop: 0 }, "slow");
			window.scrollTo(0,0);
			queryServer(true);

		});

		var prev = $("#prev-page");
		prev.click(function(){
			if(page>1){
				page--;
				//added a scroll to top when the user changes the listings of items
				$("html, body").animate({ scrollTop: 0 }, "slow");
				window.scrollTo(0,0);
				queryServer(false);
			}
		});

    });

    results.fail(function(jqXHR) {
		$("#items").html("There was an error retrieving the items from the database.");
		console.log("Error: " + jqXHR.status);
	});

}

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
