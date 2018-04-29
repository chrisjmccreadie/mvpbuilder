/*
	Main js for the template system's www




*/


var rootUrl = location.protocol + '//' + location.host;
//have the display messages defined here so we can easily call them.
var loginError = "Username or password is incorrect";
var AccountUpdated = "Account has been updated";
var AccountUpdatedError = "Account has not been updated";
var AccountExistsError = "Email already used";
var AccountCreatedError = "Account has not been created";

var EmailError = "Email is not valid";
var PasswordError = "Passwords do not match";
var GenericBlankError = "cannot be blank";
var forgotPasswordSuccess = 'Email has been sent with reset instructions';
var resetPasswordError = 'password has not been reset';

var forgotPasswordError = 'Email not found';

//set a global call  back
var callbackresult = '';


//todo have it work with the other kinds of alerts such as errors and not just the same generic layout each time.
function alertMessage(message,type,id)
{
    //basic alert
    if (type == 1)
    {
       bootbox.alert(message); 
    }
    //confirm message
    if (type == 2)
    {

    }

}

//validate the different account types.
function validateAcoountFields(checkEmail,checkPassword,validateType)
{
    
    //set login to 1 
    success = 1;

    $( ".formelement" ).each(function()
    {

        //get name of picker
        var name = $(this).attr("name");

        $('#error'+name).removeClass('select_error');
        $('#error'+name).text('');
            
        //check something is selected
        if ($( this ).val() == '')
        {
            //console.log(name);
                
            if (validateType == 'myaccount')
            {
                if ((name != 'password') && (name != 'confirmpassword'))
                {
                    console.log('in 0');
                    $('#error'+name).text(name+' '+GenericBlankError);
                    $('#error'+name).addClass('select_error');
                    success = 0; 
                }
            }
            else
            {
                 console.log('in');
                 $('#error'+name).text(name+' '+GenericBlankError);
                 $('#error'+name).addClass('select_error');
                 success = 0;
            }
               
            
           
        }
       
    });

   
    //check they have added an email and if not set logged in to false
    if (checkEmail == 1)
    {
        //check its a valid email
        //we may to check this field exists 
        if (validateEmail($('#email').val()) == false)
        {
            //error
            $('#erroremail').text(EmailError);
            $('#erroremail'+name).addClass('select_error');
            success = 0;
        } 
    }
    if (checkPassword == 1)
    {
        if (validateType != 'signin')
        {
            if ($('#password').val() != $('#confirmpassword').val())
            {
                //error

                $('#errorpassword').text(PasswordError);
                $('#errorpassword'+name).addClass('select_error');
                
                success = 0;
            }
        }

       
    }

    
   
    

    return(success);
    

}


/*

START OF AJAX CALL BACK FUNCTIONS

*/



function resetpasswordsuccess()
{
    if (callbackresult == 1)
    {
        location.href = rootUrl+'/signin';
    }
    else
    {
        alertMessage(forgotPasswordError,1,0);
    } 
}


function forgotpasswordsuccess()
{
    if (callbackresult == 1)
    {
        alertMessage(forgotPasswordSuccess,1,0);
    }
    else
    {
        alertMessage(forgotPasswordError,1,0);
    } 
}


function loginsuccess()
{
    if (callbackresult == 1)
    {
        location.href = rootUrl+'/';
    }
    else
    {
        alertMessage(loginError,1,0);
    }
}

function updateaccountsuccess()
{
	//alert(callbackresult);
	if (callbackresult == 1)
    {
        alertMessage(AccountUpdated,1,0)
    }
    else
    {
        alertMessage(AccountUpdatedError,1,0);
    }	
}

function createaccountsuccess()
{
    //check the response
    switch(callbackresult) 
    {
        case "0":
            //some error
             alertMessage(AccountCreatedError,1,0)
            break;
        case "1":
            //its good
            location.href = rootUrl+'/signin';
            break;
        case "2":
            alertMessage(AccountExistsError,1,0) 
    
    }
}

/*

START OF AJAX CALL BACK FUNCTIONS

*/




/*
    This function posts to the server.
*/
function postAjax(url, data,callback) 
{
	console.log(data);
    //return;
    $.ajax({
       type: 'POST',    
        url:url,
        data:data,
        success: function(result)
        {
            //set a callback.
            //note (chris) I put this into a global var but it would be easy enough to move it into a local var if globals offend you.
            callbackresult = result;
            console.log(callbackresult);
           	console.log(callback);
            //turn the callback into a function and call it. 
            //note (chris) this is an advanced eval function which offends me deeply (casting is bad mk) but I have yet to find a neater way to use the same ajax function
            //to hande many different.  I have seen other soltions but they are way to complex for such a simple thing.  I mean really I should be able to bind a call to a function 
            //and be done with, maybe this can be done and I am / have been to dumb to figure it out.  If this is the case and someone shows me I am happy to change the way this works
            var tmpFunc = new Function(callback);
            //call the function
            tmpFunc();
        }
     });
    
}

//check the email is valid
function validateEmail(email) 
{
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}


$( document ).ready(function() 
{





    
    $("#passwordreset").click(function(e) 
    {   
        //Stop loading
        e.preventDefault();
        var result = validateAcoountFields(0,1,'passwordreset');
        //check they passed the above
        if (result == 1)
        {
            var data = $('#passwordresetform').serialize(); 
            //console.log(data);
            //turn it into a nice JSON object as jSON parse does this very badly
            data = JSON.parse('{"' + decodeURI(data.replace(/&/g, "\",\"").replace(/=/g,"\":\"")) + '"}')
            //build the url
            var url = rootUrl+'/clientajax/resetPassword';
            postAjax(url, data,'resetpasswordsuccess()');

        }

    });

    $("#forgotpasswordbutton").click(function(e) 
    {   
       //check its a valid email
        //we may to check this field exists 
        if (validateEmail($('#email').val()) == false)
        {
            //error
            $('#erroremail').text(EmailError);
            $('#erroremail'+name).addClass('select_error');
            success = 0;
        }
        else
        {
            
            var data = $('#forgotpasswordform').serialize(); 
            data = JSON.parse('{"' + decodeURI(data.replace(/&/g, "\",\"").replace(/=/g,"\":\"")) + '"}');
            console.log(data);
            //build the url
            var url = rootUrl+'/clientajax/forgotPasssword';
            postAjax(url, data,'forgotpasswordsuccess()');          
        }

    });

    //todo (chris) the validation for myaccount/signup and signin is almost the same so we can refactor this into one function
    //sign up button
    $("#signupbutton").click(function(e) 
    {

        //Stop loading
        e.preventDefault();
        var result = validateAcoountFields(1,1,'signup');
        //check they passed the above
        if (result == 1)
        {
            var data = $('#signupform').serialize(); 
            //console.log(data);
            //turn it into a nice JSON object as jSON parse does this very badly
            data = JSON.parse('{"' + decodeURI(data.replace(/&/g, "\",\"").replace(/=/g,"\":\"")) + '"}')
            //build the url
            var url = rootUrl+'/clientajax/createAccount';
            postAjax(url, data,'createaccountsuccess()');

        }
    });

	$("#myaccount").click(function(e) 
	{

		//Stop loading
        e.preventDefault();
        var result = validateAcoountFields(1,1,'myaccount');
         //check they passed the above
        if (result == 1)
        {
        	var data = $('#myaccountform').serialize(); 
        	//console.log(data);
        	//turn it into a nice JSON object as jSON parse does this very badly
        	data = JSON.parse('{"' + decodeURI(data.replace(/&/g, "\",\"").replace(/=/g,"\":\"")) + '"}')
        	//build the url
            var url = rootUrl+'/clientajax/updateAccount';
            postAjax(url, data,'updateaccountsuccess()');

        }
	});

	//toggle class open and close the menu
    $("#clientsignin").click(function(e) 
    {
        //Stop loading
        e.preventDefault();
        var result = validateAcoountFields(1,0,'signin');
        //check they passed the above
        if (result == 1)
        {
        	//build the url
            var url = rootUrl+'/clientajax/checkSignin';
            postAjax(url, { password: $('#password').val(), email: $('#email').val() },'loginsuccess()');

        }
        else
        {
            //error
            alertMessage(loginError,1,0);

        }
       
    });
   



});