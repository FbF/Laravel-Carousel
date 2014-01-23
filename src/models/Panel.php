<?php namespace Fbf\LaravelCarousel;

class Panel extends \Eloquent {

	/**
	 * Status values for the database
	 */
	const DRAFT = 'DRAFT';
	const APPROVED = 'APPROVED';

	/**
	 * Name of the table to use for this model
	 * @var string
	 */
	protected $table = 'fbf_carousel_panels';

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::creating(function(\Eloquent $model)
		{
			// Inserts panel at the end of the list
			$model->order = self::getOrderForNewRecord();
		});

		static::deleted(function($model)
		{
			// Moves right siblings left one space to fill the gap left by the deleted panel
			self::moveRightSiblingsLeft($model->order);
		});
	}

	/**
	 * When inserting a new record, get the order value to be used
	 * @return mixed
	 */
	public static function getOrderForNewRecord()
	{
		return self::count();
	}

	/**
	 * When deleting a record, the siblings to the right of the deleted record should be moved left to fill the gap
	 * @param $order
	 * @return mixed
	 */
	public static function moveRightSiblingsLeft($order)
	{
		return self::where('order','>',$order)->decrement('order');
	}

	/**
	 * Returns true if the record with the given ID has any records to the left, or false if it is the leftmost record
	 * @param $id
	 * @return bool
	 */
	public static function hasLeftSibling($id)
	{
		$record = self::where('id','=',$id)
			->first();

		$order = $record->order;

		return (bool)self::where('order','<',$order)
			->count();
	}

	/**
	 * Moves the current record left one place, and the record that was there previously is moved right one place, i.e.
	 * the position of the current record is swapped with the record to it's left
	 * @return bool
	 */
	public function moveLeft()
	{
		try {
			// Moves the current panel left one
			$this->decrement('order');

			$newOrder = $this->order - 1;

			// Moves the panel that is now at the same 'order' as the panel we just moved, right one
			self::where('order','=',$newOrder)
				->where('id','!=',$this->id)
				->increment('order');

			return true;

		} catch (\Exception $e) {
			return false;
		}

	}


	/**
	 * Returns true if the record with the given ID has any records to the right, or false if it is the rightmost record
	 * @param $id
	 * @return bool
	 */
	public static function hasRightSibling($id)
	{
		$record = self::where('id','=',$id)
			->first();

		$order = $record->order;

		return (bool)self::where('order','>',$order)
			->count();
	}

	/**
	 * Moves the current record right one place, and the record that was there previously is moved left one place, i.e.
	 * the position of the current record is swapped with the record to it's right
	 * @return bool
	 */
	public function moveRight()
	{
		try {
			// Moves the current panel right one
			$this->increment('order');

			$newOrder = $this->order + 1;

			// Moves the panel that is now at the same 'order' as the panel we just moved, right one
			self::where('order','=',$newOrder)
				->where('id','!=',$this->id)
				->decrement('order');

			return true;

		} catch (\Exception $e) {
			return false;
		}

	}

	/**
	 * Returns a collection of Panel object for panels that are live, in the correct order, with the path, width and
	 * height of the background image and the icon image set on each panel.
	 *
	 * @return mixed
	 */
	public static function getData()
	{
		$panels = self::where('status','=',self::APPROVED)
			->where('published_date','<=',\Carbon\Carbon::now())
			->orderBy('order', 'asc')
			->get();

		$pathToBackground = self::getImageConfig('background', 'resized', 'dir');
		$backgroundWidth = self::getImageConfig('background', 'resized', 'width');
		$backgroundHeight = self::getImageConfig('background', 'resized', 'height');

		if (self::getImageConfig('icon', null, 'show'))
		{
			$pathToIcon = self::getImageConfig('icon', 'resized', 'dir');
			$iconWidth = self::getImageConfig('icon', 'resized', 'width');
			$iconHeight = self::getImageConfig('icon', 'resized', 'height');
		}

		foreach ($panels as $panel)
		{
			$panel->background_src = $pathToBackground . $panel->background_image;
			$panel->background_width = $backgroundWidth;
			$panel->background_height = $backgroundHeight;
			if (self::getImageConfig('icon', null, 'show'))
			{
				$panel->icon_src = $pathToIcon . $panel->icon_image;
				$panel->icon_width = $iconWidth;
				$panel->icon_height = $iconHeight;
			}
		}

		return $panels;
	}

	/**
	 * Returns the config setting for an image
	 *
	 * @param $type
	 * @param $size
	 * @param $property
	 * @return mixed
	 */
	public static function getImageConfig($type, $size, $property)
	{
		$config = 'laravel-carousel::images.' . $type . '.';
		if ($size == 'original')
		{
			$config .= 'original.';
		}
		elseif (!is_null($size))
		{
			$config .= 'sizes.' . $size . '.';
		}
		$config .= $property;
		return \Config::get($config);
	}

}
