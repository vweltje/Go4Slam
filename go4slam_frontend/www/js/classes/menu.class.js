// Menu Class | Go4Slam App
// Design and development by
// Vincent Weltje en Jules Peeters

$(document).on("click", "#menuBtn",function() {
    menuClass.openMenu();
});

$(document).on("click", "#app",function() {
    if (menuClass.activeState == true) {
        menuClass.closeMenu();
    }
});

$(document).on("click", "li",function() {
    loaderClass.loadPageInHeader($(this).attr("data-page-path"));
    menuClass.closeMenu();
});

var menuClass = {
    activeState: false,
    openMenu: function() {
        if (this.activeState == false) {
            $(pageWrapper).height('100%');
            $(pageWrapper).transition({ scale: 0.75 });
            $(pageWrapper).transition({
              perspective: '700px',
              rotateY: '-7deg',
            });
            $(pageWrapper).transition({
                x: 250,
                complete: function(){
                    menuClass.activeState = true;
                }
            });
        }
    },
    closeMenu: function() {
        if (this.activeState == true) {
            $(pageWrapper).transition({x: 0});
            $(pageWrapper).transition({
              rotateY: '0deg'
            });

            $(pageWrapper).transition({
                scale: 1,
                complete: function() {
                    menuClass.activeState = false;
                }
            });
        }
    },
    loadMenuItems: function(location) {
        for(var i in menuItems) {
            $(location).append('<li data-page-path=' + menuItems[i] + '>' + i + '</li>');
        }
    }
}
