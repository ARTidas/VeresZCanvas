<?php

	$bo = $bo_factory->get(StringHelper::toPascalCase(RequestHelper::$actor_name));
    
	/* ********************************************************
	 * *** Lets control exectution by actor action... *********
	 * ********************************************************/
	$view = null;

	switch (RequestHelper::$actor_action) {
		case '':
			LogHelper::addError('No actor action detected...');
			break;
		case 'display':
			$do_list = $bo->getList();
			
			$view = new (RequestHelper::$actor_class_name . StringHelper::toPascalCase(RequestHelper::$actor_action) . 'View')(
				new ViewDo(
					RequestHelper::$project_name . ' > ' . RequestHelper::$actor_name . ' > ' . RequestHelper::$actor_action,
					'DESCRIPTION - ' . RequestHelper::$project_name . ' > ' . RequestHelper::$actor_name . ' > ' . RequestHelper::$actor_action,
					$do_list //$do_list
				),
			);
			
			break;
		case 'display_2':
			$do_list = $bo->getList();
			
			$view = new (RequestHelper::$actor_class_name . StringHelper::toPascalCase(RequestHelper::$actor_action) . 'View')(
				new ViewDo(
					RequestHelper::$project_name . ' > ' . RequestHelper::$actor_name . ' > ' . RequestHelper::$actor_action,
					'DESCRIPTION - ' . RequestHelper::$project_name . ' > ' . RequestHelper::$actor_name . ' > ' . RequestHelper::$actor_action,
					null //$do_list
				),
			);
			
			break;
		case 'display_3':
			$do_list = $bo->getList();
			
			$view = new (RequestHelper::$actor_class_name . StringHelper::toPascalCase(RequestHelper::$actor_action) . 'View')(
				new ViewDo(
					RequestHelper::$project_name . ' > ' . RequestHelper::$actor_name . ' > ' . RequestHelper::$actor_action,
					'DESCRIPTION - ' . RequestHelper::$project_name . ' > ' . RequestHelper::$actor_name . ' > ' . RequestHelper::$actor_action,
					null //$do_list
				),
			);
			
			break;
		default:
			LogHelper::addError('Unhandled action...');
			break;
	}

	$view->displayHTMLOpen();
	$view->displayHeader();
	$view->displayMenu();
	$view->displayContent();
	$view->displayFooter();
	$view->displayLogs();
	$view->displayHTMLClose();

?>
