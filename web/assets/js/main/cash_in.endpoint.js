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
            console.log("here stands CASH IN");
            $('.choice').iCheck({
                            checkboxClass: 'icheckbox_square-purple'
                        });
            // $('.paymentOp').iCheck({
            //                 checkboxClass: 'icheckbox_square-blue'
            //             });
        }

        /**
         * allow to set a whole bunch of listeners
         */
        OperationEndpoint.prototype.setListeners = function() {
            this.setResetForm();
            this.setAddPurposeForm();
            this.setAddOtherItem();
            this.purposeInputKeyUpListener();
            this.analyticInputKeyUpListener();
            this.setCustomiseButton();
        }

        OperationEndpoint.prototype.purposeInputKeyUpListener = function() {
            $('body').on('keyup', '.purpose', function(){
                var total = 0;
                if ($(this).val() !="") {
                    $("#purposes tr").each(function() {
                        var currentValue = $(this).find("td input.purpose").val();
                        var intValue = parseInt(currentValue);
                        if (intValue) {
                            total = total + intValue;
                        }
                    });
                    $('input.total').val(total);
                }else{
                    $("#purposes tr").each(function() {
                        var currentValue = $(this).find("td input.purpose").val();
                        var intValue = parseInt(currentValue);
                        if (intValue) {
                            total = total + intValue;
                        }
                    });
                    $('input.total').val(total);
                }
            });
        }

        OperationEndpoint.prototype.analyticInputKeyUpListener = function() {
            $('body').on('keyup', '.analytic', function(){
                    var total = 0;
                if ($(this).val() !="") {
                    var value = parseInt($(this).closest('tr').find("th input").val());
                    var times = parseInt($(this).val());
                    var rowtotal = value * times;
                    $(this).closest('tr').find("td:eq(3) input").val(rowtotal);
                    $("#analytics tr").each(function() {
                        var currentValue = $(this).find("td input.cashResult").val();
                        var intValue = parseInt(currentValue);
                        if (intValue) {
                            total = total + intValue;
                        }
                    });

                    $('input.amount').val(total);
                    $('input.totalCash').val(total);
                }else{
                    $(this).closest('tr').find("td:eq(3) input").val(0);
                    $("#analytics tr").each(function() {
                        var currentValue = $(this).find("td input.cashResult").val();
                        var intValue = parseInt(currentValue);
                        if (intValue) {
                            total = total + intValue;
                        }
                    });

                    $('input.amount').val(total);
                    $('input.totalCash').val(total);                 
                }
            });
        }



    OperationEndpoint.prototype.setAddPurposeForm = function(){
        $('body').on('click', 'a[name="btn-other"]', function(){
            $("#otherItem").removeClass('hide');
        });
    }

    OperationEndpoint.prototype.setAddOtherItem = function(){
        $('body').on('change', '#otherItem', function(){
            if ($(this).val() == "") {

            }else{
                $subTr = $("tr.template").clone().removeClass('template');
                $subTr.find('th').text($(this).val());
                var myString = $(this).val();
                var arr = myString.split(' ');
                $subTr.find('td input').attr('name', arr[0]);
                $subTr.removeClass('hide');
                $subTr.addClass('rowTemplate');
                
                $subTr.appendTo("#purposes");
                $("#otherItem").addClass('hide');
            }
        });
    }


    OperationEndpoint.prototype.setResetForm = function(){
        $('body').on('click', 'a[name="btn-formaccount-reset"]', function(){
            $("#purposes").find("tr.rowTemplate").remove();
            $("#otherItem").addClass('hide');

            var total = 0;
            $("#purposes tr").each(function() {
                var currentValue = $(this).find("td input.purpose").val();
                var intValue = parseInt(currentValue);
                if (intValue) {
                    total = total + intValue;
                }
            });
            $('input.total').val(total);
    });
    }


    OperationEndpoint.prototype.setCustomiseButton = function(){
        $('body').on('change', '#memberNumber', function(){
            if ($(this).val() == "") {
                $("#bouton").addClass('hide')
            }else{
                $('#saving').attr('href', URL_ROOT+"/report/saving/"+$(this).val());
                $('#share').attr('href', URL_ROOT+"/report/shares/"+$(this).val());
                $('#deposit').attr('href', URL_ROOT+"/report/deposit/"+$(this).val());
                $('#loan').attr('href', URL_ROOT+"/report/loans/"+$(this).val());

                $("#bouton").removeClass('hide')
            }
        });
    }

        //this should be at the end
        operationEndpoint.initializeView();
        operationEndpoint.setListeners();


    });

});