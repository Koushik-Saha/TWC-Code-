$(document).ready(function () {
    $(function(){
        let routePath = window.location.pathname;
        let routeUrl = window.location.href;
        let rstr = routeUrl.substr(routeUrl.length - 1);
        if(rstr === '/') {
            routeUrl = routeUrl.slice(0, -1);
        }

        $('#bsoftSideNav a').each(function(){
            let navUrl = $(this).attr('href');

            if($(this).parent().hasClass('pcoded-hasmenu')) {
                $(this).parent().attr('dropdown-icon', 'style3');
                $(this).parent().attr('subitem-icon', 'style3');
            }

            if(routePath === navUrl || routeUrl === navUrl){
                $(this).parent().addClass('active');

                if($(this).parent().parent().hasClass('pcoded-submenu')) {
                    $(this).parent().parent().css('display', 'block');
                    let mainMenu = $(this).parent().parent().parent();
                    mainMenu.addClass('pcoded-trigger');
                }
            }
        });
    });
});
