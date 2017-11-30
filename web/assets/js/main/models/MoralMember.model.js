//                      _      _     _
//  _ __ ___   ___   __| | ___| |   (_)___
// | '_ ` _ \ / _ \ / _` |/ _ \ |   | / __|
// | | | | | | (_) | (_| |  __/ |   | \__ \
// |_| |_| |_|\___/ \__,_|\___|_|  _/ |___/
//                                |__/
//                                
//@author : Hydil Aicard Sokeing
//@creationDate : 12/11/2017


function MoralMember(){
	this.id = null;
	this.socialReason = null;
	this.dateOfCreation = null;
	this.address = null;
	this.proposedBy = null;
	this.isAproved = null;
	this.aprovedBy = null;
	this.memberNumber = null;
	this.doneAt = null;
	this.membershipDateCreation = null;
	this.witnessName = null;
	this.phoneNumber = null;
	this.registrationFees = null;
	this.representants = {};

	this.attributesArray = {};//has to be serialized so we have this form

		return moralMember;
}

var moralMember = new MoralMember();

MoralMember.prototype.getValuesFromForm = function(){

	this.socialReason  				= $("#memberbundle_moralmember_socialReason").val();
	this.dateOfCreation 		= $("#memberbundle_moralmember_dateOfCreation").val();
	this.address 			= $("#memberbundle_moralmember_address").val();
	this.proposedBy 		= $("#memberbundle_moralmember_proposedBy").val();
	this.isAproved 			= $("input:radio[name='memberbundle_moralmember[isAproved]']:checked").val();
	this.aprovedBy 			= $("#memberbundle_moralmember_aprovedBy").val();
	this.memberNumber 		= $("#memberbundle_moralmember_memberNumber").val();
	this.doneAt 			= $("#memberbundle_moralmember_doneAt").val();
	this.membershipDateCreation 		= $("#memberbundle_moralmember_membershipDateCreation").val();
	this.witnessName 		= $("#memberbundle_moralmember_witnessName").val();
	this.phoneNumber 		= $("#memberbundle_moralmember_phoneNumber").val();
	this.registrationFees 		= $("#memberbundle_moralmember_registrationFees").val();


	//get the representant values
	$('.beneficiary_form').not('.template').each(function(index, value){
		var element = {};

		element['name'] 	= $(this).find("#memberbundle_representant_name").val();
		element['nicNumber'] 	= $(this).find("#memberbundle_representant_nicNumber").val();
		moralMember.representants[index] = element;
	});
}


/**
 * Returns a JSON representation of the object Member
 * @return {JSON}
 */
MoralMember.prototype.getJSONValues = function(){

	this.getValuesFromForm();

	this.attributesArray["socialReason"]						= this.socialReason;
	this.attributesArray["dateOfCreation"] 				= this.dateOfCreation;
	this.attributesArray["address"]						= this.address;
	this.attributesArray["proposedBy"]					= this.proposedBy;
	this.attributesArray["isAproved"] 					= this.isAproved;
	this.attributesArray["aprovedBy"] 					= this.aprovedBy;
	this.attributesArray["memberNumber"]				= this.memberNumber;
	this.attributesArray["doneAt"]						= this.doneAt;
	this.attributesArray["membershipDateCreation"]		= this.membershipDateCreation;
	this.attributesArray["witnessName"]					= this.witnessName;
	this.attributesArray["phoneNumber"]					= this.phoneNumber;
	this.attributesArray["registrationFees"]					= this.registrationFees;
	this.attributesArray["representants"]					= this.representants;


	return JSON.parse(JSON.stringify(this.attributesArray));
}