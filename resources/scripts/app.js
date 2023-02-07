import {domReady} from '@roots/sage/client';

import 'jquery';
import 'slick-carousel/slick/slick.min.js';
import 'tilt.js/src/tilt.jquery.js';
import 'bootstrap';

import 'share-api-polyfill';

/**
 * app.main
 */
const main = async (err) => {

    let $ = jQuery;

    if (err) {
        // handle hmr errors
        console.error(err);
    }

    /* slick */
    $('.slick-carousel').slick();

    /* popovers */
//    $('[data-bs-toggle="popover"]').popover();

    /* floating icons */
    $('#floating-icons').addClass('floating-icons-collapse').addClass('floating-icons-js').find('h2').click( function () {
      $(this).parents('nav').toggleClass('floating-icons-collapse');
    } );

    /* ACF */
    if(typeof window.acf !== 'undefined') {
      // Date picker & Google Maps compatibility
      $('.acf-google-map input.search, .acf-date-picker input.input').addClass('form-control');
      // Clean errors on submission
      window.acf.addAction('validation_begin', function($form){
        $form.find('.acf-error-message').remove();
      });
      // Add alert alert-danger & move below field
     window.acf.addAction('invalid_field', function(field){
        field.$el.find('.acf-notice.-error').addClass('alert alert-danger').insertAfter(field.$el.find('.acf-input'));
      });
    }

    jQuery( '#navbar-primary' )
      .on( 'show.bs.collapse', function () {
        $( this ).parents( '.banner' ).addClass( 'expanded-banner' );
      } )
      .on( 'hide.bs.collapse', function () {
        $( this ).parents( '.banner' ).removeClass( 'expanded-banner' );
      } );

    var $window = jQuery( window );
    var $body   = jQuery( document.body );
    var $rtt    = jQuery( '#return-to-top' );
    var updatePositionClass = function () {
        var t = $window.scrollTop();
        if ( t > 125 ) {
            $body.addClass('is-scrolled').removeClass('at-top');
        }
        else if ( t < 75 ) {
            $body.removeClass('is-scrolled').addClass('at-top');
        }

        if ( t > 225 ) {
            $rtt.fadeIn( 300 );
        }
        else if ( t < 175 ) {
            $rtt.fadeOut( 300 );
        }
    };
    $window.scroll( updatePositionClass );
    setInterval( updatePositionClass, 500 );

    $rtt.click( function( e ) {
        e.preventDefault();
        jQuery( 'body,html' ).animate( { 'scrollTop' : 0 }, 500 );
    } );
};

/**
 * Initialize
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
domReady(main);
import.meta.webpackHot?.accept(main);
