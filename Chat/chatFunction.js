$(function(){
    $("#message").keypress(function (e) {
        if(e.which == 13) {
            e.preventDefault();
        	var message = $("#message").val();
    
    		if(message == ''){
    			return false;
    		}
    	    $.post("Chat/chatSend.php", { message: message}, function(data) {
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