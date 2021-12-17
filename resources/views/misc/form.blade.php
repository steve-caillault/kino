<form {!! $attributes !!}>
    @csrf
	@if($withTitle)
	<h2>{{ $title }}</h2>
	@endif
	@foreach($inputGroupKeys as $inputGroupKey)
		@foreach($inputs[$inputGroupKey] as $key => $inputWithLabel)
		<div {!! $inputAttributes[$key] !!}>
			{!! $inputWithLabel !!}
			@if($error = ($errors[$key] ?? null))
			<p class="error">{{ $error }}</p>
			@endif
		</div>
		@endforeach
	@endforeach
    <div class="form-input form-input-submit">{!! $inputs['submit'] !!}</div>

	@foreach($inputs['hidden'] as $input)
	{!! $input !!}
	@endforeach
	{!! $inputs['name'] !!}
</form>