PHP Curl Http Client

### Usage

Http Client Support Http Method : GET, POST, PUT , DELETE , HEADER , TRACE

### 构建 HttpClient

```php

protected $client ;
function __construct()
{
    $this->client = HttpClientBuilder::create()->build(); 
    // 如果你想使用自带的日志类，如 laravel中 Log
    $this->client = HttpClientBuilder::create()->build(Log::class); 

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
        'user'=>"username:ethan"
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
        'user'=>"username:ethan"
     ]
];

return $this->client->Put($data); // Put,Delete($data)

```


### HEADER 、TRACE Request

```php

$data = [
    'uri'=>'https://www.baidu.com',
    'headers'=>[
        'Content-Type'=>'application/json',
        'X-HTTP-Method-Override':'GET'
    ],
    'params'=> [
        'user'=>"username:ethan"
     ]
];

return $this->client->Header($data); // Header,Trace($data)

```


### 扩展
文件上传

```php

$file = Request::file('temp'); // 接收前端传递的文件data
$originalName = $file->getClientOriginalName(); // 文件原名
$ext = $file->getClientOriginalExtension();     // 扩展名
$realPath = $file->getRealPath();   //临时文件的绝对路径
$type = $file->getClientMimeType();
$file_obj = new \CURLFile($realPath, $type, $originalName);
$data = [
            'url'=> $url,
            'params'=>[
                'file'=>$file_obj
            ]
        ];

return $this->client->Post($data); // Upload($data)

```


