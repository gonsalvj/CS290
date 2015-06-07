$(document).ready(function(){
$("#add_error").css('display', 'none', 'important');

$("#journal").click(function(e){
          var isValid = validate(e);
          if(isValid) {
           
            jtitle=$("#title").val();
            jdate=$("#date").val();
            jentry=$("#entry").val();
            jmilestones = $.map($('#milestones :selected'), function(e) { return $(e).val(); })

            var form = new FormData(document.getElementById('journalform'));
            form.append('title', jtitle);
            form.append('date', jdate);
            form.append('entry', jentry);         
            form.append('action', 'addjournal');  
            if(typeof jmilestones !== 'undefined' && jmilestones.length > 0) {
              form.append('milestones', JSON.stringify(jmilestones));             
            } else {
              form.append('milestones', null);
            }

             
            var file = document.getElementById('fileupload').files[0];
            if (file) {   
              form.append('fileupload', file);
            }

            $.ajax({
              type: "POST",
              url: "addjournal.php",
              contentType: false,
              cache: false,
              processData: false,
              data: form,
              success: function(html){    
              if(html=='true')    {
                window.location="journals.php";
              } else {
                 $("#add_error").css('display', 'inline', 'important');
                 $("#add_error").css('color', 'red');
                 $("#add_error").html(html);
               //  $("#add_error").html("An error occured, please contact your administrator.");
               }
          }});
      }

     });

$("#updatejournal").click(function(e){
  var isValid = validate(e);
          if(isValid) {
           
            jid = $("#jid").val();
            jtitle=$("#title").val();
            jdate=$("#date").val();
            jentry=$("#entry").val();
            jmilestones = $.map($('#milestones :selected'), function(e) { return $(e).val(); })

            var form = new FormData(document.getElementById('journalform'));
            form.append('jid', jid);
            form.append('title', jtitle);
            form.append('date', jdate);
            form.append('entry', jentry);    
            form.append('action', 'updatejournal');      
            if(typeof jmilestones !== 'undefined' && jmilestones.length > 0) {
              form.append('milestones', JSON.stringify(jmilestones));             
            } else {
              form.append('milestones', null);
            }
             
            var file = document.getElementById('fileupload').files[0];
            if (file) {   
              form.append('fileupload', file);
            }
         
            $.ajax({
              type: "POST",
              url: "addjournal.php",
              contentType: false,
              cache: false,
              processData: false,
              data: form,
              success: function(html){    
              if(html=='true')    {
                window.location="journals.php?id="+jid;
              } else {
                 $("#add_error").css('display', 'inline', 'important');
                 $("#add_error").css('color', 'red');
                 $("#add_error").html(html);
                 //$("#add_error").html("An error occured, please contact your administrator.");
               }
          }});
      }

});
return false;
});

