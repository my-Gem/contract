//编译智能合约脚本
const path = require('path');
const fs = require('fs');
const  solc = require('solc');

//获取智能合约源代码的路径
const srcpath = path.resolve(__dirname,'contracts','Inbox.sol');
//console.log(srcpath);
//获取源代码的内容
const source = fs.readFileSync(srcpath,'utf-8');
//console.log(source);
//编译智能合约,1是一个占位符
const result = solc.compile(source,1);
//console.log(result);

module.exports = result.contracts[':Inbox'];


