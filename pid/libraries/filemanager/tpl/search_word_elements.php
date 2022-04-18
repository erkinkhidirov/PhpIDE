<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */
?>
<div class="morfine_file_manager_body">
    <div class="morfine_file_manager_body_header">
        <div class="morfine_file_manager_body_header_elem mf-elem-1">
            <span>Path</span>
        </div>
        <div class="morfine_file_manager_body_header_elem mf-elem-2">
            <span>Line</span>
        </div>
        <div class="morfine_file_manager_body_header_elem mf-elem-3">
            <span>Action</span>
        </div>
    </div>
    <div class="morfine_file_manager_main_body_section">
    <?php
    if(isset($founded_words) && !empty($founded_words)){
        $cnt = 1;
        foreach ($founded_words as $elem){
            $modified_name = str_replace(MORF_ROOT, '', $elem[0]) ?>
            <div class="morfine_file_manager_elem_section"
                 data-path="<?php echo isset($elem[0]) ? $elem[0] : false; ?>">
                <div class="morfine_file_manager_elem_block">
                    <div class="morfine_file_manager_elem_name mf-elem mf-elem-1">
                        <a href="#">
                            <?php echo $modified_name; ?>
                        </a>
                    </div>
                    <div class="morfine_file_manager_elem_line mf-elem mf-elem-2">
                        <?php echo isset($elem['1']) ? $elem['1'] : 'error' ?>
                    </div>
                    <div class="morfine_file_manager_elem_edit mf-elem mf-elem-3">
                        <a href="#">Edit</a>
                    </div>
                </div>
            </div>
            <?php $cnt++;
        }
    }
    ?>
    </div>
</div>
