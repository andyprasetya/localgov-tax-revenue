var fetch = require('node-fetch');
var crypto = require('crypto');
var dbgeo = require('dbgeo');
var _data = require('./data');
var helpers = require('./helpers');
var config = require('./config');

var pgpool = _data.pgpool;
var pgclient = _data.pgclient;
var mysqlpool = _data.mysqlPool;
var sqlitedb = _data.sqliteconnect;

var handlers = {};

/* End-Point: /alive */
handlers.alive = function(data,callback){
  var acceptableMethods = ['get','post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    var objDate = new Date();
    var json = {"status":"alive","datetime":objDate.toISOString()};
    callback(200, json);
  } else {
    callback(405);
  }
};

/* End-Point: /version */
handlers.version = function(data,callback){
  var acceptableMethods = ['get','post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    var json = {"version":config.version,"codename":config.codeName};
    callback(200, json);
  } else {
    callback(405);
  }
};

/* Not-Found handler */
handlers.notFound = function(data,callback){
  callback(404);
};

handlers.log = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._log[data.method](data,callback);
  } else {
    callback(405);
  }
};

handlers._log  = {};

handlers._log.post = function(data,callback){
  var objDate = new Date();
  var json = {"context":"log","datetime":objDate.toISOString()};
  callback(200, json);
};

module.exports = handlers;