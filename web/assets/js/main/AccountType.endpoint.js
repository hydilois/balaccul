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
        function AccountTypeEndpoint() {
            
        }

        var accountTypeEndpoint = new AccountTypeEndpoint();

        /**
         * allow to initialize the view
         * @return {void} nothing
         */
        AccountTypeEndpoint.prototype.initializeView = function() {
            console.log("Hello credit union AccountType.endpoint.js");
            $('#datatable-responsive').DataTable();
            TableManageButtons.init();
        }


        /**
         * allow to set a whole bunch of listeners
         */
        AccountTypeEndpoint.prototype.setListeners = function() {
            this.setCreateFormModalShow();
            this.setEditFormModalShow();
        }



        AccountTypeEndpoint.prototype.setCreateFormModalShow = function() {
            $('body').on('click','a[name="add-account"]', function() {
                var that  = this;
                $(that).parent().parent().LoadingOverlay('show');
                
                $('#account-form').load(URL_ROOT+'/accounttype/new', function(){
                    $('.modal-add-accounttype').modal('show');
                    $(that).parent().parent().LoadingOverlay('hide');
                });
            })
        }

        AccountTypeEndpoint.prototype.setEditFormModalShow = function() {
            $('body').on('click','a[name="accounttype-edit"]', function() {
                val = $(this).data('account');

                $('#account-editform').load(URL_ROOT+'/accounttype/'+val+'/edit', function(){
                    $('.modal-edit-account').modal('show');
                });
            })
        }

        //this should be at the end
        accountTypeEndpoint.initializeView();
        accountTypeEndpoint.setListeners();


    });
});