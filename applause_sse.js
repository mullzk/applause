// establish stream from remote Applause and listener for changes
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



// AJAX-Request for starting and stoping to applaud
function startApplauding() {
	sendActionToApplauseHandler("startApplauding");
}
function stopApplauding() {
	sendActionToApplauseHandler("stopApplauding");	
}
function sendActionToApplauseHandler(action) {
	var url = "./applause_handler.php?action=" + action;
	var xhr = new XMLHttpRequest();
	xhr.open("GET", url, true);

	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send();		
}
