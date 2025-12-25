<div class="flex items-center gap-4">
    @if (app()->getLocale() === 'ar')
        <a href="{{ route('lang.switch', 'en') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500 transition-colors">
            English
        </a>
    @else
        <a href="{{ route('lang.switch', 'ar') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500 transition-colors">
            العربية
        </a>
    @endif
</div>
