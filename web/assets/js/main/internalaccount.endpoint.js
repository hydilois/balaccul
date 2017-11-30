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
         APP_ROOT + "/assets/js/main/models/InternalAccount.model.js",
    ], function() {
        /**
         * constructor
         */
        function InternalAccountEndpoint() {

            this.feedbackHelper = new FeedbackHelper();

            this.internalAccount               = new InternalAccount();
            
        }

        var internalAccountEndpoint = new InternalAccountEndpoint();

        /**
         * allow to initialize the view
         * @return {void} nothing
         */
        InternalAccountEndpoint.prototype.initializeView = function() {
            console.log("Hello credit union Interna account");
            $('#datatable-responsive').DataTable();
            TableManageButtons.init();
        }

        /**
         * gets executed for all actions that need to be performed 
         * at the end of the process
         * @return {void} 
        */
        InternalAccountEndpoint.prototype.postActions = function(){

        }

        /**
         * allow to set a whole bunch of listeners
         */
        InternalAccountEndpoint.prototype.setListeners = function() {
            this.setRegisterInternalAccount();
            this.setShowInternalAccountForm();
            this.setClasseSelectorListener();
        }


        InternalAccountEndpoint.prototype.setShowInternalAccountForm = function(){
            $('a[name="btn-IA-create"]').on('click', function(){
                $('.modal-set-IA-create').modal('show');
            });
        }

        InternalAccountEndpoint.prototype.setClasseSelectorListener = function(){
            $('body').on('change', '#classbundle_internalaccount_classe', function(){
                console.log("Here stand Classe selected");

                var data = JSON.parse(JSON.stringify({"idClasse" : $(this).val()}));
                
                if (!$(this).val()) {
                    internalAccountEndpoint.feedbackHelper.showMessageWithPrompt("Warning", "Please select a valid value of the class", "warning");
                }else{
                $.ajax({
                    method      : "POST", 
                    data        : {data : data},
                    url         : URL_ROOT + "/internalaccount/subclass/list", 
                    dataType    : "JSON",
                    beforeSend      : function(){
                        $('#classbundle_internalaccount_classe').LoadingOverlay('show');

                        $("div#zoneSubClasse div.subClasse_container").find('.col-md-6.col-sm-6').remove();
                    },
                    success     : function(returnedData){
                        console.log(returnedData);
                        if (returnedData.data.length == 0) {
                            $subClassForm = $("div.template").clone().removeClass('template');
                                $subClassForm.find('.form-group').html("");
                                $zone = $("select.selecteur-zone").clone().removeClass('selecteur-zone').addClass('new-selecteur');
                                $zone.attr('id','classe');
                                $zone.append('<option value="">No Sub Class Found</option>');
                                $subClassForm.find('.form-group').append('<label value="">Sub Class</label>');
                                $subClassForm.find('.form-group').append($zone);
                                $subClassForm.appendTo("div#zoneSubClasse div.subClasse_container").slideDown("slow");
                        }else{
                                $subClassForm = $("div.template").clone().removeClass('template');
                                $subClassForm.find('.form-group').html("");
                                $zone = $("select.selecteur-zone").clone().removeClass('selecteur-zone').addClass('new-selecteur');
                                $zone.attr('id','classe');
                                returnedData.data.forEach( function(element, index) {
                                    console.log(element.name);
                                $zone.append('<option value="'+ element.id+'">'+ element.name +'</option>');
                            });

                            $subClassForm.find('.form-group').append('<label value="">Sub Class</label>');
                            $subClassForm.find('.form-group').append($zone);
                            $subClassForm.appendTo("div#zoneSubClasse div.subClasse_container").slideDown("slow");

                        }
                    }, 
                    error : function(returnedData){
                        internalAccountEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "A problem occur during the request, please contact the administrator", "error");
                    }, 
                    complete    : function(){
                        $('#classbundle_internalaccount_classe').LoadingOverlay('hide');
                    }
                });
            }
            });
        }


        InternalAccountEndpoint.prototype.setRegisterInternalAccount = function(){
            $('a[name="save-account"]').on('click', function(){
                var that = this;
                var data = internalAccountEndpoint.internalAccount.getJSONValues();
                console.log(data);

                $.ajax({
                    method      : "POST", 
                    data        : {data : data},
                    url         : URL_ROOT + "/internalaccount/new_json",
                    dataType    : "JSON",
                    beforeSend  : function(){
                        $(that).parent().parent().parent().parent().LoadingOverlay('show');
                    },
                    success     : function(data){
                        console.log(data);
                        location.reload();
                    }, 
                    error : function(returnedData){
                        internalAccountEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "un problème est survenu pendant la soumission de votre requête si le problème persiste, contactez votre administrateur", "error");

                    },
                });
            });
        }

        //this should be at the end
        internalAccountEndpoint.initializeView();
        internalAccountEndpoint.setListeners();
        internalAccountEndpoint.postActions();


    });
});