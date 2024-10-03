<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class SensorDisplayView extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
			?>
                <script>
                    var sensorData = <?php print(json_encode($this->do->do_list)); ?>;
                </script>
                <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/P5/p5.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/genesys.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/canvas.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/sketch.js"></script>

                <h1>
                    <?php print(RequestHelper::$actor_class_name); ?>
                    <?php print(RequestHelper::$actor_action); ?>
                </h1>

				<main id="main">
                </main>

                <br clear="all" />
			<?php
		}

    }

?>