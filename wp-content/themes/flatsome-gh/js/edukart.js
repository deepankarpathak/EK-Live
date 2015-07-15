function validateemail(emailadd){
   var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(emailadd)) {
       return true;
    }
    else {
	  return false;
    }
  

    
}
$(document).ready(function(){
    jQuery("#username").blur(function() {
        var emailadd = $("#username").val();
        
        if(validateemail(emailadd)){
            
           //calling ajax 
             $.ajax({
                url:"http://localhost/edukart/wp-admin/admin-ajax.php",
                type:'POST',
                data:'action=validate_email&username=' +emailadd,
                success:function(result){
                    //alert(result);
                    if(result != ''){
                    $("#edkrtpwd").show();
                    $("#login_form_check").show();
                    }
                    else{
                     $("#customer_details").show();
                    }
                }
            });
            
        }
        else{
            alert("email address is not valid");
        }
    });

});

