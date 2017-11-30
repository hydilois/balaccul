// 
// 
//@author : Hydil Aicard Sokeing for GreenSoftTeam
//@creationDate : 12/11/2017


$(function() {

    var APP_ROOT;

    (window.location.pathname.match(/(.*?)web/i)) ? (APP_ROOT = window.location.pathname.match(/(.*?)web/i)[1]) : (APP_ROOT = "");
    (APP_ROOT) ? (APP_ROOT += "web") : (APP_ROOT);

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
        function ClientEndpoint() {

            this.feedbackHelper = new FeedbackHelper();
            
        }

        var clientEndpoint = new ClientEndpoint();

        /**
         * allow to initialize the view
         * @return {void} nothing
         */
        ClientEndpoint.prototype.initializeView = function() {
            console.log("Here stands  DAILY SERVICE");
        }

        /**
         * allow to set a whole bunch of listeners
         */
        ClientEndpoint.prototype.setListeners = function() {
            this.setClientNameSelectorListener();
            this.validateRegisterOperationListener();
            this.setResetForm();
        }


        ClientEndpoint.prototype.setClientNameSelectorListener = function(){
            $('body').on('change', '#clientId', function(){

                $("#detailClient").addClass('hide');
                $("#OwnerDetails").addClass('hide');
                var that = this;

            var operationType = $("#operationType").val();
            var idClient =  $(this).val();
            $("#operationType").attr("disabled", true);

            if (idClient && operationType) {
                $.ajax({
                    type: "GET",
                    url : "../client_api/" +idClient,
                    dataType: "JSON",
                    beforeSend: function() {
                        $(that).LoadingOverlay('show');
                    },
                    success: function(returnedData){
                        console.log(returnedData);
                            $("#solde").text(returnedData.data.amount.amount);
                            $("#clientNamed").text(returnedData.data.name);
                            $("#detailClient").removeClass('hide');


                        //     var formattedDate1   = new Date(returnedData.data.physical_member.date_of_birth);
                        //         var d               = ( '0' + formattedDate1.getDate()).slice(-2);
                        //         var m               = ( '0' + formattedDate1.getMonth()).slice(-2);
                        //         var y               = formattedDate1.getFullYear();
                        //         var stringDate      =  d + "-" + m + "-" + y;
                        //     $("#pdateBirth").text(stringDate);

                            
                        // }

                    },
                    error : function(returnedData){
                        clientEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "A problem occur during the request, please contact the administrator", "error");
                        console.log(returnedData);
                    },
                    complete: function(){
                        $(that).LoadingOverlay('hide');
                    }
                });
            }else{
                clientEndpoint.feedbackHelper.showMessageWithPrompt("Warning", "Please select a valid name of a client", "warning");
                // $("#operationType").attr("disabled", false);
                location.reload();
           }
            });
    }

    ClientEndpoint.prototype.validateRegisterOperationListener = function(){
        $('body').on('click', 'a[name="btn-save-operation"]', function(){

            var feedbackMessage = JSON.parse(JSON.stringify({
                'title' : 'Validation of the operation',
                'message' : 'You agree that the operation is correct?',
                'type' : 'warning',
                'confirmeButtonText' : 'Yes I confirm',
                'callback' : clientEndpoint.setRegisterCashInListener
            }));

            clientEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, 
                                                            feedbackMessage.message,
                                                            feedbackMessage.type,
                                                            feedbackMessage.confirmeButtonText,
                                                            clientEndpoint.setRegisterCashInListener);
        });

    }



    ClientEndpoint.prototype.setRegisterCashInListener = function(){
            
            var data = JSON.parse(JSON.stringify({
                "idClient" : $("#clientId").val(),
                "operationType" : $("#operationType").val(),
                "amount" : $('input[type="number"]').val()
            }));

            console.log(data);

            $.ajax({
                method      : "POST", 
                data        : {data : data},
                url         : URL_ROOT + "/client/operation/save",
                dataType    : "JSON",
                beforeSend  : function(){
                },
                success     : function(data){
                    console.log(data);
                    clientEndpoint.feedbackHelper.showAutoCloseMessage("Operation done", "Validation information", "success", 2000);
                    location.reload();
                }, 
                error : function(returnedData){
                    clientEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "A problem occur during the request, please contact the administrator", "error");

                },
            });
    }

    ClientEndpoint.prototype.setResetForm = function(){
        $('body').on('click', 'a[name="btn-formaccount-reset"]', function(){

            $("#operationType").attr("disabled", false);
        });
    }

        //this should be at the end
        clientEndpoint.initializeView();
        clientEndpoint.setListeners();


    });

    

});