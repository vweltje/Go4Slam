// Get Content Class | Go4Slam App
// Design and development by
// Vincent Weltje en Jules Peeters

var timeline = {
    items: '',
    init: function() {
        ajaxClass.url = 'http://go4slam.vweltje.nl/api/get_timeline';
        ajaxClass.data = {
            'App-Request-Token': '4293f73ef982a68f69f3a402e49d0ab52611b084',
            'App-Request-Timestamp': Date.now(),
            'load_count': 1
        };
		// console.log(ajaxClass.data);
        this.items = ajaxClass.init();
        this.checkTypes();
		console.log(this.items);
    },
    checkTypes: function() {
        $.each(this.items, function(key, value) {
            switch(value.type) {
                case 'score':
                    timeline.printScore(value, key);
                    break;
                case 'newsletter':
                    timeline.printNewsletter(value, key);
                    break;
                case 'blog_post':
                    timeline.printBlogpost(value, key);
                    break;
                case 'gallery':
                    timeline.printGallery(value, key);
                    break;
            }
        });
    },
    printScore: function(item, key) {
        $('.content').append('<div class="timelineItem"><div class="score"><div class="conImgLeft" style="background-image:url(http://go4slam.vweltje.nl/data/scores/'+item.image+')"></div><div class="right"><div class="playerOne"><div class="points">'+item.player_score+'</div><div class="name">'+item.player_name+'</div></div><div class="playerTwo"><div class="points">'+item.player_2_score+'</div><div class="name">'+item.player_2_name+'</div></div><div class="more"><a onclick="loaderClass.loadScorePost('+key+');">Meer lezen</a></div></div><div class="clearfix"></div></div></div>');
    },
    printBlogpost: function(item, key) {
        $('.content').append('<div class="timelineItem"><div class="blog"><div class="conImgLeft" style="background-image:url(http://go4slam.vweltje.nl/data/scores/M25zaCGvHKFYhUys.jpg)"></div><div class="right"><div class="itemTitle">'+item.title+'</div><div class="itemDescription">'+item.short_description+'</div><div class="more"><a onclick="loaderClass.loadBlogPost('+key+');">Meer lezen</a></div></div><div class="clearfix"></div></div></div>');
    },
    printNewsletter: function(item) {
        $('.content').append('<div class="timelineItem"><div class="colorbar"></div><div class="nieuwsbrief"><div class="left"><div class="nieuwsbriefnr">'+item.number+'</div><div class="nieuwsbriefondertitel">nummer</div></div><div class="right"><div class="itemTitle">'+item.title+'</div><div class="itemDescription">'+item.short_description+'</div><div class="more"><a download href="http://go4slam.vweltje.nl/data/newsletters/'+item.pdf+'">Download</a></div></div><div class="clearfix"></div></div></div>');
    },
    printGallery: function(item, key) {
        $('.content').append('<div class="timelineItem"><div class="gallery"><div class="conImgLeft" style="background-image:url(http://go4slam.vweltje.nl/data/galleries/'+item.items[0].src+')"></div><div class="right"><div class="itemTitle">'+item.title+'</div><div class="itemDescription">'+item.description+'</div><div class="more"><a onclick="loaderClass.loadGalleryPost('+key+');">Bekijk gallerij</a></div></div><div class="clearfix"></div></div></div>');
    }
}
