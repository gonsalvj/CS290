$(document).ready(function(){
$("#add_error").css('display', 'none', 'important');

$("#login").click(function(e){
         
          var isValid = validate(e);
          if(isValid) {
            username=$("#username").val();
            password=$("#password").val();
            $.ajax({
            type: "POST",
            url: "login.php",
            data: "username="+username+"&password="+password,
            success: function(html){    
            if(html=='true')    {
              window.location="index.php";
            } else {
              $("#add_error").css('display', 'inline', 'important');
              $("#add_error").css('color', 'red');
              $("#add_error").html("Username or password is incorrect.");
         }
        }});
      }

     });
return false;
});


