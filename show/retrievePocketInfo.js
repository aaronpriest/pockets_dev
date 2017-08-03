$(document).ready(function(){
  $.get("defs.json", function(data){getPocketById(data[0].id)});//function


  $("#showUpdateButton").click(function(){

    $('input').removeAttr("disabled");
    $('select').removeAttr("disabled");

    $("#updateButton").show();
    $("#deleteButton").show();
    $("#showUpdateButton").hide();
  });
  /* Button Click: Delete*/

     $('#deleteButton').click(function(){
       $('.waitShield').show();
       $('.warning').show();
     });

     $('#no-button').click(function(){
       $('.waitShield').hide();
       $('.warning').hide();
     });

     $('#yes-button').click(function(){
      var thisId=$('#pocket-id').val();
      var pocketData={id:thisId};
       $.post("../delete_pocket.php", pocketData, function(data, status){
        if(status=="success"){
        /*Get rid of the "wait shield" if this is successful*/
        $('.waitShield').hide();
        $('.warning').hide();
        /*Fading message to the user*/
        $('.message').text("Pocket deleted.");
        $('.message').show();
        $('.message').fadeOut(1000, function(){window.location.href="../../pockets.html";});


      }//end if
    });//end: $.post

     });//end yes-button click

});
function getPocketById(id){
  $.get("../../get_pocket_details.php",{pocket_id:id},
  function(data){

    /*If this is the special Spare Change pocket, it is not editable*/
    if(id==1){
      $("#showUpdateButton").hide();
    }

    $('#pocket-id').val(id);
    $("input[name='pocketname']").val(data[0].name);
    $("input[name='amount']").val(data[0].amount);
    $("#type").val(data[0].type);
    $("input[name='daydue']").val(data[0].daydue);

    if(data[0].accruetf==1){

      $("#accruetf-y").attr("checked", "checked");
    }
    else {

      $("#accruetf-n").attr("checked", "checked");
    }

    $("input[name='savedamount']").val(data[0].savedamount);
    if(data[0].capacity==null) $("input[name='capacity']").val("No Limit.");
    else $("input[name='capacity']").val(data[0].capacity);

    $("#account").val(data[0].account);

  }, "json");
}
