// 
// 
//@author : Hydil Aicard Sokeing for GreenSoftTeam
//@creationDate : 25/06/2017


$(function(){

	var APP_ROOT; 

	(window.location.pathname.match(/(.*?)web/i))?(APP_ROOT = window.location.pathname.match(/(.*?)web/i)[1]):(APP_ROOT = "");
	(APP_ROOT)?(APP_ROOT+= "web"):(APP_ROOT);

	var URL_ROOT = APP_ROOT;
	if(window.location.pathname.indexOf("app_dev.php") !== -1){
		URL_ROOT = APP_ROOT + "/app_dev.php";
	}else if(window.location.pathname.indexOf("app.php") !== -1){
		URL_ROOT = APP_ROOT + "/app.php";
	}

	head.load([
			APP_ROOT + "/assets/js/main/helpers/FeedbackHelper.js",
		], function(){
			
			/**
			 * constructor
			 */
			function StatistiqueEndpoint(){
				this.feedbackHelper = new FeedbackHelper();
			}

			var statistiqueEndpoint = new StatistiqueEndpoint();

			/**
			 * allow to initialize the view
			 * @return {void} nothing
			 */
			StatistiqueEndpoint.prototype.initializeView = function(){
				console.log("Here Statistiques");
			}
			/**
			 * allow to set a whole bunch of listeners
			 */
			StatistiqueEndpoint.prototype.setListeners = function(){
				this.setTypeAccountSelectorListener();
				this.validateOperationListener();
			}

			StatistiqueEndpoint.prototype.setTypeAccountSelectorListener = function(){
			    $('body').on('change', '#accountCategory', function(){
			        console.log("Here stand a account category selected");

			        var data = JSON.parse(JSON.stringify({"idCategory" : $(this).val()}));
			        console.log(data);
			        if (!$(this).val()) {
			            $("div#zoneAccounts div.accounts_container").html("");
			            statistiqueEndpoint.feedbackHelper.showMessageWithPrompt("Warning", "Please select a valid value of the class", "warning");
			        }else{
			        $.ajax({
			            method      : "POST", 
			            data        : {data : data},
			            url         : URL_ROOT + "/operation/accounts/list", 
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
			                    	$subClassForm.append('<label>Account Number</label>');
			                        $subClassForm.append($zone);

			                        $subClassForm.appendTo("div#zoneAccounts div.accounts_container").slideDown(250);
			                }else{
			                        $subClassForm = $("div.template").clone().removeClass('template');
			                        $subClassForm.html("");
			                        $zone = $("select.selecteur-zone").clone().removeClass('hide');
			                        $zone.attr('id','accountNumber');
			                        $zone.append('<option value="">----- Choose an account number ----</option>');
			                        returnedData.data.forEach( function(element, index) {

			                        $zone.append('<option value="'+ element.id+'">'+ element.accountNumber +'</option>');
			                    });

			                    $subClassForm.append('<label>Account Number</label>');
			                    $subClassForm.append($zone);
			                    $subClassForm.appendTo("div#zoneAccounts div.accounts_container").slideDown(250);

			                }
			            }, 
			            error : function(returnedData){
			                statistiqueEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "A problem occur during the request, please contact the administrator", "error");
			            }, 
			            complete    : function(){
			                $('#accountCategory').LoadingOverlay('hide');
			            }
			        });
			    }
			    });
			}

			StatistiqueEndpoint.prototype.validateOperationListener = function(){
				$('body').on('click','.validate-operation', function(){

					$('input[name="operation-input-id"]').val($(this).data('operation'));
					$('input[name="account-input-id"]').val($(this).data('account'));
					$('input[name="type-input-id"]').val($(this).data('type'));

					var feedbackMessage = JSON.parse(JSON.stringify({
						'title' : 'Validation of the operation',
						'message' : 'Confirm, You agree that the operation is correct?',
						'type' : 'warning',
						'confirmeButtonText' : 'Yes I confirm',
						'callback' : statistiqueEndpoint.validateOperation
					}));

					statistiqueEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, feedbackMessage.message, feedbackMessage.type, feedbackMessage.confirmeButtonText, statistiqueEndpoint.validateOperation);
				})

			}

			StatistiqueEndpoint.prototype.validateOperation = function(){

				var data = JSON.parse(JSON.stringify(
					{
						"idOperation" : $('input[name="operation-input-id"]').val(),
						"account" : $('input[name="account-input-id"]').val(),
						"type" : $('input[name="type-input-id"]').val()
					}
				));

				console.log(data);

				$.ajax({
					method 		: "POST", 
					data 		: {data : data},
					url 		: URL_ROOT + "/validate/operation", 
					dataType 	: "JSON",
					beforeSend 	: function(){

					},
					success 	: function(returnedData){
						console.log(returnedData);

						var feedbackMessage = JSON.parse(JSON.stringify({
							'title' : 'Operation Validated',
							'message' : 'Success',
							'type' : 'success',
							'timeout' : 3000
						}));

						statistiqueEndpoint.feedbackHelper.showAutoCloseMessage(feedbackMessage.title, feedbackMessage.message, feedbackMessage.type, feedbackMessage.timeout);
						location.reload();
					}, 
					error : function(returnedData){
						console.error('something wrong happened on the server');
						var feedbackMessage = JSON.parse(JSON.stringify({
							'title' 	: 'error',
							'message' 	: 'An error occur, if it persist contact the administrator',
							'type' 		: 'error',
							'timeout' 	: 20000
						}));
						statistiqueEndpoint.feedbackHelper.showAutoCloseMessage(feedbackMessage.title, feedbackMessage.message, feedbackMessage.type, feedbackMessage.timeout);
					}
				});

			}
			//this should be at the end
			statistiqueEndpoint.initializeView();
			statistiqueEndpoint.setListeners();
	});
});