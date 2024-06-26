<?php

namespace Mary\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Range extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?bool $omitError = false,
        public ?int $min = 0,
        public ?int $max = 100,

    ) {
        $this->uuid = "mary" . md5(serialize($this));
    }

    public function modelName(): string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <div>
                    <!-- Label -->
                    @if($label)
                        <label for="{{ $uuid }}" class="pt-0 label label-text font-semibold">
                            <span>
                                {{ $label }}

                                @if($attributes->get('required'))
                                    <span class="text-error">*</span>
                                @endif
                            </span>
                        </label>
                    @endif

                    <!-- Range -->
                    <input
                        type="range"
                        min="{{ $min }}"
                        max="{{ $max }}"
                        {{ $attributes->merge(["class" => "range"])->except('label', 'hint', 'min', 'max') }}
                    />

                    <!-- ERROR -->
                    @if(!$omitError && $modelName())
                        @error($modelName())
                            <div class="text-red-500 label-text-alt p-1">{{ $message }}</div>
                        @enderror
                    @endif

                    <!-- HINT -->
                    @if($hint)
                        <div class="label-text-alt text-gray-400 p-1 pb-0">{{ $hint }}</div>
                    @endif
                </div>
            HTML;
    }
}
