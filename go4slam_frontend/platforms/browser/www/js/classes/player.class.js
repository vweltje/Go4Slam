// Get Content Class | Go4Slam App
// Design and development by
// Vincent Weltje en Jules Peeters

var player = {
    getUser: function(id) {
        console.log('test');
        ajaxClass.url = 'http://go4slam.vweltje.nl/api/get_user_details';
        ajaxClass.data = {
            'App-Request-Token': '4293f73ef982a68f69f3a402e49d0ab52611b084',
            'App-Request-Timestamp': Date.now(),
            'user_id': id
        };

        return ajaxClass.init();
    }
}
