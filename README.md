## composer 集成
```json
{
  "require": {
    "tencent/tls-sig-api": "1.0"
  }
}
```


## 调用接口

### 默认过期时间
```php
require 'vendor/autoload.php';

$api = new Tencent\TLSSigAPI();
$api->SetAppid(140000000);
$private = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'private_key');
$api->SetPrivateKey($private);
$sig = $api->genSig('xiaojun');
var_export($sig);
```

### 指定过期时间
```php
require 'vendor/autoload.php';

$api = new Tencent\TLSSigAPI();
$api->SetAppid(140000000);
$private = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'private_key');
$api->SetPrivateKey($private);
$sig = $api->genSig('xiaojun', 24*3600*180);
var_export($sig);
```