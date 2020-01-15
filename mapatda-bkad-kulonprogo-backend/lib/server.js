var http = require('http');
var https = require('https');
var url = require('url');
var StringDecoder = require('string_decoder').StringDecoder;
var config = require('./config');
var fs = require('fs');
var helpers = require('./helpers');
var handlers = require('./handlers');
var authhandlers = require('./authhandlers');
var syshandlers = require('./systemhandlers');
var webservicehandlers = require('./webservicehandlers');
var geodatahandlers = require('./geodatahandlers');
var filedbhandlers = require('./filedbhandlers');
var charthandlers = require('./vegaspechandlers');
var developmenthandlers = require('./dummyhandlers');
var path = require('path');
var util = require('util');
var formidable = require('formidable');
var debug = util.debuglog('server');

var server = {};

server.httpServer = http.createServer(function(req,res){
  server.unifiedServer(req,res);
});

server.httpsServerOptions = {
  'key': fs.readFileSync(path.join(__dirname,'/../https/key.pem')),
  'cert': fs.readFileSync(path.join(__dirname,'/../https/cert.pem'))
};
server.httpsServer = https.createServer(server.httpsServerOptions,function(req,res){
  server.unifiedServer(req,res);
});

server.unifiedServer = function(req,res){
  var parsedUrl = url.parse(req.url, true);
  var path = parsedUrl.pathname;
  var trimmedPath = path.replace(/^\/+|\/+$/g, '');
  var queryStringObject = parsedUrl.query;
  var method = req.method.toLowerCase();
  var headers = req.headers;
  var decoder = new StringDecoder('utf-8');
  var buffer = '';
  if(trimmedPath == 'dataservices/uploadFile'){
    if(method == 'post'){
      var form = new formidable.IncomingForm({
        uploadDir: config.fileUploadTempDir
      });
      form.parse(req, function(err, fields, files){
        let fileExt = helpers.fileExtensionType(files.filetoupload.type);
        let hashedFilename = helpers.hash(files.filetoupload.name +''+ files.filetoupload.mtime +''+ files.filetoupload.path) + fileExt;
        var tmppath = files.filetoupload.path;
        var newpath = config.fileUploadDir + hashedFilename;
        fs.rename(tmppath, newpath, function(err){
          if (err) throw err;
          res.setHeader('Content-Type', 'application/json');
          res.writeHead(200);
          var payloadString = JSON.stringify({"status":201,"filename":hashedFilename});
          return res.end(payloadString);
        });
      });
    } else {
      res.setHeader('Content-Type', 'application/json');
      res.writeHead(200);
      var payloadString = JSON.stringify({"status":404,"message":""});
      return res.end(payloadString);
    }
  } else {
    req.on('data', function(data) {
      buffer += decoder.write(data);
    });
    req.on('end', function() {
      buffer += decoder.end();
      
      var chosenHandler = typeof(server.router[trimmedPath]) !== 'undefined' ? server.router[trimmedPath] : handlers.notFound;
      
      var data = {
        'trimmedPath' : trimmedPath,
        'queryStringObject' : queryStringObject,
        'method' : method,
        'headers' : headers,
        'payload' : helpers.parseJsonToObject(buffer)
      };
      
      chosenHandler(data,function(statusCode,payload){
        statusCode = typeof(statusCode) == 'number' ? statusCode : 200;
        
        payload = typeof(payload) == 'object'? payload : {};
        
        var payloadString = JSON.stringify(payload);
        
        res.setHeader('Access-Control-Allow-Origin', '*');
        res.setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        res.setHeader('Content-Type', 'application/json');
        res.writeHead(statusCode);
        res.end(payloadString);
        
        if(statusCode == 200){
          debug('\x1b[32m%s\x1b[0m',method.toUpperCase()+' /'+trimmedPath+' '+statusCode);
        } else {
          debug('\x1b[31m%s\x1b[0m',method.toUpperCase()+' /'+trimmedPath+' '+statusCode);
        }
      });
    });
  }
};

server.router = {
  'dataservices/alive' : handlers.alive,
  'dataservices/version' : handlers.version,
  
  'dataservices/doLogin' : authhandlers.doLogin,
  'dataservices/doNotarisPPATLogin' : authhandlers.doNotarisPPATLogin,
  'dataservices/doLogoff' : authhandlers.doLogoff,
  'dataservices/doNotarisPPATLogoff' : authhandlers.doNotarisPPATLogoff,
  'dataservices/changePassword' : authhandlers.changePassword,
  'dataservices/changeNotarisPPATPassword' : authhandlers.changeNotarisPPATPassword,
  'dataservices/resetPassword' : authhandlers.resetPassword,
  'dataservices/resetNotarisPPATPassword' : authhandlers.resetNotarisPPATPassword,
  'dataservices/deactivateUser' : authhandlers.deactivateUser,
  'dataservices/deactivateNotarisPPATUser' : authhandlers.deactivateNotarisPPATUser,
  'dataservices/activateUser' : authhandlers.activateUser,
  'dataservices/activateNotarisPPATUser' : authhandlers.activateNotarisPPATUser,
  'dataservices/generatePassword' : authhandlers.generatePassword,
  
  'dataservices/listObjects' : syshandlers.listObjects,
  'dataservices/createNewUser' : syshandlers.createNewUser,
  'dataservices/editSupervisorUser' : syshandlers.editSupervisorUser,
  'dataservices/editStaffUser' : syshandlers.editStaffUser,
  'dataservices/createNotarisPPATUser' : syshandlers.createNotarisPPATUser,
  'dataservices/editNotarisPPATUser' : syshandlers.editNotarisPPATUser,
  
  'dataservices/datasiakektp' : webservicehandlers.datasiakektp,
  
  'dataservices/getAllPoints' : geodatahandlers.getAllPoints,
  'dataservices/getAllLines' : geodatahandlers.getAllLines,
  'dataservices/getAllPolygons' : geodatahandlers.getAllPolygons,
  'dataservices/getAllMySQLPoints' : geodatahandlers.getAllMySQLPoints,
  
  'dataservices/getAllSQLiteUsers' : filedbhandlers.getAllSQLiteUsers,
  
  'dataservices/sysResetUsers' : syshandlers.sysResetUsers,
  
  'dataservices/getBPHTBChartSpec' : charthandlers.getBPHTBChartSpec,
  
  'dataservices/devTestRequest' : developmenthandlers.devTestRequest,
  
  'dataservices/log' : handlers.log
};

server.init = function(){
  server.httpServer.listen(config.httpPort,function(){
    console.log('\x1b[36m%s\x1b[0m','The HTTP server is running on port '+config.httpPort);
  });
  server.httpsServer.listen(config.httpsPort,function(){
    console.log('\x1b[35m%s\x1b[0m','The HTTPS server is running on port '+config.httpsPort);
  });
};

module.exports = server;