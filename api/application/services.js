var apiurl = 'http://localhost:3000/api';
(function(){
	angular.module('app.services', [
		'srv.absapi',
		'srv.loader',
		'srv.authapi',
		'srv.reports',
		'srv.socket'
	])
})();