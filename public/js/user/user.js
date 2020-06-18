$(document).on('scroll', function(){
    if($(document).scrollTop() > 120){
        $('#navbackground').css({'visibility': 'hidden'});
    } else {
        $('#navbackground').css({'visibility': 'visible'});
    }
});