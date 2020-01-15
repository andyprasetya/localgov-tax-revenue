var dbgeo = require('dbgeo');
var _data = require('./data');
var helpers = require('./helpers');
var config = require('./config');

var sqlitedb = _data.sqliteconnect;

var handlers = {};

/* End-Point: /getAllSQLiteUsers */
handlers.getAllSQLiteUsers = function(data,callback){
  var acceptableMethods = ['get'];
  if(acceptableMethods.indexOf(data.method) > -1){
    sqlitedb.serialize(function(){
      sqlitedb.all("SELECT * FROM users", [], (err, rows) => {
        if (err) throw err;
        if(rows){
          var json = rows;
          callback(200, json);
        } else {
          callback(405);
        }
      });
    });
  } else {
    callback(405);
  }
};

module.exports = handlers;