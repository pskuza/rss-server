<?php
declare(strict_types=1);

namespace pskuza\rss;

class posts
{
    protected $db;

    public function __construct(\ParagonIE\EasyDB\EasyDB $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        //for now
        return [true, 204, "getall was called"];
    }
}