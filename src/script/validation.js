function isNumberKey(evt)
{
  var charCode = (evt.which) ? evt.which : event.keyCode
  if (charCode > 31 && (charCode < 48 || charCode > 57))
  {
    return false;
  }
  return true;
}

function isNumeric(n)
{
  return !isNaN(parseFloat(n)) && isFinite(n);
}

var isLetter = function(ch)
{
	return /^[A-Z]$/i.test(ch);
}

var isNameChar = function(ch)
{
	return /^[a-zA-Z\s]*$/.test(ch);
}

function hasClass(element, className)
{
  return (' ' + element.className + ' ').indexOf(' ' + className + ' ') > -1;
}

function isEmptyText(inputField)
{
  if (inputField.value=="") { return true; }
  return false;
}

function isEmptySelect(inputField)
{
  if(inputField.options.length == 0) { return true; }
  else if (inputField.selectedIndex == 0) {return true; }
  return false;
}

function makeRed(inputDiv)
{
	inputDiv.style.borderColor="#AA0000";

  var span = $("<span class='error'></span>");
  var para = $("<p>Please correct the field below</p>");

  $(inputDiv).before($(span).append(para)).fadeIn(1000);
}

function makeRedImage(inputDiv)
{
  inputDiv.style.borderColor="#AA0000";
  $("#image-error").remove();

  var span = $("<span id='image-error' class='error'></span>");
  var para = $("<p>The image size is too big!</p>");

  $(inputDiv).before($(span).append(para)).fadeIn(1000);
}

function makeRedSelect(inputDiv)
{
	inputDiv.style.borderColor="#AA0000";

  var span = $("<span class='error'></span>");
  var para = $("<p>Please correct the Selection Box below</p>");

  var div = $("#registration-select-box-" + $(inputDiv).attr('name') );

  div.before($(span).append(para)).fadeIn(1000);
}

function makeRedTerms(inputDiv)
{
	inputDiv.style.borderColor="#AA0000";

  var span = $("<span class='error'></span>");
  var para = $("<p>Please accept the terms below</p>");

  $(inputDiv).before($(span).append(para)).fadeIn(1000);
}

function makeClean(inputDiv)
{
	inputDiv.style.borderColor="#FFFFFF";
}

function makeCleanImage(inputDiv)
{
  inputDiv.style.borderColor="#FFFFFF";
  $("#image-error").remove();
}

function validateRegistration(event)
{
  var name = document.forms["registration-form"]["cname"];
  var email = document.forms["registration-form"]["email"];
  var password1 = document.forms["registration-form"]["password"];
  var password2 = document.forms["registration-form"]["password-check"];
  var img = document.forms["registration-form"]["profilePhoto"];
  var gender = document.forms["registration-form"]["gender"];
  var country = document.forms["registration-form"]["country"];
  var provState = document.forms["registration-form"]["prov-state"];
  var city = document.forms["registration-form"]["city"];
  var street = document.forms["registration-form"]["steet"];
  var postalCode = document.forms["registration-form"]["postcode"];
  var payType = document.forms["registration-form"]["payment-method"];
  var cardNum = document.forms["registration-form"]["cardNum"];
  var cvv = document.forms["registration-form"]["cvv"];
  var month = document.forms["registration-form"]["month"];
  var year =  document.forms["registration-form"]["year"];
  var terms = document.forms["registration-form"]["terms"];

  //Clear any existing error messages
  $("#registration-form .error").remove();

  //Put all fields into arrays by type so that the correct checks will be made on them
  var textFields = [name, email, password1, password2, country, provState, city, postalCode, cardNum, cvv];
  var selectionFields = [gender, country, month, year];

  var err = false;

  //Check Text Fields
  for (var i = 0; i < textFields.length; i++)
  {
    //console.log("Checking: " + textFields[i].getAttribute("name") + ", '" + textFields[i].value + "'");
    if(isEmptyText(textFields[i]))
    {
      makeRed(textFields[i]);
      err |= true;
    }
    else { makeClean(textFields[i]); }
  }

  //Check Selection Fields
  //console.log("     -> Selection Fields: " + selectionFields.length);

  var monthErrorShown = false;

  for (var i = 0; i < selectionFields.length; i++) {
    //console.log("Checking: " + selectionFields[i].name + ", '" + selectionFields[i].selectedIndex + "'");
    if(isEmptySelect(selectionFields[i]))
    {
      if(i == 0) makeRedSelect(selectionFields[i]);
      else if (i == 2)
      {
        makeRedSelect(selectionFields[i]);
        monthErrorShown |= true;
      }
      else if (i == 3 && !monthErrorShown)
      {
        makeRedSelect(selectionFields[i-1]);
      }

      err |= true;
    }
    else { makeClean(textFields[i]); }
  }

  //Check image size
  if(img.files[0].size > 500000) {
    makeRedImage(document.getElementById("registration-profile-photo"));
    err |= true;
  }
  else
  {
    makeCleanImage(document.getElementById("registration-profile-photo"));
  }

  //Check Image Type
  if (!isImage( $(img).val() )) { makeRedImage(document.getElementById("registration-profile-photo")); err |= true; }

  if (img.files[0].size < 500000 && isImage( $(img).val() )) { makeCleanImage(document.getElementById("registration-profile-photo")); }

  //Check Terms checkbox
  if(!terms.checked) { makeRedTerms(terms); err |= true; }

  //Then check if any errors occuered with fields being empty
  if(err) { event.preventDefault(); }

  //Check that passwords match
  if(password1.value !== password2.value)
  {
    makeRed(password1);
    event.preventDefault();
  }
  else
  {
    makeClean(password1);
  }
}

function isImage(filename) {
    var parts = filename.split('.');
    var ext = parts[parts.length - 1];
    switch (ext.toLowerCase()) {
    case 'jpg':
    case 'gif':
    case 'jpeg':
    case 'png':
        return true;
    }
    return false;
}

function validateSignIn(event)
{
  var email = document.forms["signin-form-form"]["email"];
  var password = document.forms["signin-form-form"]["password"];

  if(isEmptyText(email))
  {
    event.preventDefault();
  }

  if(isEmptyText(password))
  {
    event.preventDefault();
  }
}

function validatePasswordReset(event)
{
  var email = document.forms["forgot-password-form2"]["email"];
  var key = document.forms["forgot-password-form2"]["email"];
  var password = document.forms["forgot-password-form2"]["email"];
  var password2 = document.forms["forgot-password-form2"]["email"];
  var fields = [email, key, password, password2];

  var err = false;

  for(var i = 0; i < fields.length; i++)
  {
    if(isEmptyText(fields[i]))
    {
      makeRed(textFields[i]);
      err |= true;
    }
  }

  //Then check if any errors occuered with fields being empty
  if(err) { event.preventDefault(); }

  //Check that passwords match
  if(password.value !== password2.value)
  {
    makeRed(password);
    event.preventDefault();
  }
  else
  {
    makeClean(password1);
  }
}
