//                      _      _     _
//  _ __ ___   ___   __| | ___| |   (_)___
// | '_ ` _ \ / _ \ / _` |/ _ \ |   | / __|
// | | | | | | (_) | (_| |  __/ |   | \__ \
// |_| |_| |_|\___/ \__,_|\___|_|  _/ |___/
//                                |__/
//                                
//@author : Hydil Aicard Sokeing
//@creationDate : 12/11/2017


function Member(){
	this.id = null;
	this.name = null;
	this.sex = null;
	this.dateOfBirth = null;
	this.placeOfBirth = null;
	this.occupation = null;
	this.address = null;
	this.nicNumber = null;//the country ID that holds the reference
	this.issuedOn = null;
	this.issuedAt = null;
	this.proposedBy = null;
	this.isAproved = null;
	this.aprovedBy = null;
	this.memberNumber = null;
	this.doneAt = null;
	this.membershipDateCreation = null;
	this.witnessName = null;
	this.phoneNumber = null;
	this.registrationFees = null;
	this.share = null;
	this.saving = null;
	this.deposit = null;
	this.buildingFees = null;
	this.beneficiary = {};

	this.attributesArray = {};//has to be serialized so we have this form

		return member;
}

var member = new Member();

Member.prototype.getValuesFromForm = function(){

	this.name  						= $("#memberbundle_member_name").val();
	this.sex 						= $("select#memberbundle_member_sex").val();
	this.dateOfBirth 				= $("#memberbundle_member_dateOfBirth").val();
	this.placeOfBirth 				= $("#memberbundle_member_placeOfBirth").val();
	this.occupation 				= $("#memberbundle_member_occupation").val();
	this.address 					= $("#memberbundle_member_address").val();
	this.nicNumber					= $("#memberbundle_member_nicNumber").val();
	this.issuedOn 					= $("#memberbundle_member_issuedOn").val();
	this.issuedAt 					= $("#memberbundle_member_issuedAt").val();
	this.proposedBy 				= $("#memberbundle_member_proposedBy").val();
	this.isAproved 					= $("select#memberbundle_member_isAproved").val();
	this.aprovedBy 					= $("#memberbundle_member_aprovedBy").val();
	this.memberNumber 				= $("#memberbundle_member_memberNumber").val();
	this.doneAt 					= $("#memberbundle_member_doneAt").val();
	this.membershipDateCreation 	= $("#memberbundle_member_membershipDateCreation").val();
	this.witnessName 				= $("#memberbundle_member_witnessName").val();
	this.phoneNumber 				= $("#memberbundle_member_phoneNumber").val();
	this.registrationFees 			= $("#memberbundle_member_registrationFees").val();
	this.share 						= $("#memberbundle_member_share").val();
	this.saving 					= $("#memberbundle_member_saving").val();
	this.deposit 					= $("#memberbundle_member_deposit").val();
	this.buildingFees 				= $("#memberbundle_member_buildingFees").val();


	//get the beneficiary values
	$('.beneficiary_form').not('.template').each(function(index, value){
		var element = {};
		element['name'] 	= $(this).find("#memberbundle_beneficiary_name").val();
		element['relation'] 	= $(this).find("#memberbundle_beneficiary_relation").val();
		element['ratio'] 	= $(this).find("#memberbundle_beneficiary_ratio").val();
		member.beneficiary[index] = element;
	});
}


/**
 * Returns a JSON representation of the object Member
 * @return {JSON}
 */
Member.prototype.getJSONValues = function(){

	this.getValuesFromForm();

	this.attributesArray["name"]						= this.name;
	this.attributesArray["sex"]							= this.sex;
	this.attributesArray["dateOfBirth"] 				= this.dateOfBirth;
	this.attributesArray["placeOfBirth"] 				= this.placeOfBirth;
	this.attributesArray["occupation"]					= this.occupation;
	this.attributesArray["address"]						= this.address;
	this.attributesArray["nicNumber"]					= this.nicNumber;
	this.attributesArray["issuedOn"]					= this.issuedOn;
	this.attributesArray["issuedAt"] 					= this.issuedAt;
	this.attributesArray["proposedBy"]					= this.proposedBy;
	this.attributesArray["isAproved"] 					= this.isAproved;
	this.attributesArray["aprovedBy"] 					= this.aprovedBy;
	this.attributesArray["memberNumber"]				= this.memberNumber;
	this.attributesArray["doneAt"]						= this.doneAt;
	this.attributesArray["membershipDateCreation"]		= this.membershipDateCreation;
	this.attributesArray["witnessName"]					= this.witnessName;
	this.attributesArray["phoneNumber"]					= this.phoneNumber;
	this.attributesArray["registrationFees"]			= this.registrationFees;
	this.attributesArray["beneficiary"]					= this.beneficiary;
	this.attributesArray["buildingFees"]				= this.buildingFees;
	this.attributesArray["share"]						= this.share;
	this.attributesArray["saving"]						= this.saving;
	this.attributesArray["deposit"]						= this.deposit;


	return JSON.parse(JSON.stringify(this.attributesArray));
}