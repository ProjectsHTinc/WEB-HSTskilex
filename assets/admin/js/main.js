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
    phone_number: {
        required: true,digits:true,minlength:10,maxlength:10
    }
},
messages: {
    phone_number: {
      required:"Please Enter Mobile number",
      maxlength:"Maximum 10 digits",
      minlength:"Minimum 10 digits"
    }
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
                 swal('OTP Sent to Mobile number')
                $('#reset_otp_form_section').show();
              $('#forgot_password_mobile_number_section').hide();

             }else{
                 $('#res').html(response.msg)
                 }
           }
       });
     }
});



$('#reset_otp_form').validate({
rules: {
    phone_number_otp: {
        required: true
        }
},
messages: {
    phone_number_otp: {
      required:"Please Enter Mobile number"
    }
},
submitHandler: function(form) {
$.ajax({
           url: "home/check_otp_password",
           type: 'POST',
           data: $('#reset_otp_form').serialize(),
           dataType: "json",
           success: function(response) {
              var stats=response.status;
               if (stats=="success") {
              swal('OTP Verified')
              $('#password_form_section').show();
              $('#forgot_password_mobile_number_section').hide();
              $('#reset_otp_form_section').hide();

             }else{
                 $('#res_otp').html(response.msg)
                 }
           }
       });
     }
});


$('#reset_password_form').validate({
rules: {
    new_password: {
        required: true,  minlength : 6,maxlength:12,
      },
      confrim_password: {
          required: true,
          equalTo : '[name="new_password"]',
        }
},
messages: {
    new_password: {
      required:"Please Enter New Password"
    },
    confrim_password: {
            required: "Enter Confirm Password",
            notEqualTo: "Password Should Match"
    }
},
submitHandler: function(form) {
$.ajax({
           url: "home/reset_password",
           type: 'POST',
           data: $('#reset_password_form').serialize(),
           dataType: "json",
           success: function(response) {
              var stats=response.status;
               if (stats=="success") {
              swal('Password changed successfully')
              window.setTimeout(function () {
               location.href = "login";
             }, 1000);

             }else{
                 $('#res_otp').html(response.msg)
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
    phone: {required: true,digits:true,minlength:10,maxlength:10,  remote: {
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





$('#create_city').validate({
rules: {

    city_name: { required: true,
              remote: {
                     url: "checkcity",
                     type: "post"
                  }
        },
    city_ta_name: { required: true,
              remote: {
                     url: "checkcitytamil",
                     type: "post"
                  }
     },
    latitude: {required: true },
    longitude: {required: true }
},
messages: {
    longitude:{
        required :"Enter the longitude"
    },
    latitude:{
        required :"Enter the latitude"
      },
    city_name: {
					 required: "Please Enter City Name.",
					 remote: "City Name  already in Exist!"
							 },
     city_ta_name: {
           required: "Please Enter City Tamil Name.",
           remote: "City Tamil Name  Already in Exist!"
               },

},
submitHandler: function(form) {
$.ajax({
           url: "city_creation",
           type: 'POST',
           data: $('#create_city').serialize(),
           dataType: "json",
           success: function(response) {
              var stats=response.status;
               if (stats=="success") {
                 swal('City Created successfully')
                 window.setTimeout(function () {
                  location.href = "create_city";
              }, 1000);

             }else{
                swal(stats);
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
