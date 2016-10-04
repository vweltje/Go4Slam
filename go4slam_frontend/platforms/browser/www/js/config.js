// Go4Slam App | Config.js
// Design and development by
// Vincent Weltje en Jules Peeters

// Default page
var defaultPage  = 'Tijdlijn';

// Container where pages gets loaded
var defaultContainer = '#wrapper';

// pageWrapper
var pageWrapper = "#app";

// loader
var loader = "#loader";

// Time to show sponsor logo's
var sponsorTimeout = 10000;

// Header page (template)
var headerPage = '../header.html';

// Menu items
// Key   = pagename
// Value = pagepath
var menuItems = {
    Tijdlijn    : './pages/tijdlijn.html',
    Agenda      : './pages/agenda.html',
    Spelers     : './pages/spelers.html',
    Sponsoren   : './pages/sponsoren.html'
};
