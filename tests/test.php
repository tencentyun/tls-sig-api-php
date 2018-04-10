<?php
include __DIR__.'/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use Tencent\TLSSigAPI;

final class TLSSigAPITest extends TestCase
{
    static private $private_key_string = <<<'EOT'
-----BEGIN EC PARAMETERS-----
BgUrgQQACg==
-----END EC PARAMETERS-----
-----BEGIN EC PRIVATE KEY-----
MHQCAQEEIEJDBDY4KVdj3dPBacADreB772ok45A57YWrUUvc5fMQoAcGBSuBBAAK
oUQDQgAEaPVFHhWqRDnKnVlyU5JIzXOUyOJd/pPUwhLUovf+PYBm7otRBptnvJ4E
oJ4qeSJNG0v4XdiqM3mtChkhUEFT3Q==
-----END EC PRIVATE KEY-----
EOT;
    static private $public_key_string = <<<'EOT'
-----BEGIN PUBLIC KEY-----
MFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEaPVFHhWqRDnKnVlyU5JIzXOUyOJd/pPU
whLUovf+PYBm7otRBptnvJ4EoJ4qeSJNG0v4XdiqM3mtChkhUEFT3Q==
-----END PUBLIC KEY-----
EOT;

    public function testGenAndVerify()
    {
        $api = new TLSSigAPI();
        $api->SetAppid(1400001052);//设置在腾讯云申请的appid
        $api->SetPrivateKey(self::$private_key_string);//生成usersig需要先设置私钥
        $api->SetPublicKey(self::$public_key_string);//校验usersig需要先设置公钥
        $sig = $api->genSig('user1');//生成usersig
        $result = $api->verifySig($sig, 'user1', $init_time, $expire_time, $error_msg);//校验usersig
        $this->assertEquals(true, $result);

        $result = $api->verifySig($sig, 'user2', $init_time, $expire_time, $error_msg);
        $this->assertEquals(false, $result);
    }

    public function testGenAndVerifyUserbuf()
    {
        $api = new TLSSigAPI();
        $api->SetAppid(1400001052);//设置在腾讯云申请的appid
        $api->SetPrivateKey(self::$private_key_string);//生成usersig需要先设置私钥
        $api->SetPublicKey(self::$public_key_string);//校验usersig需要先设置公钥
        $sig = $api->genSigWithUserbuf('user1', 'buf');//生成usersig
        $result = $api->verifySigWithUserbuf($sig, 'user1', $init_time, $expire_time, $userbuf, $error_msg);//校验usersig
        $this->assertEquals(true, $result);
        $this->assertEquals('buf', $userbuf);

        $result = $api->verifySigWithUserbuf($sig, 'user2', $init_time, $expire_time, $userbuf, $error_msg);
        $this->assertEquals(false, $result);
    }

    public function testVerify()
    {
        $api = new TLSSigAPI();
        $api->SetAppid(1400001052);//设置在腾讯云申请的appid
        $api->SetPublicKey(self::$public_key_string);//校验usersig需要先设置公钥
        $result = $api->verifySig('eAFNjlFvgjAUhf9LX1lGbxFZl-iwWGcUt8jQsflCOlpdJwKBguiy-24lmOw8ft89J-cXrRbhPU*SvM50rE*FRI8Io7sOKyEzrbZKlgbyr6THvCiUiLmOnVL8u67EPu6UYTDAJoBd0ndkW6hSxnyruzF6S68bWVYqz0yTYHjADnGu-V5qdbh*BS4BSgHobbNSO4NfJuvxLGCrd8Ws*ZPX*lEz9vYhnZwHDXx6wfLNXnrpccqzj5bZ6Tk8znbR9w-Poa3hdQgsPwQMnjV2N-bGF9O5X5M0stbEWqhhHYzQ3wVIlVX1', 'abc', $init_time, $expire_time, $error_msg);//校验usersig
        echo $error_msg;
        $this->assertEquals(true, $result);

        $result = $api->verifySigWithUserbuf('eAE1jl1vgjAYhf9Lb1lcKxDHEi*YNh2ESZiK86ppS9GKH3y028zifx*Yei6f55y87x9YJctRV1SU1bUqwCtAHuyDoD8GT3epCnnWqlSy7S3jwmKtTnKo*2MUBGjieZbL31q1krJS3weBjbVMiIs5a6qv9bCGDzwcp0xTtx1*eGDTyZabsifbTXyw3U7tevCB17OIVE0a7k38HhHnJU4LPk*Mm2FhMp6S*dfMeb5KlnkIbT9DhUNN4twngawgdJsjz8X3m1iECV7nBwMvG3yKFs6**dktiZiC2z-8z1UA', 'abc', $init_time, $expire_time, $userbuf, $error_msg);
        $this->assertEquals(true, $result);
        $this->assertEquals('abc', $userbuf);
    }

    public function testWithout3rd()
    {
        $api = new TLSSigAPI();
        $api->SetAppid(1400000226);//设置在腾讯云申请的appid
        $api->SetPublicKey(<<<'EOT'
-----BEGIN PUBLIC KEY-----
MFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAED5Ffi4qIe4XUZ5zDGR9pC0Z6UL/gCHf0
vgoLVestQxqOGJB5mcbaKULeriaevZoq0Sx8gGtfDlSf4fXwzPtGvg==
-----END PUBLIC KEY-----
EOT
);//校验usersig需要先设置公钥
        $result = $api->verifySig('eJxNz11vgjAYBeB7fkXTW41rKwL1bkMMGnGyidu8IaxUfdehFWpkMfvv8ysL5-Y5yck5WQghPJ*8djIhdoetSc2Plhj1EabUwe1-lrWGUqbZysjyyqzHGSGkUYFcbg2s4F6gZ6UNrnKVZlpDflObXMJYc6SC9RWj4MMfxf57lz0xb8ztcd2aKgrTJYdSh8lsU8dmcnQTFaqdGC48dRxtHp8DL5qLw-fnW*Q8BCJosXj-FfpLx7eLRf2iOUnWVTEgXuI2Jg0U97s9do7LXY6tX*sPGj5LXg__', '10001', $init_time, $expire_time, $error_msg);//校验usersig
        $this->assertEquals(true, $result);
    }
}
