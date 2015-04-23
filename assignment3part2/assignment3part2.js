function requestObject(httpmethod, page, success) {
	this.httpmethod = httpmethod;
	this.url = 'https://api.github.com/gists?page=' + page;
	this.success = success;

}

function getGistResults() {
	var selectedlanguages = [];
	var pages = 0;

	var ddl = document.getElementById("ddlPages");
	pages = ddl.options[ddl.selectedIndex].value;
	var checkboxes = document.getElementsByName('language');

	for(var i=0; i<checkboxes.length; i++) {
		if (checkboxes[i].checked) {
			selectedlanguages.push(checkboxes[i].value);		
		}
}
	
function sendDataRequest(reqObject) {
	var httpRequest;
	if (window.XMLHttpRequest) { // Mozilla, Safari, IE7+ ...Note: IE6 not required
  	  httpRequest = new XMLHttpRequest();
	} 

	if (!httpRequest) {
      alert('Giving up :( Cannot create an XMLHTTP instance');
      return false;
  	}
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState == 4 && httpRequest.status == 200) {		
			reqObject.success(httpRequest.responseText);		
		}
	}	
	httpRequest.open(reqObject.httpmethod, reqObject.url);
	httpRequest.send();
}

function displayResults(object) {
		var results = JSON.parse(object);

}