/* 
 * Validation of reservation form fields from front end (front page).
 * use of regular expressions
 */
 
function nameValidation(){
		function validName(name) {
		var re = /^[a-zA-Z ]{2,30}$/;
		return re.test(name);
		}
		
		var yourname = document.getElementById('yourname');
		var nameval = yourname.value;
		
		
		
		validName(nameval);
		
			if( !validName(nameval)){ 
			     yourname.setAttribute("placeholder", "Name is invalid");
				 yourname.className = "form-control is-invalid";
			    return false;} 
			else {   
					yourname.setAttribute("class", "form-control is-valid");
					return true;}
				
}

function emailValidation(){
		function validEmail(email) {
		var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		return re.test(email);
		}
		
		var email = document.getElementById('email');
		var emailval = email.value;
		
		
		validEmail(emailval);
			if( !validEmail(emailval)) {
				   email.setAttribute("placeholder", "Email is invalid");
				   email.className = "form-control is-invalid";
				    return false; }
			     
			   else {   
			          email.setAttribute("class", "form-control is-valid");
					  return true;}
					
}


function placeValidation(){
		function validPlace(place) {
		var re = /[0-9]+/;
		return re.test(place);
		}
		
		var place = document.getElementById('places');
		var placeval = place.value;
		
		
		validPlace(placeval);
			if( !validPlace(placeval)  || ( placeval > 8 )) {
				
				   place.setAttribute("placeholder", "Place is invalid");
				   place.className = "form-control is-invalid";
				   return false;}
					
			else {    place.setAttribute("class", "form-control is-valid");
					  return true;}
					
}

function phoneNumberValidation(){
		function ValidPhoneNumber(phone) {
			
		var re = /^\s*(?:\+?(\d{1,3}))?[- (]*(\d{3})[- )]*(\d{3})[- ]*(\d{4})(?: *[x/#]{1}(\d+))?\s*$/;
		return re.test(phone);
		}
		// example ;9820098200
		var phone =  document.getElementById('tel');
		var phoneval = phone.value;
		
		
			if( !ValidPhoneNumber(phoneval)){
				
				   phone.setAttribute("placeholder", "Phone number is invalid");
				   phone.className = "form-control is-invalid";
				    return false;}
				   
				   
			else { 
				    phone.setAttribute("class", "form-control is-valid"); 
					return true;}
			        
}

function dataValidation(){
		function ValidData(da) {
			
		var re = /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/;
		return re.test(da);
		}
		 
		var data =  document.getElementById('da');
		var dataval = data.value;
		
		
			if( !ValidData(dataval)) {
				
				 data.setAttribute("placeholder", "Data is invalid");
				 data.className = "form-control is-invalid";
				  return false; }
				 
				  
			else {  data.setAttribute("class", "form-control is-valid");
					return true;}
} 


function submit_form_check(){

		nameValidation();
		emailValidation();
		placeValidation();
		phoneNumberValidation();
		dataValidation(); 



		if ((nameValidation()== true )&&(emailValidation()== true )&&(phoneNumberValidation()== true )
			&& (dataValidation()== true &&(placeValidation()== true ) ))
			return true;
		else{
			alert('Empty form or wrong data');
		return false;}
}	