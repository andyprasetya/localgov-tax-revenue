var _data = require('./data');
var helpers = require('./helpers');
var config = require('./config');

var mysqlpool = _data.mysqlPool;

var handlers = {};

/* End-Point: /doLogin */
handlers.doLogin = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._doLogin[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._doLogin = {};
handlers._doLogin.post = function(data, callback){
  const hashedPass = helpers.hash(data.payload.password);
  mysqlpool.query('SELECT * FROM appx_users WHERE username = ? AND wordpass = ? AND origin = ? AND context = ? AND status = 1', [data.payload.username, hashedPass, 'MAPATDA_BKAD', 'BKAD'], (error, queryresult, fields) => {
    if(error){
      throw error;
    }
    if(queryresult.length==0){
      callback(200, {"status":404});
    } else {
      // create session id
      let sessionId = helpers.createRandomString(32);
      // set session expiration
      let expires = Date.now() + 1000 * 60 * 60;
      let response = {}, sessionObject;
      Object.keys(queryresult).forEach(function(key) {
        let row = queryresult[key];
        sessionObject = {
          'status': 201,
          'sessionid': sessionId,
          'idx': row.idx,
          'origin': row.origin,
          'opdcode': row.opdcode,
          'bidangid': row.bidangid,
          'bidang': row.bidang,
          'subbidangid': row.subbidangid,
          'subbidang': row.subbidang,
          'posisi': row.posisi,
          'spvidx': row.spvidx,
          'context': row.context,
          'realname': row.realname,
          'initial': row.initial,
          'username': row.username,
          'hashedpassword': row.wordpass,
          'module': row.module,
          'add_module': row.add_module,
          'verificator': row.verificator,
          'collector': row.collector,
          'user_status': row.status,
          'expires' : expires
        };
      });
      _data.create('sessions', sessionId, sessionObject, function(err){
        if(!err){
          callback(200, sessionObject);
        } else {
          callback(500, {'Error' : 'Could not create the new session'});
        }
      });
    }
  });  
};

/* End-Point: /doNotarisPPATLogin */
handlers.doNotarisPPATLogin = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._doNotarisPPATLogin[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._doNotarisPPATLogin = {};
handlers._doNotarisPPATLogin.post = function(data, callback){
  const hashedPass = helpers.hash(data.payload.password);
  mysqlpool.query('SELECT * FROM notaris_ppat_users WHERE username = ? AND wordpass = ? AND status = 1', [data.payload.username, hashedPass], (error, queryresult, fields) => {
    if(error){
      throw error;
    }
    if(queryresult.length==0){
      callback(200, {"status":404});
    } else {
      // create session id
      let sessionId = helpers.createRandomString(32);
      // set session expiration
      let expires = Date.now() + 1000 * 60 * 60;
      let response = {}, sessionObject;
      Object.keys(queryresult).forEach(function(key) {
        let row = queryresult[key];
        sessionObject = {
          'status': 201,
          'sessionid': sessionId,
          'idx': row.id,
          'docidx': row.docidx,
          'kode': row.kode,
          'notaris_ppat': row.notaris_ppat,
          'realname': row.realname,
          'username': row.username,
          'hashedpassword': row.wordpass,
          'address': row.address,
          'phone': row.phone,
          'email': row.email,
          'user_status': row.status,
          'expires' : expires
        };
      });
      _data.create('sessions', sessionId, sessionObject, function(err){
        if(!err){
          callback(200, sessionObject);
        } else {
          callback(500, {'Error' : 'Could not create the new session'});
        }
      });
    }
  });  
};

/* End-Point: /doLogoff */
handlers.doLogoff = function(data, callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._doLogoff[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._doLogoff = {};
handlers._doLogoff.post = function(data, callback){
  let sessionId = data.payload.sessionid;
  _data.read('sessions', sessionId, function(errread){
    if(!errread){
      _data.delete('sessions',sessionId,function(errdelete){
        if(!errdelete){
          callback(200, {"status": 201, "message":"loggedout"});
        } else {
          callback(500, {'Error' : 'Could not terminate current session'});
        }
      });
    } else {
      callback(200, {"status": 201, "message":"loggedout"});
    }
  });
};

/* End-Point: /doNotarisPPATLogoff */
handlers.doNotarisPPATLogoff = function(data, callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._doNotarisPPATLogoff[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._doNotarisPPATLogoff = {};
handlers._doNotarisPPATLogoff.post = function(data, callback){
  let sessionId = data.payload.sessionid;
  _data.read('sessions', sessionId, function(errread){
    if(!errread){
      _data.delete('sessions',sessionId,function(errdelete){
        if(!errdelete){
          callback(200, {"status": 201, "message":"loggedout"});
        } else {
          callback(500, {'Error' : 'Could not terminate current session'});
        }
      });
    } else {
      callback(200, {"status": 201, "message":"loggedout"});
    }
  });
};

/* End-Point: /changePassword */
handlers.changePassword = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._changePassword[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._changePassword = {};
handlers._changePassword.post = function(data, callback){
  let _userid = data.payload.userid, 
    _oldPassword = data.payload.oldpassword, 
    _hashedNewPassword = helpers.hash(data.payload.newpassword), 
    _hashedCnfPassword = helpers.hash(data.payload.cnfpassword);
  if(_oldPassword == _hashedNewPassword || _oldPassword == _hashedCnfPassword){
    callback(200, {"status":202,"message":"Password baru/konfirmasi sama dengan password sekarang."});
  } else {
    mysqlpool.query('UPDATE appx_users SET wordpass = ? WHERE idx = ? LIMIT 1', [_hashedNewPassword, _userid], (error, queryresult, fields) => {
      if(error){
        throw error;
      }
      if(queryresult.changedRows == 1){
        callback(200, {"status":201,"message":"Pembaruan password berhasil. Proses keluar aplikasi, Anda harus login kembali..."});
      } else {
        callback(200, {"status":202,"message":"Pembaruan password Anda gagal. Hubungi administrator sistem Anda untuk mendapatkan bantuan teknis."});
      }
    });
  }  
}

/* End-Point: /changeNotarisPPATPassword */
handlers.changeNotarisPPATPassword = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._changeNotarisPPATPassword[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._changeNotarisPPATPassword = {};
handlers._changeNotarisPPATPassword.post = function(data, callback){
  let _userid = data.payload.userid, 
    _oldPassword = data.payload.oldpassword, 
    _hashedNewPassword = helpers.hash(data.payload.newpassword), 
    _hashedCnfPassword = helpers.hash(data.payload.cnfpassword);
  if(_oldPassword == _hashedNewPassword || _oldPassword == _hashedCnfPassword){
    callback(200, {"status":202,"message":"Password baru/konfirmasi sama dengan password sekarang."});
  } else {
    mysqlpool.query('UPDATE notaris_ppat_users SET wordpass = ? WHERE id = ? LIMIT 1', [_hashedNewPassword, _userid], (error, queryresult, fields) => {
      if(error){
        throw error;
      }
      if(queryresult.changedRows == 1){
        callback(200, {"status":201,"message":"Pembaruan password berhasil. Proses keluar aplikasi, Anda harus login kembali..."});
      } else {
        callback(200, {"status":202,"message":"Pembaruan password Anda gagal. Hubungi administrator sistem Anda untuk mendapatkan bantuan teknis."});
      }
    });
  }  
}

/* End-Point: /resetPassword */
handlers.resetPassword = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._resetPassword[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._resetPassword = {};
handlers._resetPassword.post = function(data, callback){
  let _userid = data.payload.userid,  
    _hashedNewPassword = helpers.hash('1234');
  mysqlpool.query('UPDATE appx_users SET wordpass = ? WHERE idx = ? LIMIT 1', [_hashedNewPassword, _userid], (error, queryresult, fields) => {
    if(error){
      throw error;
    }
    if(queryresult.changedRows == 1){
      callback(200, {"status":201,"message":"Reset password berhasil. Password telah berubah ke 1234."});
    } else {
      callback(200, {"status":202,"message":"Reset password gagal."});
    }
  });
};

/* End-Point: /resetNotarisPPATPassword */
handlers.resetNotarisPPATPassword = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._resetNotarisPPATPassword[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._resetNotarisPPATPassword = {};
handlers._resetNotarisPPATPassword.post = function(data, callback){
  let _userid = data.payload.userid,  
    _hashedNewPassword = helpers.hash('1234');
  mysqlpool.query('UPDATE notaris_ppat_users SET wordpass = ? WHERE id = ? LIMIT 1', [_hashedNewPassword, _userid], (error, queryresult, fields) => {
    if(error){
      throw error;
    }
    if(queryresult.changedRows == 1){
      callback(200, {"status":201,"message":"Reset password berhasil. Password telah berubah ke 1234."});
    } else {
      callback(200, {"status":202,"message":"Reset password gagal."});
    }
  });
};

/* End-Point: /deactivateUser */
handlers.deactivateUser = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._deactivateUser[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._deactivateUser = {};
handlers._deactivateUser.post = function(data, callback){
  let _userid = data.payload.userid;
  mysqlpool.query('UPDATE appx_users SET status = 0 WHERE idx = ? LIMIT 1', [_userid], (error, queryresult, fields) => {
    if(error){
      throw error;
    }
    if(queryresult.changedRows == 1){
      callback(200, {"status":201,"message":"Deaktivasi user berhasil."});
    } else {
      callback(200, {"status":202,"message":"Deaktivasi user gagal. Hubungi Administrator Sistem Anda."});
    }
  });
};

/* End-Point: /deactivateNotarisPPATUser */
handlers.deactivateNotarisPPATUser = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._deactivateNotarisPPATUser[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._deactivateNotarisPPATUser = {};
handlers._deactivateNotarisPPATUser.post = function(data, callback){
  let _userid = data.payload.userid;
  mysqlpool.query('UPDATE notaris_ppat_users SET status = 0 WHERE id = ? LIMIT 1', [_userid], (error, queryresult, fields) => {
    if(error){
      throw error;
    }
    if(queryresult.changedRows == 1){
      callback(200, {"status":201,"message":"Deaktivasi user berhasil."});
    } else {
      callback(200, {"status":202,"message":"Deaktivasi user gagal. Hubungi Administrator Sistem Anda."});
    }
  });
};

/* End-Point: /activateUser */
handlers.activateUser = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._activateUser[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._activateUser = {};
handlers._activateUser.post = function(data, callback){
  let _userid = data.payload.userid;
  mysqlpool.query('UPDATE appx_users SET status = 1 WHERE idx = ? LIMIT 1', [_userid], (error, queryresult, fields) => {
    if(error){
      throw error;
    }
    if(queryresult.changedRows == 1){
      callback(200, {"status":201,"message":"Aktivasi user berhasil."});
    } else {
      callback(200, {"status":202,"message":"Aktivasi user gagal. Hubungi Administrator Sistem Anda."});
    }
  });
};

/* End-Point: /activateNotarisPPATUser */
handlers.activateNotarisPPATUser = function(data,callback){
  var acceptableMethods = ['post'];
  if(acceptableMethods.indexOf(data.method) > -1){
    handlers._activateNotarisPPATUser[data.method](data, callback);
  } else {
    if(config.envName == 'staging'){
      callback(200);
    } else {
      callback(405);
    }
  }
};
handlers._activateNotarisPPATUser = {};
handlers._activateNotarisPPATUser.post = function(data, callback){
  let _userid = data.payload.userid;
  mysqlpool.query('UPDATE notaris_ppat_users SET status = 1 WHERE id = ? LIMIT 1', [_userid], (error, queryresult, fields) => {
    if(error){
      throw error;
    }
    if(queryresult.changedRows == 1){
      callback(200, {"status":201,"message":"Aktivasi user berhasil."});
    } else {
      callback(200, {"status":202,"message":"Aktivasi user gagal. Hubungi Administrator Sistem Anda."});
    }
  });
};

/* End-Point: /generatePassword */
handlers.generatePassword = function(data,callback){
  var acceptableMethods = ['post','options'];
  if(acceptableMethods.indexOf(data.method) > -1){
    let response = {"status":201};
    callback(200, response);
  } else {
    callback(405);
  }
};

module.exports = handlers;