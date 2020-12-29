/* 		 
	These functions use howlerJS (https://howlerjs.com/).
	- howler.js must be loaded before
	- See https://www.kevssite.com/seamless-audio-looping/ for more info
*/

var applaus_local = new Howl({
		src: ['applaus_local.mp3'],
		loop: true, 
		rate: 1
});
var applaus_remote = [];

function play_local_applaus() {
	applaus_local.play();
}
function stop_local_applaus() {
	applaus_local.stop();
}


function play_remote_applaus(users) {
	for (let i = 0; i < 10; i++) {
		if (i < users) {
			// User i should applaud
			if (i < applaus_remote.length) {
				// This user is already applauding
			} else {
				// A new User should start applauding
				applaus_remote.push(new Howl({
					src: ['applaus_remote.mp3'],
					loop: true, 
					volume: 1.0,
					rate: (Math.random()*2+0.6)
				}));
				applaus_remote[i].play();
				applaus_remote[i].volume(0.1);
//				applaus_remote[i].fade(0.0, 1.0, 2000);
			}
		} else {
			// User i should not applaud
			if (i < applaus_remote.length) {
				// This user should stop applauding
				
				applaus_remote.pop().stop();
			} else {
				// This user does not applaud
			}
		}
	}
}

function playAudio(rate) { 
	if (!rate) {
		rate = 1;
	}

	var sound = new Howl({
		src: ['applaus1.mp3'],
		loop: true, 
		rate: rate
	});
	sound.loop();
	sound.play();
}
