<?php
declare(strict_types=1);

namespace pskuza\rss;

class server
{
    protected $db;

    public function __construct(string $db)
    {
        try {
            $this->db = \ParagonIE\EasyDB\Factory::create(
                'sqlite:' . $db
            );
        } catch (\Exception $e) {
            $this->error(500, 'No database connection.');
        }
    }

    public function error(int $http_code, string $error_message) {
        http_response_code($http_code);
        //for now
        die($error_message);
    }

    public function success() {
        //for now
        die("OK");
    }

    public function getAll() {
        return;
    }
}