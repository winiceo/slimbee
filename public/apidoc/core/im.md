## 消息结构体(jsonSchema)

```json5
{
  "$schema": "http://json-schema.org/draft-06/schema#",
  "title": "Product set",
  "type": "object",
  "items": {
    "title": "Message",
    "type": "object",
    "properties": {
      "order_id": {
        "description": "订单编号",
        "type": "number"
      },
      "from": {
        "type": "number",
        "description": "消息发送者ID"
      },
      "to": {
        "type": "number",
        "description": "消息接收者ID"
      },
      "public": {
        "type": "number",
        "description": "是否系统消息"
      },
      "message": {
        "type": "object",
        "properties": {
          "content": {
            "type": "string"
          },
          "type": {
            "type": "string"
          },
          "url": {
            "type": "string"
          }
        },
        "required": [
          "content",
          "type"
        ]
      }
    },
    "required": [
      "order_id",
      "from",
      "to",
      "message"
    ]
  }
}

```
客户端与服务器端以此结构传送消息
message.type 分为两种1、Text；2、Image;
当为Image的时候必须传 url;

### 用户发送消息

>Post {{apiurl}}/im/message/send

### 输入
```json
{
  "order_id":1,
  "from":1,
  "to":12,
  "content":{
  	"type":"Text",
  	"content":"adsf"
  } 
  
}
```

```json
{
  "order_id":1,
  "from":1,
  "to":12,
  "content":{
  	"type":"Image",
  	"content":"http://img.url/random.jpg"
  } 
  
}
```
| 名称 | 需求 | 类型 | 描述 |
|:----:|:----:|:----:|----|
| order_id | 是 | int |订单Id| 
| from | 是 | int | 消息发送者Id | 
| to | 是 | int | 消息接收者id | 
| content | 是 | object | 消息体 |

### 发送成功返回
```json
{
    "status": 200,
    "message": "",
    "data": {
        "order_id": 1,
        "from": 1,
        "to": 12,
        "public": 0,
        "content": {
            "type": "Image",
            "content": "adsf",
            "url": "http://asdfasf"
        },
        "sendtime": 1513246537
    }
}
``` 

## 消息历史

>Post {{apiurl}}/im/message/history

### 输入
```json

{
   "order_id":1 
}

```
### 输出
```json

{
    "status": 200,
    "message": "",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 28,
                "order_id": 1,
                "message": "{\"order_id\":1,\"from\":1,\"to\":12,\"public\":0,\"content\":{\"type\":\"Image\",\"content\":\"adsf\",\"url\":\"http:\\/\\/asdfasf\"},\"sendtime\":1513246537}",
                "from": 1,
                "to": 12,
                "created_at": "2017-12-14 10:15:37",
                "updated_at": "2017-12-14 10:15:37"
            },
            ...
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "/?page=1",
        "next_page_url": null,
        "path": "/",
        "per_page": 20,
        "prev_page_url": null,
        "to": 8,
        "total": 8
    }
}
```

