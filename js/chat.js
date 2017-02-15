var lastTimeID = 0;
$(document).ready(function() {
  $('#chatInput').keypress(function (e) {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
      $('#btnSend').click();
        return false;
    } else {
        return true;
    }
  });
	$('#btnSend').click(function(){
		sendChatText();
		$('#chatInput').val("");
	});
  $("#submitEmail, #modal-launcher, #modal-background").click(function () {
    if ($("#tempModalWindow").hasClass("active")){
      $("#modal-background").toggleClass("active");
      $("#tempModalWindow").remove();
    }
    else{
		  $("#share,#modal-background").toggleClass("active");
      $("#urlInput").val(location.href);
    }
	});
  $('#searchbox').keypress(function (e) {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
      $(".searches").empty();
      var string = $('#searchbox').val();
      search(encodeURIComponent( string ));
    }
  });
  $("#searchbox").bind("change paste keyup", function() {
  	if ("" ===  $("#searchbox").val()){
      $("#searchResults").hide();
      $(".searches").empty();
    }
  });
   $(document).on('dblclick', '.box', function(){
    var session = $(this).children(".session").html();
    getPastChat(session);
  });
  startChat();
});

function startChat(){
	setInterval(function(){ getChatText(); }, 500);
}

function getChatText(){
  if (!$(".msg").length && !$("#initMessage").length){
    var initMessage= 'Feel free to write down any message until an Admin is connected';
    var html = '<p id="initMessage" class="notification">'+initMessage+'</p>';
    $('#view_ajax').append(html);
  }
	$.ajax({
		type: "GET",
		url: "refresh.php?lastTimeID="+lastTimeID
	}).done(function( data )
	{
		var jsonData = JSON.parse(data);
		var jsonLength = jsonData.results.length;
		var html = "";
		for (var i = 0; i < jsonLength; i++) {
			var result = jsonData.results[i];
			var admin = "";
			if(1 === result.admin){
				admin = '<span class="range admin">Admin</span>';
			}
			html += '<li class="'+result.type+'"> <div class="msg"> <div class="user" style="color:'+ result.color +'">' + result.username + admin + '</div> <p>'+result.chattext+'</p> <time>'+ result.chattime+'</time> </div> </li>';
			lastTimeID = result.id;
		}
		if (!isEmpty(html)){
			$('#view_ajax').append(html);
      $("html, body, #windowchat").animate({ scrollTop: $("#view_ajax").height() }, 500);
		}
	});
}

function sendChatText(){
	var chatInput = $('#chatInput').val();
	if(chatInput != ""){
		$.ajax({
			type: "GET",
			url: "submit.php?chattext=" + encodeURIComponent( chatInput )
		});
	}
}

function isEmpty(str) {
    return (!str || 0 === str.length);
}

function search(str) {
  if (str && 0 !== str.length){
    $("#searchResults").show();
    $.ajax({
  		type: "GET",
  		url: "search.php?info="+encodeURIComponent( str )
  	}).done(function( data )
  	{
  		var jsonData = JSON.parse(data);
  		var jsonLength = jsonData.results.length;
  		var html = "";
      var lastDate = "";
  		for (var i = 0; i < jsonLength; i++) {
  			var result = jsonData.results[i];
        if (lastDate !== result.chatdate){
          lastDate = result.chatdate;
          html += '<p class="date"><time>'+ lastDate+'</time></p>';
        }
  			html += '<li class="box"> <time>'+ result.chattime+'</time> <div class="session">' + result.session+ '</div> <div class="user" style="color:'+ result.color +'">' + result.username+ '</div> <p>'+result.chattext+'</p></li>';
  		}
  		if (!isEmpty(html)){
  			$('#view_searches').append(html);
  		}
    });
  }
}

function getPastChat(session) {
  if (session && 0 !== session.length){
    $.ajax({
  		type: "GET",
  		url: "getpastchat.php?session="+encodeURIComponent( session )
  	}).done(function( data )
  	{
  		var jsonData = JSON.parse(data);
  		var jsonLength = jsonData.results.length;
  		var html = '<div id="tempModalWindow" class="modal-content active"> <ol class="chat">'
        + '<div class="menu"><div class="tittle">Session: '+ session +'</div>'
        + '<div class="members"><b id="membersModal"></b></div></div>';
      var members = [];
  		for (var i = 0; i < jsonLength; i++) {
  			var result = jsonData.results[i];
        var admin = "";
  			if(1 === result.admin){
  				admin = '<span class="range admin">Admin</span>';
  			}
        html += '<li class="other"> <div class="msg"> <div class="user" style="color:'+ result.color +'">' + result.username + admin + '</div> <p>'+result.chattext+'</p> <time>'+ result.chattime+'</time> </div> </li>';
        if ($.inArray(result.username,members) == -1){
            members.push(result.username);
        }
      	//html += '<time>'+ result.chattime+'</time> <div class="user" style="color:'+ result.color +'">' + result.username+ '</div> <p>'+result.chattext+'</p>';
  		}
      html +='</ol></div>'
			$('#windowchat').append(html);
      $('#membersModal').append(members.join());
      $("#modal-background").toggleClass("active");
    });
  }
}
