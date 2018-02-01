/*
 * with var my_plugin the my_plugin variabele exists
 * wordpress has put the array from wp_localize_script in it
 */
var kontje;

// start one main function with some extra's
(function ($, root, undefined) {

    'use strict';

    // the ajax request function
    // page clicked should match the words in all lines with (4)
    function rate_kontje(rating) {

        jQuery.ajax({
            // we get my_plugin.ajax_url from php, ajax_url was the key the url the value
            url : atob(kontje.ajax_url),
            type : 'post',
            data : {
                // remember_setting should match the last part of the hook (2) in the php file (4)
                action        : 'rate_kontje',
                filter_nonce  : atob(kontje.nonce),
                rating        : rating,
                attachment    : atob(kontje.id)
            },
            error: function(){
                $('.pech-pechhulp-info').html( "<p>Helaas is iets mis gegaan.</p>" );
                // Here Loader animation
            },
            beforeSend: function(){
                // Here Loader animation
            },
            success : function( response ) {
                if (response.response != '') {
                    console.table(response.data);
                    console.log(response.response);
                    $('div[class^=attachment-star-]').removeClass('rated-red');
                    var i;
                    var stars = '';
                    for (i = 1; i <= rating; i++) {
                        $('.attachment-star-' + i).addClass('rated-red');
                    }
                    if (response.response == 'failure') {
                        stars = 'je mag 1x stemmen';
                    } else {
                        for (i = 1; i <= 5; i++) {
                            stars += '<span class="rating-star-' + i + '">' + i + '</span>' + response.data[0][i];
                        }
                    }

                    // console.log(response.data[0][1]);

                    $('#attachment-rating').addClass('rating-red');
                    $('#attachment-rating').html(stars);
                }

            }
        });

    }

    /*
     *      DOCUMENT READY
     */

    jQuery(document).ready(function() {

        $('div[class^=attachment-star]').click(function () {
            var rating = this.className.replace('attachment-star-', '').replace(' rated-red', '');
            rate_kontje(rating);
            console.log(atob(kontje.ajax_url));
        });

    })

})(jQuery, this);