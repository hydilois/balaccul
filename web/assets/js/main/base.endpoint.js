                        
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

			BaseEndpoint.prototype.alertsArray = [];

			// we set up the booket listener

			BaseEndpoint.prototype.initializeView = function(){
				console.log("Here stand the base Endpoint file for the whole application");
			}

			BaseEndpoint.prototype.setListerners = function(){
				this.setAlertListener();
				this.showNotificationsDetails();
				// this.setupDatePicker();
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
				// setInterval(this.setAlert, 20000);
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
						// mvtdata = returnedDataParsed.mouvementdata;

						$('#numberNotif').text(data.length);

						var notificationMenu = $('.dropdown-menu-lg .notification-list');

						for(var i in data){

							var singleObject = data[i];
							// we only happend if not in the array otherwise it gets redundant
							// if( -1 == $.inArray(singleObject.nh_id, baseEndpoint.alertsArray)){

								var liElement = $('.dropdown-menu-lg .notification-list a.template').clone();
								liElement.find('.media-heading').text(singleObject.l_loanCode);
								liElement.find('.detail').text("At least the first installement and interest are not paid");
								liElement.removeClass('template');
								liElement.removeClass('hide');
								liElement.attr("href", URL_ROOT+"/loan/"+singleObject.l_id);
								// liElement.find('a').attr("alert-type", "notification");
								liElement.prependTo(notificationMenu);
								// liElement.fadeIn('slow');
								
								// baseEndpoint.alertsArray.push(singleObject.nh_id);
							// }
						}

						// for(var i in mvtdata){

						// 	var singleObject = mvtdata[i];
						// 	// we only happend if not in the array otherwise it gets redundant
						// 	if( -1 == $.inArray(singleObject.mh_id, baseEndpoint.alertsArray)){

						// 		var liElement = $('.notifications-menu .dropdown-menu li.template').clone();
						// 		liElement.find('.notification-message').text(singleObject.mh_type + " par " + singleObject.u_nom +  " avec le role: " + singleObject.g_nom.toLowerCase());
						// 		liElement.removeClass('template');
						// 		liElement.find('a').attr("data-alert", singleObject.mh_id);
						// 		liElement.find('a').attr("alert-type", "mouvement");
						// 		liElement.prependTo(notificationMenu);
						// 		liElement.fadeIn('slow');
								
						// 		baseEndpoint.alertsArray.push(singleObject.mh_id);
						// 	}
						// }

					}, 
					error : function(returnedData){
						console.log(returnedData);
					}, 
					complete 	: function(){

					}
				});
			}

			BaseEndpoint.prototype.showNotificationsDetails = function(){
				$('body').on('click', 'a[name="notification-link"]', function(){
					var that = this;
					var data = JSON.parse(JSON.stringify({
						"idAlert"   : $(this).data('alert'),
						"alertType" : $(this).attr('alert-type')
					}));
					if ($(this).attr('alert-type') == "mouvement") {
						console.log("Mouvement");
						$.ajax({
							method		: "POST",
							data 		: {data: data},
							url 		: URL_ROOT+'/mouvement/getHistory',
							dataType 	: "JSON",
							beforeSend 	: function(){
								$('.navbar-static-top').LoadingOverlay('show');
							},
							success: function(returnedData){
								console.log(returnedData);
								data = returnedData;
								$('.navbar-static-top').LoadingOverlay('hide');
								$('#information p').text(data.data.type +" par "+data.data.user_id.nom+" "+data.data.user_id.prenom+" du groupe"+data.data.user_id.groupe.nom);
								$('.modal-alert-show').modal('show');
							},
							error: function(returnedData){
								console.log(returnedData);
								$('.navbar-static-top').LoadingOverlay('hide');
							},
							complete: function(){
								console.log("Fin");
								$('.navbar-static-top').LoadingOverlay('hide');
							}
						});
					}else{
						console.log("Notification");
						$.ajax({
								method		: "POST",
								data 		: {data: data},
								url 		: URL_ROOT+'/notification/getHistory',
								dataType 	: "JSON",
								beforeSend 	: function(){
									$('.navbar-static-top').LoadingOverlay('show');
								},
								success: function(returnedData){
									console.log(returnedData);
									data = returnedData;
									$('#information p').text(data.data.type +" par "+data.data.user_id.nom+" "+data.data.user_id.prenom+" du groupe"+data.data.user_id.groupe.nom+" pour la notification de réference "+data.data.notification_id.reference);
									$('.modal-alert-show').modal('show');
									$('.navbar-static-top').LoadingOverlay('hide');
								},
								error: function(returnedData){
									console.log(returnedData);
									$('.navbar-static-top').LoadingOverlay('hide');
								},
								complete: function(){
									console.log("Fin");
									$('.navbar-static-top').LoadingOverlay('hide');
								}
							});
					}

				});
			}

			var baseEndpoint = new BaseEndpoint();
			baseEndpoint.initializeView();
			baseEndpoint.setListerners();
			baseEndpoint.postActions();
		}
	);


});