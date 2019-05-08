$(document).ready(function() {


$('#login_form').validate({
  rules: {
      username: {
          required: true
      },
      password: {
          required: true
      }
  },
  messages: {
      username: "Please Enter Username",
      password: "Please Enter Password"
  },
submitHandler: function(form) {
  $.ajax({
             url: "home/check_login",
             type: 'POST',
             data: $('#login_form').serialize(),
             dataType: "json",
             success: function(response) {
                var stats=response.status;
                 if (stats=="success") {
                   swal('Logging in Please wait')
                   window.setTimeout(function () {
                    location.href = "dashboard";
                }, 3000);

               }else if(stats=='incomplete'){
                 $("#loading").hide();
                  $('#last_insert').val(response.last_id)
                  $("#ins_details").show();
                  $('#login_section').hide();
               }else{
                   $('#res').html(response.msg)
                   }
             }
         });
       }

});


$('#forgot_password_form').validate({
rules: {
    email: {
        required: true
    }
},
messages: {
    email: "Please Enter Email"
},
submitHandler: function(form) {
$.ajax({
           url: "home/forgot_password",
           type: 'POST',
           data: $('#forgot_password_form').serialize(),
           dataType: "json",
           success: function(response) {
              var stats=response.status;
               if (stats=="success") {
                 swal('Password is Sent to  Regsitered Email')
                 window.setTimeout(function () {
                  location.href = "login";
              }, 5000);

             }else{
                 $('#res').html(response.msg)
                 }
           }
       });
     }

});




$('#profile_update').validate({
rules: {
    name: {required: true },
    email: { email: true,required: true,
              remote: {
                     url: "home/check_email_exist",
                     type: "post"
                  } },
    gender: {required: true },
    address: {required: true },
    city: {required: true },
    phone: {required: true,  remote: {
             url: "home/check_phone_exist",
             type: "post"
          }
         }
},
messages: {
    name:{
      required :"Please enter name"
    },
    city:{
      required :"Please enter city"
    },
    address:{
      required :"Please enter address"
    },
    gender:{
        required :"Select Gender"
      },
    email: {
					 required: "Please enter Email.",
					 remote: "Email  already in Exist!"
							 },
   phone: {
   					 required: "Please enter phone number.",
   					 remote: "Phone number  already in Exist!"
   							 },

},
submitHandler: function(form) {
$.ajax({
           url: "home/update_profile",
           type: 'POST',
           data: $('#profile_update').serialize(),
           dataType: "json",
           success: function(response) {
              var stats=response.status;
               if (stats=="success") {
                 swal('Profile Updated')
                 window.setTimeout(function () {
                  location.href = "dashboard";
              }, 1000);

             }else{
                 $('#res').html(response.msg)
                 }
           }
       });
     }

});





$('#password_change').validate({
rules: {
        current_password:{
              required: true,
               remote: {
                      url: "home/check_current_password",
                      type: "post"
                   }
            },
            new_password: {
                required: true,
                maxlength: 10,
                minlength:6
            },
            confrim_password: {
                required: true,
                maxlength: 10,
                minlength:6,equalTo: '[name="new_password"]'
            }
},
messages: {
              current_password: {
                    required: "Please enter your old password.",
                    remote: "Old Password Doesn't Match!"
                },
                new_password: {
                  required: "New  password",
                  maxlength:"Maximum 10 digits",
                  minlength:"Minimum 6 digits"

                },
               confrim_password: {
                 required: "New  password does not match",
                 maxlength:"Maximum 10 digits",
                 minlength:"Minimum 6 digits",
                 equalTo:"Password Must Match"

                }

},
submitHandler: function(form) {
$.ajax({
           url: "home/update_password",
           type: 'POST',
           data: $('#password_change').serialize(),
           dataType: "json",
           success: function(response) {
              var stats=response.status;
               if (stats=="success") {
                 swal('Password Updated')
                 window.setTimeout(function () {
                  location.href = "dashboard";
              }, 5000);

             }else{
                 $('#res').html(response.msg)
                 }
           }
       });
     }

});




});
