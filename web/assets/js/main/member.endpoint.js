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
        APP_ROOT + "/assets/js/main/models/Member.model.js",
    ], function() {
        /**
         * constructor
         */
        function MemberEndpoint() {

            this.feedbackHelper = new FeedbackHelper();

            this.member               = new Member();

            this.beneficiaryNumber = 0;
            
        }

        var memberEndpoint = new MemberEndpoint();

        /**
         * allow to initialize the view
         * @return {void} nothing
         */
        MemberEndpoint.prototype.initializeView = function() {
            console.log("Hello Member");

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
            this.setCreateFormModalShow();
            this.setEditFormModalShow();
            this.validateCloseAccountListener();
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
                    memberEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "You have to add at least one beneficiary", "error");
                    // alert('You have to add at least one beneficiary');
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
                        url         : URL_ROOT+"/member/new_json", 
                        dataType    : "JSON", 
                        success     : function(returnedData){
                            returnedData = JSON.parse(returnedData);
                            console.log(returnedData);
                            if(returnedData.status == "success"){
                                memberEndpoint.feedbackHelper.showAutoCloseMessage("Member Registred", "The member is registred successfully", "success", 3000);
                                window.location.href = URL_ROOT + "/member";
                            }else{
                                memberEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", returnedData.message, "error")
                            }
                        }, 
                        error : function(returnedData){
                            console.error(returnedData);
                            memberEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "A problem occur during the request, please contact the administrator", "error");
                        }
                    });

        }


        MemberEndpoint.prototype.setCreateFormModalShow = function() {
            $('body').on('click','a[name="add-beneficiary"]', function() {
                id = $(this).data('value');
                $('#beneficiary-form').load(URL_ROOT+'/member/beneficiary/'+id+'/new', function(){
                    $('.modal-add-beneficiary').modal('show');
                });
            })
        }

        MemberEndpoint.prototype.validateCloseAccountListener = function(){
            $('body').on('click', 'a[name="account-status"]', function(){

                var feedbackMessage = JSON.parse(JSON.stringify({
                    'title' : 'Validation of the operation',
                    'message' : 'Confirm, You agree that you want to close the account ?',
                    'type' : 'warning',
                    'confirmeButtonText' : 'Yes I confirm',
                    'callback' : memberEndpoint.setCloseAccount
                }));

                memberEndpoint.feedbackHelper.showLoaderMessage(feedbackMessage.title, feedbackMessage.message, feedbackMessage.type, feedbackMessage.confirmeButtonText, memberEndpoint.setCloseAccount);
            });

        }

        MemberEndpoint.prototype.setCloseAccount = function() {
            // $('body').on('click','a[name="close-account"]', function() {
                var data = JSON.parse(JSON.stringify({
                        "idMember" : $('a.edit-btn').data('member'),
                        "choice" : $('a.member').data('choice')
                    }));
                console.log(data);

                // $.ajax({
                //     method      : "POST", 
                //     data        : {data : data},
                //     url         : URL_ROOT+"/member/close", 
                //     dataType    : "JSON", 
                //     success     : function(returnedData){
                //         returnedData = JSON.parse(returnedData);
                //         console.log(returnedData);
                //         if(returnedData.status == "success"){
                //             memberEndpoint.feedbackHelper.showAutoCloseMessage("Member Registred", "The member is registred successfully", "success", 3000);
                //             window.location.href = URL_ROOT + "/member";
                //         }else{
                //             memberEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", returnedData.message, "error")
                //         }
                //     }, 
                //     error : function(returnedData){
                //         console.error(returnedData);
                //         memberEndpoint.feedbackHelper.showMessageWithPrompt("Sorry", "A problem occur during the request, please contact the administrator", "error");
                //     }
                // });
            // });
        }



        MemberEndpoint.prototype.setEditFormModalShow = function() {
            $('body').on('click','a[name="beneficiary-edit"]', function() {
                console.log("Je sui déja la");
                val = $(this).data('beneficiary');

                $('#beneficiary-editform').load(URL_ROOT+'/beneficiary/'+val+'/edit', function(){
                    $('.modal-edit-beneficiary').modal('show');
                });
            })
        }

        //this should be at the end
        memberEndpoint.initializeView();
        memberEndpoint.setListeners();
        memberEndpoint.postActions();


    });
});
