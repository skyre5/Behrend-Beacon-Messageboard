function ajaxPost(id, postTarget, callback) {
    var form = $("#" + id);
    
    form.submit(function(event) {
        event.preventDefault();
        
        var message = {};
        var formArray = form.serializeArray();
        for (i in formArray) {
            message[formArray[i].name] = formArray[i].value;
        }
    
        $.post(postTarget, message, callback);
    });
}