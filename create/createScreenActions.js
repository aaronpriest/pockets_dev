/****************
setupScreenActions.js
Purpose: jQuery to validate and send information to
create a new "pocket" to set_pockets.php or update a
pocket to update_pockets.php
****************/

$(document).ready(function(){
  var buttonSelect="";
  var pageSelect="";
  var href_loc="../pockets.html";

    if($("#purpose").val()=="create"){
      buttonSelect="#sendButton";
      pageSelect="create_pockets.php";
    }
    else if($("#purpose").val()=="update") {
      buttonSelect="#updateButton";
      pageSelect="../update_pockets.php";
      href_loc="../../pockets.html";
    }
/* Button Click: Create or Update Button*/
   $(buttonSelect).click(function(event){
     event.preventDefault();
     /*Throw up a little "wait shield"*/
     $('.waitShield').show();
     $('.message').show();

    /*collect all the data*/
     var pocket_id = $('#pocket-id').val();//used to update not create
     var pocketName = $('input[name="pocketname"]').val();
     var amount = $('input[name="amount"]').val();
     var expenseType = $('#type').val();
     var  dayDue = $('input[name="daydue"]').val();
     var savedAmount = $('input[name="savedamount"]').val();
     var accrue_tf = $('input[name=accruetf]:checked').val();
     var capacity = $('input[name="capacity"]').val();
     var account = $('#account').val();


     var pocketData = {
       id:  pocket_id,
       name: pocketName,
       amount: amount,
       type: expenseType,
       daydue: dayDue,
       savedamount: savedAmount,
       capacity: capacity,
       account: account,
       accruetf:accrue_tf
     };//end of pocketData def

     /*** Send the data to the PHP file*/


     $.post(pageSelect, pocketData, function(data, status){
        if(status=="success"){
        /*Get rid of the "wait shield" if this is successful*/
        $('.waitShield').hide();
        $('.message').hide();
        /*Fading message to the user*/
        $('.message').text("Pocket created!");
        $('.message').show();
        $('.message').fadeOut(1000);
        window.location.href=href_loc;

      }//end if
    });



  });//end of .click()

/*Validate items entered on the screen. We don't want nameless pockets or NULLs, but
zero values are fine*/
$("input[name='pocketname']").blur(
  function(){

    $("#pocketname-err").empty(); //clear out previous messages
    if($(this).val()==""){
      $("#pocketname-err").append("  Please enter a name.");
      $(this).val("");
      $(buttonSelect).attr("disabled", "disabled");
    }
    else $(buttonSelect).removeAttr("disabled");
  });

    $("input[name='amount']").blur(
    function(){
        numberValidation("amount");
    });

    $("input[name='daydue']").blur(
    function(){
        numberValidation("daydue");
    });

    $("input[name='savedamount']").blur(
      function(){
          numberValidation("savedamount");
      });

    $("input[name='capacity']").blur(
      function(){
          numberValidation("capacity");
      });

});//end of .ready()

function numberValidation(name){
  var selector = "input[name='"+name+"']";
  var errorContainer = "#"+name+"-err";
  $(errorContainer).empty(); //clear out previous messages
  if(!$.isNumeric($(selector).val())||$(selector).val()==""){
    $(errorContainer).append(" A valid number wasn't entered, so we set it to \"0\" for now.");
    $(selector).val(0);
  }
  }
