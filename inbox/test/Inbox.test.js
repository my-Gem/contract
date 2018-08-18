const assert  = require('assert');
const ganache = require('ganache-cli');
//引入这个是为了下面测试部署智能合约做准备,interface在这里是json对象
const {interface,bytecode} = require('../compile');
//约定的变量如果变量以大写做字母开头
const Web3 = require('web3');
//把ganache测试网络的卡插入到web3里面
const web3 = new Web3(ganache.provider());

describe('测试智能合约',()=>{
    //it代表的是每一个测试用例
    it('测试web3的版本',()=>{
        console.log(web3.version);
    });

    it('测试web3的api',async ()=>{
        const accounts = await web3.eth.getAccounts();
        console.log(accounts);
        const money = await web3.eth.getBalance(accounts[0]);
        console.log(web3.utils.fromWei(money, 'ether'));
    });

    it('测试部署智能合约',async()=>{
        const accounts = await web3.eth.getAccounts();
      const result =  await new web3.eth.Contract(JSON.parse(interface)).deploy({data:bytecode,arguments:['abc']}).send({
        from:accounts[0],
            gas:1000000
        });

        //智能合约部署的地址
        console.log('address:'+result.options.address);
        let message = await result.methods.getMessage().call();
        console.log(message);
        assert.equal(message,'abc');

        await  result.methods.setMessage('itheima').send({
            from :accounts[0],
            gas:1000000
        });
         message = await result.methods.getMessage().call();
        console.log(message);
        assert.equal(message,'itheima');
    });
});