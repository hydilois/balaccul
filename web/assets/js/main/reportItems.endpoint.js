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
			function ReportItemEndpoint(){
				this.feedbackHelper = new FeedbackHelper();
			}

			var reportItemEndpoint = new ReportItemEndpoint();

			/**
			 * allow to initialize the view
			 * @return {void} nothing
			 */
			ReportItemEndpoint.prototype.initializeView = function(){
				console.log("Here stand report item");
			}
			/**
			 * allow to set a whole bunch of listeners
			 */
			ReportItemEndpoint.prototype.setListeners = function(){
				// this.setSaveItemValue();
			}


			ReportItemEndpoint.prototype.validateNotificationListener = function(){
				$('body').on('click','a[name="btn-save-report"]', function(){
						    if ($("#classeDestination").val() == 0) {
			                    reportItemEndpoint.feedbackHelper.showMessageWithPrompt("Avertissement", "La valeur de la classe de destination ne peut pas être nulle", "warning");
						    }else{
						        var feedbackMessage = JSON.parse(JSON.stringify({
						            'title' : 'Validation de l\'inscription',
						            'message' : 'En confirmant, vous certifiez que le transfert des élèves est effective, voulez vous continuer?',
						            'type' : 'warning',
						            'confirmeButtonText' : 'Je le confirme',
						            'callback' : reportItemEndpoint.setStudentTransfert
						        }));

						        reportItemEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, feedbackMessage.message, feedbackMessage.type, feedbackMessage.confirmeButtonText, reportItemEndpoint.setStudentTransfert);
						}
						    });

						}


			ReportItemEndpoint.prototype.setSaveItemValue = function(){
					var data = []; //Initialisation du tableau
					var compteur = 0;//Compteur permettant de determiner les indices du tableau
					$("#items tbody tr").each(function() {
						var element = {};
						element["dateDebut"] = $('input[name="start"]').val();
						element["dateFin"] = $('input[name="end"]').val();
						element["idItem"] = $(this).find("td:eq(2) input").attr('id-item');
						element["value"] = $(this).find("td:eq(2) input").val();
						data[compteur] = element;
						compteur = compteur + 1; //incrementation du compteur
					});
						var dataJSON = JSON.stringify(data);
						console.log(data);

						$.ajax({
							method 		: "POST",
							data 		: {data : data},
							url 		: URL_ROOT + "/reportitem/items/save", 
							dataType 	: "JSON", 
							beforeSend:  function(){
								$('#transfer').LoadingOverlay('show');
							},
							success 	: function(returnedData){
								console.log(returnedData);
								if (returnedData.success) {
                            	reportItemEndpoint.feedbackHelper.showAutoCloseMessage("Information de confirmation", "Transfert effectué avec succès", "success", 10000);
                            	location.reload();
								}else{
                            		reportItemEndpoint.feedbackHelper.showMessageWithPrompt("Avertissement", returnedData.message, "warning");
								}
							},
							error : function(returnedData){
								console.error(returnedData);
                            	reportItemEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "Erreure lors de l'évaluation de la requête, Contactez votre administrateur! si le problème persiste", "error");
							},
							complete: function(returnedData){
								$('#transfer').LoadingOverlay('hide');
							}
						});
			}

			//this should be at the end
			reportItemEndpoint.initializeView();
			reportItemEndpoint.setListeners();
	});
});