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

/* End-Point: /getAllPoints */
handlers.getAllPoints = function(data,callback){
  var acceptableMethods = ['get'];
  if(acceptableMethods.indexOf(data.method) > -1){
    pgpool.query('SELECT * FROM data_point', [], (err, res) => {
      if(err){
        throw err
      }
      var data = res.rows;
      dbgeo.parse(data, {
        outputFormat: 'geojson'
      }, function(error, result) {
        callback(200, result);
      });
    });
  } else {
    callback(405);
  }
};

/* End-Point: /getAllLines */
handlers.getAllLines = function(data,callback){
  var acceptableMethods = ['get'];
  if(acceptableMethods.indexOf(data.method) > -1){
    pgpool.query('SELECT * FROM data_linestring', [], (err, res) => {
      if(err){
        throw err
      }
      var data = res.rows;
      dbgeo.parse(data, {
        outputFormat: 'geojson'
      }, function(error, result) {
        callback(200, result);
      });
    });
  } else {
    callback(405);
  }
};

/* End-Point: /getAllPolygons */
handlers.getAllPolygons = function(data,callback){
  var acceptableMethods = ['get'];
  if(acceptableMethods.indexOf(data.method) > -1){
    pgpool.query('SELECT * FROM data_polygon', [], (err, res) => {
      if(err){
        throw err
      }
      var data = res.rows;
      dbgeo.parse(data, {
        outputFormat: 'geojson'
      }, function(error, result) {
        callback(200, result);
      });
    });
  } else {
    callback(405);
  }
};

/* End-Point: /getAllMySQLPoints */
handlers.getAllMySQLPoints = function(data,callback){
  var acceptableMethods = ['get'];
  if(acceptableMethods.indexOf(data.method) > -1){
    mysqlpool.query('SELECT id,context,d_created,CAST(longitude AS DECIMAL(10,6)) AS longitude, CAST(latitude AS DECIMAL(10,6)) AS latitude FROM points', [], (err, queryresult, fields) => {
      if(err){
        throw err
      }
      dbgeo.parse(queryresult, {
        geometryType: 'll',
        geometryColumn: ['longitude', 'latitude'],
        outputFormat: 'geojson'
      }, function(error, result) {
        callback(200, result);
      });
    });
    /* mysqlpool.on('acquire', function(connection){
      console.log('Connection %d acquired', connection.threadId);
    });
    mysqlpool.on('connection', function(connection){
      
    }); */
  } else {
    callback(405);
  }
};

module.exports = handlers;