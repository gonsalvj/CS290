$(document).ready(function(){

$("#select").click(function(e){
                 
            milestoneid = $('#possmilestones').val();
            $.ajax({
              type: "POST",
              url: "addmilestone.php",
              data: { action: 'selectmilestone', milestoneid: milestoneid },
              success: function(html){     
                  $("#milestoneform").html(html);
          }});
      e.preventDefault();

});


$("#milestoneform").on("click", '#createmilestone',function(e){
                 
            var isValid = validate(e);
            if(isValid) {           
              detail1=$("#detail1").val();
              detail2=$("#detail2").val();
              mid=$("#mid").val();
              emotion=$("#emotion").val();    
              entryDate=$("#entryDate").val();        
              mjournals = $.map($('#journals :selected'), function(e) { return $(e).val(); })

              var form = new FormData(document.getElementById('milestoneform'));
              form.append('detail1', detail1);
              form.append('detail2', detail2);
              form.append('entryDate', entryDate);
              form.append('mid', mid);
              form.append('emotion', emotion);
              form.append('action', 'addmilestone');
              if(typeof mjournals !== 'undefined' && mjournals.length > 0) {
                form.append('journals', JSON.stringify(mjournals));             
              }else {
              form.append('journals', null);
            }
               
              var file = document.getElementById('fileupload').files[0];
              if (file) {   
                form.append('fileupload', file);
              }

              $.ajax({
                type: "POST",
                url: "addmilestone.php",             
                contentType: false,
                cache: false,
                processData: false,
                data: form,
                success: function(html){                  
                if(html=='true')    {
                  window.location="milestones.php";
                } else {
                    $("#add_error").css('display', 'inline', 'important');
                    $("#add_error").css('color', 'red');
                   // $("#add_error").html(html);
                   $("#add_error").html("An error occured, please contact your administrator.");
                }
                }});
      }

     });

$("#milestoneform").on("click", '#updatemilestone',function(e){
                 
            var isValid = validate(e);
            if(isValid) {           
              detail1=$("#detail1").val();
              detail2=$("#detail2").val();
              mid=$("#mid").val();
              emotion=$("#emotion").val();    
              entryDate=$("#dateoccured").val();        
              mjournals = $.map($('#journals :selected'), function(e) { return $(e).val(); })

              var form = new FormData(document.getElementById('milestoneform'));
              form.append('detail1', detail1);
              form.append('detail2', detail2);
              form.append('entryDate', entryDate);
              form.append('mid', mid);
              form.append('emotion', emotion);
              form.append('action', 'updatemilestone');
              if(typeof mjournals !== 'undefined' && mjournals.length > 0) {
                form.append('journals', JSON.stringify(mjournals));             
              }else {
              form.append('journals', null);
            }
               
              var file = document.getElementById('fileupload').files[0];
              if (file) {   
                form.append('fileupload', file);
              }

              $.ajax({
                type: "POST",
                url: "addmilestone.php",             
                contentType: false,
                cache: false,
                processData: false,
                data: form,
                success: function(html){                  
                if(html=='true')    {
                  window.location="milestones.php?id="+mid;
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




