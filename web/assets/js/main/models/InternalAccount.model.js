//                      _      _     _
//  _ __ ___   ___   __| | ___| |   (_)___
// | '_ ` _ \ / _ \ / _` |/ _ \ |   | / __|
// | | | | | | (_) | (_| |  __/ |   | \__ \
// |_| |_| |_|\___/ \__,_|\___|_|  _/ |___/
//                                |__/
//                                
//@author : Hydil Aicard Sokeing for GreenSoft-Team
//@creationDate : 14/11/2017
// @Modified Date: 

function InternalAccount(){
	this.id = null;
	this.name = null;
	this.accountNumber = null;
	this.classe = null;
	this.amount = null;
	this.description = null;
	this.type = null;
	
	this.attributesArray = {};//has to be serialized so we have this form

		return internalAccount;
}

var internalAccount = new InternalAccount();

InternalAccount.prototype.getValuesFromForm = function(){

	this.name  				= $("#classbundle_internalaccount_name").val();
	this.accountNumber  	= $("#classbundle_internalaccount_accountNumber").val();
	this.description  		= $("#classbundle_internalaccount_description").val();
	this.amount  			= $("#classbundle_internalaccount_amount").val();
	if ($("#classe").val() != ""){
		this.classe			= $("#classe").val();
	}else{
		this.classe			= $("select#classbundle_internalaccount_classe").val();
	}
	this.type			= $("select#classbundle_internalaccount_type").val();
}

/**
 * Returns a JSON representation of the object InternalAccount
 * @return {JSON}
 */
InternalAccount.prototype.getJSONValues = function(){

	this.getValuesFromForm();

	this.attributesArray["name"]				= this.name;
	this.attributesArray["accountNumber"]				= this.accountNumber;
	this.attributesArray["description"]		= this.description;
	this.attributesArray["amount"]		= this.amount;
	this.attributesArray["classe"]		= this.classe;
	this.attributesArray["type"]		= this.type;

	return JSON.parse(JSON.stringify(this.attributesArray));
}