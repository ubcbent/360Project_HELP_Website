$(document).ready( function(e){
  $("#updateForm").on("submit", function(event) {
    //event.preventDefault();
    validateNewProduct(event);
  });
});

function validateNewProduct(event)
{
  //Clear any existing error messages
  $("#updateForm .error").remove();
  var err = false;

  //Validate Textbox's
  var productName = document.forms["updateForm"]["drugName"];
  var productShort = document.forms["updateForm"]["descShort"];
  var productLong = document.forms["updateForm"]["descLong"];

  if(productName.value.includes("\"")) {
    makeRedTextField(productName);
    err |= true;
  }
  else {
    makeCleanTextField(productName);
  }

  if(productShort.value.includes("\"")) {
    makeRedTextField(productShort);
    err |= true;
  }
  else {
    makeCleanTextField(productShort);
  }

  if(productLong.value.includes("\"")) {
    makeRedTextField(productLong);
    err |= true;
  }
  else {
    makeCleanTextField(productLong);
  }

  //Validate Image 
  var img = document.forms["updateForm"]["productImage"];

  //Check image size
  if(img !== null && img !== undefined) {
    if(img.files[0] !== null && img.files[0] !== undefined) {
      if(img.files[0].size > 500000) {
        makeRedImage(document.getElementById("productImage"));
        err |= true;
      }
      //Check Image Type
      if (!isImage( $(img).val() )) { makeRedImage(document.getElementById("productImage")); err |= true; }

      if (img.files[0].size <= 500000 && isImage( $(img).val() )) { makeCleanImage(document.getElementById("productImage")); }
    }
  }
  else
  {
    makeCleanImage(document.getElementById("productImage"));
  }

  //Check that at least one category has been selected
  var accessories = document.forms["updateForm"]["Accessories"];
  var edible = document.forms["updateForm"]["Edible"];
  var inhalant = document.forms["updateForm"]["Inhalant"];
  var injectable = document.forms["updateForm"]["Injectable"];
  var smokeable = document.forms["updateForm"]["Smokeable"];
  var snortable = document.forms["updateForm"]["Snortable"];
  var used = document.forms["updateForm"]["Used"];

  var categories = [accessories, edible, inhalant, injectable, smokeable, snortable, used];
  var count = 0;

  for(var i = 0; i < categories.length; i++)
  {
    if(categories[i].checked) count++;
  }
  //console.log(count);

  if(count <= 0) { makeRedCategory(document.getElementById("firstCategory")); err |= true; }
  else { makeCleanCategory(document.getElementById("firstCategory")); }

  //Then check if any errors occuered with fields being empty
  if(err) { event.preventDefault(); }
}

function makeRedTextField(inputDiv)
{
  inputDiv.style.borderColor="#AA0000";
  $("#textfield-error-"+inputDiv.name).remove();

  var span = $("<span id='textfield-error-" + inputDiv.name +"' class='error'></span>");
  var para = $("<p>You are not allowed to use double quotation marks!</p>");

  $(inputDiv).before($(span).append(para)).fadeIn(1000);
}

function makeCleanTextField(inputDiv)
{
  inputDiv.style.borderColor="#FFFFFF";
  $("#textfield-error-" + inputDiv.name).remove();
}

function makeRedCategory(inputDiv)
{
  inputDiv.style.borderColor="#AA0000";
  $("#category-error").remove();

  var span = $("<span id='category-error' class='error'></span>");
  var para = $("<p>You must select at least one category!</p>");

  $(inputDiv).before($(span).append(para)).fadeIn(1000);
}

function makeCleanCategory(inputDiv)
{
  inputDiv.style.borderColor="#FFFFFF";
  $("#category-error").remove();
}
