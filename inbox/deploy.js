//部署智能合约到Rinkeby Test网络
const Web3 =require('web3');
const {interface,bytecode} = require('./compile');
const HDWalletProvider = require('truffle-hdwallet-provider');
//12个助记词
const mnemonic = 'ripple skirt seat diamond upgrade remove spare globe again cry amount excuse';
//连接infura提供的节点同步到以太坊网络
const provider = new HDWalletProvider(mnemonic,"https://rinkeby.infura.io/v3/f5f165a5767744daacb53c8bfc2e6031");
const web3 = new Web3(provider);

deploy=async ()=>{
    const accounts = await web3.eth.getAccounts();
    //metamask的账户地址0x94a62d35074fa068466DB5675a7b4F022B746e14
   console.log(accounts[0]);
    //拿到智能合约在网络上的地址
    const result = await new web3.eth.Contract(JSON.parse(interface)).deploy({
        data:bytecode,
        arguments:['abc']
    }).send({
        from:accounts[0],
        gas:'3000000'
    });
    console.log('address:' + result.options.address);
};
deploy();





