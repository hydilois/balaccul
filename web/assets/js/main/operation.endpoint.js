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
        function OperationEndpoint() {

            this.feedbackHelper = new FeedbackHelper();
            
        }

        var operationEndpoint = new OperationEndpoint();

        /**
         * allow to initialize the view
         * @return {void} nothing
         */
        OperationEndpoint.prototype.initializeView = function() {
            console.log("Here stands  credit union operation CREDIT");
        }

        /**
         * allow to set a whole bunch of listeners
         */
        OperationEndpoint.prototype.setListeners = function() {
            this.setTypeAccountSelectorListener();
            this.setAccountNumberSelectorListener();
            this.validateRegisterOperationListener();
            this.setResetForm();
        }


        OperationEndpoint.prototype.setTypeAccountSelectorListener = function(){
            $('body').on('change', '#accountCategory', function(){
                console.log("Here stand a credit operation selected");

                var data = JSON.parse(JSON.stringify({"idCategory" : $(this).val()}));
                console.log(data);
                if (!$(this).val()) {
                    $("div#zoneAccounts div.accounts_container").html("");
                    operationEndpoint.feedbackHelper.showMessageWithPrompt("Warning", "Please select a valid value of the class", "warning");
                }else{
                $.ajax({
                    method      : "POST", 
                    data        : {data : data},
                    url         : "../account_list", 
                    dataType    : "JSON",
                    beforeSend      : function(){
                        $('#accountCategory').LoadingOverlay('show');
                        $("div#zoneAccounts div.accounts_container").html("");
                    },
                    success     : function(returnedData){
                        console.log(returnedData);
                        if (returnedData.data.length == 0) {
                            $subClassForm = $("div.template").clone().removeClass('template');
                                $subClassForm.html("");
                                $zone = $("select.selecteur-zone").clone().removeClass('hide');
                                $zone.attr('id','accountNumber');
                                $zone.append('<option value="">No accounts Found</option>');
                                $subClassForm.append('<label value="">Choose the name of the member</label>');
                                $subClassForm.append($zone);

                                $subClassForm.appendTo("div#zoneAccounts div.accounts_container").slideDown(250);
                        }else{
                                $subClassForm = $("div.template").clone().removeClass('template');
                                $subClassForm.html("");
                                $zone = $("select.selecteur-zone").clone().removeClass('hide');
                                $zone.attr('id','accountNumber');
                                $zone.append('<option value="">----- Choose an account number ----</option>');
                                returnedData.data.forEach( function(element, index) {
                                    if (element.physical_member) {
                                        $zone.append('<option value="'+ element.id+'">'+ element.physical_member.name +' -- '+element.account_number+'</option>');
                                    }else{
                                        $zone.append('<option value="'+ element.id+'">'+ element.moral_member.social_reason +' -- '+element.account_number+'</option>');

                                    }
                            });

                            $subClassForm.append('<label value="">Choose the name of the member</label>');
                            $subClassForm.append($zone);
                            $subClassForm.appendTo("div#zoneAccounts div.accounts_container").slideDown(250);

                        }
                    }, 
                    error : function(returnedData){
                        operationEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "A problem occur during the request, please contact the administrator", "error");
                    }, 
                    complete    : function(){
                        $('#accountCategory').LoadingOverlay('hide');
                    }
                });
            }
            });
        }


        OperationEndpoint.prototype.setAccountNumberSelectorListener = function(){
            $('body').on('change', '#accountNumber', function(){

                $("#detailAddcount").addClass('hide');
                $("#ownerDetails").addClass('hide');
                $("#physicalOwnerDetails").addClass('hide');
                var that = this;

            var accountCategory = $("#accountCategory").val();
            var idAccount =  $(this).val();
            $("#accountCategory").attr("disabled", true);

            if (idAccount) {
                $.ajax({
                    type: "GET",
                    url : "../account_api/" +idAccount+"/"+accountCategory,
                    dataType: "JSON",
                    beforeSend: function() {
                        $(that).LoadingOverlay('show');
                    },
                    success: function(returnedData){
                        console.log(returnedData);
                        $("#solde").text(returnedData.data.solde);
                        $("#number").text(returnedData.data.account_number);
                        $("#detailAddcount").removeClass('hide');
                        $('#saveCredit').attr('name','btn-account-save');


                        if (returnedData.data.moral_member) {
                            console.log("Moral Member existes");
                            $("#socialReason").text(returnedData.data.moral_member.social_reason);
                            $("#memberNumber").text(returnedData.data.moral_member.member_number);
                            $("#address").text(returnedData.data.moral_member.address);
                            $("#phoneNumber").text(returnedData.data.moral_member.phone_number);

                            var formattedDate   = new Date(returnedData.data.moral_member.membership_date_creation);
                                var d               = ( '0' + formattedDate.getDate()).slice(-2);
                                var m               = ( '0' + formattedDate.getMonth()).slice(-2);
                                var y               = formattedDate.getFullYear();
                                var stringDate      =  d + "-" + m + "-" + y;
                            $("#date").text(stringDate);

                            $("#witness").text(returnedData.data.moral_member.witness_name);
                            $("#proposed").text(returnedData.data.moral_member.proposed_by);
                            $("#ownerDetails").removeClass('hide');

                        }else{
                            console.log("Physical Member existes");
                            $("#pname").text(returnedData.data.physical_member.name);
                            $("#pmemberNumber").text(returnedData.data.physical_member.member_number);
                            $("#poccupation").text(returnedData.data.physical_member.occupation);
                            $("#paddress").text(returnedData.data.physical_member.address);
                            $("#pnicNumber").text(returnedData.data.physical_member.nic_number);
                            $("#pphoneNumber").text(returnedData.data.physical_member.phone_number);

                            var formattedDate   = new Date(returnedData.data.physical_member.membership_date_creation);
                                var d               = ( '0' + formattedDate.getDate()).slice(-2);
                                var m               = ( '0' + formattedDate.getMonth()).slice(-2);
                                var y               = formattedDate.getFullYear();
                                var stringDate      =  d + "-" + m + "-" + y;
                            $("#pdate").text(stringDate);

                            $("#pwitness").text(returnedData.data.physical_member.witness_name);
                            $("#pproposed").text(returnedData.data.physical_member.proposed_by);

                            var formattedDate1   = new Date(returnedData.data.physical_member.date_of_birth);
                                var d               = ( '0' + formattedDate1.getDate()).slice(-2);
                                var m               = ( '0' + formattedDate1.getMonth()).slice(-2);
                                var y               = formattedDate1.getFullYear();
                                var stringDate      =  d + "-" + m + "-" + y;
                            $("#pdateBirth").text(stringDate);
                            $("#pPlaceBirth").text(returnedData.data.physical_member.place_of_birth);
                            
                            $("#physicalOwnerDetails").removeClass('hide');
                        }

                    },
                    error : function(returnedData){
                        operationEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "A problem occur during the request, please contact the administrator", "error");
                        console.log(returnedData);
                    },
                    complete: function(){
                        $(that).LoadingOverlay('hide');
                    }
                });
            }else{
                operationEndpoint.feedbackHelper.showMessageWithPrompt("Warning", "Please select a valid number account", "warning");
                $("#accountCategory").attr("disabled", false);
           }
            });
    }

    OperationEndpoint.prototype.validateRegisterOperationListener = function(){
        $('body').on('click', 'a[name="btn-account-save"]', function(){

            var feedbackMessage = JSON.parse(JSON.stringify({
                'title' : 'Validation of the operation',
                'message' : 'Confirm, You agree that the operation is correct?',
                'type' : 'warning',
                'confirmeButtonText' : 'Yes I confirm',
                'callback' : operationEndpoint.setRegisterCashInListener
            }));

            operationEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, feedbackMessage.message, feedbackMessage.type, feedbackMessage.confirmeButtonText, operationEndpoint.setRegisterCashInListener);
        });

    }



    OperationEndpoint.prototype.setRegisterCashInListener = function(){
            var data = JSON.parse(JSON.stringify({
                "idAccount" : $("#accountNumber").val(),
                "accountCategory" : $("#accountCategory").val(),
                "amount" : $('input[type="number"]').val(),
                "fees" : $('input[name="fees"]').val(),
            }));

            console.log(data);

            $.ajax({
                method      : "POST", 
                data        : {data : data},
                url         : URL_ROOT + "/operation/credit/save",
                dataType    : "JSON",
                beforeSend  : function(){
                },
                success     : function(data){
                    console.log(data);
                    operationEndpoint.feedbackHelper.showAutoCloseMessage("Operation done", "Validation information", "success", 2000);
                    window.location.href = URL_ROOT + "/operation/"+data.optionalData;
                }, 
                error : function(returnedData){
                    operationEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "un problème est survenu pendant la soumission de votre requête si le problème persiste, contactez votre administrateur", "error");

                },
            });
    }

    OperationEndpoint.prototype.setResetForm = function(){
        $('body').on('click', 'a[name="btn-formaccount-reset"]', function(){
            $("div#zoneAccounts div.accounts_container").html("");
            $("#detailAddcount").addClass('hide');
            $("#ownerDetails").addClass('hide');
            $("#physicalOwnerDetails").addClass('hide');
            $("#accountCategory").attr("disabled", false);


        });
    }

        //this should be at the end
        operationEndpoint.initializeView();
        operationEndpoint.setListeners();


    });

});