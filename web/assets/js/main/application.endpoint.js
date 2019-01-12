                        
//@author : william eyidi for madia<williameyidi@yahoo.fr>
// Modified by Hydil Aicard Sokeing for GreenSoft Team


$(function(){

    var APP_ROOT; 

    (window.location.pathname.match(/(.*?)web/i))?(APP_ROOT = window.location.pathname.match(/(.*?)web/i)[1]):(APP_ROOT = "");
    (APP_ROOT)?(APP_ROOT = APP_ROOT + "web"):(APP_ROOT);

    var URL_ROOT = APP_ROOT;
    if(window.location.pathname.indexOf("app_dev.php") !== -1){
        URL_ROOT = APP_ROOT + "/app_dev.php";
    }else if(window.location.pathname.indexOf("app.php") !== -1){
        URL_ROOT = APP_ROOT + "/app.php";
    }

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
            this.setChartDisplay();
        }


        /**
         * allow to set a whole bunch of listeners
         */
        AppEndpoint.prototype.setListeners = function() {

        }


        /**
         * Sets the alerts for the whole application
         * the function will perform the following
         * - check the role of the user connected then set paths
         * according to the user connected
         */
        AppEndpoint.prototype.setChartDisplay = function(){
            var data = {};

            $.ajax({
                method      : "POST", 
                data        : {data : data},
                url         : URL_ROOT + "/alert/chart", 
                dataType    : "JSON",
                beforeSend      : function(){
                },
                success     : function(returnedData){
                    returnedDataParsed = JSON.parse(returnedData);
                    balance = returnedDataParsed.data;

                    var canvas = $("#myChart");

                    var data = {
                        labels: ["Liabilities", "Assets", "Stocks", "Class 4", "Cash & Bank", "Expenditure", "Income"],
                        datasets: [
                            {
                                label: "Global Situation",
                                backgroundColor: "rgba(255,99,132,0.2)",
                                borderColor: "rgba(255,99,132,1)",
                                borderWidth: 2,
                                hoverBackgroundColor: "rgba(255,99,132,0.4)",
                                hoverBorderColor: "rgba(255,99,132,1)",
                                data: [balance[0].cl_balance, balance[1].cl_balance, balance[2].cl_balance, balance[3].cl_balance, balance[4].cl_balance, balance[5].cl_balance, balance[6].cl_balance],
                            }
                        ]
                    };
                    var option = {
                        scales: {
                        yAxes:[{
                                stacked:true,
                            gridLines: {
                                display:true,
                              color:"rgba(255,99,132,0.6)"
                            }
                        }],
                        xAxes:[{
                                gridLines: {
                                display:false
                            }
                        }]
                      }
                    };

                    var myBarChart = Chart.Bar(canvas,{
                        data:data,
                      options:option
                    });


                }, 
                error : function(returnedData){
                    console.log(returnedData);
                }, 
                complete    : function(){

                }
            });
        }




        //this should be at the end
        appEndpoint.initializeView();
        appEndpoint.setListeners();

    });
});