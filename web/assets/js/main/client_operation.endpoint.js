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
            this.setCreateFormModalShow();
            this.setRegisterClientListener();
            this.setClientEditFormModalShow();
            this.setWithFormModalShow();
            this.validateRegisterWithListener();
            this.validateRefreshListener();
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
                            $("#solde").text(returnedData.data.balance);
                            $("#clientNamed").text(returnedData.data.name);
                            $("#detailClient").removeClass('hide');

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

    ClientEndpoint.prototype.setCreateFormModalShow = function(){
        $('body').on('click','a[name="create-client"]', function(){
            $('#memberbundle_client_name').val('');
            $('.modal-create-client').modal('show');
        })
    }

    ClientEndpoint.prototype.setRegisterClientListener = function(){

            $('body').on('click','a[name="save-client"]', function(){

                var data = JSON.parse(JSON.stringify({
                    "clientName" : $("#memberbundle_client_name").val(),
                    "collectorId" : $("#memberbundle_client_collector").val(),
                }));

                var that = this;
                console.log(data);

                    //We send an ajax requeset to register the eleve object
                    $.ajax({
                        method      : "POST", 
                        data        : {data : data},
                        url         : URL_ROOT+"/client/new_json", 
                        dataType    : "JSON", 
                        beforeSend      : function(){
                            $(that).parent().parent().LoadingOverlay('show');
                        },
                        success     : function(returnedData){
                            console.log(returnedData);
                            if(returnedData.status){
                                $('.modal-create-client').modal('hide');
                                clientEndpoint.feedbackHelper.showAutoCloseMessage("New Client Saved", "Success", "success", 2000);
                                location.reload();
                            }
                        }, 
                        error : function(returnedData){
                            console.error(returnedData);
                            clientEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "A problem occur during the request, please contact the administrator", "error");
                            $(that).parent().parent().LoadingOverlay('hide');
                        },
                        complete : function(){
                        }
                    }); 

            });

    }

    ClientEndpoint.prototype.setClientEditFormModalShow = function() {
        $('body').on('click','a[name="client-edit"]', function() {
            val = $(this).data('client');

            $('#client-editform').load(URL_ROOT+'/client/'+val+'/edit', function(){
                $('.modal-edit-client').modal('show');
            });
        })
    }

    ClientEndpoint.prototype.validateRegisterWithListener = function(){
        $('body').on('click','a[name="save-with"]', function(){

            var feedbackMessage = JSON.parse(JSON.stringify({
                'title' : 'Validation of the operation',
                'message' : 'You agree that the operation is correct?',
                'type' : 'warning',
                'confirmeButtonText' : 'Yes I confirm',
                'callback' : clientEndpoint.setRegisterWithListener
            }));

            clientEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, 
                                                            feedbackMessage.message,
                                                            feedbackMessage.type,
                                                            feedbackMessage.confirmeButtonText,
                                                            clientEndpoint.setRegisterWithListener);
        });

    }

    ClientEndpoint.prototype.setWithFormModalShow = function(){
        $('body').on('click','a[name="new-with"]', function(){
            $("#balance").text($(this).data('balance'));
            $("#nom").text($(this).data('nomclient'));
            $('#dynamicId').val($(this).data('client'));
            $('#type').val($(this).data('type'));
            $('#with1').val(0);

            if ($(this).data('type') == 1) {
                $('#title').text("WITHDRAW 1");
            }else{
                $('#title').text("WITHDRAW 2");
            }

            $('.modal-set-with').modal('show');
        })
    }



    ClientEndpoint.prototype.setRegisterWithListener = function(){

        var data = JSON.parse(JSON.stringify({
            "clientId" : $('#dynamicId').val(),
            "type" : $('#type').val(),
            "amount" : $('#with1').val(),
        }));

        var that = this;
        console.log(data);

        // We send an ajax requeset to register the eleve object
        $.ajax({
            method      : "POST", 
            data        : {data : data},
            url         : URL_ROOT+"/client/withdrawal", 
            dataType    : "JSON", 
            beforeSend      : function(){
            },
            success     : function(returnedData){
                console.log(returnedData);
                    $('.modal-set-with').modal('hide');
                    clientEndpoint.feedbackHelper.showAutoCloseMessage("New Client Saved", "Success", "success", 2000);
                    location.reload();
            }, 
            error : function(returnedData){
                console.error(returnedData);
                clientEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "A problem occur during the request, please contact the administrator", "error");
                // $(that).parent().parent().LoadingOverlay('hide');
            },
            complete : function(){
            
            }
        });
    }


    ClientEndpoint.prototype.validateRefreshListener = function(){
        $('body').on('click','a[name="refresh-data"]', function(){

            var feedbackMessage = JSON.parse(JSON.stringify({
                'title' : 'Validation of the operation',
                'message' : 'You that you want to start the collection for a new month',
                'type' : 'warning',
                'confirmeButtonText' : 'Yes I confirm',
                'callback' : clientEndpoint.setDataRefreshListener
            }));

            clientEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, 
                                                            feedbackMessage.message,
                                                            feedbackMessage.type,
                                                            feedbackMessage.confirmeButtonText,
                                                            clientEndpoint.setDataRefreshListener);
        });

    }


    ClientEndpoint.prototype.setDataRefreshListener = function(){

        var data = JSON.parse(JSON.stringify({
            "clientId" : 1,
        }));

        // var that = this;
        console.log(data);

        // We send an ajax requeset to register the eleve object
        $.ajax({
            method      : "POST", 
            data        : {data : data},
            url         : URL_ROOT+"/client/refresh", 
            dataType    : "JSON", 
            beforeSend      : function(){
            },
            success     : function(returnedData){
                console.log(returnedData);
                    clientEndpoint.feedbackHelper.showAutoCloseMessage("Operation done", "Success", "success", 2000);
                    location.reload();
            }, 
            error : function(returnedData){
                console.error(returnedData);
                clientEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "A problem occur during the request, please contact the administrator", "error");
            },
            complete : function(){
            
            }
        });
    }



        //this should be at the end
        clientEndpoint.initializeView();
        clientEndpoint.setListeners();


    });

});