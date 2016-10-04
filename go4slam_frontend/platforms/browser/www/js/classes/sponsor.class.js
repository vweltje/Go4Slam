// Get Content Class | Go4Slam App
// Design and development by
// Vincent Weltje en Jules Peeters

var sponsor = {
    items: '',
    init: function() {
        ajaxClass.url = 'http://go4slam.vweltje.nl/api/get_sponsors';
        ajaxClass.data = {
            'App-Request-Token': '4293f73ef982a68f69f3a402e49d0ab52611b084',
            'App-Request-Timestamp': Date.now()
        };

        this.items = ajaxClass.init();
        this.items = this.items.sponsors;
        console.log(this.items);

        this.printSponsors();
    },
    printSponsors: function() {
        $.each(this.items, function(key, value) {
            $('.sponsoren').append('<div class="sponsorLogo" style="background: url(http://go4slam.vweltje.nl/data/sponsors/'+value.image+')"></div>')
        });
    }
}
