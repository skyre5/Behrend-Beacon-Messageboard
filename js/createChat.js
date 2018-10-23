$(function(){
	    $('body').append('<div id="chat-window" class="chat-window"></div>');
	    $('#chat-window').append('<div id="chat-title"><p>&nbspBehrend Beacon - Talk about it </p></div>');
	    $('#chat-window').append('<div id="chat-display"></div>')   
	    $('#chat-display').after('<form id="form" method="post"></form>')
	    $('#form').append('<textarea id="message" rows="3" cols="20""></textarea><br>')
	
});