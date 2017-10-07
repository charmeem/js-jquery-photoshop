const recitelist = new Recitelist ();

const baqarah = new Surah( "Al-Baqarah", "Al Fasi", "02:30:00" );
const aleimran = new Surah( "Aale Imran", "Al Shatri", "02:15:00" );
const alnisaa = new Surah( "Al Nisaa", "Mahir", "02:00:00" );
//console.log(baqarah);
recitelist.add(baqarah);
recitelist.add(aleimran);
recitelist.add(alnisaa);

//get ol element from html file
const playlistElement = document.getElementById("plist");

//call to renderinElement in recitelist file
recitelist.renderinElement(playlistElement); 

// creating button click event handlers
const playButton = document.getElementById("play");
playButton.onclick = function() {
    recitelist.play();
	recitelist.renderinElement(playlistElement); 
}

const nextButton = document.getElementById("next");
nextButton.onclick = function() {
    recitelist.next();
	recitelist.renderinElement(playlistElement); 
}

const stopButton = document.getElementById("stop");
stopButton.onclick = function() {
    recitelist.stop();
	recitelist.renderinElement(playlistElement); 
}