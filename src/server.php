<?php
declare(strict_types=1);

namespace pskuza\rss;

class server
{
    protected $db;
    public $posts;

    public function __construct(string $db)
    {
        try {
            $this->db = \ParagonIE\EasyDB\Factory::create(
                'sqlite:' . $db
            );
        } catch (\Exception $e) {
            $this->error(500, 'No database connection.');
        }

        $this->posts = new posts($this->db);
    }

    public function error(int $http_code, string $error_message) {
        http_response_code($http_code);
        //for now
        die($error_message);
    }

    public function success(int $http_code = 200, string $message) {
        //for now
        http_response_code($http_code);
        die($message);
    }
}