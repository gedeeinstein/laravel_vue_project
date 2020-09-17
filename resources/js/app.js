/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// --------------------------------------------------------------------------
require('./bootstrap');
// --------------------------------------------------------------------------
window.Vue = require('vue');
window.numeral = require('numeral');
// --------------------------------------------------------------------------
require('jquery.scrollto');
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Init mathjs instance to global
// --------------------------------------------------------------------------
const { create, all } = require('mathjs')
window.mathjs = create;
window.mathAll = all;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Scroll-to-top shortcode
// --------------------------------------------------------------------------
window.scrollTop = function(){
    window.$('body').scrollTo( 0, 500 );
}
// --------------------------------------------------------------------------