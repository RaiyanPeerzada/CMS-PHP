/*
 * Survey Form Validation: 
 * Handles the validation of form elements on the contact us.
 *
 * Script: formValidate.js
 * Author: Raiyan Peerzada
 * Version: 1.0
 * Date Created: 04.10.2018
 * Last Updated: 04.31.2018
 *
 */


/*
 * Removes white space from a string value.
 *
 * return  A string with leading and trailing white-space removed.
 */
function trim(str) 
{
	return str.replace(/^\s+|\s+$/g,"");
}

/*
 * Determines if a text field element has input
 *
 * param   fieldElement A text field input element object
 * return  True if the field contains input; False if nothing entered
 */
function formFieldHasInput(fieldElement)
{	
	if(fieldElement.value == null || trim(fieldElement.value) 
		== ""){
		return false;
	}

	return true;
}

/*
 * Handles the submit event of the order form
 *
 * param e  A reference to the event object
 * return   True if no validation errors; False if the form has
 *          validation errors
 */
function validate(e)
{
	hideErrors();

	if(formHasErrors()){

		e.preventDefault();
		return false;
	}

	return true;
}

/*
 * Handles the reset event for the form.
 *
 * param e  A reference to the event object
 * return   True allows the reset to happen; False prevents
 *          the browser from resetting the form.
 */
function resetForm(e)
{
	if ( confirm('Clear information?') )
	{
		hideErrors();

		document.getElementById("fname").focus();

		return true;
	}
	e.preventDefault();

	return false;	
}


/*
 * Determines if the email id is valid or not.
 *
 * return   True if the field contains valid input; False if input is not valid.
 */
function checkEmail(){

	var emailregex = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
	var email = document.getElementById("email");

	if(!(email.value == "")){

		if(!emailregex.test(email.value)){
			document.getElementById("emailformat_error").style.display
		 	= "block";	 
		 	
		 	return true;
		}
	}
	return false;
}

/*
 * Determines if the phone number is valid or not.
 *
 * return   True if the field contains valid input; False if input is not valid.
 */
function checkPhone(){

	var phoneregex = new RegExp(/^\d{10}$/);
	var phone = document.getElementById("phone");

	if(!(phone.value == "")){

		if(!phoneregex.test(phone.value)){
			document.getElementById("phoneformat_error").style.display
		 	= "block";	 
		 	
		 	return true;
		}
	}
	return false;
}

/*
 * Does all the error checking for the form.
 *
 * return   True if an error was found; False if no errors were found
 */
function formHasErrors()
{
	var errorFlag = false;
	
	var requiredTextFields = ["fname", "lname", "email", "phone"];

	//Shipping info validation
	for (var i = 0; i < requiredTextFields.length; i++){
		 
		 var textfield = document.getElementById(
		 	requiredTextFields[i]);
		 var type = textfield.type;

		 if(!formFieldHasInput(textfield)){
		 	document.getElementById(requiredTextFields[i] + 
		 		"_error").style.display = "block";

		 	if(!errorFlag){
		 			textfield.focus();
		 			textfield.select();	 			
		 	}
		 	errorFlag = true;
		 }

		 //Email validation
		 if(requiredTextFields[i] == "email"){	 		 	
		 	if(checkEmail()){	
		 		if(!errorFlag){
			 			textfield.focus();
			 			textfield.select();			
		 	}
		 		errorFlag = true;
		 	}
		 }

		 //Phone validation
		  if(requiredTextFields[i] == "phone"){	 	
		 	if(checkPhone()){	
		 		if(!errorFlag){
			 		
			 			textfield.focus();
			 			textfield.select(); 			
		 	}
		 		errorFlag = true;
		 	}
		 }
	}

	return errorFlag;
}


/*
 * Hides all of the error elements.
 */
function hideErrors()
{
	var errorFields = document.getElementsByClassName("error");

	for (var i = 0; i < errorFields.length; i++) {
		errorFields[i].style.display = "none";
	}
}

/*
 * Handles the load event of the document.
 */
function load()
{
	hideErrors();
	document.getElementById("fname").focus();

	document.getElementById("contactform").addEventListener("submit", validate, false);

	document.getElementById("contactform").reset();

	document.getElementById("contactform").addEventListener("reset", resetForm, false);
}


// Add document load event listener
document.addEventListener("DOMContentLoaded", load, false);