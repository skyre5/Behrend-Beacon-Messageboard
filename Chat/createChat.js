
function createChatBox(){
    
    $('body').append('<div id="chat-window" class="chat-window">');
    $('#chat-window').append('<div id="chat-title"><p>Title title title title title title title title title title title title title title title </p></div>');
    $('#chat-window').append('<div id="chat-display"></div>')   
    $('#chat-display').after('<form id="form" method="post"></form>')
	$('#form').append('<textarea id="message" rows="3" cols="20" maxlength="140"></textarea><br>')
}

    $(function(){
    $("#message").keypress(function (e) {
        if(e.which == 13) {
            e.preventDefault();
	    	var message = $("#message").val();

			if(message == ''){
				return false;
			}
		    $.post("chatSend.php", { message: message}, function(data) {
				$('#chat-display').append(data);
				scroll()
				$('#form')[0].reset();
		    });
        }
    });
    $('#chat-title').dblclick(function() {
    	if($(this).parent().attr('class') == 'chat-window'){
    		$('.chat-window').animate({top: '96%'}, 1200)
    		$('.chat-window').attr('class', 'chat-window-hidden');
    	}
    	else {
    		$('.chat-window-hidden').animate({top: '50%'}, 1200);
    		$('.chat-window-hidden').attr('class', 'chat-window');
    	}
    })
    function scroll(){
		document.getElementById('chat-display').scrollTop = document.getElementById('chat-display').scrollHeight;
    }
    });