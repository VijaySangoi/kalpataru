<div class="sidebar">
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'job') class="active " @endif>
                <a href="{{'/job'}}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Job') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'triggers') class="active " @endif>
                <a href="{{'/triggers'}}">
                    <i class="tim-icons icon-controller"></i>
                    <p>{{ __('Triggers') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'serial') class="active " @endif>
                <a href="{{'/serial'}}">
                    <i class="tim-icons icon-puzzle-10"></i>
                    <p>{{ __('Serial') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'workers') class="active " @endif>
                <a href="{{'/workers'}}">
                    <i class="tim-icons icon-puzzle-10"></i>
                    <p>{{ __('Workers') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'cip') class="active " @endif>
                <a href="{{'/cip'}}">
                    <i class="tim-icons icon-puzzle-10"></i>
                    <p>{{ __('CIP') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'logs') class="active " @endif>
                <a href="{{'/logs'}}">
                    <i class="tim-icons icon-notes"></i>
                    <p>{{ __('Logs') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'scrathpad') class="active " @endif>
                <a href="{{'/scrathpad'}}">
                    <i class="tim-icons icon-palette"></i>
                    <p>{{ __('Scrathpad') }}</p>
                </a>
            </li>
        </ul>
    </div>
</div>
