
/**
 * Class Helper will perform forms validation
 */

var that;
var ValidateFormsHelper = function(){

	//This will contain errors from the inputs
	//no forms will be submitted if there's an error
	this.errors = [];
	this.isFormValid = false;
	this.formElements = null;
	this.messages = [];

	that = this;

}



/**
 * Getter
 * @return {[boolean]} [whether or not the form is valid]
 */
ValidateFormsHelper.prototype.getIsFormValid = function(){

	return this.isFormValid;
}
/**
 * Getter
 * @return {[array]} All the messages that came from the forms validation
 */
ValidateFormsHelper.prototype.getMessages = function(){
	return this.messages;
}

/**
 * Get all the forms elements
 * @param  {string} jqueryformSelector string value of the form selector
 * @return {array}                    jqueryDomElements containing the elements to check in a form
 */
ValidateFormsHelper.prototype.getFormsFields = function(jqueryFormSelector){
	this.formElements = $(jqueryFormSelector + ' input[data-type="text"], input[data-type="email"], input[data-type="phone"], select, input[data-type="number"], input[data-type="date"]');
	return this.formElements;
}

/**
 * check wheter or not an email is valid
 * @param  {string}  emailAddress
 * @return {boolean}              true if the email is valid false if not
 */
ValidateFormsHelper.prototype.isValidEmailAdress = function(emailAddress){
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
};

/**
 * check wheter the field is a date or not
 * @param  {string}  dateString the string representation of the given date
 * @return {Boolean}  true if the string given is a date
 */
ValidateFormsHelper.prototype.isDate = function(dateString){
	var date = new Date(dateString);
    return !isNaN(date.valueOf());
}
/** 
 * TODO : change this an assynchrounous function
 * checks wheter or not the forms are valid
 * @return {undefined} does not return anything
 */
ValidateFormsHelper.prototype.checkFormFields = function(jqueryFormSelector){
	
	this.getFormsFields(jqueryFormSelector);
	this.isFormValid = true;
	this.messages = [];// we empty the message pipe
	$(".callout").hide();
	$(".callout-danger ol").html("");
	$(".callout-info ol").html("");
	var that = this;

	$.each(this.formElements, function(key, node){
		
		if($(node).parent().hasClass("has-error")){
				$(node).parent().removeClass("has-error");	
		}
		//this means we stumbled upon a text field that is empty
		if(($(node).data('type') == "text") && $(node).val().length == 0){

				$(node).parent().addClass("has-error");

				var li  = $('<li/>', {
						text : 'Le champ ' + $(node).attr("name") + ' ne peut etre vide'
					}
				).clone();

				li.appendTo(".callout-danger ol");

				that.isFormValid = false;

				$(".callout-danger").fadeIn();

		}
		else if( (($(node).data('type') == 'phone') || ($(node).data('type') == 'number')) && !$(node).val().match(/^\d+$/) ){
			$(node).parent().addClass("has-error");

				var li  = $('<li/>', {
						text : 'Le champ ' + $(node).attr("name") + ' ne ne doit contenir que des chiffres'
					}
				).clone();

				li.appendTo(".callout-danger ol");

				that.isFormValid = false;

				$(".callout-danger").fadeIn();
		}
		else if( ($(node).data('type') == 'email') && !that.isValidEmailAdress($(node).val())){
			$(node).parent().addClass("has-error");

			var li  = $('<li/>', {
					text : 'Le champ ' + $(node).attr("name") + ' ne ne doit contenir que des chiffres'
				}
			).clone();

			li.appendTo(".callout-danger ol");

			that.isFormValid = false;

			$(".callout-danger").fadeIn();
		}else if( ($(node).data('type') == 'date') && !that.isDate($(node).val()) ){
			$(node).parent().addClass("has-error");

			var li  = $('<li/>', {
					text : 'Le champ ' + $(node).attr("name") + ' doit être une date bien formatée'
				}
			).clone();

			li.appendTo(".callout-danger ol");

			that.isFormValid = false;

			$(".callout-danger").fadeIn();
		}

	});

	

}

console.log("ValidateFormHelper loaded");


