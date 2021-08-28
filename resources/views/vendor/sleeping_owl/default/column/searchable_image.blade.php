<div {!! $attributes !!} data-id="{{ $id }}">
    <div class="edit-buttons">
        <button data-id="{{ $id }}" class="btn btn-xs btn-success edit-custom-image"><i class="fa fa-edit"></i></button>
    </div>
	@if ($visibled)
		@if (!empty($value))
			<a href="{{ $value }}" data-toggle="lightbox">
				@if ($lazy)
					<img class="thumbnail lazyload" src="{{ config('sleeping_owl.imageLazyLoadFile') }}" data-src="{{ $value }}" width="{{ $imageWidth }}">
				@else
					<img class="thumbnail" src="{{ $value }}" width="{{ $imageWidth }}">
				@endif
			</a>
        @else
            <img class="thumbnail" src="{{ asset('/images/placeholder.jpg') }}" width="{{ $imageWidth }}" alt="">
		@endif
		{!! $append !!}

		@if($small)
			<small class="clearfix">{!! $small !!}</small>
		@endif
	@endif
</div>
