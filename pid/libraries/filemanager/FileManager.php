<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */

if (!defined('PIDE')) { echo 'You dont have access'; die; }

class FileManager
{
    public function __construct($settings){

        // Clean File system cache
        //clearstatcache(true);

        $this->set_settings($settings);
        $this->errorExeption();

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
    public function get_current_list($options = false){

        //$paths = scandir(MORF_ROOT, 0);
        $paths = $this->scan_dir(MORF_ROOT, 'dir');

        $this->main_render($paths);

    }
    public function scan_dir($main_path, $sort='dir'){

        if(is_dir($main_path)){
            $paths = scandir($main_path, 0);
            //$paths = preg_grep('/^([^.])/', $paths);
            $paths = array_diff($paths, array('.', '..'));

            //print_r($paths);

            if($sort == 'dir'){
                $paths = $this->scan_dir_by_dir($main_path, $paths);
                return $paths;
            }
        }

    }
    public function scan_dir_by_dir($main_path, $paths){

        $directories = array();
        $files = array();

        foreach ($paths as $path){

            if(is_dir($main_path . $path)){
                $directories[] = $path;
            } else {
                $files[] = $path;
            }

        }

        asort($directories);
        asort($files);

        //print_r($directories);
        $paths = array_merge($directories, $files);

        return $paths;

    }

    // AJAX METHODS
    public function dir_open($data){

        if(isset($data) && !empty($data) && is_array($data) && isset($data['path']) ) {

            $path = isset($data['path']) ? $data['path'] : '';
            $inode = isset($data['inode']) ? $data['inode'] : '';
            $type = isset($data['type']) ? $data['type'] : '';

            //$paths = scandir($path, SCANDIR_SORT_NONE);

            $paths = $this->scan_dir($path . '/', 'dir');
            $this->dir_open_render($paths, $path);

        }

    }
    public function dir_open_render($data, $curr_path){

        if(file_exists(MORF_SITE_DIR . '/libraries/filemanager/tpl/list.php')){

            // Получаем правильно сформированный массив данных файлового менеджера
            $main_list = $this->file_manager_data_list_handler($data, $curr_path);

            require_once MORF_SITE_DIR . '/libraries/filemanager/tpl/list.php';
            //print_r($main_list);

        }

    }
    public function file_open($data){

        if(isset($data) && !empty($data) && is_array($data) && isset($data['path']) ) {


            $path = isset($data['path']) ? $data['path'] : '';
            $inode = isset($data['inode']) ? $data['inode'] : '';
            $type = isset($data['type']) ? $data['type'] : '';

            //$cmd = $this->cmd_get_file_content($path);
            //$exec = $this->exec($cmd);


            if(file_exists($path)){

                $file = file_get_contents($path, FILE_USE_INCLUDE_PATH);

                echo htmlspecialchars($file, ENT_QUOTES);

            } else {

                echo "File doesnt exit";

            }

        }

    }
    public function owner_edit($post){

        if(isset($post) && !empty($post) && is_array($post) && isset($post['path']) && isset($post['owner']) ) {

            chown($post['path'], $post['owner']);

        }

    }
    public function chmod_edit($post){

        if( isset($post['path']) && !empty($post['path']) && isset($post['chmod']) && !empty($post['chmod']) ){
            $perm = octdec($post['chmod']);
            chmod($post['path'], $perm);

            $message = array(
                'status'=> '1',
                'message' => ''
            );
            $this->message($message);

        } else {
            $message = array(
                'status'=> '0',
                'message' => 'Empty field'
            );
            $this->message($message);
        }

    }
    public function dir_create($post){

        if( isset($post['path']) && !empty($post['path']) && isset($post['name']) && !empty($post['name']) ){

            try {
                $full_path = $post['path'] . '/' . $post['name'];

                $dir = mkdir($full_path, 0755);

                if($dir){
                    $message = array(
                        'status'=> '1',
                        'message' => ''
                    );
                    $this->message($message);
                } else {
                    $message = array(
                        'status'=> '0',
                        'message' => 'Error: Not created'
                    );
                    $this->message($message);
                }
            } catch(ErrorException $ex) {
                $message = array(
                    'status'=> '0',
                    'message' => $ex->getMessage()
                );
                $this->message($message);
            }

        }

    }
    public function file_delete($post){

        if( isset($post['list']) && !empty($post['list']) ) {

            if(is_array($post['list'])){

                $errors = array();

                foreach ($post['list'] as $path){

                    $file_perm = substr(sprintf('%o', fileperms($path)), -4);
                    $file_perm = intval($file_perm);



                        if (is_dir($path)) {

                            $cmd = "rm -rf {$path}";

                            if ($cmd == "") {
                                exit("Error");
                            }

                            $result = $this->exec($cmd);

                        } else {

                            $path_parts = pathinfo($path);
                            $file_name = '"' . $path_parts['basename'] . '"';

                            $cmd = "cd " . $path_parts['dirname'] . "; rm -rf {$file_name}";

                            if ($cmd == "") {
                                exit("Error");
                            }

                            $result = $this->exec($cmd);

                        }

                        //array_push($errors, $path);

                }

                if(!empty($errors)){

                    $message = array(
                        'status'=> '0',
                        'message' => "You don't have permissions for " . implode(",", $errors)
                    );
                    $this->message($message);

                } else {
                    $message = array(
                        'status'=> '1',
                        'message' => ''
                    );
                    $this->message($message);
                }

            }

        }


    }
    public function file_copy($post){

        if( isset($post['list']) && !empty($post['list']) && isset($post['curr_path']) && !empty($post['curr_path']) ) {
            if(is_array($post['list'])){
                //print_r( $post['list'] );
                $errors = array();
                foreach ($post['list'] as $path){

                    if (is_dir($path)) {

                        $os = strtolower(PHP_OS_FAMILY); // PHP 7.2 & ABOVE ONLY
                        $cmd = "";
                        if ($os == "windows") { $cmd = "Xcopy {$path} {$post['curr_path']} /E/H/C/I"; }
                        if ($os =="linux") { $cmd = "cp -R {$path} {$post['curr_path']}"; }
                        if ($cmd=="") { exit("Error"); }

                        $result = $this->exec($cmd);

                        array_push($errors, $result);

                    } else {

//                        $path_parts = pathinfo($path);
//                        $file_name = $path_parts['filename'];
//                        $extension = $path_parts['extension'];
//                        $new_file = $post['curr_path'] . '/' . $file_name . '.' . $extension;
//                        copy($path, $new_file);

                        $os = strtolower(PHP_OS_FAMILY); // PHP 7.2 & ABOVE ONLY
                        $cmd = "";
                        if ($os == "windows") { $cmd = "Xcopy {$path} {$post['curr_path']} /E/H/C/I"; }
                        if ($os =="linux") { $cmd = "cp -R {$path} {$post['curr_path']}"; }
                        if ($cmd=="") { exit("Error"); }

                        $result = $this->exec($cmd);

                        array_push($errors, $result);

                    }

                }

                $errors = json_encode($errors);

                $message = array(
                    'status'=> '1',
                    'message' => $errors
                );
                $this->message($message);

            }

        }


    }
    public function file_manager_data_list_handler($data_array, $curr_path = false){

        if(is_array($data_array)){

            if(isset($data_array) && !empty($data_array)){

                $main_list = array();

                foreach ( $data_array as $dat ){

                    if(empty($curr_path)){
                        $path = MORF_ROOT . $dat;

                    } else {
                        $path = $curr_path . '/' . $dat;

                    }

                   // echo $path . "<br>";

                    if(file_exists($path)){

                        $file = array(
                            'name' => $dat,
                            'inode' => false,
                            'info' => array()
                        );

                        $file_size = filesize($path);
                        $file_permission = fileperms($path);
                        $file_owner_id = fileowner($path);
                        $file_owner_information = posix_getpwuid($file_owner_id);
                        $file_owner_name = $file_owner_information['name'];
                        $last_modified_time_timestamp = filemtime($path);
                        $last_modified_time_mormal_date = date("Y-m-d H:i:s", $last_modified_time_timestamp);

                        $type = '';
                        if( is_file($path) ){
                            $type = 'file';
                        } else if (is_dir($path)) {
                            $type = 'directory';
                        } else {
                            $type = 'something';
                        }

                        $file['info'][3] = substr(sprintf('%o', $file_permission), -4);
                        $file['info'][6] = $this->date_parse($last_modified_time_mormal_date); // Some date
                        $file['info'][4] = $file_owner_name;
                        $file['info'][1] = $this->convert_filesize($file_size);
                        $file['info'][2] = $type;
                        $file['info'][0] = $path;
                        $file['extension'] = $this->get_file_extension($path);

                        array_push($main_list, $file);
                    }

                }

                return $main_list;

            }

        }

    }
    public function get_file_extension($path){

        if(isset($path) && !empty($path)) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            return $extension;
        }

    }
    public function main_render($data){

        if(file_exists(MORF_SITE_DIR . '/libraries/filemanager/tpl/main.php')){

            // Получаем правильно сформированный массив данных файлового менеджера
            $main_list = $this->file_manager_data_list_handler($data);

            require_once MORF_SITE_DIR . '/libraries/filemanager/tpl/main.php';
            //print_r($main_list);

        }

    }
    public function file_save($post){

        if(isset($post['content']) && isset($post['path']) && !empty($post['path']) && !empty($post['content']) ){

            try {
                $file = file_put_contents( $post['path'], $post['content'], LOCK_EX );

                if($file){
                    $message = array(
                        'status'=> '1',
                        'message' => ''
                    );
                    $this->message($message);
                } else {
                    $message = array(
                        'status' => '0',
                        'message' => 'Error:'
                    );
                    $this->message($message);
                }
            } catch(ErrorException $ex) {
                $message = array(
                    'status'=> '0',
                    'message' => $ex->getMessage()
                );
                $this->message($message);
            }

        }
    }
    public function upload($file, $request){
        if(isset($file['file']) &&
            !empty($file['file']) &&
            isset($request['curr_path']) &&
            !empty($request['curr_path']))
        {

            $curr_path = $request['curr_path'];

            if(is_dir($curr_path)) {

                switch ($file['file']['error']) {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        throw new RuntimeException('No file sent.');
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new RuntimeException('Exceeded filesize limit.');
                    default:
                        throw new RuntimeException('Unknown errors.');
                }

                $filepath = sprintf($curr_path . '/' . '%s_%s', uniqid(), $file['file']['name']);

                $file = move_uploaded_file($file['file']['tmp_name'], $filepath);

                if($file){
                    $message = array(
                        'status'=> '1',
                        'message' => ''
                    );
                    $this->message($message);
                } else {
                    $message = array(
                        'status'=> '0',
                        'message' => 'Error: Not uploaded'
                    );
                    $this->message($message);
                }

            }

            exit();

        }
    }
    public function file_rename($post){
        if(isset($post['path']) && isset($post['name'])) {
            if(!empty($post['path'])){
                $path = $post['path'];

                $path_parts = pathinfo($path[0]);
                $new_file_name = $path_parts['dirname'] . '/' . $post['name'];

                rename($path[0], $new_file_name);

                $message = array(
                    'status'=> '1',
                    'message' => ''
                );
                $this->message($message);

            }
        }
    }

    // Response
    public function message($message){

        if(!empty($message)){
            echo $message = json_encode($message);
        }

    }

    // Prepare Commands
    public function prepare_init(){

        $settings = array('linux');
        $this->get_theme_settings($settings);
        $this->get_system_settings($settings);
        $this->get_fm_settings($settings);
        $this->get_assets_settings($settings);
        $this->get_editor_settings($settings);
        $this->get_router_settings($settings);
        $this->get_os_settings($settings);
        $this->get_cmd_settings($settings);
        $this->get_uploads_settings($settings);
        
    }
    public function set_theme_settings($settings){
        return $settings;
    }
    public function set_system_settings($settings){
        return $settings;
    }
    public function set_fm_settings($settings){
        return $settings;
    }
    public function set_assets_settings($settings){
        return $settings;
    }
    public function set_editor_settings($settings){
        return $settings;
    }
    public function set_router_settings($settings){
        return $settings;
    }
    public function set_os_settings($settings){
        return $settings;
    }
    public function set_cmd_settings($settings){
        return $settings;
    }
    public function set_uploads_settings($settings){
        return $settings;
    }

    public function get_theme_settings($settings){
        $this->set_theme_settings($settings);
    }
    public function get_system_settings($settings){
        $this->set_system_settings($settings);
    }
    public function get_fm_settings($settings){
        $this->set_fm_settings($settings);
    }
    public function get_assets_settings($settings){
        $this->set_assets_settings($settings);
    }
    public function get_editor_settings($settings){
        $this->set_editor_settings($settings);
    }
    public function get_router_settings($settings){
        $this->set_router_settings($settings);
    }
    public function get_os_settings($settings){
        $this->set_os_settings($settings);
    }
    public function get_cmd_settings($settings){
        $this->set_cmd_settings($settings);
    }
    public function get_uploads_settings($settings){
        $this->set_uploads_settings($settings);
    }
    
    // Commands
    public function cmd_get_current_list($path = false){

        if(isset($this->operation_system) && !empty($this->operation_system) ){

            if($this->operation_system == 'linux'){

                /*
                -a - отображать все файлы, включая скрытые, это те, перед именем которых стоит точка;
                -A - не отображать ссылку на текущую папку и корневую папку . и ..;
                --author - выводить создателя файла в режиме подробного списка;
                -b - выводить Escape последовательности вместо непечатаемых символов;
                --block-size - выводить размер каталога или файла в определенной единице измерения, например, мегабайтах, гигабайтах или килобайтах;
                -B - не выводить резервные копии, их имена начинаются с ~;
                -c - сортировать файлы по времени модификации или создания, сначала будут выведены новые файлы;
                -C - выводить колонками;
                --color - включить цветной режим вывода, автоматически активирована во многих дистрибутивах;
                -d - выводить только директории, без их содержимого, полезно при рекурсивном выводе;
                -D - использовать режим вывода, совместимый с Emacs;
                -f - не сортировать;
                -F - показывать тип объекта, к каждому объекту будет добавлен один из специализированных символов
                --full-time - показывать подробную информацию, плюс вся информация о времени в формате ISO
                -g - показывать подробную информацию, но кроме владельца файла
                --group-directories-first - сначала отображать директории, а уже потом файлы
                -G - не выводить имена групп
                -h - выводить размеры папок в удобном для чтения формате
                -H - открывать символические ссылки при рекурсивном использовании
                --hide - не отображать файлы, которые начинаются с указанного символа
                -i - отображать номер индекса inode, в которой хранится этот файл
                -l - выводить подробный список, в котором будет отображаться владелец, группа, дата создания, размер и другие параметры
                -L - для символических ссылок отображать информацию о файле, на который они ссылаются
                -m - разделять элементы списка запятой
                -n - выводить UID и GID вместо имени и группы пользователя
                -N - выводить имена как есть, не обрабатывать контролирующие последовательности
                -Q - брать имена папок и файлов в кавычки
                -r - обратный порядок сортировки
                -R - рекурсивно отображать содержимое поддиректорий
                -s - выводить размер файла в блоках
                -S - сортировать по размеру, сначала большие
                -t - сортировать по времени последней модификации
                -u - сортировать по времени последнего доступа
                -U - не сортировать
                -X - сортировать по алфавиту
                -Z - отображать информацию о расширениях SELinux
                -1 - отображать один файл на одну строку.
                 */

                if(empty($path)){
                    $cmd = 'cd ../; ls -a --group-directories-first -i';
                } else {
                    $cmd = 'ls '. $path .' -a --group-directories-first -i';
                }


                return $cmd;
            }

        } else {

            $message = 'Не установлена переменная operation_system в настройках';
            $this->call_error($message);

            return false;
        }

    }
    public function cmd_get_file_info($file_path){

        if(!empty($file_path)){

            if(isset($this->operation_system) && !empty($this->operation_system) ){

                if($this->operation_system == 'linux'){

                    $cmd = 'stat --printf "%n<,+>%s<,+>%F<,+>%A<,+>%U<,+>%i<,+>%x<,+>%y<,+>%z" ' . $file_path;
                    return $cmd;
                }

            } else {

                $message = 'Не установлена переменная operation_system в настройках';
                $this->call_error($message);

                return false;
            }

        } else {

            return false;

        }

    }
    public function cmd_file_content_save($content, $file_path){

        if(!empty($file_path)){

            if(isset($this->operation_system) && !empty($this->operation_system) ){

                if($this->operation_system == 'linux'){

                    $cmd = 'echo "asdsa" > '. $file_path;
                    return $cmd;
                }

            } else {

                $message = 'Не установлена переменная operation_system в настройках';
                $this->call_error($message);

                return false;
            }

        } else {

            return false;

        }

    }
    public function cmd_search_word($word, $path){

        if(!empty($path)){

            if(isset($this->operation_system) && !empty($this->operation_system) ){

                if($this->operation_system == 'linux'){

                    $cmd = 'grep -rw -n "' . $word . '" ' . $path;
                    return $cmd;
                }

            } else {

                $message = 'Не установлена переменная operation_system в настройках';
                $this->call_error($message);

                return false;
            }

        } else {

            return false;

        }

    }
    public function cmd_get_file_content($file_path){

        if(!empty($file_path)){

            if(isset($this->operation_system) && !empty($this->operation_system) ){

                if($this->operation_system == 'linux'){

                    $cmd = 'cat ' . $file_path;
                    return $cmd;
                }

            } else {

                $message = 'Не установлена переменная operation_system в настройках';
                $this->call_error($message);

                return false;
            }

        } else {

            return false;

        }

    }
    public function cmd_archive($name, $curr_path, $list, $type){

        if(!empty($curr_path) && !empty($name) && !empty($list)){

            if(isset($this->operation_system) && !empty($this->operation_system) ){

                if($this->operation_system == 'linux'){

                    $tpl = '';
                    foreach ($list as $l){
                        $file_name = basename($l);
                        if(is_dir($l)){
                            $tpl .= $file_name . '/ ';
                        } else {
                            $tpl .= '"'.$file_name . '"';
                        }
                    }

                    if($type == 'zip'){
                        $cmd = 'cd ' . $curr_path . '; zip -r ' . $name . '.zip ' . $tpl;
                    } else {
                        $cmd = 'cd ' . $curr_path . '; tar -cvf ' . $name . '.tar ' . $tpl;
                    }
                    return $cmd;
                }

            } else {

                $message = 'Не установлена переменная operation_system в настройках';
                $this->call_error($message);

                return false;
            }

        } else {

            return false;

        }

    }
    public function cmd_unarchive($curr_path, $list, $type){

        if(!empty($list)){

            if(isset($this->operation_system) && !empty($this->operation_system) ){

                if($this->operation_system == 'linux'){

                    $tpl = '';
                    foreach ($list as $l){
                        if($type == 'zip'){
                            $tpl .= 'unzip ' . $l . '; ';
                        } else {
                            $tpl .= 'tar -xvf ' . $l . '; ';
                        }
                    }

                    $cmd = 'cd ' . $curr_path . '; ' . $tpl;
                    return $cmd;
                }

            } else {

                $message = 'Не установлена переменная operation_system в настройках';
                $this->call_error($message);

                return false;
            }

        } else {

            return false;

        }

    }
    public function cmd_get_dir_size($path){

        if(!empty($path)){

            if(isset($this->operation_system) && !empty($this->operation_system) ){

                if($this->operation_system == 'linux'){

                    $cmd = 'du -sh ' . $path . ' | cut -f1';

                    return $cmd;
                }

            } else {

                $message = 'Не установлена переменная operation_system в настройках';
                $this->call_error($message);

                return false;
            }

        } else {

            return false;

        }

    }
    public function cmd_rename_file($path, $name){

        if(!empty($path)){

            if(isset($this->operation_system) && !empty($this->operation_system) ){

                if($this->operation_system == 'linux'){

                    $cmd = 'du -sh ' . $path . ' | cut -f1';

                    return $cmd;
                }

            } else {

                $message = 'Не установлена переменная operation_system в настройках';
                $this->call_error($message);

                return false;
            }

        } else {

            return false;

        }

    }
    public function cmd_download_url($path, $url){

        if(!empty($path) && !empty($url)){

            if(isset($this->operation_system) && !empty($this->operation_system) ){

                if($this->operation_system == 'linux'){

                    $cmd = 'cd ' . $path . '; wget ' . $url;

                    return $cmd;
                }

            } else {

                $message = 'Не установлена переменная operation_system в настройках';
                $this->call_error($message);

                return false;
            }

        } else {

            return false;

        }

    }

    // System
    public function exec($cmd){

        if(function_exists('exec')) {

            if (!empty($cmd)) {

                $output = null;
                $retval = null;
                exec($cmd, $output, $retval);

                if (!empty($output)) {
                    if (is_array($output)) {

                        return $output;

                    } else {

                        return false;

                    }
                } else {
                    return false;
                }

            }
        } else {
            return false;
        }

    }
    public function detect_permission($str){

        if(!empty($str)){

            switch ($str) {
                case '-rw-------':
                    return 600;
                    break;
                case '-rw-r--r--':
                    return 644;
                    break;
                case '-rwx------':
                    return 700;
                    break;
                case '-rwxr-xr-x':
                    return 755;
                    break;
                case '-rwx--x--x':
                    return 711;
                    break;
                case '-rw-rw-rw-':
                    return 666;
                    break;
                case '-rwxrwxrwx':
                    return 777;
                    break;
                case 'drwx------':
                    return 700;
                    break;
                case 'drwxr-xr-x':
                    return 755;
                    break;
            }

        }

        return false;

    }
    public function date_parse($date){

        if(!empty($date)){

            $date_array = date_parse($date);

            $dateObj   = DateTime::createFromFormat('!m', $date_array['month']);
            $monthName = $dateObj->format('F'); // March

            $tpl = "<div class='morf-date'>" . $date_array['day'] . " " . $monthName . ". " . $date_array['year'] . " </div>";
            $tpl .= "<div class='morf-time'>" . $date_array['hour'] . ":" .$date_array['minute'] . ":" . $date_array['second'] . " </div>";
            return $tpl;

        }

    }
    public function convert_filesize($bytes, $decimals = 2){
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
    public function test(){

        echo 'test';

    }
    public function errorExeption(){
        function mkdir_error_handler($errno, $errstr, $errfile, $errline ) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
        set_error_handler("mkdir_error_handler");
    }
}