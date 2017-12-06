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
        function InternalAccountEndpoint() {

            this.feedbackHelper = new FeedbackHelper();
            
        }

        var internalAccountEndpoint = new InternalAccountEndpoint();

        /**
         * allow to initialize the view
         * @return {void} nothing
         */
        InternalAccountEndpoint.prototype.initializeView = function() {
            console.log("Here stands  INTERNAL ACCOUNT OPERATIONS");
        }

        /**
         * allow to set a whole bunch of listeners
         */
        InternalAccountEndpoint.prototype.setListeners = function() {
            this.setAccountNameSelectorListener();
            this.validateRegisterOperationListener();
            this.validateRegisterCashOutOperationListener();
        }


        InternalAccountEndpoint.prototype.setAccountNameSelectorListener = function(){
            $('body').on('change', '#AccountId', function(){

                $("#detailAccount").addClass('hide');
                var that = this;
                var idAccount =  $(this).val();

            if (idAccount) {
                $.ajax({
                    type: "GET",
                    url : "../internalaccount_api/" +idAccount,
                    dataType: "JSON",
                    beforeSend: function() {
                        $(that).LoadingOverlay('show');
                    },
                    success: function(returnedData){
                        console.log(returnedData);
                            $("#solde").text(returnedData.data.amount);
                            $("#AccountName").text(returnedData.data.account_number);
                            $("#detailAccount").removeClass('hide');

                    },
                    error : function(returnedData){
                        internalAccountEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "A problem occur during the request, please contact the administrator", "error");
                        console.log(returnedData);
                    },
                    complete: function(){
                        $(that).LoadingOverlay('hide');
                    }
                });
            }else{
                internalAccountEndpoint.feedbackHelper.showMessageWithPrompt("Warning", "Please select a valid name of a client", "warning");
                // location.reload();
           }
            });
    }

    InternalAccountEndpoint.prototype.validateRegisterOperationListener = function(){
        $('body').on('click', 'a[name="btn-save-operation"]', function(){

            var feedbackMessage = JSON.parse(JSON.stringify({
                'title' : 'Validation of the operation',
                'message' : 'You agree that the operation is correct?',
                'type' : 'warning',
                'confirmeButtonText' : 'Yes I confirm',
                'callback' : internalAccountEndpoint.setRegisterCashInListener
            }));

            internalAccountEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, 
                                                            feedbackMessage.message,
                                                            feedbackMessage.type,
                                                            feedbackMessage.confirmeButtonText,
                                                            internalAccountEndpoint.setRegisterCashInListener);
        });

    }

    InternalAccountEndpoint.prototype.validateRegisterCashOutOperationListener = function(){
        $('body').on('click', 'a[name="btn-save-operation-debit"]', function(){

            var feedbackMessage = JSON.parse(JSON.stringify({
                'title' : 'Validation of the operation',
                'message' : 'You agree that the operation is correct?',
                'type' : 'warning',
                'confirmeButtonText' : 'Yes I confirm',
                'callback' : internalAccountEndpoint.setRegisterCashOutListener
            }));

            internalAccountEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, 
                                                            feedbackMessage.message,
                                                            feedbackMessage.type,
                                                            feedbackMessage.confirmeButtonText,
                                                            internalAccountEndpoint.setRegisterCashOutListener);
        });

    }



    InternalAccountEndpoint.prototype.setRegisterCashInListener = function(){
            
            var data = JSON.parse(JSON.stringify({
                "idAccount" : $("#AccountId").val(),
                "amount" : $('input[type="number"]').val()
            }));

            console.log(data);

            $.ajax({
                method      : "POST", 
                data        : {data : data},
                url         : URL_ROOT + "/internalaccount/operation/save",
                dataType    : "JSON",
                beforeSend  : function(){
                },
                success     : function(data){
                    console.log(data);
                    internalAccountEndpoint.feedbackHelper.showAutoCloseMessage("Operation done", "Validation information", "success", 2000);
                    location.reload();
                }, 
                error : function(returnedData){
                    internalAccountEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "A problem occurs during the request, if it persist,  please contact the administrator", "error");

                },
            });
    }


    InternalAccountEndpoint.prototype.setRegisterCashOutListener = function(){
            
            var data = JSON.parse(JSON.stringify({
                "idAccount" : $("#AccountId").val(),
                "amount" : $('input[type="number"]').val()
            }));

            console.log(data);

            $.ajax({
                method      : "POST", 
                data        : {data : data},
                url         : URL_ROOT + "/internalaccount/debit/save",
                dataType    : "JSON",
                beforeSend  : function(){
                },
                success     : function(data){
                    console.log(data);
                    internalAccountEndpoint.feedbackHelper.showAutoCloseMessage("Operation done", "Validation information", "success", 2000);
                    location.reload();
                }, 
                error : function(returnedData){
                    internalAccountEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "A problem occurs during the request, if it persist,  please contact the administrator", "error");

                },
            });
    }

        //this should be at the end
        internalAccountEndpoint.initializeView();
        internalAccountEndpoint.setListeners();


    });

    

});