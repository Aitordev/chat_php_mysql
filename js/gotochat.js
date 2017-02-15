$(document).ready(function() {
  $('#buttongotochat').click(function(){
    newSessionChat();
  });
});

function newSessionChat(){
  var d = new Date();
  var id = d.valueOf();
	if(id != ""){
		var url= "chat.php?session=" + encodeURIComponent( id );
    //var ref = "" + window.location.href;
    //$.post( "contact.php", { type: "adminAdvise", link: ref.slice(0,ref.lastIndexOf("/")+1)+ url } );
    window.open(url,'Chat','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800px,height=900px');
	}
}
