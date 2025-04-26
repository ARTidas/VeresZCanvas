<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class CanvasDisplayV2View extends ProjectAbstractView {

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
                    <link rel="stylesheet" href="<?php print(RequestHelper::$common_url_root); ?>/css/index.css" type="text/css" />
                    <link rel="stylesheet" href="<?php print(RequestHelper::$url_root); ?>/css/index.css" type="text/css" />

                    <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/jquery/jquery.js"></script>
                    <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/nav_menu_dropdown.js"></script>

                    <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/TensorFlow/tf.js"></script>
                    <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/P5/p5.js"></script>
                    <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/genesys.js"></script>
                    <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/canvas.js"></script>
                    <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/sketch_v2.js"></script>                    
                </head>
			<?php
		}

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayHeader() {
			?>
                <body>
                    <div class="head_container">
                        <h1><?php print(RequestHelper::$project_name); ?> - Digit Drawing Practice</h1>
                        <?php
                            if (isset($_SESSION['user_id'])) {
                                if (isset($_SESSION['user_name'])) {
                                    print('<p>Hello ' . $_SESSION['user_name'] . '!</p>');
                                }
                                else {
                                    print('<p>Hello user#' . $_SESSION['user_id'] . '</p>');
                                }
                            }
                        ?>
                    </div>
			<?php
		}

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
			?>
                <div class="control-panel">
                    <button onclick="checkAnswer()" style="float:left;">Check</button>
                    <p style="display:inline-block;color:#fff;font-weight:bold;font-size:18px;padding:6px;">Draw the following digit!</p>
                    <button onclick="clearCanvas()" style="float:right;">Clear</button>
                    <br style="clear:both;" />
                </div>

                <div id="numberDisplay"></div>

                <div class="content-center">
                    <main></main>
                </div>
                
                <div>
                    Prediction: <span id="prediction"></span> /
                    Confidence: <span id="confidence"></span>
                </div>

                <br clear="all" />
			<?php
		}


        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayFooter() {
            ?>
                </body>
            <?php
        }

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayLogs() {
		}

    }

?>