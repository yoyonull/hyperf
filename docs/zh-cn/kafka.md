# Kafka

`Kafka` 是由 `Apache 软件基金会` 开发的一个开源流处理平台，由 `Scala` 和 `Java` 编写。该项目的目标是为处理实时数据提供一个统一、高吞吐、低延迟的平台。其持久化层本质上是一个 "按照分布式事务日志架构的大规模发布/订阅消息队列"

[longlang/phpkafka](https://github.com/longyan/phpkafka) 组件由 [龙之言](http://longlang.org/) 提供，支持 `PHP-FPM` 和 `Swoole`。感谢 `Swoole 团队` 和 `禅道团队` 对社区做出的贡献。

> 本组件为 Beta 版本，谨慎使用

## 安装

```bash
composer require hyperf/kafka
```

## 使用

### 配置

`kafka` 组件的配置文件默认位于 `config/autoload/kafka.php` 内，如该文件不存在，可通过 `php bin/hyperf.php vendor:publish hyperf/kafka` 命令来将发布对应的配置文件。

默认配置文件如下：


|             配置              |    类型    |            默认值             |                                                         备注                                                         |
| :---------------------------: | :--------: | :---------------------------: | :------------------------------------------------------------------------------------------------------------------: |
|        connect_timeout        | int｜float |              -1               |                                 连接超时时间（单位：秒，支持小数），为 - 1 则不限制                                  |
|         send_timeout          | int｜float |              -1               |                                 发送超时时间（单位：秒，支持小数），为 - 1 则不限制                                  |
|         recv_timeout          | int｜float |              -1               |                                 接收超时时间（单位：秒，支持小数），为 - 1 则不限制                                  |
|           client_id           |   stirng   |             null              |                                                   Kafka 客户端标识                                                   |
|      max_write_attempts       |    int     |               3               |                                                   最大写入尝试次数                                                   |
|            brokers            |   array    |              []               |                       手动配置 brokers 列表，如果要使用手动配置，请把 updateBrokers 设为 true                        |
|       bootstrap_server        |   array    |       '127.0.0.1:9092'        |                          引导服务器，如果配置了该值，会自动连接该服务器，并自动更新 brokers                          |
|        update_brokers         |    bool    |             true              |                                                 是否自动更新 brokers                                                 |
|             acks              |    int     |               0               |  生产者要求领导者，在确认请求完成之前已收到的确认数值。允许的值：0 表示无确认，1 表示仅领导者，- 1 表示完整的 ISR。  |
|          producer_id          |    int     |              -1               |                                                      生产者 ID                                                       |
|        producer_epoch         |    int     |              -1               |                                                     生产者 Epoch                                                     |
|    partition_leader_epoch     |    int     |              -1               |                                                  分区 Leader Epoch                                                   |
|            broker             |   string   |              ''|                                            broker，格式：'127.0.0.1:9092'                                            |
|           interval            | int｜float |               0               |                   未获取消息到消息时，延迟多少秒再次尝试，默认为 0 则不延迟（单位：秒，支持小数）                    |
|        session_timeout        | int｜float |              60               |                     如果超时后没有收到心跳信号，则协调器会认为该用户死亡。（单位：秒，支持小数）                     |
|       rebalance_timeout       | int｜float |              60               |                      重新平衡组时，协调器等待每个成员重新加入的最长时间（单位：秒，支持小数）。                      |
|          replica_id           |    int     |              -1               |                                                       副本 ID                                                        |
|            rack_id            |    int     |              -1               |                                                       机架编号                                                       |
|          group_retry          |    int     |               5               |                                      分组操作，匹配预设的错误码时，自动重试次数                                      |
|       group_retry_sleep       |    int     |               1               |                                              分组操作重试延迟，单位：秒                                              |
|        group_heartbeat        |    int     |               3               |                                              分组心跳时间间隔，单位：秒                                              |
|         offset_retry          |    int     |               5               |                                     偏移量操作，匹配预设的错误码时，自动重试次数                                     |
|       auto_create_topic       |    bool    |             true              |                                                是否需要自动创建 topic                                                |
| partition_assignment_strategy |   string   | KafkaStrategy::RANGE_ASSIGNOR | 消费者分区分配策略, 可选：范围分配(`KafkaStrategy::RANGE_ASSIGNOR`) 轮询分配(`KafkaStrategy::ROUND_ROBIN_ASSIGNOR`)) |
|             pool              |   object   |                               |                                                      连接池配置                                                      |


```php
<?php

declare(strict_types=1);

use Hyperf\Kafka\Constants\KafkaStrategy;

return [
    'default' => [
        'connect_timeout' => -1,
        'send_timeout' => -1,
        'recv_timeout' => -1,
        'client_id' => '',
        'max_write_attempts' => 3,
        'brokers' => [
            '127.0.0.1:9092',
        ],
        'bootstrap_server' => '127.0.0.1:9092',
        'update_brokers' => true,
        'acks' => 0,
        'producer_id' => -1,
        'producer_epoch' => -1,
        'partition_leader_epoch' => -1,
        'broker' => '',
        'interval' => 0,
        'session_timeout' => 60,
        'rebalance_timeout' => 60,
        'replica_id' => -1,
        'rack_id' => '',
        'group_retry' => 5,
        'group_retry_sleep' => 1,
        'group_heartbeat' => 3,
        'offset_retry' => 5,
        'auto_create_topic' => true,
        'partition_assignment_strategy' => KafkaStrategy::RANGE_ASSIGNOR,
        'pool' => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => 60.0,
        ],
    ],
];
```

### 创建消费者

通过 gen:kafka-consumer 命令可以快速的生成一个 消费者(Consumer) 对消息进行消费。

```bash
php bin/hyperf.php gen:kafka-consumer KafkaConsumer
```

您也可以通过使用 `Hyperf\Kafka\Annotation\Consumer` 注解来对一个 `Hyperf/Kafka/AbstractConsumer` 抽象类的子类进行声明，来完成一个 `消费者(Consumer)` 的定义，其中 `Hyperf\Kafka\Annotation\Consumer` 注解和抽象类均包含以下属性：

|    配置    |  类型  | 注解或抽象类默认值 |                 备注                 |
| :--------: | :----: | :----------------: | :----------------------------------: |
|   topic    | string |         ''         |            要监听的 topic            |
|  groupId   | string |         ''         |           要监听的 groupId           |
|  memberId  | string |         ''         |          要监听的 memberId           |
| autoCommit | string |         ''         |           是否需要自动提交           |
|    name    | string |   KafkaConsumer    |             消费者的名称             |
|    nums    |  int   |         1          |            消费者的进程数            |
|    pool    | string |      default       | 消费者对应的连接，对应配置文件的 key |


```php
<?php

declare(strict_types=1);

namespace App\kafka;

use Hyperf\Kafka\AbstractConsumer;
use Hyperf\Kafka\Annotation\Consumer;
use longlang\phpkafka\Consumer\ConsumeMessage;

/**
 * @Consumer(topic="hyperf", nums=5, groupId="hyperf", autoCommit=true)
 */
class KafkaConsumer extends AbstractConsumer
{
    public function consume(ConsumeMessage $message): string
    {
        var_dump($message->getTopic() . ':' . $message->getKey() . ':' . $message->getValue());
    }
}

```

### 投递消息

您可以通过调用 `Hyperf\Kafka\Producer::send(string $topic, ?string $value, ?string $key = null, array $headers = [], int $partitionIndex = 0, ?int $brokerId = null)` 方法来向 `kafka` 投递消息, 下面是在 `Controller` 进行消息投递的一个示例：

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Kafka\Producer;

/**
 * @AutoController()
 */
class IndexController extends AbstractController
{
    public function index()
    {
        $producer = make(Producer::class);

        $producer->send('hyperf', 'value', 'key');
    }
}

```

### 一次性投递多条消息

`Hyperf\Kafka\Producer::sendBatch(array $messages, ?int $brokerId = null)` 方法来向 `kafka` 批量的投递消息, 下面是在 `Controller` 进行消息投递的一个示例：


```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Kafka\Producer;
use longlang\phpkafka\Producer\ProduceMessage;

/**
 * @AutoController()
 */
class IndexController extends AbstractController
{
    public function index()
    {
        $producer = make(Producer::class);

        $producer->sendBatch([
            new ProduceMessage('hyperf1', 'hyperf1_value', 'hyperf1_key'),
            new ProduceMessage('hyperf2', 'hyperf2_value', 'hyperf2_key'),
            new ProduceMessage('hyperf3', 'hyperf3_value', 'hyperf3_key'),
        ]);

    }
}

```

