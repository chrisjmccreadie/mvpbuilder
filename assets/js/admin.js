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
var fieldUpdated = 'The field has been updated';
var fieldUpdatedError = 'The field was not updated';
var fieldBlankError = 'Cannot be blank';
var fieldNumberError = 'Must be a number';



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

function updatetablemetasuccess()
{
    console.log(callbackresult);
    if (callbackresult == 1)
        alertMessage(fieldUpdated,1,0); 
    else 
        alertMessage(fieldUpdatedError,1,0); 

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

    //reset all errors
    $( ".formelement" ).each(function()
    {
        //get name 
        var name = $(this).attr("name");
        //resset the error
        $('#error'+name).text('');
        $('#error'+name).removeClass('select_error');
    });
           
    //check the int types have a number
    $( ".fieldint" ).each(function()
    {
        //get name 
        var name = $(this).attr("name");
        //get type
        var type = $(this).val();
        if(isNaN(type))
        {
            $('#error'+name).text(fieldNumberError);
            $('#error'+name).addClass('select_error');
            
            success = 0;
        }
    });
   
    
    //loop the form elements looking for blanks
    $( ".formelement" ).each(function()
    {
        //get name 
        var name = $(this).attr("name");
        var required = $(this).attr("data-required");

        //check something is selected
        if (required == 1)
        {
            if ($( this ).val() == '')
            {
                //set the error
                $('#error'+name).text(fieldBlankError);
                $('#error'+name).addClass('select_error');
                success = 0;
            }
        }
    });


    


    return;
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

var elementname = '';
 var fsClient = filestack.init('AcmgIR4JRI6Qxp4TRF0lOz');
  function openPicker(elementname) {
    elementname = elementname;
    fsClient.pick({
      fromSources:["local_file_system","imagesearch","facebook","instagram","dropbox"]
    }).then(function(response) {
        console.log(elementname);
        $('#image'+elementname).attr('src','https://process.filestackapi.com/resize=width:400,height:200/'+response.filesUploaded[0].handle);
        //document.getElementById(name).value = "tinkumaster";

        $('#'+elementname).val(response.filesUploaded[0].url);
        $('#imagefile'+elementname).val(response.filesUploaded[0].filename);
        $('#imagehandle'+elementname).val(response.filesUploaded[0].handle);

        //do we want to hide the chooser?
       /// $('#choose'+elementname).text('');

      // declare this function to handle response
      console.log(response);
    });
  }



$( document ).ready(function() 
{







    $('.wysiwyg').summernote({
        placeholder: '',
        tabsize: 2,
        height: 500,
         toolbar: [
    // [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['font', ['strikethrough', 'superscript', 'subscript']],
    ['fontsize', ['fontsize']],
    ['fontname', ['fontname']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['height', ['height']]
  ]
      });


    //this function deals with the type drop down.  It has to disbale the lookup drop down if it is not required.
    //note (chris) we blank the select when we disbale it, it may prove poor ux and if this is the case will then change
    $(".htmltyleclass").change(function ()
    {
        //get the value
        var selectedvalue = $(this).val();
        //get the dataif
        var dataid = $(this).attr('data-id');
        //check if they have selected lookup
        if (selectedvalue != 'lookup')
        {
            //disable the look up drop down
             $('#lookup'+dataid).prop('disabled', 'disabled');
             //reset the look up dropdown (see note on UX above)
             $('#lookup'+dataid+' option:first').attr('selected','selected');

        }
        else
        {
            //renable the look up
            $('#lookup'+dataid).prop('disabled', false);
        }
        
    });

    //this function checks and uncheks the required box
    //note (chris) you can set the actual checked box to selected by pasing an attr of checked. But is barely used I prefer to just set the value to 1 or 0 and check for this on the input
    $(".requiredcheck").change(function () 
    { 
        //check if it is checked
        if($(this).is(':checked'))
        {
            //set it to checked
            $(this).val(1);
        }
        else
        {
            //set it to uncehced
             $(this).val(0);
        }
    });

    //this function calls the ajax function that saves the meta data
    $(".savetablemeta").click(function(e) 
    {   
        //get the table
        var table = $('#table').val();
        //get the field
        var field = $(this).attr('data-id');
        //get the html type selected
        var htmltype = $('#htmltype'+field).val();
        //get if it is required
        var required = $('#required'+field).val();
        //get the lookup
        var lookup = $('#lookup'+field).val();
        //note (chris) this does not work so the code is in line 262 for now
        //var data = "{table:"+table+",lookup:"+lookup+",field:"+field+",type:"+htmltype+",required:"+required+"}";

        //build the url
        var url = rootUrl+'/ajax/updatetablemeta';
        postAjax(url, {table:table,field:field,type:htmltype,required:required,lookup:lookup},'updatetablemetasuccess()');

    });
    
    //start with menu open
    $("#wrapper").toggleClass("toggled");

    //toggle class open and close the menu
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });


   
    //initalise the datatable
    //note (chris) if there is no table on this page this may throw an error on some browsers if this is the case then we will have to 
    //             check if the elements exists before initalising it.
	$('#admintable').DataTable();

    //date and time picker
    $('.datepicker').datepicker({});

    

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