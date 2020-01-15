var config = require('./config');

var handlers = {};

/*
 * Development-purpose Request Test
 * */
handlers.devTestRequest = function(data,callback){
  var acceptableMethods = ['get','post','options'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._devTestRequest[data.method](data,callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._devTestRequest = {};
handlers._devTestRequest.get = function(data,callback){
  callback(200, {"status":201,"requestmethod":data.method,"requestdata":data.queryStringObject});
};
handlers._devTestRequest.post = function(data,callback){
  callback(200, {"status":201,"requestmethod":data.method,"requestdata":data.payload});
};
handlers._devTestRequest.options = function(data,callback){
  callback(200, {"status":201,"requestmethod":data.method,"requestdata":data.payload});
};

module.exports = handlers;