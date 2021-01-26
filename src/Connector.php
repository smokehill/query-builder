<?php

namespace QueryBuilder;

use QueryBuilder\Builder;

class Connector extends Builder
{
    protected $pdo;

    protected $config = [
        'driver' => '',
        'host' => '',
        'port' => '',
        'user' => '',
        'password' => '',
        'database' => '',
        'charset' => ''
    ];

    public function __construct($config = [])
    {
        $this->checkConfig($config);

        try {
            $this->pdo = new \PDO(
                sprintf(
                    "%s:dbname=%s;host=%s;port=%s;charset=%s",
                    $this->config['driver'],
                    $this->config['database'],
                    $this->config['host'],
                    $this->config['port'],
                    $this->config['charset']
                ),
                $this->config['user'],
                $this->config['password']
            );
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage() . '(' . $e->getLine() . ')');
        }

        parent::__construct($this->pdo);
    }

    public function pdo()
    {
        return $this->pdo;
    }

    protected function checkConfig($config = [])
    {
        $result = array_diff_key($this->config, $config);

        if (count($result) > 0) {
            throw new \Exception('bad config keys');
        }

        $result = array_filter($config);

        if (empty($result) || count($result) != count($this->config)) {
            throw new \Exception('bad config values');
        }

        $this->config = $config;
    }
}