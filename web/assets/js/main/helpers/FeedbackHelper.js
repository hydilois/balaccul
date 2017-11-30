var that; 

var FeedbackHelper = function(){


}

/**
 * show messages that only dissapear with a prompt 
 * @param  {string} title   
 * @param  {string} message 	
 * @param  {string} type    	can either be {success | error | warning}
 * @return {void}         
 */
FeedbackHelper.prototype.showMessageWithPrompt = function(title, message, type){

	swal({
	  title: title,
	  text: message,
	  type: type,
	  closeOnConfirm: false
	});

}
/**
 * show messages that only dissapear with a prompt 
 * @param  {string} title   
 * @param  {string} message 	
 * @param  {string} type    	can either be {success | errer | warning}
 * @param  {} countdown [time in milli seconds to make the alert dissapear]
 * @return {void}         
 */
FeedbackHelper.prototype.showAutoCloseMessage = function(title, message, type, countdown){
	swal({
	  title: title,
	  text: message,
	  type: type,
	  closeOnConfirm: false, 
	  timer : countdown
	});

}

/**
 * show messages that only dissapear with a prompt 
 * @param  	{string} title   
 * @param  	{string} message 	
 * @param  	{string} type    	can either be {success | errer | warning}
 * @param  	{integer} countdown [time in milli seconds to make the alert dissapear]
 * @param 	{string} confirmButtonText the text to display
 * @param 	{function} callback 
 * @return {void}         
 */
FeedbackHelper.prototype.showConfirmMessage = function(title, message, type, confirmButtonText, callback){
	swal({
			title: title,
			text: message,
			type: type,
			showCancelButton: true,
			confirmButtonColor: '#7266ba',
			cancelButtonColor: '#d33',
			confirmButtonText: confirmButtonText,
			closeOnConfirm: false
		}, function(){
			callback();
		}
		
	)
}

/**
 * show messages that only dissapear with a prompt 
 * @param  	{string} title   
 * @param  	{string} message 	
 * @param  	{string} type    	can either be {success | errer | warning}
 * @param  	{integer} countdown [time in milli seconds to make the alert dissapear]
 * @param 	{string} confirmButtonText the text to display
 * @param 	{function} callback 
 * @return {void}         
 */
FeedbackHelper.prototype.showLoaderMessage = function(title, message, type, confirmButtonText, callback){
	swal({
			title: title,
			text: message,
			type: type,
			confirmButtonColor: '#7266ba',
			cancelButtonColor: '#d33',
			showCancelButton: true,
			closeOnConfirm: false,
			showLoaderOnConfirm: true,
		}, function(isConfirm){
			if(isConfirm)
				callback();
			else
				console.log("implement the revert method like uncheck the radio button")
		}
		
	)
}

/**
 * gets value from the prompt and performs actions according to it 
 * @param  	{string} title   
 * @param  	{string} message 	
 * @param  	{string} type    	can either be {success | errer | warning}
 * @param  	{integer} countdown [time in milli seconds to make the alert dissapear]
 * @param 	{string} confirmButtonText the text to display
 * @param 	{function} callback 
 * @return {void}         
 */
FeedbackHelper.prototype.showMessageWithInput = function(title, message, confirmButtonText, callback){
	swal({
		title: title,
		text: message,
		type: 'input',
		confirmButtonColor: '#7266ba',
		cancelButtonColor: '#d33',
		showCancelButton: true,
		closeOnConfirm: false,
		showLoaderOnConfirm: true,
		animation: "slide-from-bottom",
  		inputPlaceholder: "Nom du Document requis"
	}, function(inputValue){
		  if (inputValue === false) return false;
		  
		  if (inputValue === "") {
		    swal.showInputError("Vous devez remplir le champ obligatoire!");
		    return false;
		  }
		  
		  callback(inputValue);
		}
		
	)
}


