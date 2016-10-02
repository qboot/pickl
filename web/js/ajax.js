$(window).ready(function(){
    $('#view').css('background','rgb(122,215,141)');
    $('#view').children('a').css('color','white');

    $('#view, #news, #comments, #donators').click(function(e) {

        e.preventDefault();

        // ou data = "#blocView" par exemple
        var data = $(this).attr('data');

        if($(data).css('display') == 'block') {
            return;
        }

        // sinon
        $('#blockView, #blockNews, #blockComments, #blockDonators').css('display','none');
        $('#view, #news, #comments, #donators').css({
            background:'white'
        });
        $('#view, #news, #comments, #donators').children('a').css({
            color:'rgb(122,215,141)'
        });

        $(data).css('display','block');
        $(this).css({
            background:'rgb(122,215,141)'
        });
        $(this).children('a').css('color','white');
    });
});