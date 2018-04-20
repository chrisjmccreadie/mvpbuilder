/*
	admin js for the template system

	We can improve this by using a form validator such as parsley, this is the bootstrapped version with the simplest use case. 

*/
var rootUrl = location.protocol + '//' + location.host;
//have the display messages defined here so we can easily call them.
var loginError = "Username or password is incorrect";
var recordAddedSuccess = "The record was added";
var recordAddedError = "The record was not added";
var selectDropdownError = "Select from dropdown";
var recordUpdated = 'The record has been updated';
var recordUpdatedError = 'The record was not updated';
//set a global call  back
var callbackresult = '';


//this function shows alerts
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


/*

START OF AJAX CALL BACK FUNCTIONS

*/


function loginsuccess()
{
    if (callbackresult == 1)
    {
        location.href = rootUrl+'/admin/dashboard';
    }
    else
    {
        alertMessage(loginError,1,0);
    }
}

function addrecorddone()
{
     if (callbackresult == 1)
    {
        alertMessage(recordAddedSuccess,1,0);
    }
    else
    {
        alertMessage(recordAddedError,1,0);
    }
}

function editrecorddone()
{

    if (callbackresult == 1)
    {
        alertMessage(recordUpdated,1,0);
    }
    else
    {
        alertMessage(recordUpdatedError,1,0);
    }
}

function deleterecorddone()
{
    //todo (chris) add animation (is this required in the generic version)
    //todo (chris) Remove the row and replace hard refresh
    //var table = $('#admintable').DataTable();
    //table.row('#row'.callbackresult).remove().draw();
    location.reload();

}

/*

END OF AJAX CALL BACK FUNCTIONS

*/


/*
    This function posts to the server.
*/
function postAjax(url, data,callback) 
{
    //console.log(data);
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

/* 
    This function deletes / archives a record from the database. 


*/
function deletetablerecord(id)
{
     //todo (chris) move this to the generic message function at some point
     bootbox.confirm({
            message: "Are you sure you want to delete this record?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
               if (result == true)
               {
                    var data = { id: id,table: $('#table').val() }
                    //get the tables
                    var url = rootUrl+'/ajax/deleterecord';
                    //call it
                    postAjax(url, data,'deleterecorddone()');
                    
                    
               }
            }
        });
}

/*
    This function handles the add / editing or records.

*/
function processrecord(updatetype)
{
    //set the succes var 
    var success = 1;
    //reset error classes
    $( ".selecterrors" ).text('');
    $( ".selecterrors" ).removeClass('select_error');
    
    //loop through the pickers 
    $( ".picker" ).each(function()
    {
        //get name of picker
        var name = $(this).attr("name");
        //check something is selected
        if ($( this ).val() == '')
        {
            //set the error
            $('#error'+name).text(selectDropdownError);
            $('#error'+name).addClass('select_error');
            
            success = 0;
           
        }
       
    });
    //check it passed validation
    if (success == 1)
    {
        //seralise the form
        var data = $('#addrecordform').serialize(); //  <-----------
        //turn it into a nice JSON object as jSON parse does this very badly
        data = JSON.parse('{"' + decodeURI(data.replace(/&/g, "\",\"").replace(/=/g,"\":\"")) + '"}')
        //set the url to call edit or add
        if (updatetype == 'edit')
        {
            var url = rootUrl+'/ajax/editrecord';
             //call it
            postAjax(url, data,'editrecorddone()');
        }
        else
        {
            var url = rootUrl+'/ajax/addrecord';
             //call it
            postAjax(url, data,'addrecorddone()');
        }
       
    }
}


$( document ).ready(function() 
{

   
    //initalise the datatable
    //note (chris) if there is no table on this page this may throw an error on some browsers if this is the case then we will have to 
    //             check if the elements exists before initalising it.
	$('#admintable').DataTable();

    //date and time picker
    $('.datepicker').datepicker({});

    //todo (chris) get wysiwyg working
    $('#editor').wysiwyg();

    $('.selectpicker').selectpicker({});

    

     //this function adds a record
    $("#updaterecord").click(function(e) 
    {
        processrecord('edit');
       
    });

    //this function adds a record
    $("#addrecord").click(function(e) 
    {
        processrecord('add');

    });

    //toggle class open and close the menu
    $("#login").click(function(e) 
    {
    	//Stop loading
        e.preventDefault();
        //set login to 1 
        loggedin = 1;
        //check they have added an email and if not set logged in to false
        if ($('#email').val() == '')
        {
        	loggedin = 0;
        }
        //check they have added a password and if not set logged in to false
        if ($('#password').val() == '')
        {
        	loggedin = 0;
        }

        //check they passed the above
        if (loggedin == 1)
        {
        	//build the url
            var url = rootUrl+'/ajax/checkLogin';
            postAjax(url, { password: $('#password').val(), email: $('#email').val() },'loginsuccess()');

        }
        else
        {
            //error
            alertMessage(loginError,1,0);

        }
       
    });
});