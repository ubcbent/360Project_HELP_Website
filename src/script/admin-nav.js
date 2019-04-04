function hamburgerMenuOpenner()
{
  var menu = document.getElementById("left-nav");

  if (!hasClass(menu, "open"))
  {
    $("#sidebar").css("top", "25.6em");
    menu.classList.add("open");
    menu.style.display = "block";
  }
  else
  {
    $("#sidebar").css("top", "5.78em");
    menu.classList.remove("open");
    menu.style.display = "none";
  }
}

function hamburgerMenuReset()
{
  var menu = document.getElementById("left-nav");
  if(hasClass(menu, "open"))
  {
    $("#sidebar").css("top", "5.78em");
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

  //Mobile Hamburger Events
  $("#left-nav-mobile-hamburger").on("click", hamburgerMenuOpenner);

  $("nav [href]").each(function() {
    if (this.href.split("?")[0] == window.location.href.split("?")[0] && !this.href.includes("#")) {
      $(this).addClass("active");
    }
  });
});

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
