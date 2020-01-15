var fetch = require('node-fetch');
var crypto = require('crypto');
var _data = require('./data');
var helpers = require('./helpers');
var config = require('./config');

var pgpool = _data.pgpool;
var pgclient = _data.pgclient;
var mysqlpool = _data.mysqlPool;

var handlers = {};

/* End-Point: /sysListBidang */
handlers.listObjects = function(data,callback){
  var acceptableMethods = ['get'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._listObjects[data.method](data, callback);
  } else {
    callback(405);
  }
};
handlers._listObjects = {};
handlers._listObjects.get = function(data, callback){
  if(data.queryStringObject.listall == 'bidang'){
    mysqlpool.query('SELECT * FROM appx_bidang', [], (error, queryresult, fields) => {
      if(error){
        throw error;
      }
      if(queryresult.length > 0){
        callback(200, {"status":201,"dataarray":queryresult});
      } else {
        callback(405);
      }
    });
  } else if(data.queryStringObject.listall == 'subbidang'){
    mysqlpool.query('SELECT * FROM appx_subbidang ORDER BY bidangid,id', [], (error, queryresult, fields) => {
      if(error){
        throw error;
      }
      if(queryresult.length > 0){
        callback(200, {"status":201,"dataarray":queryresult});
      } else {
        callback(405);
      }
    });
  } else if(data.queryStringObject.listall == 'ddsubbidang'){
    mysqlpool.query('SELECT * FROM appx_subbidang WHERE bidangid = ? ORDER BY id', [data.queryStringObject.bidangid], (error, queryresult, fields) => {
      if(error){
        throw error;
      }
      if(queryresult.length > 0){
        callback(200, {"status":201,"dataarray":queryresult});
      } else {
        callback(405);
      }
    });
  } else if(data.queryStringObject.listall == 'developer-view-users'){
    mysqlpool.query('SELECT * FROM appx_users WHERE idx > 1', [], (error, queryresult, fields) => {
      if(error){
        throw error;
      }
      if(queryresult.length > 0){
        callback(200, {"status":201,"dataarray":queryresult});
      } else {
        callback(405);
      }
    });
  } else if(data.queryStringObject.listall == 'bkad-internal-users'){
    mysqlpool.query('SELECT * FROM appx_users WHERE idx > 2 AND origin = ? AND context = ?', ['MAPATDA_BKAD','BKAD'], (error, queryresult, fields) => {
      if(error){
        throw error;
      }
      if(queryresult.length > 0){
        callback(200, {"status":201,"dataarray":queryresult});
      } else {
        callback(405);
      }
    });
  } else if(data.queryStringObject.listall == 'notaris-users'){
    mysqlpool.query('SELECT * FROM notaris_ppat_users ORDER BY id', [], (error, queryresult, fields) => {
      if(error){
        throw error;
      }
      if(queryresult.length > 0){
        callback(200, {"status":201,"dataarray":queryresult});
      } else {
        callback(405);
      }
    });
  } else {
    callback(405);
  }
};

/* End-Point: /createNewUser */
handlers.createNewUser = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._createNewUser[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._createNewUser = {};
handlers._createNewUser.post = function(data,callback){
  mysqlpool.query('SELECT * FROM appx_users WHERE username = ?', [data.payload.username], (error, queryresulta, fields) => {
    if(error){
      throw error;
    }
    if(queryresulta.length == 0){
      mysqlpool.query('SELECT * FROM appx_users WHERE initial = ?', [data.payload.inisial], (error, queryresultb, fields) => {
        if(error){
          throw error;
        }
        if(queryresultb.length == 0){
          let bidangid, bidangname, bidangmodule, subbidangid, subbidangname, subbidangmodule, usermodule, positionname;
          let _hashedPassword = helpers.hash('1234');
          let _rawBidang = data.payload.bidang, 
            _rawSubBidang = data.payload.subbidang;
          let _arrBidang = _rawBidang.split('|'), 
            _arrSubBidang = _rawSubBidang.split('|');
          bidangid = _arrBidang[0];
          bidangname = _arrBidang[1];
          bidangmodule = _arrBidang[2];
          subbidangid = _arrSubBidang[0];
          subbidangname = _arrSubBidang[1];
          subbidangmodule = _arrSubBidang[2];
          if(subbidangid == '0'){
            positionname = 'Staf '+ bidangname;
            usermodule = bidangmodule+'-staf';
            mysqlpool.query('INSERT INTO appx_users(idx,origin,opdcode,bidangid,bidang,subbidangid,subbidang,posisi,spvidx,context,realname,initial,username,wordpass,module,add_module,verificator,collector,status) VALUES(NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1)', ['MAPATDA_BKAD','_UNDEFINED_',parseInt(bidangid),bidangname,parseInt(subbidangid),subbidangname,positionname,0,'BKAD',data.payload.realname,data.payload.inisial,data.payload.username,_hashedPassword,usermodule,'_UNDEFINED_',parseInt(data.payload.verificator),parseInt(data.payload.collector)], (error, queryresult, fields) => {
              if(error){
                throw error;
              }
              callback(200, {"status":201});
            });
          } else {
            positionname = 'Staf '+ subbidangname +'';
            usermodule = ''+ bidangmodule +'-'+ subbidangmodule +'-staf';
            mysqlpool.query('INSERT INTO appx_users(idx,origin,opdcode,bidangid,bidang,subbidangid,subbidang,posisi,spvidx,context,realname,initial,username,wordpass,module,add_module,verificator,collector,status) VALUES(NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1)', ['MAPATDA_BKAD','_UNDEFINED_',parseInt(bidangid),bidangname,parseInt(subbidangid),subbidangname,positionname,0,'BKAD',data.payload.realname,data.payload.inisial,data.payload.username,_hashedPassword,usermodule,'_UNDEFINED_',parseInt(data.payload.verificator),parseInt(data.payload.collector)], (error, queryresult, fields) => {
              if(error){
                throw error;
              }
              callback(200, {"status":201});
            });
          }
        } else {
          callback(200, {"status": 204});
        }
      });
    } else {
      callback(200, {"status": 203});
    }
  });
};

/* End-Point: /editSupervisorUser */
handlers.editSupervisorUser = function(data,callback){
  var acceptableMethods = ['post','options'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._editSupervisorUser[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._editSupervisorUser = {};
handlers._editSupervisorUser.post = function(data, callback){
  if(data.payload.orealname == data.payload.realname && data.payload.ousername == data.payload.username && data.payload.oinisial == data.payload.inisial){
    // realname=0,username=0,initial=0
    mysqlpool.query('UPDATE appx_users SET verificator = ? , collector = ? WHERE idx = ?', [data.payload.verificator,data.payload.collector,data.payload.idx], (error, queryresult, fields) => {
      if(error){
        throw error;
      }
      if(queryresult.affectedRows==0){
        callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
      } else {
        callback(200, {"status":201, "message":"Perubahan data berhasil."});
      }
    });
  } else if(data.payload.orealname == data.payload.realname && data.payload.ousername == data.payload.username && data.payload.oinisial != data.payload.inisial){
    // realname=0,username=0,initial=1
    mysqlpool.query('SELECT * FROM appx_users WHERE initial = ?', [data.payload.inisial], (error, queryresultcheckinitial, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckinitial.length == 0){
        mysqlpool.query('UPDATE appx_users SET initial = ? , verificator = ? , collector = ? WHERE idx = ?', [data.payload.inisial,data.payload.verificator,data.payload.collector,data.payload.idx], (error, queryresult, fields) => {
          if(error){
            throw error;
          }
          if(queryresult.affectedRows==0){
            callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
          } else {
            callback(200, {"status":201, "message":"Perubahan data berhasil."});
          }
        });
      } else {
        callback(200, {"status": 205});
      }
    });
  } else if(data.payload.orealname == data.payload.realname && data.payload.ousername != data.payload.username && data.payload.oinisial != data.payload.inisial){
    // realname=0,username=1,initial=1
    mysqlpool.query('SELECT * FROM appx_users WHERE username = ?', [data.payload.username], (error, queryresultcheckusername, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckusername.length == 0){
        mysqlpool.query('SELECT * FROM appx_users WHERE initial = ?', [data.payload.inisial], (error, queryresultcheckinitial, fields) => {
          if(error){
            throw error;
          }
          if(queryresultcheckinitial.length == 0){
            mysqlpool.query('UPDATE appx_users username = ? , initial = ? , verificator = ? , collector = ? WHERE idx = ?', [data.payload.username,data.payload.inisial,data.payload.verificator,data.payload.collector,data.payload.idx], (error, queryresult, fields) => {
              if(error){
                throw error;
              }
              if(queryresult.affectedRows==0){
                callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
              } else {
                callback(200, {"status":201, "message":"Perubahan data berhasil."});
              }
            });
          } else {
            callback(200, {"status": 205});
          }
        });
      } else {
        callback(200, {"status": 204});
      }
    });
  } else if(data.payload.orealname != data.payload.realname && data.payload.ousername != data.payload.username && data.payload.oinisial != data.payload.inisial){
    // realname=1,username=1,initial=1
    mysqlpool.query('SELECT * FROM appx_users WHERE realname = ?', [data.payload.realname], (error, queryresultcheckrealname, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckrealname.length == 0){
        mysqlpool.query('SELECT * FROM appx_users WHERE username = ?', [data.payload.username], (error, queryresultcheckusername, fields) => {
          if(error){
            throw error;
          }
          if(queryresultcheckusername.length == 0){
            mysqlpool.query('SELECT * FROM appx_users WHERE initial = ?', [data.payload.inisial], (error, queryresultcheckinitial, fields) => {
              if(error){
                throw error;
              }
              if(queryresultcheckinitial.length == 0){
                mysqlpool.query('UPDATE appx_users realname = ? , username = ? , initial = ? , verificator = ? , collector = ? WHERE idx = ?', [data.payload.realname,data.payload.username,data.payload.inisial,data.payload.verificator,data.payload.collector,data.payload.idx], (error, queryresult, fields) => {
                  if(error){
                    throw error;
                  }
                  if(queryresult.affectedRows==0){
                    callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
                  } else {
                    callback(200, {"status":201, "message":"Perubahan data berhasil."});
                  }
                });
              } else {
                callback(200, {"status": 205});
              }
            });
          } else {
            callback(200, {"status": 204});
          }
        });
      } else {
        callback(200, {"status": 203});
      }
    });
  } else if(data.payload.orealname != data.payload.realname && data.payload.ousername == data.payload.username && data.payload.oinisial != data.payload.inisial){
    // realname=1,username=0,initial=1
    mysqlpool.query('SELECT * FROM appx_users WHERE realname = ?', [data.payload.realname], (error, queryresultcheckrealname, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckrealname.length == 0){
        mysqlpool.query('SELECT * FROM appx_users WHERE initial = ?', [data.payload.inisial], (error, queryresultcheckinitial, fields) => {
          if(error){
            throw error;
          }
          if(queryresultcheckinitial.length == 0){
            mysqlpool.query('UPDATE appx_users realname = ? , initial = ? , verificator = ? , collector = ? WHERE idx = ?', [data.payload.realname,data.payload.inisial,data.payload.verificator,data.payload.collector,data.payload.idx], (error, queryresult, fields) => {
              if(error){
                throw error;
              }
              if(queryresult.affectedRows==0){
                callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
              } else {
                callback(200, {"status":201, "message":"Perubahan data berhasil."});
              }
            });
          } else {
            callback(200, {"status": 205});
          }
        });
      } else {
        callback(200, {"status": 203});
      }
    });
  } else if(data.payload.orealname != data.payload.realname && data.payload.ousername == data.payload.username && data.payload.oinisial == data.payload.inisial){
    // realname=1,username=0,initial=0
    mysqlpool.query('SELECT * FROM appx_users WHERE realname = ?', [data.payload.realname], (error, queryresultcheckrealname, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckrealname.length == 0){
        mysqlpool.query('UPDATE appx_users realname = ? , verificator = ? , collector = ? WHERE idx = ?', [data.payload.realname,data.payload.verificator,data.payload.collector,data.payload.idx], (error, queryresult, fields) => {
          if(error){
            throw error;
          }
          if(queryresult.affectedRows==0){
            callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
          } else {
            callback(200, {"status":201, "message":"Perubahan data berhasil."});
          }
        });
      } else {
        callback(200, {"status": 203});
      }
    });
  } else if(data.payload.orealname != data.payload.realname && data.payload.ousername != data.payload.username && data.payload.oinisial == data.payload.inisial){
    // realname=1,username=1,initial=0
    mysqlpool.query('SELECT * FROM appx_users WHERE realname = ?', [data.payload.realname], (error, queryresultcheckrealname, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckrealname.length == 0){
        mysqlpool.query('SELECT * FROM appx_users WHERE username = ?', [data.payload.username], (error, queryresultcheckusername, fields) => {
          if(error){
            throw error;
          }
          if(queryresultcheckusername.length == 0){
            mysqlpool.query('UPDATE appx_users realname = ? , username = ? , verificator = ? , collector = ? WHERE idx = ?', [data.payload.realname,data.payload.username,data.payload.verificator,data.payload.collector,data.payload.idx], (error, queryresult, fields) => {
              if(error){
                throw error;
              }
              if(queryresult.affectedRows==0){
                callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
              } else {
                callback(200, {"status":201, "message":"Perubahan data berhasil."});
              }
            });
          } else {
            callback(200, {"status": 204});
          }
        });
      } else {
        callback(200, {"status": 203});
      }
    });
  } else if(data.payload.orealname == data.payload.realname && data.payload.ousername != data.payload.username && data.payload.oinisial == data.payload.inisial){
    // realname=0,username=1,initial=0
    mysqlpool.query('SELECT * FROM appx_users WHERE username = ?', [data.payload.username], (error, queryresultcheckusername, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckusername.length == 0){
        mysqlpool.query('UPDATE appx_users realname = ? , username = ? , initial = ? , verificator = ? , collector = ? WHERE idx = ?', [data.payload.realname,data.payload.username,data.payload.inisial,data.payload.verificator,data.payload.collector,data.payload.idx], (error, queryresult, fields) => {
          if(error){
            throw error;
          }
          if(queryresult.affectedRows==0){
            callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
          } else {
            callback(200, {"status":201, "message":"Perubahan data berhasil."});
          }
        });
      } else {
        callback(200, {"status": 204});
      }
    });
  } else {
    callback(405);
  }
};

/* End-Point: /editStaffUser */
handlers.editStaffUser = function(data,callback){
  var acceptableMethods = ['post','options'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._editStaffUser[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._editStaffUser = {};
handlers._editStaffUser.post = function(data, callback){
  if(data.payload.orealname == data.payload.realname && data.payload.oinisial == data.payload.inisial && data.payload.ousername == data.payload.username){
    let bidangid, bidangname, bidangmodule, subbidangid, subbidangname, subbidangmodule, usermodule, positionname;
    let _rawBidang = data.payload.bidang, 
      _rawSubBidang = data.payload.subbidang;
    let _arrBidang = _rawBidang.split('|'), 
      _arrSubBidang = _rawSubBidang.split('|');
    bidangid = _arrBidang[0];
    bidangname = _arrBidang[1];
    bidangmodule = _arrBidang[2];
    subbidangid = _arrSubBidang[0];
    subbidangname = _arrSubBidang[1];
    subbidangmodule = _arrSubBidang[2];
    if(subbidangid == '0'){
      positionname = 'Staf '+ bidangname;
      usermodule = bidangmodule+'-staf';
      mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,0,'_UNDEFINED_',positionname,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
        if(error){
          throw error;
        }
        if(queryresult.affectedRows==0){
          callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
        } else {
          callback(200, {"status":201, "message":"Perubahan data berhasil."});
        }
      });
    } else {
      positionname = 'Staf '+ subbidangname +'';
      usermodule = ''+ bidangmodule +'-'+ subbidangmodule +'-staf';
      mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,parseInt(subbidangid),subbidangname,positionname,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
        if(error){
          throw error;
        }
        if(queryresult.affectedRows==0){
          callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
        } else {
          callback(200, {"status":201, "message":"Perubahan data berhasil."});
        }
      });
    }
  } else if(data.payload.orealname == data.payload.realname && data.payload.oinisial == data.payload.inisial && data.payload.ousername != data.payload.username){
    mysqlpool.query('SELECT * FROM appx_users WHERE username = ?', [data.payload.username], (error, queryresultcheckusername, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckusername.length == 0){
        let bidangid, bidangname, bidangmodule, subbidangid, subbidangname, subbidangmodule, usermodule, positionname;
        let _rawBidang = data.payload.bidang, 
          _rawSubBidang = data.payload.subbidang;
        let _arrBidang = _rawBidang.split('|'), 
          _arrSubBidang = _rawSubBidang.split('|');
        bidangid = _arrBidang[0];
        bidangname = _arrBidang[1];
        bidangmodule = _arrBidang[2];
        subbidangid = _arrSubBidang[0];
        subbidangname = _arrSubBidang[1];
        subbidangmodule = _arrSubBidang[2];
        if(subbidangid == '0'){
          positionname = 'Staf '+ bidangname;
          usermodule = bidangmodule+'-staf';
          mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , username = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,0,'_UNDEFINED_',positionname,data.payload.username,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
            if(error){
              throw error;
            }
            if(queryresult.affectedRows==0){
              callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
            } else {
              callback(200, {"status":201, "message":"Perubahan data berhasil."});
            }
          });
        } else {
          positionname = 'Staf '+ subbidangname +'';
          usermodule = ''+ bidangmodule +'-'+ subbidangmodule +'-staf';
          mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , username = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,parseInt(subbidangid),subbidangname,positionname,data.payload.username,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
            if(error){
              throw error;
            }
            if(queryresult.affectedRows==0){
              callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
            } else {
              callback(200, {"status":201, "message":"Perubahan data berhasil."});
            }
          });
        }
      } else {
        callback(200, {"status": 204});
      }
    });
  } else if(data.payload.orealname == data.payload.realname && data.payload.oinisial != data.payload.inisial && data.payload.ousername == data.payload.username){
    mysqlpool.query('SELECT * FROM appx_users WHERE initial = ?', [data.payload.inisial], (error, queryresultcheckinitial, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckinitial.length == 0){
        let bidangid, bidangname, bidangmodule, subbidangid, subbidangname, subbidangmodule, usermodule, positionname;
        let _rawBidang = data.payload.bidang, 
          _rawSubBidang = data.payload.subbidang;
        let _arrBidang = _rawBidang.split('|'), 
          _arrSubBidang = _rawSubBidang.split('|');
        bidangid = _arrBidang[0];
        bidangname = _arrBidang[1];
        bidangmodule = _arrBidang[2];
        subbidangid = _arrSubBidang[0];
        subbidangname = _arrSubBidang[1];
        subbidangmodule = _arrSubBidang[2];
        if(subbidangid == '0'){
          positionname = 'Staf '+ bidangname;
          usermodule = bidangmodule+'-staf';
          mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , initial = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,0,'_UNDEFINED_',positionname,data.payload.inisial,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
            if(error){
              throw error;
            }
            if(queryresult.affectedRows==0){
              callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
            } else {
              callback(200, {"status":201, "message":"Perubahan data berhasil."});
            }
          });
        } else {
          positionname = 'Staf '+ subbidangname +'';
          usermodule = ''+ bidangmodule +'-'+ subbidangmodule +'-staf';
          mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , initial = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,parseInt(subbidangid),subbidangname,positionname,data.payload.inisial,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
            if(error){
              throw error;
            }
            if(queryresult.affectedRows==0){
              callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
            } else {
              callback(200, {"status":201, "message":"Perubahan data berhasil."});
            }
          });
        }
      } else {
        callback(200, {"status": 205});
      }
    });
  } else if(data.payload.orealname != data.payload.realname && data.payload.oinisial == data.payload.inisial && data.payload.ousername == data.payload.username){
    mysqlpool.query('SELECT * FROM appx_users WHERE realname = ?', [data.payload.realname], (error, queryresultcheckrealname, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckrealname.length == 0){
        let bidangid, bidangname, bidangmodule, subbidangid, subbidangname, subbidangmodule, usermodule, positionname;
        let _rawBidang = data.payload.bidang, 
          _rawSubBidang = data.payload.subbidang;
        let _arrBidang = _rawBidang.split('|'), 
          _arrSubBidang = _rawSubBidang.split('|');
        bidangid = _arrBidang[0];
        bidangname = _arrBidang[1];
        bidangmodule = _arrBidang[2];
        subbidangid = _arrSubBidang[0];
        subbidangname = _arrSubBidang[1];
        subbidangmodule = _arrSubBidang[2];
        if(subbidangid == '0'){
          positionname = 'Staf '+ bidangname;
          usermodule = bidangmodule+'-staf';
          mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , realname = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,0,'_UNDEFINED_',positionname,data.payload.realname,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
            if(error){
              throw error;
            }
            if(queryresult.affectedRows==0){
              callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
            } else {
              callback(200, {"status":201, "message":"Perubahan data berhasil."});
            }
          });
        } else {
          positionname = 'Staf '+ subbidangname +'';
          usermodule = ''+ bidangmodule +'-'+ subbidangmodule +'-staf';
          mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , realname = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,parseInt(subbidangid),subbidangname,positionname,data.payload.realname,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
            if(error){
              throw error;
            }
            if(queryresult.affectedRows==0){
              callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
            } else {
              callback(200, {"status":201, "message":"Perubahan data berhasil."});
            }
          });
        }
      } else {
        callback(200, {"status": 203});
      }
    });
  } else if(data.payload.orealname != data.payload.realname && data.payload.oinisial != data.payload.inisial && data.payload.ousername == data.payload.username){
    mysqlpool.query('SELECT * FROM appx_users WHERE realname = ?', [data.payload.realname], (error, queryresultcheckrealname, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckrealname.length == 0){
        mysqlpool.query('SELECT * FROM appx_users WHERE initial = ?', [data.payload.inisial], (error, queryresultcheckinitial, fields) => {
          if(error){
            throw error;
          }
          if(queryresultcheckinitial.length == 0){
            let bidangid, bidangname, bidangmodule, subbidangid, subbidangname, subbidangmodule, usermodule, positionname;
            let _rawBidang = data.payload.bidang, 
              _rawSubBidang = data.payload.subbidang;
            let _arrBidang = _rawBidang.split('|'), 
              _arrSubBidang = _rawSubBidang.split('|');
            bidangid = _arrBidang[0];
            bidangname = _arrBidang[1];
            bidangmodule = _arrBidang[2];
            subbidangid = _arrSubBidang[0];
            subbidangname = _arrSubBidang[1];
            subbidangmodule = _arrSubBidang[2];
            if(subbidangid == '0'){
              positionname = 'Staf '+ bidangname;
              usermodule = bidangmodule+'-staf';
              mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , realname = ? , initial = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,0,'_UNDEFINED_',positionname,data.payload.realname,data.payload.inisial,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
                if(error){
                  throw error;
                }
                if(queryresult.affectedRows==0){
                  callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
                } else {
                  callback(200, {"status":201, "message":"Perubahan data berhasil."});
                }
              });
            } else {
              positionname = 'Staf '+ subbidangname +'';
              usermodule = ''+ bidangmodule +'-'+ subbidangmodule +'-staf';
              mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , realname = ? , initial = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,parseInt(subbidangid),subbidangname,positionname,data.payload.realname,data.payload.inisial,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
                if(error){
                  throw error;
                }
                if(queryresult.affectedRows==0){
                  callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
                } else {
                  callback(200, {"status":201, "message":"Perubahan data berhasil."});
                }
              });
            }
          } else {
            callback(200, {"status": 205});
          }
        });
      } else {
        callback(200, {"status": 203});
      }
    });
  } else if(data.payload.orealname != data.payload.realname && data.payload.oinisial == data.payload.inisial && data.payload.ousername != data.payload.username){
    mysqlpool.query('SELECT * FROM appx_users WHERE realname = ?', [data.payload.realname], (error, queryresultcheckrealname, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckrealname.length == 0){
        mysqlpool.query('SELECT * FROM appx_users WHERE username = ?', [data.payload.username], (error, queryresultcheckusername, fields) => {
          if(error){
            throw error;
          }
          if(queryresultcheckusername.length == 0){
            let bidangid, bidangname, bidangmodule, subbidangid, subbidangname, subbidangmodule, usermodule, positionname;
            let _rawBidang = data.payload.bidang, 
              _rawSubBidang = data.payload.subbidang;
            let _arrBidang = _rawBidang.split('|'), 
              _arrSubBidang = _rawSubBidang.split('|');
            bidangid = _arrBidang[0];
            bidangname = _arrBidang[1];
            bidangmodule = _arrBidang[2];
            subbidangid = _arrSubBidang[0];
            subbidangname = _arrSubBidang[1];
            subbidangmodule = _arrSubBidang[2];
            if(subbidangid == '0'){
              positionname = 'Staf '+ bidangname;
              usermodule = bidangmodule+'-staf';
              mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , realname = ? , username = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,0,'_UNDEFINED_',positionname,data.payload.realname,data.payload.username,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
                if(error){
                  throw error;
                }
                if(queryresult.affectedRows==0){
                  callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
                } else {
                  callback(200, {"status":201, "message":"Perubahan data berhasil."});
                }
              });
            } else {
              positionname = 'Staf '+ subbidangname +'';
              usermodule = ''+ bidangmodule +'-'+ subbidangmodule +'-staf';
              mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , realname = ? , username = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,parseInt(subbidangid),subbidangname,positionname,data.payload.realname,data.payload.username,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
                if(error){
                  throw error;
                }
                if(queryresult.affectedRows==0){
                  callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
                } else {
                  callback(200, {"status":201, "message":"Perubahan data berhasil."});
                }
              });
            }
          } else {
            callback(200, {"status": 204});
          }
        });
      } else {
        callback(200, {"status": 203});
      }
    });
  } else if(data.payload.orealname == data.payload.realname && data.payload.oinisial != data.payload.inisial && data.payload.ousername != data.payload.username){
    mysqlpool.query('SELECT * FROM appx_users WHERE username = ?', [data.payload.username], (error, queryresultcheckusername, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckusername.length == 0){
        mysqlpool.query('SELECT * FROM appx_users WHERE initial = ?', [data.payload.inisial], (error, queryresultcheckinitial, fields) => {
          if(error){
            throw error;
          }
          if(queryresultcheckinitial.length == 0){
            let bidangid, bidangname, bidangmodule, subbidangid, subbidangname, subbidangmodule, usermodule, positionname;
            let _rawBidang = data.payload.bidang, 
              _rawSubBidang = data.payload.subbidang;
            let _arrBidang = _rawBidang.split('|'), 
              _arrSubBidang = _rawSubBidang.split('|');
            bidangid = _arrBidang[0];
            bidangname = _arrBidang[1];
            bidangmodule = _arrBidang[2];
            subbidangid = _arrSubBidang[0];
            subbidangname = _arrSubBidang[1];
            subbidangmodule = _arrSubBidang[2];
            if(subbidangid == '0'){
              positionname = 'Staf '+ bidangname;
              usermodule = bidangmodule+'-staf';
              mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , realname = ? , initial = ? , username = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,0,'_UNDEFINED_',positionname,data.payload.realname,data.payload.inisial,data.payload.username,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
                if(error){
                  throw error;
                }
                if(queryresult.affectedRows==0){
                  callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
                } else {
                  callback(200, {"status":201, "message":"Perubahan data berhasil."});
                }
              });
            } else {
              positionname = 'Staf '+ subbidangname +'';
              usermodule = ''+ bidangmodule +'-'+ subbidangmodule +'-staf';
              mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , realname = ? , initial = ? , username = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,parseInt(subbidangid),subbidangname,positionname,data.payload.realname,data.payload.inisial,data.payload.username,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
                if(error){
                  throw error;
                }
                if(queryresult.affectedRows==0){
                  callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
                } else {
                  callback(200, {"status":201, "message":"Perubahan data berhasil."});
                }
              });
            }
          } else {
            callback(200, {"status": 205});
          }
        });
      } else {
        callback(200, {"status": 204});
      }
    });
  } else if(data.payload.orealname != data.payload.realname && data.payload.oinisial != data.payload.inisial && data.payload.ousername != data.payload.username){
    mysqlpool.query('SELECT * FROM appx_users WHERE realname = ?', [data.payload.realname], (error, queryresultcheckrealname, fields) => {
      if(error){
        throw error;
      }
      if(queryresultcheckrealname.length == 0){
        mysqlpool.query('SELECT * FROM appx_users WHERE username = ?', [data.payload.username], (error, queryresultcheckusername, fields) => {
          if(error){
            throw error;
          }
          if(queryresultcheckusername.length == 0){
            mysqlpool.query('SELECT * FROM appx_users WHERE initial = ?', [data.payload.inisial], (error, queryresultcheckinitial, fields) => {
              if(error){
                throw error;
              }
              if(queryresultcheckinitial.length == 0){
                let bidangid, bidangname, bidangmodule, subbidangid, subbidangname, subbidangmodule, usermodule, positionname;
                let _rawBidang = data.payload.bidang, 
                  _rawSubBidang = data.payload.subbidang;
                let _arrBidang = _rawBidang.split('|'), 
                  _arrSubBidang = _rawSubBidang.split('|');
                bidangid = _arrBidang[0];
                bidangname = _arrBidang[1];
                bidangmodule = _arrBidang[2];
                subbidangid = _arrSubBidang[0];
                subbidangname = _arrSubBidang[1];
                subbidangmodule = _arrSubBidang[2];
                if(subbidangid == '0'){
                  positionname = 'Staf '+ bidangname;
                  usermodule = bidangmodule+'-staf';
                  mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , realname = ? , initial = ? , username = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,0,'_UNDEFINED_',positionname,data.payload.realname,data.payload.inisial,data.payload.username,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
                    if(error){
                      throw error;
                    }
                    if(queryresult.affectedRows==0){
                      callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
                    } else {
                      callback(200, {"status":201, "message":"Perubahan data berhasil."});
                    }
                  });
                } else {
                  positionname = 'Staf '+ subbidangname +'';
                  usermodule = ''+ bidangmodule +'-'+ subbidangmodule +'-staf';
                  mysqlpool.query('UPDATE appx_users SET bidangid = ? , bidang = ? , subbidangid = ? , subbidang = ? , posisi = ? , realname = ? , initial = ? , username = ? , module = ? , verificator = ? , collector = ? WHERE idx = ?', [parseInt(bidangid),bidangname,parseInt(subbidangid),subbidangname,positionname,data.payload.realname,data.payload.inisial,data.payload.username,usermodule,parseInt(data.payload.verificator),parseInt(data.payload.collector),parseInt(data.payload.idx)], (error, queryresult, fields) => {
                    if(error){
                      throw error;
                    }
                    if(queryresult.affectedRows==0){
                      callback(200, {"status":202,"message":"Perubahan data berhasil, tetapi data tetap tidak berubah."});
                    } else {
                      callback(200, {"status":201, "message":"Perubahan data berhasil."});
                    }
                  });
                }
              } else {
                callback(200, {"status": 205});
              }
            });
          } else {
            callback(200, {"status": 204});
          }
        });
      } else {
        callback(200, {"status": 203});
      }
    });
  } else {
    callback(405);
  }
};

/* End-Point: /createNotarisPPATUser */
handlers.createNotarisPPATUser = function(data,callback){
  var acceptableMethods = ['post','options'];
  if(acceptableMethods.indexOf(data.method) > -1){
    let response = {"status":201};
    callback(200, response);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};

/* End-Point: /editNotarisPPATUser */
handlers.editNotarisPPATUser = function(data,callback){
  var acceptableMethods = ['post','options'];
  if(acceptableMethods.indexOf(data.method) > -1){
    let response = {"status":201};
    callback(200, response);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};

/* End-Point: /sysResetUsers */
handlers.sysResetUsers = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._sysResetUsers[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._sysResetUsers = {};
handlers._sysResetUsers.post = function(data,callback){
  if(data.payload.command && data.payload.command == 'reset-all-users'){
    mysqlpool.query('DELETE FROM appx_users WHERE idx > 29', [], (error, queryresult, fields) => {
      if(error){
        throw error;
      }
      if(queryresult.affectedRows==0){
        callback(200, {"status":404});
      } else {
        mysqlpool.query('ALTER TABLE appx_users AUTO_INCREMENT = 30', [], (error, queryresult, fields) => {
          if(error){
            throw error;
          }
          let response = {"status":201,"message":"Table appx_users is reset."};
          callback(200, response);
        });
      }
    });
  } else if(data.payload.command && data.payload.command == 'reset-internal-bkad-users'){
    mysqlpool.query('DELETE FROM appx_users WHERE idx > 29 AND origin = ? AND context = ?', ['MAPATDA_BKAD','BKAD'], (error, queryresult, fields) => {
      if(error){
        throw error;
      }
      if(queryresult.affectedRows==0){
        callback(200, {"status":404});
      } else {
        mysqlpool.query('ALTER TABLE appx_users AUTO_INCREMENT = 30', [], (error, queryresult, fields) => {
          if(error){
            throw error;
          }
          let response = {"status":201,"message":"Table appx_users is reset."};
          callback(200, response);
        });
      }
    });
  } else {
    callback(405);
  }
};

module.exports = handlers;