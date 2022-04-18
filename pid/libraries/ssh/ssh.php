<?php

session_start();

class ssh{

    public $connect = false;

    public function __construct()
    {



    }

    public function connect($post){

        if(!empty($post)){

            $connection = ssh2_connect($post['host'], $post['port']);
            if (ssh2_auth_password($connection, $post['login'], $post['pass'] )) {
                echo "Успешная аутентификация!\n";

                $this->connect = $connection;


            } else {
                die('Неудачная аутентификация...');
            }

        }

    }

    public function cmd($post){

        if(!empty($this->connect)){

            $connection = $this->connect;

            $stream = ssh2_exec($connection, 'pwd');
            stream_set_blocking($stream, true);
            $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
            echo stream_get_contents($stream_out);

            print_r($connection);

        }

    }

    public function test($post){
        echo "test";
        print_r($post);
    }

}