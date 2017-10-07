//constructor
function Recitelist() {
    this.surahs = [];
	this.nowRecitingIndex = 0;
}

//prototype functions
Recitelist.prototype.add = function(surah) {
    this.surahs.push(surah);
};

Recitelist.prototype.play = function() {
    const currentsurah = this.surahs[this.nowRecitingIndex];
	currentsurah.play();
};

Recitelist.prototype.stop = function() {
    const currentsurah = this.surahs[this.nowRecitingIndex];
	currentsurah.stop();
};

Recitelist.prototype.next = function() {
    this.stop();
	this.nowRecitingIndex++;
	if ( this.nowRecitingIndex === this.surahs.length ) {
	   this.nowRecitingIndex = 0; 
	}
	this.play();
};



Recitelist.prototype.renderinElement = function(list) {
    list.innerHTML = "";
	for( var i = 0; i < this.surahs.length; i++) {
	list.innerHTML += this.surahs[i].toHTML();
	}
};