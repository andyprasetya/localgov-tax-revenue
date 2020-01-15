var fetch = require('node-fetch');
var _data = require('./data');
var helpers = require('./helpers');
var config = require('./config');

var handlers = {};

/* End-Point: /datasiakektp */
handlers.datasiakektp = function(data,callback){
  var acceptableMethods = ['post','options'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._datasiakektp[data.method](data,callback);
  } else {
    callback(405);
  }
};

handlers._datasiakektp  = {};

handlers._datasiakektp.post = function(data,callback){
  var objectId = data.payload.nik;
  var accessId = config.dukcapiluserid;
  var accessPw = config.dukcapilpasswd;
  fetch(config.capilDSEndPoint, {
    method: 'POST',
    headers: {
      'Accept': 'application/json, text/plain, */*',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({"nik":objectId,"user_id":accessId,"password":accessPw})
  })
  .then(res => res.json())
  .then(json => callback(200, json));
};

module.exports = handlers;