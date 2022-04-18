<?php
/**
 * @author Erkin Khidirov
 * @author Erkin Khidirov <erkinofabrichi@gmail.com>
 */
?>

<div class="morfine_file_manager_section" data-currpath="<?php echo MORF_ROOT; ?>">
    <div class="container">
        <div class="morfine_file_manager_block">
            <div class="morfine_file_manager_head">
                <div class="morf_f_m_h_elem morfine_file_manager_files">
                    <span><i class="fa-solid fa-folder-open"></i> File</span>
                    <div class="morf_sub_section">
                        <div class="morf_sub_block">
                            <ul>
                                <li><a href="#" data-param="delete"><i class="fa-solid fa-trash-can"></i> Delete</a></li>
                                <li><a href="#" data-param="copy"><i class="fa-solid fa-clone"></i> Copy</a></li>
                                <li><a href="#" data-param="past" class="invisible"><i class="fa-solid fa-paste"></i> Paste</a></li>
                                <li><a href="#" data-param="create"><i class="fa-solid fa-folder-plus"></i> Create</a></li>
                                <li><a href="#" data-param="rename"><i class="fa-solid fa-font"></i> Rename</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="morf_f_m_h_elem morfine_file_manager_params">
                    <span><i class="fa-solid fa-box"></i> Archive</span>
                    <div class="morf_sub_section">
                        <div class="morf_sub_block">
                            <ul>
                                <li><a href="#" data-param="archive" data-type="tar"><i class="fa-solid fa-box"></i> Archive (.tar)</a></li>
                                <li><a href="#" data-param="unarchive" data-type="tar"><i class="fa-solid fa-box-open"></i> Unarchive (.tar)</a></li>
                                <li><a href="#" data-param="archive" data-type="zip"><i class="fa-solid fa-box"></i> Archive (.zip)</a></li>
                                <li><a href="#" data-param="unarchive" data-type="zip"><i class="fa-solid fa-box-open"></i> Unarchive (.zip)</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="morf_f_m_h_elem morfine_file_manager_params">
                    <span><a href="#" data-param="upload"><i class="fa-solid fa-arrow-up-from-bracket"></i> Upload</a></span>
                </div>
                <div class="morf_f_m_h_elem morfine_file_manager_params">
                    <span><i class="fa-solid fa-magnifying-glass"></i> Search</span>
                    <div class="morf_sub_section">
                        <div class="morf_sub_block">
                            <ul>
                                <li><a href="#" data-param="search_words"><i class="fa-solid fa-magnifying-glass"></i> Search words in files</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="morf_f_m_h_elem morfine_file_manager_params">
                    <span><a href="#" data-param="downloadurl"><i class="fa-solid fa-download"></i> Download URL</a></span>
                </div>
                <div class="dark_version_section">
                    <div class="dark_version_block">
                        <?php
                            $dark = $light = '';
                            if(get_dark_light_version() == 'dark'){
                                $dark = 'active';
                            } else {
                                $light = 'active';
                            }
                        ?>
                        <div class="dark_version_elem <?php echo $dark; ?>" data-type="dark">
                            Dark
                        </div>
                        <div class="dark_version_elem <?php echo $light; ?>" data-type="light">
                            Light
                        </div>
                    </div>
                </div>
            </div>
            <div class="morfine_file_manager_path_section">
                <div class="morfine_file_manager_path_block">
                    <div class="morf_f_m_h_elem morfine_file_manager_prev_next">
                        <div class="m_prev_next_elem morf_f_m_h_elem_home" data-type="home" data-path="<?php echo MORF_ROOT_NO_SLASH; ?>">
                            <i class="fa-solid fa-house"></i>
                        </div>
                        <div class="m_prev_next_elem morf_f_m_h_elem_prev" data-type="prev" data-paths='[]'>
                            <i class="fa-solid fa-angle-left"></i>
                        </div>
                        <div class="m_prev_next_elem morf_f_m_h_elem_next" data-type="next" data-paths='[]'>
                            <i class="fa-solid fa-angle-right"></i>
                        </div>
                        <div class="m_prev_next_elem morf_f_m_h_elem_reload" data-type="reload">
                            <i class="fa-solid fa-arrow-rotate-right"></i>
                        </div>
                        <div class="m_prev_next_elem header_preloader"></div>
                    </div>
                    <div class="morfine_file_manager_path_body_section">
                        <div class="morfine_file_manager_path_body_block">
                            <div class="morfine_file_manager_path_body_input">
                                <input type="text" value="<?php echo MORF_ROOT; ?>" placeholder="Current Path"/>
                            </div>
                            <div class="morfine_file_manager_path_body_params">
                                <div class="morfine_file_manager_path_body_param_elem">
                                    <a href="#" data-type="go"><i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="morfine_file_manager_body">
                <div class="morfine_file_manager_body_header">
                    <div class="morf_check_all morfine_file_manager_body_header_elem mf-elem-1">
                        <span><input type="checkbox"/></span>
                    </div>
                    <div class="morfine_file_manager_body_header_elem mf-elem-2">
                        <span>Name</span>
                    </div>
                    <div class="morfine_file_manager_body_header_elem mf-elem-3">
                        <span>Size</span>
                    </div>
                    <div class="morfine_file_manager_body_header_elem mf-elem-4">
                        <span>Permission</span>
                    </div>
                    <div class="morfine_file_manager_body_header_elem mf-elem-5">
                        <span>Owner</span>
                    </div>
                    <div class="morfine_file_manager_body_header_elem mf-elem-6">
                        <span>Last modified</span>
                    </div>
                </div>
                <div class="morfine_file_manager_main_body_section">
                    <?php require_once MORF_SITE_DIR . '/libraries/filemanager/tpl/element.php'; ?>
                </div>
            </div>
            <div class="morfine_file_manager_bottom"></div>
        </div>
    </div>
</div>
