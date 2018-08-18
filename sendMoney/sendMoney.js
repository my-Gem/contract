const Web3 = require('web3');
//测试的时候使用的是ganache环境，真实转帐的时候就得使用truffle-hdwallet-provider钱包
//const ganache = require('ganache-cli');
const HDWalletProvider = require("truffle-hdwallet-provider");
const mnemonic = "ripple skirt seat diamond upgrade remove spare globe again cry amount excuse";
const provider = new HDWalletProvider(mnemonic,"https://rinkeby.infura.io/v3/f5f165a5767744daacb53c8bfc2e6031");
//const web3 = new Web3(ganache.provider());
const web3 = new Web3(provider);

send = async()=>{
      //拿到了一组账户
    const accounts =  await web3.eth.getAccounts();
     //用第0和账户给第一个账户转钱
    // noinspection JSAnnotator
    let account0balance = await web3.eth.getBalance(accounts[0]);

    console.log("account0balance:"+account0balance+"wei");

    const str ="I love 蒋玲 forever";
    let data = Buffer.from(str).toString('hex');
    data = '0x'+data;

    await web3.eth.sendTransaction({
        from :accounts[0],
        to:'0x63a96c20eb8668ed62ae5101a6faaa5038e694b4',
        value:'1000000000000000000',
        data:data
    });
    account0balance = await web3.eth.getBalance(accounts[0]);


    console.log("account0balance:"+account0balance+"wei");

};

send();

//运行之后，打印出的转账新信息
// account0balance:100000000000000000000wei
// account1balance:100000000000000000000wei
// account0balance:99999957999990000000wei
// account1balance:100000000000010000000wei
