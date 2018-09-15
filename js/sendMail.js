

$(".sending-mail form").submit(function(event){
  var _this = $(this);
  event.preventDefault();
  $.ajax({
    url     : "php/insertMailContent.php",
    data    : _this.serialize(),
    dataType: "json",
    method  : "POST"
  })
    .done(function(result){
      var content = _this.find("#mailContent").val();
      var card    = "<div class='from-me card bg-primary text-white container'>\
                       <div class='card-body row justify-content-end'>\
                         <p class='card-text'>"+content+"</p>\
                       </div>\
                     </div>\
                     <div class='clear'><br /></div>";
      
      _this.before(card).fadeIn(1200);
      _this.find("#mailContent").val("");
    })
    .fail(function(jxx, textStatus, errMsg){
      alert("Fail textStatus: "+textStatus+"  Error msg: "+errMsg);
    });
});
