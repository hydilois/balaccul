                        
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
		],
		function(){

			function BaseEndpoint(){
				this.feedbackHelper  = new FeedbackHelper();
			}

			BaseEndpoint.prototype.initializeView = function(){
				console.log("Here stand the base Endpoint file for the whole application");
			}

			BaseEndpoint.prototype.setListerners = function(){
				this.setAlertListener();
				this.validateDatabaseDumpListener();
			}

			BaseEndpoint.prototype.postActions = function(){

			}

			//customize thz date picker
			BaseEndpoint.prototype.setupDatePicker = function(){
				console.log("Here the Date picker setup");

				webshims.setOptions('forms-ext', {types: 'date'});
				webshims.polyfill('forms forms-ext');
			}

			BaseEndpoint.prototype.setAlertListener = function(){
				this.setAlert();
				setInterval(this.setAlert, 3600000);
			}

			/**
			 * Sets the alerts for the whole application
			 * the function will perform the following
			 * - check the role of the user connected then set paths
			 * according to the user connected
			 */
			BaseEndpoint.prototype.setAlert = function(){

				var data = {};
				
				$.ajax({
					method 		: "POST", 
					data 		: {data : data},
					url 		: URL_ROOT + "/alert/list", 
					dataType 	: "JSON",
					beforeSend		: function(){
					},
					success 	: function(returnedData){
						returnedDataParsed = JSON.parse(returnedData);
						console.log(returnedDataParsed);
						data = returnedDataParsed.data;
						$('#numberNotif').text(data.length);

						var notificationMenu = $('.dropdown-menu-lg .notification-list');

						for(var i in data){

							var singleObject = data[i];
								var liElement = $('.dropdown-menu-lg .notification-list a.template').clone();
								liElement.find('.media-heading').text(singleObject.l_loanCode);
								liElement.find('.detail').text("At least the first installement and interest are not paid");
								liElement.removeClass('template');
								liElement.removeClass('hide');
								liElement.attr("href", URL_ROOT+"/loan/"+singleObject.l_id);
								liElement.prependTo(notificationMenu);

						}

					}, 
					error : function(returnedData){
						console.log(returnedData);
					}, 
					complete 	: function(){

					}
				});
			}

			BaseEndpoint.prototype.validateDatabaseDumpListener = function(){
				$('body').on('click', '#btn-dump', function(){
			        var feedbackMessage = JSON.parse(JSON.stringify({
			            'title' : 'System backup',
			            'message' : 'You agree that you want to make a backup of the system ?',
			            'type' : 'warning',
			            'confirmeButtonText' : 'Yes I confirm',
			            'callback' : baseEndpoint.databaseDump
			        }));

			        baseEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, feedbackMessage.message, feedbackMessage.type, feedbackMessage.confirmeButtonText, baseEndpoint.databaseDump);
			    });

			}

			BaseEndpoint.prototype.databaseDump = function(){
				var data = JSON.parse(JSON.stringify({
				                "info" : "backup of the database",
				                }));
			        $.ajax({
			            method      : "POST", 
			            data        : {data : data},
			            url         : URL_ROOT + "/dump",
			            dataType    : "JSON",
			            beforeSend  : function(){
			            },
			            success     : function(data){
			                returnedData = JSON.parse(data);
			                console.log(returnedData);
			                baseEndpoint.feedbackHelper.showMessageWithPrompt("System Backup", "The Backup of the system has been done succesfully", "success");
			            }, 
			            error : function(){
			                baseEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "A problem occur during the request, please contact the administrator", "error");
			            },
			        });
			    }

			var baseEndpoint = new BaseEndpoint();
			baseEndpoint.initializeView();
			baseEndpoint.setListerners();
			baseEndpoint.postActions();
		}
	);


});