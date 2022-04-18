<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */

if(isset($main_list) && !empty($main_list)){
    $cnt = 1;
    foreach ($main_list as $elem){

        $without_root_path = str_replace(MORF_ROOT, "", $elem['info']['0']);
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $cur_url = $actual_link . '/' . $without_root_path;

        if(isset($elem['extension']) && !empty($elem['extension'])){

            if($elem['extension'] == 'php'){
                $icon = 'fa-brands fa-php';
            } elseif ($elem['extension'] == 'js'){
                $icon = 'fa-brands fa-js';
            } else if($elem['extension'] == 'jpeg' or
                $elem['extension'] == 'jpg' or
                $elem['extension'] == 'JPG' or
                $elem['extension'] == 'png' or
                $elem['extension'] == 'svg'
            ){
                $icon = 'fa-solid fa-file-image';
            } else if($elem['extension'] == 'html'){
                $icon = 'fa-solid fa-file-code';
            } else if($elem['extension'] == 'zip' or
                $elem['extension'] == 'tar' or
                $elem['extension'] == 'gz' or
                $elem['extension'] == 'rar'
            ) {
                $icon = 'fa-solid fa-file-zipper';
            } else if($elem['extension'] == 'txt') {
                $icon = 'fa-solid fa-file-lines';
            } else if($elem['extension'] == 'htaccess') {
                $icon = 'fa-solid fa-gears';
            } else if($elem['extension'] == 'sql') {
                $icon = 'fa-solid fa-database';
            } else if($elem['extension'] == 'css') {
                $icon = 'fa-brands fa-css3-alt';
            } else {
                $icon = 'fa-solid fa-file';
            }

        } else {

            $icon = 'fa-solid fa-folder';

        }

        ?>

        <div class="morfine_file_manager_elem_section"
             data-inode="<?php echo isset($elem['inode']) ? $elem['inode'] : false; ?>"
             data-path="<?php echo isset($elem['info']['0']) ? $elem['info']['0'] : false; ?>"
             data-url="<?php echo $cur_url; ?>"
        >
            <div class="morfine_file_manager_elem_block">
                <div class="morfine_file_manager_elem_count mf-elem mf-elem-1">
                    <input type="checkbox" value="" />
                </div>
                <div class="morfine_file_manager_elem_name mf-elem mf-elem-2"
                     data-type="<?php echo isset($elem['info']['2']) ? $elem['info']['2'] : 'error' ?>"
                     data-extension="<?php echo isset($elem['extension']) ? $elem['extension'] : 'error' ?>">
                    <a href="#">
                        <div class="morf_icon_section">
                            <i class="<?php echo $icon; ?>"></i>
                        </div>
                        <?php echo isset($elem['name']) ? $elem['name'] : 'error' ?>
                    </a>
                </div>
                <div class="morfine_file_manager_elem_file_size mf-elem mf-elem-3">
                    <?php if(!is_dir($elem['info']['0'])): ?>
                    <?php echo isset($elem['info']['1']) ? $elem['info']['1'] : 'error' ?>
                    <?php else: ?>
                    <?php echo "<a href='#' class='get_folder_size' data-param='get_dir_size'>Get size</a>"; endif;?>
                </div>
                <div class="morfine_file_manager_elem_can mf-elem mf-elem-4">
                    <div class="morfine_file_manager_elem_can_name">
                        <?php echo isset($elem['info']['3']) ? intval( $elem['info']['3'] ) : 'error' ?>
                    </div>
                    <div class="morfine_file_manager_elem_can_button">
                        <a href="#"><i class="fa-solid fa-pen-to-square"></i></a>
                    </div>
                </div>
                <div class="morfine_file_manager_elem_owner mf-elem mf-elem-5">
                    <div class="morfine_file_manager_elem_owner_name">
                        <?php echo isset($elem['info']['4']) ? $elem['info']['4'] : 'error' ?>
                    </div>
                    <div class="morfine_file_manager_elem_owner_button">
                        <a href="#"><i class="fa-solid fa-pen-to-square"></i></a>
                    </div>
                </div>
                <div class="morfine_file_manager_elem_last_access mf-elem mf-elem-6">
                    <?php echo isset($elem['info']['6']) ? $elem['info']['6'] : 'error' ?>
                </div>
            </div>
        </div>
        <?php $cnt++; }
}