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

function Classe(){
	this.id = null;
	this.name = null;
	this.classCategory = null;
	this.description = null;
	
	this.attributesArray = {};//has to be serialized so we have this form

		return classe;
}

var classe = new Classe();

Classe.prototype.getValuesFromForm = function(){

	this.name  				= $("#classbundle_classe_name").val();
	this.description  		= $("#classbundle_classe_description").val();
	this.classCategory		= $("select#classbundle_classe_classCategory").val();
}

/**
 * Returns a JSON representation of the object Classe
 * @return {JSON}
 */
Classe.prototype.getJSONValues = function(){

	this.getValuesFromForm();

	this.attributesArray["name"]				= this.name;
	this.attributesArray["description"]		= this.description;
	this.attributesArray["classCategory"]		= this.classCategory;

	return JSON.parse(JSON.stringify(this.attributesArray));
}