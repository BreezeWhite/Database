
var played = false;
$("#audioPlayer").on('play', function(){
  var path = decodeURI($.urlParam('path'));
  if(!played){
    played = true;
    $.post("php/increaseMusicViewedNum.php", { music_path: path })
      .done(function(data){
        //alert(data); // To debug, uncomment this line
      })
      .fail(function(jqXHR, textStatus, errorMsg){
        alert("There is something wrong updating the viewed num: "+errorMsg);
      });
  }
});

$("#commitCmt").click(function(){
  var cmt = $("#cmtMSG").val();
  var music = decodeURI($.urlParam('path'));
  $.post("php/insertMusicComment.php", { comment: cmt, music_path: music })
    .done(function(last_id){
      $.newComment(cmt, last_id);
      $("#cmtMSG").val('');
    })
    .fail(function(jqXHR, textStatus ){
      alert('fail' + textStatus);
    });
});
$.newComment = function(text, id){
  $.get("../php/getCurrentLoginName.php")
    .done(function(name) { 
      $("#playerPane").after("<div class='card mb-1' id='comment_"+ id +"'>\
                                <div class='card-header'>"+ name +"</div>\
                                <div class='card-body'>\
                                  <p class='card-text'>" + text + "</p>\
                                </div>\
                              </div>");
      $("#comment_"+id).hide().fadeIn(1200);
    })
    .fail(function(jxx, textStatus, msg) {
      alert("Fail textStatus: "+textStaus+"  Message: "+msg);
      return 0;
    });
};




