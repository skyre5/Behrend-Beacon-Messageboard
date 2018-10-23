var since = -1;


$(function() {
    $('#chat-window').bind("DOMSubtreeModified",function(){
        $('#chat-title').css({'background': 'linear-gradient(#FBDC5F, #FBDC5F, white'});
    });

    $('#chat-title').hover(function(){
        $('#chat-title').css({'background': 'white'});
    })
    

    setInterval(function() {
        $.post("new_chat.php", { since: since}, function(data) {

    		var message = JSON.parse(data);
    		for (i in message){
    		    
    		    if(message[i].chatId > since){
    		        since = message[i].chatId;
    		    }
    		    
    			$('#chat-display').append("<h5 id='chat-username' style='display: inline'>" + message[i].userName + ":  </h5><p id='disp-message' style='display: inline'>" + message[i].message + "</p></br>");
    			scroll()
    		}
        });
    }, 1000);
    
    $("#message").keypress(function (e) {
        if(e.which == 13) {
            e.preventDefault();
        	var message = $("#message").val();
            $('#form')[0].reset();
            
    		if(message == ''){
    			return false;
    		}
    	    $.post("send_chat.php", { message: message}, function() {

    	    });
        }
    });
    
    function scroll(){
    	document.getElementById('chat-display').scrollTop = document.getElementById('chat-display').scrollHeight;
    }
    
    $('#chat-title').dblclick(function() {
    	if($(this).parent().attr('class') == 'chat-window'){
    		$('.chat-window').animate({top: '50%', right: '0%'}, 1200)
    		$('.chat-window').attr('class', 'chat-window-hidden');
    		$('#chat-display').css({visibility: 'visible'});
    		$('#form').css({visibility: 'visible'});
    	}
    	else {
    		$('.chat-window-hidden').animate({top: '96%', right: '-22%'}, 1200);
    		$('.chat-window-hidden').attr('class', 'chat-window');
    	}
    })
    
    

});