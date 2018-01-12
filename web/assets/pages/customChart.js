/**
Template Name: Ubold Dashboard
Author: CoderThemes
Email: coderthemes@gmail.com
File: Chartjs
*/


!function($) {
    "use strict";

    var ChartJs = function() {};

    ChartJs.prototype.respChart = function(selector,type,data, options) {
        // get selector by context
        var ctx = selector.get(0).getContext("2d");
        // pointing parent container to make chart js inherit its width
        var container = $(selector).parent();

        // enable resizing matter
        $(window).resize( generateChart );

        // this function produce the responsive Chart JS
        function generateChart(){
            // make chart width fit with its container
            var ww = selector.attr('width', $(container).width()-100 );
            switch(type){
                case 'Doughnut':
                    new Chart(ctx, {type: 'doughnut', data: data, options: options});
                    break;
                case 'Pie':
                    new Chart(ctx, {type: 'pie', data: data, options: options});
                    break;
            }
            // Initiate new chart or Redraw

        };
        // run function - render chart at first load
        generateChart();
    },
    //init
    ChartJs.prototype.init = function() {

        //donut chart
        var donutChart = {
            labels: [
                "Desktops",
                "Tablets",
                "Mobiles"
            ],
            datasets: [
                {
                    data: [4763261, 15374480, 389075, 380000, 750000],
                    backgroundColor: [
                        "#5d9cec",
                        "#5fbeaa",
                        "#5fbefa",
                        "#5fbe5a",
                        "#ebeff2"
                    ],
                    hoverBackgroundColor: [
                        "#5d9cec",
                        "#5fbeaa",
                        "#ebeff2",
                        "#5fbefa",
                        "#5fbe5a",
                        "#ebeff2"
                    ],
                    hoverBorderColor: "#fff"
                }]
        };
        this.respChart($("#doughnut"),'Doughnut',donutChart);


        //Pie chart
        var pieChart = {
            labels: [
                "Loan Contracted",
                "Loan Unpaid",
                "Unpaid Interest",
            ],
            datasets: [
                {
                    data: [24981000, 19950688, 1978261],
                    backgroundColor: [
                        "#5d9cec",
                        "#5fbeaa",
                        "#ebeff2"
                    ],
                    hoverBackgroundColor: [
                        "#5d9cec",
                        "#5fbeaa",
                        "#ebeff2"
                    ],
                    hoverBorderColor: "#fff"
                }]
        };
        this.respChart($("#pie"),'Pie',pieChart);
    },
    $.ChartJs = new ChartJs, $.ChartJs.Constructor = ChartJs

}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.ChartJs.init()
}(window.jQuery);

