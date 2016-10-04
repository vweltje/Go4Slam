// Loading Class | Go4Slam App
// Design and development by
// Vincent Weltje en Jules Peeters

var loaderClass = {
    currentPage: '',
    loadHeader: function() {
        if (!window.jQuery) {
            alert('Kan geen verbinding maken met internet');
        }
        else {
            $(defaultContainer).load(headerPage, function() {
                setTimeout(function() {
                    $(loader).fadeOut();
                }, sponsorTimeout);
            });
        }
    },
    loadPageInHeader: function(url) {
        if (url == 'default') {
            url = menuItems[defaultPage];
        }
        $(pageWrapper).load(url);
    },
    loadBlogPost: function(key) {
        $(pageWrapper).load('../pages/blogpost.html', function() {
            $(".header").attr('background', 'url(http://go4slam.vweltje.nl/data/scores/'+timeline.items[key].image+')');
            $('.title').append(timeline.items[key].title);
            $('.inner').append(timeline.items[key].description);
            $('.date').append(timeline.items[key].created_at);
        });
    },
    loadScorePost: function(key) {
        $(pageWrapper).load('../pages/score.html', function() {
            $(".header").attr('style', 'background:url(http://go4slam.vweltje.nl/data/scores/'+timeline.items[key].image+')');

            $('.title').append(timeline.items[key].title);

            $('.playerOne .points').append(timeline.items[key].player_score);
            $('.playerOne .name').append(timeline.items[key].player_name);

            $('.playerTwo .points').append(timeline.items[key].player_2_score);
            $('.playerTwo .name').append(timeline.items[key].player_2_name);

            $('.inner').append(timeline.items[key].description);
            $('.date').append(timeline.items[key].created_at);
        });
    },
    loadGalleryPost: function(key) {
        $(pageWrapper).load('../pages/gallery.html', function() {
            $(".header").attr('style', 'background: url(http://go4slam.vweltje.nl/data/galleries/'+timeline.items[key].items[0].src+')');
            $('.title').append(timeline.items[key].title);
            $('.inner').append(timeline.items[key].description);
            $('.date').append(timeline.items[key].created_at);

            console.log('');
            $('.inner').append('<div class="clearfix"></div>');
            $.each(timeline.items[key].items, function(key, value) {
                $('.inner').append('<a href="http://go4slam.vweltje.nl/data/galleries/'+this.src+'" class="swipebox"><img src="http://go4slam.vweltje.nl/data/galleries/'+this.src+'" ></a>');
            });

        });
    },
    loadPlayer: function(id) {
        $(pageWrapper).load('../pages/spelerB.html', function() {
            var item = player.getUser(id);
            $(".header").attr('style', 'background: url(http://go4slam.vweltje.nl/data/app_users/cover_images/'+item.cover_image+')');
            $('.title').append(item.first_name + ' ' + item.last_name);
            console.log(item);
        });
    }
}
