<?php

return array(

	/**
	 * Model title
	 *
	 * @type string
	 */
	'title' => 'Homepage Carousel',

	/**
	 * The singular name of your model
	 *
	 * @type string
	 */
	'single' => 'panel',

	/**
	 * The class name of the Eloquent model that this config represents
	 *
	 * @type string
	 */
	'model' => 'Fbf\LaravelCarousel\Panel',

	/**
	 * The columns array
	 *
	 * @type array
	 */
	'columns' => array(
		'title' => array(
			'title' => 'Title',
			'sortable' => false,
		),
		'order' => array(
			'title' => 'Panel order',
		),
		'status' => array(
			'title' => 'Status',
			'select' => "CASE (:table).status WHEN '".Fbf\LaravelCarousel\Panel::APPROVED."' THEN 'Approved' WHEN '".Fbf\LaravelCarousel\Panel::DRAFT."' THEN 'Draft' END",
			'sortable' => false,
		),
		'published_date' => array(
			'title' => 'Published',
			'sortable' => false,
		),
		'updated_at' => array(
			'title' => 'Last Updated',
			'sortable' => false,
		),
	),

	/**
	 * The edit fields array
	 *
	 * @type array
	 */
	'edit_fields' => array(
		'title' => array(
			'title' => 'Title',
			'type' => 'text',
		),
		'description' => array(
			'title' => 'Description',
			'type' => 'textarea',
		),
		'link_text' => array(
			'title' => 'Link text',
			'type' => 'text',
		),
		'link_url' => array(
			'title' => 'Link URL (enter the relative URL, i.e. the bit after the domain, e.g. "/about")',
			'type' => 'text',
		),
		'css_class' => array(
			'title' => 'CSS Class',
			'type' => 'enum',
			'options' => Config::get('laravel-carousel::css_class.options'),
			'visible' => Config::get('laravel-carousel::css_class.show'),
		),
		'background_image' => array(
			'title' => 'Background Image',
			'type' => 'image',
			'naming' => 'random',
			'location' => public_path(Config::get('laravel-carousel::images.background.original.dir')),
			'size_limit' => 5,
			'sizes' => array(
				array(
					Config::get('laravel-carousel::images.background.sizes.resized.width'),
					Config::get('laravel-carousel::images.background.sizes.resized.height'),
					'crop',
					public_path(Config::get('laravel-carousel::images.background.sizes.resized.dir')),
					100
				),
			),
		),
		'icon_image' => array(
			'title' => 'Icon Image',
			'type' => 'image',
			'naming' => 'random',
			'location' => public_path(Config::get('laravel-carousel::images.icon.original.dir')),
			'size_limit' => 5,
			'sizes' => array(
				array(
					Config::get('laravel-carousel::images.icon.sizes.resized.width'),
					Config::get('laravel-carousel::images.icon.sizes.resized.height'),
					'crop',
					public_path(Config::get('laravel-carousel::images.icon.sizes.resized.dir')),
					100
				),
			),
			'visible' => Config::get('laravel-carousel::images.icon.show'),
		),
		'status' => array(
			'type' => 'enum',
			'title' => 'Status',
			'options' => array(
				Fbf\LaravelCarousel\Panel::DRAFT => 'Draft',
				Fbf\LaravelCarousel\Panel::APPROVED => 'Approved',
			),
		),
		'published_date' => array(
			'title' => 'Published Date',
			'type' => 'datetime',
			'date_format' => 'yy-mm-dd', //optional, will default to this value
			'time_format' => 'HH:mm',    //optional, will default to this value
		),
		'created_at' => array(
			'title' => 'Created',
			'type' => 'datetime',
			'editable' => false,
		),
		'updated_at' => array(
			'title' => 'Updated',
			'type' => 'datetime',
			'editable' => false,
		),
	),

	/**
	 * This is where you can define the model's custom actions
	 */
	'actions' => array(
		// Ordering an item left
		'order_up' => array(
			'title' => 'Order Up / Left',
			'messages' => array(
				'active' => 'Reordering...',
				'success' => 'Reordered',
				'error' => 'There was an error while reordering',
			),
			'permission' => function($model)
				{
					// Get the ID of the record
					if (!Request::segment(3))
					{
						return true;
					}
					$id = Request::segment(3);
					// If there any siblings to the left of this panel, show the 'Order Up / Left' button
					return $model::hasLeftSibling($id);
				},
			//the model is passed to the closure
			'action' => function($model)
				{
					return $model->moveLeft();
				}
		),
		// Ordering an item down / right
		'order_down' => array(
			'title' => 'Order Down / Right',
			'messages' => array(
				'active' => 'Reordering...',
				'success' => 'Reordered',
				'error' => 'There was an error while reordering',
			),
			'permission' => function($model)
				{
					// Get the ID of the record
					if (!Request::segment(3))
					{
						return true;
					}
					$id = Request::segment(3);
					// If there any siblings to the right of this panel, show the 'Order Down / Right' button
					return $model::hasRightSibling($id);
				},
			//the model is passed to the closure
			'action' => function($model)
				{
					return $model->moveRight();
				}
		),

	),

	/**
	 * The validation rules for the form, based on the Laravel validation class
	 *
	 * @type array
	 */
	'rules' => array(
		'title' => 'required|max:25',
		'description' => 'required|max:200',
		'link_text' => 'required|max:20',
		'link_url' => 'required|max:255',
		'background_image' => '',
		'icon_image' => '',
		'css_class' => 'max:255',
		'status' => 'required',
		'published_date' => 'required',
	),

	/**
	 * The sort options for a model
	 *
	 * @type array
	 */
	'sort' => array(
		'field' => 'order',
		'direction' => 'asc',
	),

	/**
	 * If provided, this is run to construct the front-end link for your model
	 *
	 * @type function
	 */
	'link' => function($model)
		{
			return $model->link_url;
		},

);