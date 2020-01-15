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

/* End-Point: /getBPHTBChartSpec */
handlers.getBPHTBChartSpec = function(data,callback){
  var acceptableMethods = ['get'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._getBPHTBChartSpec[data.method](data, callback);
  } else {
    callback(405);
  }
};
handlers._getBPHTBChartSpec = {};
handlers._getBPHTBChartSpec.get = function(data, callback){
  let currentdate = new Date();
  mysqlpool.query('SELECT DATE_FORMAT(d_verified,"%Y-%m-%d") AS dt, veri_bphtb_hb AS bv FROM appx_sspd_bphtb WHERE th_pajak = ? AND EXTRACT(YEAR FROM d_verified) = ? AND stat_dok > 2 ORDER BY d_verified', [data.queryStringObject.th_pajak,data.queryStringObject.th_pajak], (err, queryresult, fields) => {
    if(err){
      throw err
    }
    let cv = 0;
    queryresult.map(function(e,index){
      e.cs = cv + e.bv;
      cv += e.bv;
    });
    let vegaspec = {
      "$schema": "https://vega.github.io/schema/vega/v5.json",
      "width": 960,
      "height": 200,
      "padding": 5,
      "title": {
        "text": "Grafik Pendapatan Daerah - BPHTB",
        "subtitle": "Tahun Anggaran Pendapatan "+ currentdate.getFullYear() +""
      },
      "signals": [{
        "name": "interpolate",
        "value": "basis"
      }],
      "data": [{
        "name": "table",
        "values": queryresult,
        "format": {
          "type": "json",
          "parse": {
            "dt": "date"
          }
        }
      }],
      "scales": [{
          "name": "x",
          "type": "time",
          "range": "width",
          "nice": "week",
          "domain": {"data": "table", "field": "dt"}
        },
        {
          "name": "x2",
          "type": "time",
          "range": "width",
          "nice": "month",
          "domain": {"data": "table", "field": "dt"}
        },
        {
          "name": "y",
          "type": "linear",
          "range": "height",
          "nice": true,
          "zero": true,
          "domain": {"data": "table", "field": "cs"}
        }],
      "axes": [
        {
          "orient": "bottom", 
          "scale": "x",
          "grid": true,
          "ticks": true,
          "tickCount": "week",
          "labels": false
        },
        {
          "orient": "bottom", 
          "scale": "x2",
          "ticks": true,
          "tickColor": "#FF0000",
          "tickSize": 10
        },
        {
          "orient": "left", 
          "scale": "y",
          "grid": true
        }
      ],
      "marks": [
        {
          "type": "line",
          "from": {"data": "table"},
          "encode": {
            "enter": {
              "x": {"scale": "x", "field": "dt"},
              "y": {"scale": "y", "field": "cs"},
              "stroke": {"value": "blue"}
            },
            "update": {
              "interpolate": {"signal": "interpolate"},
              "fillOpacity": {"value": 1}
            }
          }
        }
      ]
    };
    callback(200, vegaspec);
  });
}

module.exports = handlers;