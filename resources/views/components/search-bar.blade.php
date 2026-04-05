@props([
    'action' => null,
    'method' => 'GET',
    'inputId' => null,
    'inputName' => 'q',
    'inputValue' => '',
    'placeholder' => 'Search...',
    'buttonId' => null,
    'buttonType' => 'submit',
    'buttonLabel' => 'Search',
    'buttonAriaLabel' => 'Search',
    'iconOnly' => false,
    'wrapperClass' => '',
    'groupClass' => '',
    'inputClass' => '',
    'buttonClass' => '',
])

@php
    $wrapperClasses = trim('search-wrapper ' . $wrapperClass);
    $groupClasses = trim('search-group ' . $groupClass);
    $inputClasses = trim('search-input ' . $inputClass);
    $buttonClasses = trim('search-button ' . ($iconOnly ? 'search-button--icon-only ' : '') . $buttonClass);
    $searchMethod = strtoupper($method);
@endphp

<style>
    .search-wrapper {
        margin-bottom: 1.5rem;
    }

    .search-group {
        display: flex;
        align-items: center;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        border: 1px solid;
        border-radius: 0.375rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background-color: #f3f4f6;
        border-color: #e5e7eb;
        color: #1f2937;
    }

    .search-input::placeholder {
        color: #9ca3af;
    }

    .search-input:focus {
        outline: none;
        background-color: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    :root[data-theme="dark"] .search-input {
        background-color: #1f2937;
        border-color: #374151;
        color: #f3f4f6;
    }

    :root[data-theme="dark"] .search-input::placeholder {
        color: #6b7280;
    }

    :root[data-theme="dark"] .search-input:focus {
        background-color: #111827;
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 0.75rem;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        width: 18px;
        height: 18px;
    }

    :root[data-theme="dark"] .search-icon {
        color: #6b7280;
    }

    .search-button {
        padding: 0.6rem 1.5rem;
        margin-left: 0.5rem;
        border: none;
        border-radius: 0.375rem;
        background-color: #000000;
        color: #ffffff;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .search-button:hover {
        background-color: #1f2937;
    }

    :root[data-theme="dark"] .search-button {
        background-color: #ffffff;
        color: #000000;
    }

    :root[data-theme="dark"] .search-button:hover {
        background-color: #e5e7eb;
    }

    .search-button--icon-only {
        padding: 0.5rem;
        width: 2.5rem;
        height: 2.5rem;
        justify-content: center;
    }
</style>

@if (!empty($action))
    <form action="{{ $action }}" method="{{ $searchMethod }}" {{ $attributes->class($wrapperClasses) }}>
@else
    <div {{ $attributes->class($wrapperClasses) }}>
@endif
    <div class="{{ $groupClasses }}">
        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.35-4.35"></path>
        </svg>
        <input
            type="text"
            name="{{ $inputName }}"
            value="{{ $inputValue }}"
            @if ($inputId) id="{{ $inputId }}" @endif
            class="{{ $inputClasses }}"
            placeholder="{{ $placeholder }}"
        >
        @unless ($iconOnly)
            <button
                type="{{ $buttonType }}"
                class="{{ $buttonClasses }}"
                @if ($buttonId) id="{{ $buttonId }}" @endif
                aria-label="{{ $buttonAriaLabel }}"
            >
                {{ $buttonLabel }}
            </button>
        @endunless
    </div>
@if (!empty($action))
    </form>
@else
    </div>
@endif
