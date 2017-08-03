$(document).ready(function(){
  $.get("load_pockets.php",
  function(data){
    buildPocketDisplay(data);
  }, "json");

  function buildPocketDisplay(pocket_data_json){
      for(i=0; i<pocket_data_json.length; i++){
        if(pocket_data_json[i].savedamount.indexOf(".")>0) var savedAmount=pocket_data_json[i].savedamount;
        else if (pocket_data_json[i].savedamount.indexOf(".")<0) var savedAmount=pocket_data_json[i].savedamount+".00";
        pocket_div="<div class='pockets' onclick='seeDetails("+pocket_data_json[i].pocket_id+")' id='"+pocket_data_json[i].pocket_id+"'>"+pocket_data_json[i].name+"<p class='savedamount'>$"+savedAmount+"</p></div>";
        $('section').append(pocket_div);

      } //end for
      $('section').append("<div class='pockets' onclick='location.href=\"create/create-pockets.html\"'' id='addAPocket'><h2>+</h2></div>")
  }//end buildPocketDisplay


}); //end of .ready()

function seeDetails(id){
  $.get("get_pocket_details.php",{pocket_id:id},
  function(data){
    /*There is only one item in the array since get_pocket_details.php displays only one.
     However, PHP's json_encode always sends the JSON as an array.*/
    var pocket_dir = data[0].pocket_link;
    window.location.href="./show/"+pocket_dir;
  }, "json");
 }
/* */
