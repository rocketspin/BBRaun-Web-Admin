var express 	= require("express");
var mysql   	= require("mysql");
var bodyParser  = require("body-parser");
var md5 		= require('md5');
var rest 		= require("./system/REST.js");
var app  		= express();


app.use(function( req, res, next ) {
	res.header("Access-Control-Allow-Origin", "http://52.39.152.236:3000");
	res.header("Access-Control-Allow-Methods", "GET, POST, OPTIONS, PUT, DELETE");
	res.header("Access-Control-Allow-Credentials", true);
	res.header("Access-Control-Allow-Headers", "X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
	next();
});

app.use(express.static( __dirname+ '/app' ));
app.use('/public_files',express.static(__dirname + '/app/public_files'));
app.get('/', function (req, res) {res.sendFile(__dirname + '/app/index.html')});

function REST(){
    var self = this;
    self.connectMysql();
};


REST.prototype.connectMysql = function() {
    var self = this;
    var pool      =    mysql.createPool({
        connectionLimit 	: 100,
		multipleStatements	: true,
        host     			: 'abs.cn04nbmngbjk.us-west-2.rds.amazonaws.com',
        user     			: 'codero',
        password 			: 'Remote2013Aries!123',
        database 			: 'abs',
        debug    			: false
    });
	
    pool.getConnection(function(err,connection){
        if(err) {
          self.stop(err);
        } else {
			self.configureExpress(connection);
        }
    });
}

REST.prototype.configureExpress = function(connection) {
	
	var self = this;
	
	app.use(bodyParser.urlencoded({limit: '500mb', extended: true }));
	app.use(bodyParser.json({limit: '300mb'}));
	
	var router = express.Router();
	app.use('/api', router);
	
	
	
	var rest_router = new rest(router,connection,md5);
	self.startServer();
}

REST.prototype.startServer = function() {
	app.listen(3000,function(){ 
		console.log("All right ! I am alive at Port 3000.");
	});
}


REST.prototype.stop = function(err) {
    console.log("ISSUE WITH MYSQL n" + err);
    process.exit(1);
}

new REST();