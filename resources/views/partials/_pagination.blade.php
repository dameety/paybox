@if ($paginator->hasPages())
<ul class="uk-pagination">
    @if ($paginator->onFirstPage())
        <li>
            <span class="uk-margin-small-right uk-disabled"></span> @lang('pagination.previous')
        </li>
    @else
        <li>
            <a href="{{ $paginator->previousPageUrl() }}">
                <span class="uk-margin-small-right"
                ></span>
                @lang('pagination.previous')
            </a>
        </li>
    @endif

    @if ($paginator->hasMorePages())
        <li class="uk-margin-auto-left">
            <a href="{{ $paginator->nextPageUrl() }}">
                @lang('pagination.next')
                <span class="uk-margin-small-left"></span>
            </a>
        </li>
    @else
        <li class="uk-margin-auto-left uk-disabled">
            @lang('pagination.next')
            <span class="uk-margin-small-left"></span>
        </li>
    @endif
</ul>
@endif