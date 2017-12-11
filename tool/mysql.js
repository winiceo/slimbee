

const mysql = require('mysql2/promise');

// get the promise implementation, we will use bluebird
const bluebird = require('bluebird');

// create the connection, specify bluebird as Promise
const connection =  mysql.createConnection({host:'192.168.10.10', user: 'homestead',password:'secret', database: 'bee', Promise: bluebird});

// query database
const rows =  connection.execute(' select * from INFORMATION_SCHEMA.Columns where table_name=\'advert\' ' );
// Object.forEach(rows,function(n){
//     console.log(n)
// })

console.log(rows)