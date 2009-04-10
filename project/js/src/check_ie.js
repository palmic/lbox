isIE = function() {
	return (window.navigator.appName.toLowerCase() == 'microsoft internet explorer');
}
isIE6 = function() {
	if (window.navigator.appName.toLowerCase() == 'microsoft internet explorer') {
		if (window.navigator.appVersion) {
			return (window.navigator.appVersion.indexOf('MSIE 6') > -1);
		}
	}
}
isIE7 = function() {
	if (window.navigator.appName.toLowerCase() == 'microsoft internet explorer') {
		if (window.navigator.appVersion){
			return (window.navigator.appVersion.indexOf('MSIE 7') > -1);
		}
	}
}