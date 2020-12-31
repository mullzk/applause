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
	play_remote_applaus(Math.min(applause, 5));
	printer = document.getElementById("current_applause");
	if (printer) {
		printer.textContent = applause;
	}
};

const evtSource = new EventSource("./applause_sse_applauding_users.php");
evtSource.addEventListener("open", listener);
evtSource.addEventListener("message", listener);
evtSource.addEventListener("error", listener);




// If we are in applause_server.html then we also connect to SSE-Stream for current_users 
var setCurrentUsers;
var evtUserSource;
function setupSSEStreamAndListenerForCurrentUsers() {
	evtUserSource = new EventSource("./applause_sse_registered_users.php");
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
function mdebug(text) {
	var debug = document.getElementById("debugger");
	if (debug) {
		var p = document.createElement("p");
		p.appendChild(document.createTextNode(text));
		debug.appendChild(p);
	}
}

var isTouch = ('ontouchstart' in window);
var isCurrentlyApplauding = false;
var stopApplaudingTimer;


function startApplauding() {
	if (!isCurrentlyApplauding) {
		isCurrentlyApplauding = true;
		document.getElementById("applauseButton").style.background="url('clappinghands.svg')"
		play_local_applaus();
		sendActionToApplauseHandler("startApplauding");
		if (isTouch) {
			// Touch Devices sometimes do not properly handle pointerDown- and pointerUp-Events on the DIV, so we ensure that the Applause stops after 7 seconds.
			stopApplaudingTimer = setTimeout(stopApplauding, 7000);
		}
	}
}

function stopApplauding() {
	if (isCurrentlyApplauding) {
		var workerfunction = function() {
			stop_local_applaus();
			sendActionToApplauseHandler("stopApplauding");	
			isCurrentlyApplauding = false;
			document.getElementById("applauseButton").style.background="url('claphands.svg')"	
		};
		if (isTouch) {
			// Touch Devices sometimes do not properly handle pointerDown- and pointerUp-Events on the DIV, so we ensure that the Applause stops after 7 seconds.
			var stopLocalApplauseTimer = setTimeout(workerfunction, 1500);
		} else {
			workerfunction();
		}
	}
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
