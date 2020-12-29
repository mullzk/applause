/*
	Javascript used by applause_client.html and applause_server.html 
	- connecting to Server-Side Events on remote-applauding Users, updating the view and 
	  playing the remote applause via applause_audio.js  
	- connecting to Server-Side Events on registered Users (applause_server only) 
	  and updating the respective view. 
	- Start and Stop Applauding:
		o local via applause_audio.js
		o sending remote-applause to Server via AJAX-Call
*/

// establish stream for remotely applauding users and listen for changes
var listener = function (event) {
    if(typeof event.data !== 'undefined'){
	    if (event.type=="message") {
			setRemoteApplause(event.data);
	    }
    }
};

var setRemoteApplause = function(applause) {
	printer = document.getElementById("current_applause");
	printer.textContent = applause;
};

const evtSource = new EventSource("./applause_sse.php");
evtSource.addEventListener("open", listener);
evtSource.addEventListener("message", listener);
evtSource.addEventListener("error", listener);




// If we are in applause_client.html then we also connect to SSE-Stream for current_users 
var setCurrentUsers;
var evtUserSource;
function setupSSEStreamAndListenerForCurrentUsers() {
	evtUserSource = new EventSource("./applause_users_sse.php");
	evtUserSource.addEventListener("message", function (event) {
			if(typeof event.data !== 'undefined'){
				setCurrentUsers(event.data);
			}
		}
	);
	
	setCurrentUsers = function(users) {
		printer = document.getElementById("current_users");
		printer.textContent = users - 1;
	};
}




// AJAX-Request for starting and stoping to applaud
function startApplauding() {
	sendActionToApplauseHandler("startApplauding");
}
function stopApplauding() {
	sendActionToApplauseHandler("stopApplauding");	
}
function stopAllApplause() {
	sendActionToApplauseHandler("stopAllApplause");
}

function sendActionToApplauseHandler(action) {
	var url = "./applause_handler.php?action=" + action;
	var xhr = new XMLHttpRequest();
	xhr.open("GET", url, true);

	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send();		
}
