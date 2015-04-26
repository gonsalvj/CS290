var favoriteGists = [];
var searchResultGists = [];
var selectedlanguages = [];

window.onload = function() {
    loadFavoriteGists();
};

function loadFavoriteGists() {
    if (typeof (Storage) !== "undefined") {
    	var favoriteStr = localStorage.getItem("favorites");
    	if(favoriteStr !== null) {
    		createGitObjCollection(favoriteStr, favoriteGists);
    		var context = document.getElementById("divfavorites");
    		for(var i = 0; i < favoriteGists.length; i++) {
    			generateGistHTML(context, favoriteGists[i], removeFavorite, "-");
    		}  
		}    	
    }
}

function requestObject(httpmethod, page, success) {
    this.httpmethod = httpmethod;
    this.url = 'https://api.github.com/gists?page=';
    this.page = page;
    this.success = success;
}

function GistObject() {
	var id;
	var html_url;
	var description;

	this.setId = function(id) {
		this.id = id;
	};
	this.getId = function() {
		return this.id;
	}
	this.setHtmlURL = function(htmlurl) {
		this.html_url=htmlurl;
	}
	this.getHtmlURL = function() {
		return this.html_url;
	}
	this.setDescription = function(description) {
		this.description = description;
	}
	this.getDescription = function() {
		return this.description;
	}
}
function getGistResults() {
    var ddl = document.getElementById("ddlPages");
    var page = ddl.options[ddl.selectedIndex].value;
    var checkboxes = document.getElementsByName('language');
    for (var i=0; i<checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            selectedlanguages.push(checkboxes[i].value);		
        }
    }
    var reqObject = new requestObject("GET", page, printData);
    fetchData(reqObject);
}	

function isSelectedLanguage(files) {

	if (selectedlanguages.length === 0)
	{
		return true;
	}
	
	for (var i in files) {
		for (var j = 0; j < selectedlanguages.length; j++) {
			if(files[i].language === selectedlanguages[j])
			{
				return true;
			}
		}
	}
	return false;
}

function isFavoriteGist(id) {
	for (var i = 0; i < favoriteGists.length; i++) {
		if(favoriteGists[i].getId() === id)
		{
			return true;
		}
	}
	return false;
}

function fetchData(reqObject) {	 	
  	for (var i = 1; i <= reqObject.page; i++) {
  		var httpRequest;
	    if (window.XMLHttpRequest) { // Mozilla, Safari, IE7+ ...Note: IE6 not required
  	        httpRequest = new XMLHttpRequest();
	    } 
	    if (!httpRequest) {
            throw 'Could not create XMLHttpRequest';
  	    }
  	    httpRequest.onreadystatechange = function() {
		    if (httpRequest.readyState === 4 && httpRequest.status === 200) {	
		    	    reqObject.success(httpRequest.responseText);		    	    	    	    
		    }	
		}  	  
		var url = reqObject.url + i;
		httpRequest.open(reqObject.httpmethod, url, false);
		httpRequest.send();
	}			
}

function createGitObjCollection(result, collection) {
	var gistList = JSON.parse(result);
    for (var i =0;i < gistList.length; i++) {	    
	    var objGist = new GistObject();
	    objGist.setId(gistList[i].id);
	    if(gistList[i].description != "") {
	    	objGist.setDescription(gistList[i].description);	
	    }		
	    else {
	    	objGist.setDescription("No description provided");
	    }
	    objGist.setHtmlURL(gistList[i].html_url);
	    collection.push(objGist);	   
    }    
}

function printData(result) {
    var gistList = JSON.parse(result);
    var context = document.getElementById("divresults");	
    for (var i =0;i < gistList.length; i++) {

	    if((!isFavoriteGist(gistList[i].id)) && (isSelectedLanguage(gistList[i].files))) {
		    var objGist = new GistObject();
		    objGist.setId(gistList[i].id);
		    if(gistList[i].description != "") {
		    	objGist.setDescription(gistList[i].description);	
		    }		
		    else {
		    	objGist.setDescription("No description provided");
		    }
		    objGist.setHtmlURL(gistList[i].html_url);
		    generateGistHTML(context, objGist, addToFavorites, "+");
		    searchResultGists.push(objGist);	    			
	    }
    }    
}

function generateGistHTML(context, gist, callback, btnText)
{
		var childdiv = document.createElement("div");
		childdiv.setAttribute("id", gist.getId());
		var btn = document.createElement("button");
		btn.style.margin = "10px";
		btn.innerHTML = btnText;
		btn.setAttribute("gistId", gist.getId());		
		btn.onclick = function() {
			var gistId = this.getAttribute("gistId");
			callback(gistId);
		};
		childdiv.appendChild(btn);
		var link = document.createElement("a");
		var desc = document.createTextNode(gist.getDescription());
		link.appendChild(desc);
		link.href = gist.getHtmlURL();
		link.target="_blank";
		childdiv.appendChild(link);
		var hr = document.createElement("hr");
		childdiv.appendChild(hr);
		context.appendChild(childdiv);		
}

function findById(id, gistList) {
	for(var i=0; i < gistList.length; i++) {
		if(gistList[i].getId() === id) {
			return gistList[i];
		}
	}
	return false;	
}

function addToFavorites(id) {
	var element = document.getElementById(id);
	element.parentNode.removeChild(element);
	var context = document.getElementById("divfavorites");
	var gist = findById(id, searchResultGists);
	generateGistHTML(context, gist, removeFavorite, "-");	
	favoriteGists.push(gist);
	_setFavorites();
	_deleteCollectionElement(searchResultGists, id);	
}

function removeFavorite(id) {

	var element = document.getElementById(id);
	element.parentNode.removeChild(element);
	_deleteCollectionElement(favoriteGists, id);	
	_setFavorites();
}

function _deleteCollectionElement(collection, id) {
	for (var i = 0; i < collection.length; i++) {
		if (collection[i].getId() === id) {
			collection.splice(i, 1);
		}
	}
}

function _setFavorites() {
	if (typeof (Storage) !== "undefined") {
        localStorage.removeItem("favorites");
        var favorites = '[';
	    for(var i =0; i < favoriteGists.length; i++) {

		    favorites += JSON.stringify(favoriteGists[i]);
		    if(i != favoriteGists.length-1)
		    {
			    favorites += ",";
		    }
     	}
	    favorites += ']';
	    localStorage.setItem("favorites", favorites);
    }
}

