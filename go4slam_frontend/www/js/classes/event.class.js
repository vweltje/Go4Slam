// Get Content Class | Go4Slam App
// Design and development by
// Vincent Weltje en Jules Peeters

var events = {
    items: '',
    init: function() {
        console.log('test');
        ajaxClass.url = 'http://go4slam.vweltje.nl/api/get_calendar';
        ajaxClass.data = {
            'App-Request-Token': '4293f73ef982a68f69f3a402e49d0ab52611b084',
            'App-Request-Timestamp': Date.now(),
            'start_date': '16-12-2000 15:55:56',
            'end_date': '16-12-2050 15:55:56'
        };

        this.items = ajaxClass.init();
        console.log(this.items);

        this.printEvents();
    },
    printEvents: function() {
        $.each(this.items, function(key, value){
            $('.events').append('<div class="eventItem"><div class="left"><div class="day">18</div><div class="month">Januari</div></div><div class="right"><div class="itemTitle">'+value.title+'</div><div class="itemDescription">'+value.short_description+'<div><div class="more"><b>Van:</b> 18:00  <b>tot</b> 21:00</div></div></div>')
            console.log(value.title);
        });
    }
}
