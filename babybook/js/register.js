$(document).ready(function(){
$("#register_error").css('display', 'none', 'important');

$("#register").click(function(e){
  $("#register_error").css('display', 'none', 'important');


    var valid = validate(e);

    if(valid) {
      fname=$("#fname").val();
      lname=$("#lname").val();
      email=$("#email").val();
      psw=$("#psw").val();
      psw2=$("#psw2").val();

      if(psw != psw2) {
        $("#register_error").css('display', 'inline', 'important');
        $("#register_error").css('color', 'red');
        $("#register_error").html("Password values do not match.");
      } else {
            $.ajax({
            type: "POST",
            url: "addnewuser.php",
            data: "fname="+fname+"&lname="+lname+"&email="+email+"&psw="+psw+"&psw2="+psw2,
            success: function(html){   
               if(html=='true')    {

                window.location="index.php";
              } else {
                $("#register_error").css('display', 'inline', 'important');
                $("#register_error").css('color', 'red');
                $("#register_error").html("An error with your registration has occured.  Please contact your administrator.");
              }
        }});
      }
          

   }
     });
return false;
});
