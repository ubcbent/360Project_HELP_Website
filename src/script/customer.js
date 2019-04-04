function openBox(id) {
  var button = document.getElementById(id);
  button.classList.toggle("active");
  var content = button.nextElementSibling;
  if (content.style.maxHeight){
    content.style.maxHeight = null;
  } else {
    content.style.maxHeight = content.scrollHeight + "px";
  }
}


$(document).ready( function()
{
  var coll = document.getElementsByClassName("collapsible");
  var i;

  for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var content = this.nextElementSibling;
      if (content.style.maxHeight){
        content.style.maxHeight = null;
      } else {
        content.style.maxHeight = content.scrollHeight + "px";
      }
    });
  }

  //TODO: Check url for id, if so need to open the collapsible item accordingly
  var hash = window.location.hash;
  hash = hash.substring(hash.lastIndexOf('#')+1);
  if(hash !== null && hash !== undefined && hash !== "") { openBox(hash); }

});
