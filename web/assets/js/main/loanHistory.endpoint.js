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
        function LoanHistoryEndpoint() {

            this.feedbackHelper = new FeedbackHelper();
            
        }

        var loanHistoryEndpoint = new LoanHistoryEndpoint();

        /**
         * allow to initialize the view
         * @return {void} nothing
         */
        LoanHistoryEndpoint.prototype.initializeView = function() {
            console.log("Here stands  credit union loan history");
        }

        /**
         * allow to set a whole bunch of listeners
         */
        LoanHistoryEndpoint.prototype.setListeners = function() {
            this.setLoanCodeSelectorListener();
            this.validateRegisterOperationListener();
            this.setResetForm();
        }

        LoanHistoryEndpoint.prototype.setLoanCodeSelectorListener = function(){
            $('body').on('change', '#loanCode', function(){

                $("#detailLoan").addClass('hide');
                $("#ownerDetails").addClass('hide');
                $("#physicalOwnerDetails").addClass('hide');
                var that = this;

                var loanCode =  $(this).val();

            if (loanCode) {
                $.ajax({
                    type: "GET",
                    url : "../../loan_api/"+loanCode,
                    dataType: "JSON",
                    beforeSend: function() {
                        $(that).LoadingOverlay('show');
                    },
                    success: function(returnedData){
                        console.log(returnedData);

                        $("#loanCodeD").text(returnedData.data.loan_code);
                        $("#loanRate").text(returnedData.data.rate+"%");

                        var formattedDate   = new Date(returnedData.data.date_loan);
                            var d               = ( '0' + formattedDate.getDate()).slice(-2);
                            var m               = ( '0' + formattedDate.getMonth()).slice(-2);
                            var y               = formattedDate.getFullYear();
                            var stringDate      =  d + "-" + m + "-" + y;
                        $("#loanDate").text(stringDate);

                        var formattedDate1   = new Date(returnedData.data.deadline);
                            var d               = ( '0' + formattedDate1.getDate()).slice(-2);
                            var m               = ( '0' + formattedDate1.getMonth()).slice(-2);
                            var y               = formattedDate1.getFullYear();
                            var stringDate1      =  d + "-" + m + "-" + y;

                        $("#loadDeadLine").text(stringDate1);

                        $("#monthlyPayment").text(returnedData.data.monthly_payment);


                        if (returnedData.loanhistory) {
                            console.log("loan history");

                            $("#loanAmount").text(returnedData.loanhistory.remain_amount);
                            //get the amount of the interest paid
                            var totalInterest  = returnedData.interestToPay + returnedData.loanhistory.unpaid_interest;
                            $("#loanInterest").text(totalInterest.toFixed(2));

                        }else{

                            $("#loanAmount").text(returnedData.data.loan_amount);
                            // var loanInterest = (returnedData.data.loan_amount * returnedData.data.rate)/100;
                            $("#loanInterest").text(returnedData.interestToPay.toFixed(2));

                        }

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

                            $("#detailLoan").removeClass('hide');
                    },
                    error : function(returnedData){
                        loanHistoryEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "A problem occur during the request, please contact the administrator", "error");
                        console.log(returnedData);
                    },
                    complete: function(){
                        $(that).LoadingOverlay('hide');
                    }
                });
            }else{
                loanHistoryEndpoint.feedbackHelper.showMessageWithPrompt("Warning", "The loan code cannot be null.  Please select a valid loan  code", "warning");
           }
            });
    }


    LoanHistoryEndpoint.prototype.validateRegisterOperationListener = function(){
        $('body').on('click', 'a[name="btn-loan-payment-save"]', function(){

            var feedbackMessage = JSON.parse(JSON.stringify({
                'title' : 'Validation of the operation',
                'message' : 'Confirm, You agree that the operation is correct?',
                'type' : 'warning',
                'confirmeButtonText' : 'Yes I confirm',
                'callback' : loanHistoryEndpoint.setRegisterCashInListener
            }));

            loanHistoryEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, feedbackMessage.message, feedbackMessage.type, feedbackMessage.confirmeButtonText, loanHistoryEndpoint.setRegisterCashInListener);
        });

    }



    LoanHistoryEndpoint.prototype.setRegisterCashInListener = function(){
            
            var data = JSON.parse(JSON.stringify({
                "loanCode" : $("#loanCode").val(),
                "monthlyPayment" : $('#accountbundle_loanhistory_monthlyPayement').val(),
                "interest" : $('#accountbundle_loanhistory_interest').val()
            }));

            console.log(data);

            $.ajax({
                method      : "POST", 
                data        : {data : data},
                url         : URL_ROOT + "/loanhistory/save",
                dataType    : "JSON",
                beforeSend  : function(){
                },
                success     : function(data){
                    console.log(data);
                    loanHistoryEndpoint.feedbackHelper.showAutoCloseMessage("Operation done", "Validation information", "success", 2000);
                    // location.reload();
                    window.location.href = URL_ROOT + "/loanhistory/"+data.optionalData+"/receipt";
                }, 
                error : function(returnedData){
                    loanHistoryEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "un problème est survenu pendant la soumission de votre requête si le problème persiste, contactez votre administrateur", "error");

                },
            });
    }

    LoanHistoryEndpoint.prototype.setResetForm = function(){
        $('body').on('click', 'a[name="btn-formaccount-reset"]', function(){
            $("div#zoneAccounts div.accounts_container").html("");
            $("#detailLoan").addClass('hide');
            $("#ownerDetails").addClass('hide');
            $("#physicalOwnerDetails").addClass('hide');
            $("#loanCode").attr("disabled", false);


        });
    }

        //this should be at the end
        loanHistoryEndpoint.initializeView();
        loanHistoryEndpoint.setListeners();


    });

});