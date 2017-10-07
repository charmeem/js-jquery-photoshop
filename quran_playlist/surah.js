// Constructor
function Surah(surah, qari, duration) {
    this.surah= surah;
	this.qari = qari;
	this.duration = duration;
	this.isPlaying = false;
}

//prototype methods
Surah.prototype.play = function() {
    this.isPlaying = true;
	};

Surah.prototype.stop = function() {
    this.isPlaying = false;
	};
	
Surah.prototype.toHTML = function() {
    var htmlstring = ' <li ';
	if (this.isPlaying) {
        htmlstring += '	class="current"';
	}
	
	htmlstring += '>';
    htmlstring += this.surah;
	htmlstring += '--';
    htmlstring += this.qari;
	htmlstring += '<span class= "duration">';
    htmlstring += this.duration;
    htmlstring += '</span></li>';
    
	return htmlstring;
	
	};
	

	