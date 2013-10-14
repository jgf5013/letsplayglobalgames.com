/*reminder: js files cannot have php code in them! */


function validateInputPanel() {
	document.getElementById("index_nav_form").submit();
	alert('Thanks for your input!');
}


function LoadingOverlay() {
	this.overlay = document.getElementById('overlay');
	this.globeClicked = false;
}
LoadingOverlay.prototype.show = function() {
	this.overlay.style.visibility = 'visible';
}			
LoadingOverlay.prototype.hide = function() {
	this.overlay.style.display = 'none';
	var body = document.getElementsByTagName('body')[0];
	body.style.overflow = "visible";
}
LoadingOverlay.prototype.setGlobeClicked = function() {
	this.globeClicked = true;
}
LoadingOverlay.prototype.isGlobeClicked = function() {
	return this.globeClicked;
}
LoadingOverlay.prototype.showSpinner = function() {

	var instructions = document.getElementById('overlay_instructions');
	var spinner = document.getElementById('overlay_loading');
	instructions.style.display = "none";
	spinner.style.visibility = "visible";
}
		

	
function addLastUpdatedText() {
	var date = new Date(document.lastModified) - 1;
	
	months = ['January', 'Febraury', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	/* date.setTime( date.getTime()); */
	with (date) {
		document.getElementById('last_updated').innerHTML = 'Last Updated: ' + getDate() + ' ' + months[getMonth()] + ', ' + getFullYear();
	}
}


function HighScoreForm() {
	this.form;
	this.gameType;
}
/* javascript inheritance */
HighScoreNavForm.prototype = new HighScoreForm();
HighScoreNavForm.prototype.constructor = HighScoreNavForm;
function HighScoreNavForm() {
	HighScoreForm.call(this);
	this.form = document.getElementById('high_score_nav_game_form');
	this.gameType = document.getElementById('high_score_nav_game_type');
}
HighScoreNavForm.prototype.sendData = function(_highScoreType) {
	this.gameType.value = _highScoreType;
	this.form.submit();
}


function include( filename ) {  // The include() statement includes and evaluates the specified file.
	    // 
	    // +   original by: mdsjack (http://www.mdsjack.bo.it)
		// +   improved by: Legaev Andrey
	    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // +   improved by: Michael White (http://crestidg.com)
	    // %        note 1: Force Javascript execution to pause until the file is loaded. Usually causes failure if the file never loads. ( Use sparingly! )
	 
	    var js = document.createElement('script');
	    js.setAttribute('type', 'text/javascript');
	    js.setAttribute('src', filename);
	    js.setAttribute('defer', 'defer');
	    document.getElementsByTagName('HEAD')[0].appendChild(js);
	 
	    // save include state for reference by include_once
	    var cur_file = {};
	    cur_file[window.location.href] = 1;
	 
	    if (!window.php_js) window.php_js = {};
	    if (!window.php_js.includes) window.php_js.includes = cur_file;
	    if (!window.php_js.includes[filename]) {
	        window.php_js.includes[filename] = 1;
	    } else {
	        window.php_js.includes[filename]++;
	    }
	 
	    return window.php_js.includes[filename];
}