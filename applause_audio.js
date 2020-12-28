/* 		 
	These functions use howlerJS (https://howlerjs.com/).
	- howler.js must be loaded before
	- See https://www.kevssite.com/seamless-audio-looping/ for more info
*/


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
