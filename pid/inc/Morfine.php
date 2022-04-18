<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */

class Morfine
{

    protected $settings = array();

    public function __construct($settings){

        $this->set_settings($settings);
        $this->settings = $settings;

    }

    public function set_settings(array $settings){

        if(!empty($settings)){

            foreach ($settings as $key => $setting){

                $this->$key = $setting;

            }
        } else {
            return false;
        }

    }


    public function find_word($word, $path_for_find_word){

        $cmd = $this->search($word, $path_for_find_word);
        $exec = $this->exec($cmd);

    }

    public function find_current_path(){

        $cmd = $this->current_path();
        $exec = $this->exec($cmd);

    }

    public function find_edited_files($from, $to, $path){

        $cmd = $this->search_edited_files($from, $to, $path);
        $exec = $this->exec($cmd);

    }

    public function remove_dir($path){

        $cmd = $this->cmd_remove_dir($path);
        $exec = $this->exec($cmd);

    }

    public function get_ssh($cmd){

        if(!empty($cmd)){

            $exec = $this->exec($cmd);
            print_r($exec);

        }

    }

    // File Manager
    public function fm_init(){

        $options = array();
        $fm = new FileManager($this->settings);
        $fm->get_current_list();

    }

    // Comands
    public function search($word, $path_for_find_word){

        if(isset($this->operation_system) && !empty($this->operation_system) ){

            if($this->operation_system == 'linux'){
                $cmd = 'grep -iRl '.$word.' '. $path_for_find_word;
                return $cmd;
            }

        } else {

            $message = 'Не установлена переменная operation_system в настройках';
            $this->call_error($message);

            return false;
        }

    }

    public function search_edited_files($from, $to, $path){

        if(isset($this->operation_system) && !empty($this->operation_system) && isset($from) && !empty($path)){

            if($this->operation_system == 'linux'){

                if(empty($to)){
                    $cmd = "find ".$path." -type f -mtime -".$from." -printf '%TY-%Tm-%Td %TT %p\n' | sort -r";
                    return $cmd;
                } else {
                    $cmd = "find ".$path." -type f -mtime -".$from." ! -mtime -".$to." -printf '%TY-%Tm-%Td %TT %p\n' | sort -r";
                    return $cmd;
                }

            }

        } else {

            $message = 'Не установлена переменная operation_system в настройках';
            $this->call_error($message);

            return false;
        }

    }

    public function current_path(){

        if(isset($this->operation_system) && !empty($this->operation_system) ){

            if($this->operation_system == 'linux'){
                $cmd = 'pwd';
                return $cmd;
            }

        } else {

            $message = 'Не установлена переменная operation_system в настройках';
            $this->call_error($message);

            return false;
        }

    }

    public function cmd_remove_dir($path){

        if(isset($this->operation_system) && !empty($this->operation_system) ){

            if($this->operation_system == 'linux'){
                if(!empty($path)){
                    $cmd = 'rm -rf ' . $path;
                    return $cmd;
                } else {
                    $message = 'Не указан путь к директории';
                    $this->call_error($message);
                }
            }

        } else {

            $message = 'Не установлена переменная operation_system в настройках';
            $this->call_error($message);

            return false;
        }

    }

    // System
    public function exec($cmd){

        if(!empty($cmd)){

            $output=null;
            $retval=null;
            exec($cmd, $output, $retval);
            echo "<strong>Команда:</strong> " . $cmd . '<br>';
            echo "<strong>Вернёт статус $retval и значение:</strong>\n <br><br>";

            if(!empty($output)){
                if( is_array($output) ){

                    foreach ($output as $key=>$elem){

                        echo "<strong>Key:</strong> " . $key . " | <strong>Result:</strong> " . $elem . "<br>\n";

                    }

                } else {

                    print_r($output);

                }
            }

        }

    }

    public function call_error($message){

        if(!empty($message)){
            echo $message;
        }

    }

}