<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
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
