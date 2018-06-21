<?php
declare(strict_types=1);

namespace pskuza\rss;

class posts
{
    protected $db;
    protected $cache;

    public function __construct(\ParagonIE\EasyDB\EasyDB $db, \Doctrine\Common\Cache\ApcuCache $cache)
    {
        $this->db = $db;
        $this->cache = $cache;
    }

    public function getAll(): array
    {
        //for now
        return [true, 200, "getall was called"];
    }
}