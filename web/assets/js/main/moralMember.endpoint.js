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
        APP_ROOT + "/assets/js/main/models/MoralMember.model.js",
    ], function() {
        /**
         * constructor
         */
        function MemberEndpoint() {

            this.feedbackHelper = new FeedbackHelper();

            // this.validateFormHelper     = new ValidateFormsHelper();
            this.member               = new MoralMember();

            this.beneficiaryNumber = 0;
            
        }

        var memberEndpoint = new MemberEndpoint();

        /**
         * allow to initialize the view
         * @return {void} nothing
         */
        MemberEndpoint.prototype.initializeView = function() {
            console.log("Hello Moral Member");
            // this.validateFormHelper     = new ValidateFormsHelper();
            $('#datatable-responsive').DataTable();
            TableManageButtons.init();
        }

                /**
                 * gets executed for all actions that need to be performed 
                 * at the end of the process
                 * @return {void} 
                 */
                MemberEndpoint.prototype.postActions = function(){
                    
                    //by default we add a beneficiary form
                    $('#addBeneficiary').trigger('click');

                    }

        /**
         * allow to set a whole bunch of listeners
         */
        MemberEndpoint.prototype.setListeners = function() {
            this.setAddBeneficiaryListener();
            this.setRemoveBeneficiaryListener();
            this.validatRegisterMemberListener();
        }


        /**
         * listener of beneficiary
         */
        MemberEndpoint.prototype.setAddBeneficiaryListener = function(){
            console.log("Adding beneficiary");
            $('#addBeneficiary').on('click', function(){
                $mtmtForm = $("div.template.beneficiary_form").clone().removeClass('template');
                $mtmtForm.find('legend b').text(++(memberEndpoint.beneficiaryNumber));
                $mtmtForm.appendTo("div#beneficiary div div.beneficiary_form_container").slideDown("slow");
            });//end on click #addBeneficiary event
        }


        MemberEndpoint.prototype.setRemoveBeneficiaryListener = function(){
            $('#removeBeneficiary').on('click', function(){
                //check wheter or not we have reached the minimum requirement
                if(memberEndpoint.beneficiaryNumber > 1){
                    --memberEndpoint.beneficiaryNumber;
                    $("div.beneficiary_form").not('.template').last().remove();
                }else{
                    // alert('You have to add at least one beneficiary');
                    memberEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "You have to add at least one Representant", "error");
                }
            });//end on click #removeBenefciary event
        }


        MemberEndpoint.prototype.validatRegisterMemberListener = function(){
            $('#submit').on('click', function(event){
                event.preventDefault();
                console.log("test");

                var feedbackMessage = JSON.parse(JSON.stringify({
                    'title' : 'Confirmation of the informations',
                    'message' : 'You agree that the informations of the member are correct?',
                    'type' : 'warning',
                    'confirmeButtonText' : 'Yes I confirm',
                    'callback' : memberEndpoint.setRegisterMemberListener
                }));

                memberEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, feedbackMessage.message, feedbackMessage.type, feedbackMessage.confirmeButtonText, memberEndpoint.setRegisterMemberListener);
            });

        }

        MemberEndpoint.prototype.setRegisterMemberListener = function(){

                var data = memberEndpoint.member.getJSONValues();
                console.log(data);
                    
                    //We send an ajax requeset to register the mouvement object
                    $.ajax({
                        method      : "POST", 
                        data        : {data : data},
                        url         : URL_ROOT+"/moralmember/new_json", 
                        dataType    : "JSON", 
                        success     : function(returnedData){
                            returnedDataParsed = returnedData;
                            console.log(returnedData);
                            window.location.href = URL_ROOT + "/moralmember";
                        }, 
                        error : function(returnedData){
                            console.error(returnedData);
                            memberEndpoint.feedbackHelper.showMessageWithPrompt("Désolé", "un problème est survenu pendant la soumission de votre requête si le problème persiste, contactez votre administrateur", "error");
                        }
                    });
                //we should only get here if the form is valid

        }

        //this should be at the end
        memberEndpoint.initializeView();
        memberEndpoint.setListeners();
        memberEndpoint.postActions();

    });
});
