// 
// 
//@author : Hydil Aicard Sokeing for GreenSoftTeam
//@creationDate : 09/06/2017


$(function() {

    var APP_ROOT;

    (window.location.pathname.match(/(.*?)web/i)) ? (APP_ROOT = window.location.pathname.match(/(.*?)web/i)[1]) : (APP_ROOT = "");
    (APP_ROOT) ? (APP_ROOT += "web") : (APP_ROOT);

    head.load([
        APP_ROOT + "/assets/js/main/helpers/FeedbackHelper.js",
    ], function() {
        /**
         * constructor
         */
        function AppEndpoint() {
            this.feedbackHelper = new FeedbackHelper();
        }

        var appEndpoint = new AppEndpoint();

        /**
         * allow to initialize the view
         * @return {void} nothing
         */
        AppEndpoint.prototype.initializeView = function() {
            console.log("Hello");
            //paste this code under the head tag or in a separate js file.
                // Wait for window load
                $(window).load(function() {
                    // Animate loader off screen
                    $(".se-pre-con").fadeOut("slow");;
                });
            //remove active class from the dashboard and assign it to active view
            $('ul.custom-nav li.active').removeClass('active');
            $('li.home').addClass('active');
        }


        /**
         * allow to set a whole bunch of listeners
         */
        AppEndpoint.prototype.setListeners = function() {

        }


        //this should be at the end
        appEndpoint.initializeView();
        appEndpoint.setListeners();

    });
});
