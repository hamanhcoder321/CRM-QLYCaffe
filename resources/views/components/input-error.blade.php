@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'input-error-wrapper']) }}>
        @foreach ((array) $messages as $message)
            <div class="input-error-box">{{ $message }}</div>
        @endforeach
    </div>
@endif
