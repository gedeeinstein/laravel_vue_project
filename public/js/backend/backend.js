$("document").ready(function(){
    var url = document.location.href.replace("http://","").replace("https://","").replace("www.","").split('?')[0] ;
    var geturl='';

    if(!url.includes('create') && document.querySelector(" ol > li:nth-child(2) > a")!=null){
        geturl = document.querySelector(" ol > li:nth-child(2) > a").href;
    }
    $("#nav-left a").each(function(){
        var href = $(this).attr("href").replace("http://","").replace("https://","").replace("www.","").split('?')[0] ;
        if(href == url || ($(this).attr("href") === geturl ) ) {
            $(this).addClass('active')
            var treeview = $(this).closest(".nav-treeview");
            treeview.show();
            treeview.closest(".has-treeview").addClass('menu-open active');
            $('.menu-open.active > a').addClass('active');
        }
    })
    $('[data-toggle="tooltip"]').tooltip();
});
