<?php

    /* ********************************************************
	 * *** Models *********************************************
	 * ********************************************************/

        /* ********************************************************
         * *** Business Objects ***********************************
         * ********************************************************/
        require(RequestHelper::$file_root . '/models/bos/mariadb_database_connection_bo.php');
        require(RequestHelper::$common_file_root . '/models/bos/abstract_bo.php');
        require(RequestHelper::$common_file_root . '/models/bos/security_bo.php');
        require(RequestHelper::$common_file_root . '/models/bos/permission_bo.php');
        require(RequestHelper::$common_file_root . '/models/bos/user_bo.php');
        require(RequestHelper::$file_root . '/models/bos/canvas_bo.php');
        require(RequestHelper::$file_root . '/models/bos/digit_bo.php');

        /* ********************************************************
         * *** Data Access Objects ********************************
         * ********************************************************/
        require(RequestHelper::$common_file_root . '/models/daos/abstract_dao.php');
        require(RequestHelper::$common_file_root . '/models/daos/permission_dao.php');
        require(RequestHelper::$common_file_root . '/models/daos/user_dao.php');
        require(RequestHelper::$file_root . '/models/daos/canvas_dao.php');
        require(RequestHelper::$file_root . '/models/daos/digit_dao.php');

        /* ********************************************************
         * *** Data Objects ***************************************
         * ********************************************************/
        require(RequestHelper::$common_file_root . '/models/dos/view_do.php');
        require(RequestHelper::$common_file_root . '/models/dos/abstract_do.php');
        require(RequestHelper::$common_file_root . '/models/dos/permission_do.php');
        require(RequestHelper::$common_file_root . '/models/dos/user_do.php');
        require(RequestHelper::$file_root . '/models/dos/canvas_do.php');
        require(RequestHelper::$file_root . '/models/dos/digit_do.php');

        /* ********************************************************
         * *** Helpers ********************************************
         * ********************************************************/
        require(RequestHelper::$common_file_root . '/models/helpers/log_helper.php');
        require(RequestHelper::$common_file_root . '/models/helpers/actor_helper.php'); //TODO: Do we need this?
        require(RequestHelper::$common_file_root . '/models/helpers/string_helper.php');
        require(RequestHelper::$common_file_root . '/models/helpers/datetime_helper.php');
        require(RequestHelper::$common_file_root . '/models/helpers/permission_helper.php');

        /* ********************************************************
         * *** Factories ******************************************
         * ********************************************************/
        require(RequestHelper::$common_file_root . '/models/factories/bo_factory.php');
        require(RequestHelper::$common_file_root . '/models/factories/dao_factory.php');
        require(RequestHelper::$common_file_root . '/models/factories/do_factory.php');


    /* ********************************************************
	 * *** Views **********************************************
	 * ********************************************************/
    require(RequestHelper::$common_file_root . '/views/abstract_view.php');
    require(RequestHelper::$file_root . '/views/project_abstract_view.php');
    require(RequestHelper::$file_root . '/views/index_view.php');
    require(RequestHelper::$file_root . '/views/canvas_display_v1_view.php');
    require(RequestHelper::$file_root . '/views/canvas_display_v2_view.php');
    require(RequestHelper::$file_root . '/views/digit_view_view.php');

?>