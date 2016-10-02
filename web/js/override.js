$('.buttonSearch').click(function(){
    if($('#inputSearch').val().length > 0)
        $('.formSearch').submit();
});

setTimeout(function(){
    $('.messageflash').animate(
        {
            opacity: 0
        },
        1500
    );

    setTimeout(function(){
        $('.messageflash').css('display','none');
    },1500);
},10000);

$('.closemessageflash').click(function(){
    var el = $(this).parent();

    $(el).animate(
        {
            opacity: 0
        },
        1500
    );

    setTimeout(function(){
        $(el).css('display','none');
    },1500);
});