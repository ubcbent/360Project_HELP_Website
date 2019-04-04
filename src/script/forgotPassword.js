$(document).ready( function() {
  $("#forgot-password-form2").on("submit", function(event) {
    //event.preventDefault();
    validatePasswordReset(event);
  });
});
