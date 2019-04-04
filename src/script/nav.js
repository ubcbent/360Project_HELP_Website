function toggleFullScreen()
{
  var doc = window.document;
  var docEl = doc.documentElement;

  var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
  var cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

  if(!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
    requestFullScreen.call(docEl);
  }
  else {
    cancelFullScreen.call(doc);
  }
}

function closeModal()
{
  $("#modal").hide();
  $("#registration-form").hide();
  $("#signin-form").hide();
  $("#forgot-password-form").hide();
  $("#modal-close-background").hide();
  $("#shopping-cart-container").hide();
  $("#modal").css("opacity", "0.7");
  //Make sure forms are reset if user goes back a page
  /*var registrationForm = $("#registration-form")[0];
  if(registrationForm !== null && registrationForm !== undefined){
      $("#registration-form")[0].reset();
  }*/

  var signinForm = $("#signin-form-form")[0];
  if(signinForm !== null && signinForm !== undefined){
      $("#signin-form-form")[0].reset();
  }
  //$("#registration-profile-photo").attr('src', "assets/images/unknown-profile.png");
  $("#modal").css("zIndex", "12");
}

function displayRegistrationModal()
{
  $("#modal").show();
  $("#registration-form").show();
  $("#modal-close-background").show();
}

function displaySignInModal()
{
  $("#modal").show();
  $("#modal-close-background").show();
  $("#signin-form").show();
  $("#signin-form-form").show();
}

function displayForgotPasswordModal()
{
  $("#signin-form-form").hide();
  $("#forgot-password-form").show();
}

//User Account
function openAccountMenu()
{
  var menu = document.getElementById("nav-user-selection-container");

  var useraccountMouseout = document.getElementById("nav-user-account-mouseout");
  var bodyMouseout = document.getElementById("body-mouseout");

  if (!hasClass(menu, "open"))
  {
    menu.style.display = "block";
    menu.classList.add("open");

    useraccountMouseout.style.display = "block";
    bodyMouseout.style.display = "block";
  }
}

function closeAccountMenu()
{
  var menu = document.getElementById("nav-user-selection-container");

  var useraccountMouseout = document.getElementById("nav-user-account-mouseout");
  var bodyMouseout = document.getElementById("body-mouseout");

  if(hasClass(menu, "open"))
  {
    menu.style.display = "none";
    menu.classList.remove("open");

    useraccountMouseout.style.display = "none";
    bodyMouseout.style.display = "none";
  }
}

function closeErrorMessage()
{
  $("#error-dialog-box").hide();
}

//Shopping Cart:
function displayShoppingCart()
{
  $("#modal").show();
  $("#modal").css("opacity", "0");
  $("#shopping-cart-container").show();
  $("#modal").css("zIndex", "11");
  $("#shopping-cart-element-container").css("scrollTop", "0");
}

function addToCart(name, quantity, price)
{

}

//Display Hamburger Dropdown Menu For Mobile Resolutions
function hamburgerMenuOpenner()
{
  var menu = document.getElementById("left-nav");

  if (!hasClass(menu, "open"))
  {
    menu.classList.add("open");
    menu.style.display = "block";
  }
  else
  {
    menu.classList.remove("open");
    menu.style.display = "none";
  }
}

function hamburgerMenuReset()
{
  var menu = document.getElementById("left-nav");
  if(hasClass(menu, "open"))
  {
    menu.classList.remove("open");
    menu.style.display = "none";
  }

  if(window.innerWidth >= 959)
  {
    menu.style.display = "block";
  }
  else
  {
      menu.style.display = "none";
  }
}

$(document).ready( function(e)
{
  //Check the scaling for the viewport and don't let it go below 492px
  if (screen.width < 492)
  {
        var mvp = $("#myViewPort");
        mvp.attr('content','user-scalable=no,width=492');
  }

  //Make sure forms are reset if user goes back a page
  /*var registrationForm = $("#registration-form")[0];
  if(registrationForm !== null && registrationForm !== undefined){
      $("#registration-form")[0].reset();
  }*/

  var signinForm = $("#signin-form-form")[0];
  if(signinForm !== null && signinForm !== undefined){
      $("#signin-form-form")[0].reset();
  }

  //Blocks to hide
  $("#nav-register-noscript").hide();
  $("#nav-signin-noscript").hide();
  $("#registration-photo-uploader").hide();

  //Block to display
  $("#nav-register").show();
  $("#nav-signin").show();
  $("registration-profile-camera-icon").show();

  //Mobile Hamburger Events
  $("#left-nav-mobile-hamburger").on("click", hamburgerMenuOpenner);

  //Add events
  $("#nav-register").on("click", displayRegistrationModal);
  $("#nav-signin").on("click", displaySignInModal);
  $("#nav-shopping-cart").on("click", displayShoppingCart);
  $("#shopping-cart-header").on("click", closeModal);
  $("#modal-close").on("click", closeModal);
  $("#modal").on("click", closeModal);

  $("#nav-user-profile-picture").on("mouseover", openAccountMenu);
  $("#nav-shopping-cart-mouseout").on("mouseover", closeAccountMenu);
  $("#nav-shopping-cart-mouseout").on("touchstart", closeAccountMenu);
  $("#body-mouseout").on("mouseover", closeAccountMenu);

  $("#forgot-password").on("click", displayForgotPasswordModal);
  $("#error-dialog-box a").on("click", closeErrorMessage);

  //Validation Events:
  $("#registration-form").on("submit", function(event) {
    //event.preventDefault();
    validateRegistration(event);
  });
  $("#signin-form").on("submit", function(event) {
    //event.preventDefault();
    validateSignIn(event);
  });
  var inputCardNum = $("#registration-form input[name='cardNum']"); //document.forms["registration-form"]["cardNum"];
  var inputCardCVV = $("#registration-form input[name='cvv']"); //document.forms["registration-form"]["cvv"];
  inputCardNum.on("keypress", function(event) {
    return isNumberKey(event);
  });
  inputCardCVV.on("keypress", function(event) {
    return isNumberKey(event);
  });
  inputCardNum.on("change", function(event) {
    if (!isNumeric(event.target.value)) { inputCardNum.value = ""; }
  });
  inputCardCVV.on("change", function(event) {
    if (!isNumeric(event.target.value)) { inputCardCVV.value = ""; }
  });

  $("nav [href]").each(function() {
    if (this.href.split("?")[0] == window.location.href.split("?")[0] && !this.href.includes("#")) {
      $(this).addClass("active");
    }
  });
});

/*window.onclick = function(event) {
  var accountMenu = document.getElementById("nav-user-selection-container");
  if(hasClass(accountMenu, "open") && event.target !== accountMenu) {
    closeAccountMenu();
  }
}*/

window.onresize = function()
{
  hamburgerMenuReset();

  //Check the scaling for the viewport and don't let it go below 492px
  var mvp = $("#myViewPort"); //document.getElementById("myViewPort");
  if (screen.width < 492)
  {
      mvp.attr('content','user-scalable=no,width=492');
  }
  else
  {
    mvp.attr('content','width=device-width, initial-scale=1');
  }
}


//Registration Photo and select box methods:
$(document).ready( function()
{

  //Profile Picture Upload and set the photoviewer:
  var readURL = function(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $("#registration-profile-photo").attr("src", e.target.result);
      }
      reader.readAsDataURL(input.files[0]);

      if(input.files[0].size > 500000) {
        makeRedImage(document.getElementById("registration-profile-photo"));
      }
      else
      {
        makeCleanImage(document.getElementById("registration-profile-photo"));
      }
    }
  }

  $("#registration-photo-uploader").on("change", function() {
      readURL(this);
  });

  $("#registration-profile-camera-icon").on("click", function() {
     $("#registration-photo-uploader").click();
  });

  $("#registration-profile-photo").on("click", function() {
     $("#registration-photo-uploader").click();
  });


  //
  //Registration form select boxes (Update label for selection)
  //
  $("#registration-form select").on("click" , function() {
    $(this).parent(".registration-select-box").toggleClass("open");
  });

  $(document).mouseup( function (e) {
      var container = $(".registration-select-box");
      if (container.has(e.target).length === 0) {
          container.removeClass("open");
      }
  });

  $("#registration-form select").on("change" , function() {
    var selection = $(this).find("option:selected").text();
    var labelFor = $(this).attr("id");
    var parent = $(this).parent();
    var label = parent.find("label");
    label.html(selection);
  });
});
