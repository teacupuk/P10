@props(['name', 'selected'])

@php
    use Symfony\Component\Intl\Countries;
    $countries = Countries::getNames(app()->getLocale());
@endphp


<select name="{{ $name }}" id="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select w-full dark:bg-gray-700 dark:text-white']) }}>
    @foreach($countries as $code => $label)
        <option value="{{ $code }}" @selected($selected === $code)>
            {{ $label }} ({{ $code }})
        </option>
    @endforeach
</select>