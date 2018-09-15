$("button.btn-rm").click(function(event){
  var button_id = $(this).attr("mid");
  var card_id   = button_id.replace("_", "");
  var mid       = button_id.split("_")[0];
 
  $.post('php/removeMusic.php', { IdMusic: mid } )
    .done(function(){
      $("#"+card_id).fadeOut(800, function(){ $("#"+card_id).remove()});
    })
    .fail(function(data){
      alert("Failed to remove: " + data)
    });
});

$("button.btn-edit").click( function(event){

  var button_id = $(this).attr("mid");
  var card_id   = button_id.replace("_", "");
  var mid       = button_id.split("_")[0];
  var card      = $("#"+card_id);
  var ori_link  = card.find("a").attr("href");

  var dscpt     = card.find("div.description");
  var dscpt_val = dscpt.find("p").text();
  
  var tags      = card.find("h5.tags");
  var tag_html  = `
    <div class='tmpTagInput'>
      <div class='input-group mb-1'>
        <input type='text' class='form-control' id='tagInput'> 
        <div class='input-group-append'>
          <span class='btn input-group-text btn-outline-secondary add-tag'>+</span>
        </div>
      </div>
      <h5 id='tags'>`+tags.html()+"</h5></div>";

  card.find("a").removeAttr("href");
  dscpt.html("<textarea class='form-control' id='modify_"+card_id+"'>"+dscpt_val+"</textarea>");
  tags.after(tag_html);
  tags.hide();
  $("#modify_"+card_id).focus();

  $(this).after(`
    <button type='button' class='btn btn-outline-success mb-2 mx-1' id="btnConfirm">
      Confirm
    </button>`);
  $(this).hide();

  var new_tag = [];
  $(document).on('click', '.add-tag', function(){
    var tag = $("#tagInput").val();
    tag = tag.replace(/\W/g, '');
    new_tag.push(tag);
    tags.append( "<span class='btn badge badge-info mr-1 remove-me'>"+tag+"</span>");
    $('#tags').append("<span class='btn badge badge-info mr-1 remove-me'>"+tag+"</span>");
    $('#tagInput').val('');
  });

  // After modify
  $("#btnConfirm").click(function(){
    if($("#modify_"+card_id).val() != dscpt_val){
      dscpt_val = $("#modify_"+card_id).val();
    }

    
    $.ajax({ url:      "../php/EditUpload.php", 
             method:   "POST",
             dataType: "json",
             data:     { IdMusic: mid,
                         NewTag : new_tag,
                         Dscpt  : dscpt_val }})
      .done(function(result){
        if(!result.success){
          alert("Error: "+result.msg);
        }
      })
      .fail(function(jxx, textStatus, errMsg){
        alert("Fail status: "+textStatus+" Fail message: "+errMsg);
      });

            

    card.find("a").attr("href", ori_link);
    dscpt.html("<p class='card-text'>"+dscpt_val+"</p>");
    $(".tmpTagInput").remove();
    new_tag.length = 0;
    tags.show();
    
    card.find("button.btn-edit").show();
    $(this).remove();
  });
});



