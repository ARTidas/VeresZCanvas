<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	abstract class ProjectAbstractView extends AbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayHTMLOpen() {
			?>
				<!doctype html>
                <html lang="en-US">
                <head>
                    <title><?php print($this->do->title); ?></title>

                    <meta charset="UTF-8" />
                    <meta http-equiv="content-type" content="text/html" />
                    <meta name="description" content="<?php print($this->do->description); ?>" />
                    <meta http-equiv="cache-control" content="max-age=0" />
                    <meta http-equiv="cache-control" content="no-cache" />
                    <meta http-equiv="expires" content="0" />
                    <meta http-equiv="pragma" content="no-cache" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">

                    <link rel="stylesheet" href="<?php print(RequestHelper::$common_url_root); ?>/css/menu.css" type="text/css" />
                    <link rel="stylesheet" href="<?php print(RequestHelper::$common_url_root); ?>/css/form.css" type="text/css" />
                    <link rel="stylesheet" href="<?php print(RequestHelper::$common_url_root); ?>/css/footer.css" type="text/css" />
                    <link rel="stylesheet" href="<?php print(RequestHelper::$common_url_root); ?>/css/index.css" type="text/css" />
                    <link rel="stylesheet" href="<?php print(RequestHelper::$url_root); ?>/css/index.css" type="text/css" />

                    <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/jquery/jquery.js"></script>
                    <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/nav_menu_dropdown.js"></script>
                </head>
			<?php
		}

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayMenu() {
			?>
                <!-- <div class="box">
                    <?php
                        print(RequestHelper::$project_name . ' > ' . RequestHelper::$actor_name . ' > ' . RequestHelper::$actor_action);
                    ?>
                </div> -->

				<section id="menu">
                    <nav>
                        <a href="<?php print(RequestHelper::$url_root); ?>">Main</a>
                        <a href="<?php print(RequestHelper::$url_domain); ?>">PTI Main</a>

                        <div>
                            <button>User</button>
                            <div>
                                <a href="<?php print(RequestHelper::$common_url_root); ?>/user/create">Register</a>
                                <a href="<?php print(RequestHelper::$common_url_root); ?>/user/login">Login</a>
                                <a href="<?php print(RequestHelper::$common_url_root); ?>/user_profile/view">Profile</a>
                            </div>
                        </div>

                        <div>
                            <button>Digits</button>
                            <div>
                                <a href="<?php print(RequestHelper::$url_root); ?>/canvas/display_v2#main">Canvas</a>
                                <a href="<?php print(RequestHelper::$url_root); ?>/digit/view">Results</a>
                                <a href="<?php print(RequestHelper::$common_url_root); ?>/js/TensorFlow/models/trainer.html">Trainer</a>
                            </div>
                        </div>

                        <!-- <a href="<?php print(RequestHelper::$url_root); ?>/canvas/display_v1#main">Canvas V1</a> -->
                        <!-- <a href="<?php print(RequestHelper::$url_root); ?>/canvas/display_v2#main">Digit drawing</a> -->
                        <!-- <a href="<?php print(RequestHelper::$url_root); ?>/canvas/display_v3#main">Addition practice</a> -->

                        <!-- <div>
                            <button>Canvas</button>
                            <div>
                                <a href="<?php print(RequestHelper::$url_root); ?>/canvas/display#main">Display V1</a>
                            </div>
                        </div> -->
                        <!-- <div>
                            <button>Models</button>
                            <div>
                                <a href="<?php print(RequestHelper::$common_url_root); ?>/js/TensorFlow/models/trainer.html">Trainer - HandDrawnDigits</a>
                            </div>
                        </div> -->

                    </nav>
                </section>

                
			<?php
		}

    }

?>