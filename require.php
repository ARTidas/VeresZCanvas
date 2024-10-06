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
        require(RequestHelper::$file_root . '/models/bos/message_queue_bo.php');
        require(RequestHelper::$file_root . '/models/bos/sensor_bo.php');

        /* ********************************************************
         * *** Data Access Objects ********************************
         * ********************************************************/
        require(RequestHelper::$common_file_root . '/models/daos/abstract_dao.php');
        require(RequestHelper::$common_file_root . '/models/daos/permission_dao.php');
        require(RequestHelper::$common_file_root . '/models/daos/user_dao.php');
        require(RequestHelper::$file_root . '/models/daos/message_queue_dao.php');
        require(RequestHelper::$file_root . '/models/daos/sensor_dao.php');

        /* ********************************************************
         * *** Data Objects ***************************************
         * ********************************************************/
        require(RequestHelper::$common_file_root . '/models/dos/view_do.php');
        require(RequestHelper::$common_file_root . '/models/dos/abstract_do.php');
        require(RequestHelper::$common_file_root . '/models/dos/permission_do.php');
        require(RequestHelper::$common_file_root . '/models/dos/user_do.php');
        require(RequestHelper::$file_root . '/models/dos/message_queue_do.php');
        require(RequestHelper::$file_root . '/models/dos/sensor_do.php');

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
    require(RequestHelper::$file_root . '/views/message_queue_view_view.php');
    require(RequestHelper::$file_root . '/views/message_queue_get_view.php');
    require(RequestHelper::$file_root . '/views/message_queue_mqtt_view.php');
    require(RequestHelper::$file_root . '/views/message_queue_simulation_2_view.php');
    require(RequestHelper::$file_root . '/views/message_queue_simulation_3_view.php');
    require(RequestHelper::$file_root . '/views/sensor_display_view.php');
    require(RequestHelper::$file_root . '/views/sensor_display_2_view.php');
    require(RequestHelper::$file_root . '/views/sensor_display_3_view.php');

?>