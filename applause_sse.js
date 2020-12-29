// establish stream from remote Applause and listener for changes
var listener = function (event) {
    if(typeof event.data !== 'undefined'){
	    if (event.type=="message") {
	    	console.log("applause");
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

/* If we are in applause_client.html which has a p#current_users, then we also connect to SSE-Stream for current_users */
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
