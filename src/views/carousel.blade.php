<div class="carousel">
	<div class="carousel--inner">
		<ul class="carousel--list">
			@foreach ($panels as $panel)
				<li class="carousel--panel{{ !empty($panel->css_class) ? ' carousel--panel__' . $panel->css_class : '' }}">
					<img src="{{ $panel->background_src }}" class="carousel--panel--img" width="{{ $panel->background_width }}" height="{{ $panel->background_height }}" alt="{{ $panel->title }}" />
					<div class="carousel--panel--text">
						<h3>{{ $panel->title }}</h3>
						<p>{{ $panel->description }}</p>
						<a href="{{ $panel->link_url }}" title="{{ $panel->title }}">{{ $panel->link_text }} <i class="fa fa-chevron-circle-right"></i></a>
					</div>
				</li>
			@endforeach
		</ul>
		@if (Fbf\LaravelCarousel\Panel::getImageConfig('icon', null, 'show'))
		<ul class="carousel--nav">
			@foreach ($panels as $panel)
				<li class="carousel--nav--item{{ !empty($panel->css_class) ? ' carousel--nav--item__' . $panel->css_class : '' }}">
					<a href="{{ $panel->link_url }}" title="{{ $panel->title }}">
						<img src="{{ $panel->icon_src }}" class="carousel--nav--img" width="{{ $panel->icon_width }}" height="{{ $panel->icon_height }}" alt="{{ $panel->title }}" />
						<span>{{ $panel->title }}</span>
					</a>
				</li>
			@endforeach
		</ul>
		@endif
	</div>
</div>