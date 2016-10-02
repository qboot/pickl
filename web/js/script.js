$(window).ready(function(){

    // Appel des fonctions + if en série



    if ($('.top').length !== 0)
    {
        $(window).scroll(navScroll);
    }
    else if ($('.top').length == 0)
    {
        $('nav').css({background:'rgb(255,255,255)'});
        $('nav a').css({color:'rgb(21,22,23)'});
        $('nav li').css({color:'rgb(21,22,23)'});
        $('#search').css({'border-left':'1px solid rgb(141,142,143)'});
        $('#search input').css({'color':'rgb(71,71,71)'});
        $('#search img').attr("src",window.search_nb);
        $('#reward img').attr("src",window.rewards_nb);
        $('#statistics img').attr("src",window.statistics_nb);
        $('#navtwo').css({'height':$(window).height()-50});
        $(window).scroll(navTwo);
    }
    if ($('#contenttitle').length !== 0)
    {
        $(window).scroll(titleScroll);
    }



    if($('#signupform').length !== 0)
    {
        $('body').css({'background':'rgb(237,237,237)'});
    }

    if($('.top').length == 0 && $('#categories').length !== 0)
    {
        $('#categories').css({'padding-top':50});
    }


    // Variables

    var hauteur = $(window).height();


    // NAV qui change avec le scroll
    function navScroll(){

        var scrolltop = $(this).scrollTop();

        if(scrolltop<60)
        {
            $('nav').css({background:'rgba(21,22,23,.5)'});
            $('nav a').css({color:'rgb(255,255,255)'});
            $('nav li').css({color:'rgb(255,255,255)'});
            $('#search').css({'border-left':'1px solid rgb(255,255,255)'});
            $('#search input').css({'color':'rgb(237,237,237)'});
            $('#search img').attr("src",window.search);
            $('#reward img').attr("src",window.rewards);
            $('#statistics img').attr("src",window.statistics);
        }
        else if(scrolltop>hauteur)
        {
            $('nav').css({background:'rgb(255,255,255)'});
            $('nav a').css({color:'rgb(21,22,23)'});
            $('nav li').css({color:'rgb(21,22,23)'});
            $('#search').css({'border-left':'1px solid rgb(141,142,143)'});
            $('#search input').css({'color':'rgb(71,71,71)'});
            $('#search img').attr("src",window.search_nb);
            $('#reward img').attr("src",window.rewards_nb);
            $('#statistics img').attr("src",window.statistics_nb);
        }
    }

    //Barre de contribution qui se colle en haut dans les projets

    function titleScroll(){

        var scrolltop = $(this).scrollTop();

        if(scrolltop>hauteur-130)
        {
            $('#contenttitle').css({'position':'fixed','top':130,'height':40});
            $('#contenttitle h1').css({'font-size':'16px','line-height':'40px'});
        }

        if(scrolltop<hauteur-130)
        {
            $('#contenttitle').css({'position':'inherit','height':80});
            $('#contenttitle h1').css({'font-size':'32px','line-height':'80px'});
        }

    }

    //Navtwo qui se décolle quand on passe la div sponsor au scroll

    function navTwo(){

        var scroll = $('body').scrollTop();
        var win_height = $( window ).height();
        var win= win_height+scroll;
        var elem=$('#sponsorscontent').offset().top;

        if (win>elem) {
            /**MODIFICATION ATTENTION**/
            if(scroll < 100) {
                $('#navtwo').css({'position':'fixed','top':50});
            } else {
                $('#navtwo').css({'position':'absolute','top':elem-hauteur+50});
            }
        }

        else if (win<elem) {
            $('#navtwo').css({'position': 'fixed', 'top': 50});
        }
    }


    // Menu déroulant sur Log In
    $('#rightnav a').click(function(e){

        if ($(this).attr('href') == 'login' && $('#login').css('display') == 'none')
        {
            e.preventDefault();
            $('#login').css('display','block');
        }
        else if($(this).attr('href') == 'login')
        {
            e.preventDefault();
            $('#login').css('display','none');
        }
    })
    $('#content, footer, #sponsorscontent, #create, #navtwo, .top').click(function(){
        if ($('#login').length !== 0)
        {
            $('#login').css('display','none');
        }
        $('#mobileaccountliens').css('display','none');
        $('#mobilenavliens').css('display','none');
    });


    // ----------------------------SCRIPTS POUR LA VERSION MOBILE----------------------------------------

    if($(window).width() < 1000) {

        //Barre de recherche qui se cache en mobile + se montre après

        if ($('#inputSearch').val().length > 0) {
            $('#search form input').val('');
        }


        $('.buttonSearch').click(function (e) {
            if ($('#inputSearch').val().length > 0 && $('#search form').css('display') !== 'none') {
                $('.formSearch').submit();
            }
            else if ($('#inputSearch').val().length < 1 && $('#search form').css('display') == 'none') {
                $('#search form').show();
                $('#search form input').val('');
            }

            else if ($('#inputSearch').val().length < 1 && $('#search form').css('display') !== 'none') {
                $('#search form').hide();
                $('#search form input').val('');
            }
        });

        // Menus au clic dans la version mobile

        $('#mobilenav').click(function () {
            $('#mobilenavliens').show();
            $('#mobileaccountliens').hide();
        });

        $('#mobileaccount').click(function () {
            $('#mobilenavliens').hide();
            $('#mobileaccountliens').show();
        });

        $(window).resize(function () {
            if ($(window).innerWidth() > 1000) {
                $('#mobilenavliens').hide();
                $('#mobileaccountliens').hide();
            }
        });

    }

    // Taille #top
    $('.top').css({height:hauteur});
    $('#signupform').css({height:hauteur});


});