function validate(e, form) {
	var isValid = true;
	e.preventDefault();
    $(":input").not("[type=submit], .optional").removeClass('error').each(function () {
      if ($.trim($(this).val()).length == 0) {
        isValid = false;
        $(this).addClass('error');
        $(this).parent().css('color', 'red');
      }
   });
    return isValid;
}

function validatedate(date) {
  var isValid = true;
  var today = new Date();
  if(date > now) {
    isValid = false;
  }
  return isValid;
}
