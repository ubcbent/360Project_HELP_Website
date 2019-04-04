$(document).ready( function() {

  $("#checkout-form").on("submit", function(event) {
    if(!$('#final-confirm input[type="checkbox"]').prop("checked"))
    {
      $(".red").removeClass("invis");
      event.preventDefault();
    }
  });
});
