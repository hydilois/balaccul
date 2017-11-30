
// 
//@author : Hydil Aicard Sokeing for GreenSoftTeam
//@creationDate : 06/07/2017


$(function() {

    var APP_ROOT;

    (window.location.pathname.match(/(.*?)web/i)) ? (APP_ROOT = window.location.pathname.match(/(.*?)web/i)[1]) : (APP_ROOT = "");
    (APP_ROOT) ? (APP_ROOT += "web") : (APP_ROOT);

    head.load([
        APP_ROOT + "/assets/js/main/helpers/FeedbackHelper.js",
    ], function() {
        /**
         * constructor
         */
        function UtilisateurEndpoint() {
            this.feedbackHelper = new FeedbackHelper();
        }

        var utilisateurEndpoint = new UtilisateurEndpoint();

        /**
         * allow to initialize the view
         * @return {void} nothing
         */
        UtilisateurEndpoint.prototype.initializeView = function() {
            $('#datatable-responsive').DataTable();
            TableManageButtons.init();
        }


        /**
         * allow to set a whole bunch of listeners
         */
        UtilisateurEndpoint.prototype.setListeners = function() {
            this.setShowUtilisateurListener();
        }

        /**
         * listen to show classe button, this will show elements on a modal window
         * @return {void} nothing
         */
        UtilisateurEndpoint.prototype.setShowUtilisateurListener = function() {
            $('a[name="show-utilisateur"').on('click', function() {
                console.log("Show user");

                var that = this;

                var data = JSON.parse(JSON.stringify({
                    "idUtilisateur": $(this).data('utilisateur')
                }));

                $.ajax({
                    method: "POST",
                    data: { data: data },
                    url: "../utilisateur/getUtilisateur",
                    dataType: "JSON",
                    beforeSend: function() {
                        $(that).parent().parent().parent().parent().parent().LoadingOverlay('show');
                    },
                    success: function(returnedData) {
                        data = returnedData;
                        console.log(returnedData);
                        if (data.status == "success") {
                            $("#idUtilisateur").text(data.data.id);
                            $("#nomUtilisateur").text(data.data.nom);
                            $("#loginUser").text(data.data.username);
                            $("#emailUtilisateur").text(data.data.email);
                            $("#numeroTelephone").text(data.data.numeroTelephone);
                            $('.modal-show-utilisateur-details').modal('show');

                        } else {
                            console.log("Message d'erreur Impossible de recuperer la classe en question");
                            utilisateurEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "un problème est survenu pendant la soumission de votre requête si le problème persiste, contactez votre administrateur", "error")
                        }
                    },
                    error: function(returnedData) {
                        console.error(returnedData);
                        utilisateurEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "un problème est survenu pendant la soumission de votre requête si le problème persiste, contactez votre administrateur", "error");
                    }
                    ,
                    complete: function() {
                        $(that).parent().parent().parent().parent().parent().LoadingOverlay('hide');
                    }
                });

            });
        }

        /**
         * listen to show classe button, this will show elements on a modal window
         * @return {void} nothing
         */
        UtilisateurEndpoint.prototype.setUserEnableListener = function() {
            $('a[name="enable-utilisateur"').on('click', function() {
                console.log("enable user");

                var that = this;

                var data = JSON.parse(JSON.stringify({
                    "idUtilisateur": $(this).data('utilisateur')
                }));
                console.log(data);

                $.ajax({
                    method: "POST",
                    data: { data: data },
                    url: "../utilisateur/getUtilisateur",
                    dataType: "JSON",
                    beforeSend: function() {
                        $(that).parent().parent().parent().parent().parent().LoadingOverlay('show');
                    },
                    success: function(returnedData) {
                        data = returnedData;
                        console.log(returnedData);
                        if (data.status == "success") {
                            $("#idUtilisateur").text(data.data.id);
                            $("#nomUtilisateur").text(data.data.nom);
                            $("#loginUser").text(data.data.username);
                            $("#emailUtilisateur").text(data.data.email);
                            $("#numeroTelephone").text(data.data.numeroTelephone);
                            $('.modal-show-utilisateur-details').modal('show');

                        } else {
                            console.log("Message d'erreur Impossible de recuperer la classe en question");
                            utilisateurEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "un problème est survenu pendant la soumission de votre requête si le problème persiste, contactez votre administrateur", "error")
                        }
                    },
                    error: function(returnedData) {
                        console.error(returnedData);
                        utilisateurEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "un problème est survenu pendant la soumission de votre requête si le problème persiste, contactez votre administrateur", "error");
                    }
                    ,
                    complete: function() {
                        $(that).parent().parent().parent().parent().parent().LoadingOverlay('hide');
                    }
                });

            });
        }
        //this should be at the end
        utilisateurEndpoint.initializeView();
        utilisateurEndpoint.setListeners();
    });
});
