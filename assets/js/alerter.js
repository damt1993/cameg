function alerter(data){
    const textStatus = data['textStatus'];
    let message = data['message'];
    let result = "";
    if (textStatus === 'success'){
        message = '✔ '+message;
        result = 'alert alert-success';
    } else {
        message = '❌ '+message;
        result = 'alert alert-danger';
    }

    //Creating new alert items
    let div = document.createElement("div");
    $(div).addClass("alerter w-50 p-3");

    let p = document.createElement("p");
    
    //Add creating items to the DOM
    let body = $("body");
    $(div).append(p);
    $(body).append(div);

    $(p).removeClass();
    $(p).addClass(result);
    $(p).text(message);
    $(div).fadeIn({duration: 2500}).fadeOut({duration: 2500, complete: function(){
        $(div).remove();
    }});
}
