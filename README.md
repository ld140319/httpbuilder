PHP Curl Http Client

### Usage

Http Client Support Http Method : GET, POST, PUT , DELETE

### 构建 HttpClient

```php

protected $client ;
function __construct()
{
    $this->client = HttpClientBuilder::create()->build();
}

```

### GET Request

```php

$data = [
    'uri'=>'https://www.baidu.com',
    'headers'=>[
        'Content-Type'=>'application/json',
        'X-HTTP-Method-Override':'GET'
    ]
];

return $this->client->Get($data);

```

### POST Request

```php

$data = [
    'uri'=>'https://www.baidu.com',
    'headers'=>[
        'Content-Type'=>'application/json',
        'X-HTTP-Method-Override':'GET'
    ],
    'params'=> [
        'user'=>ethan
     ]
];

return $this->client->Post($data);

```

### PUT 、DELETE Request

```php

$data = [
    'uri'=>'https://www.baidu.com',
    'headers'=>[
        'Content-Type'=>'application/json',
        'X-HTTP-Method-Override':'GET'
    ],
    'params'=> [
        'user'=>ethan
     ]
];

return $this->client->Put($data); // Delete($data)

```

### 扩展
文件上传

