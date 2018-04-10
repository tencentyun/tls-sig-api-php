see demo
```php
try{
    $api = new TLSSigAPI();
    $api->SetAppid(1);//设置在腾讯云申请的appid
    $private = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'ec_key.pem');
    $api->SetPrivateKey($private);//生成usersig需要先设置私钥
    $public = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'public.pem');
    $api->SetPublicKey($public);//校验usersig需要先设置公钥
    $sig = $api->genSig('user1');//生成usersig
    $result = $api->verifySig($sig, 'user1', $init_time, $expire_time, $error_msg);//校验usersig
    var_dump($result);
    var_dump($init_time);
    var_dump($expire_time);
    var_dump($error_msg);
    
    $result = $api->verifySig($sig, 'user2', $init_time, $expire_time, $error_msg);
    var_dump($result);
    var_dump($init_time);
    var_dump($expire_time);
    var_dump($error_msg);
}catch(\Exception $e){
    echo $e->getMessage();
}
```
