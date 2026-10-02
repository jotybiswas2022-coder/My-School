@if (session('success') || session('error') || session('warning') || session('status'))
    <div class="container" style="padding-top:22px;">
        @if (session('success'))
            <div class="alert alert-success" data-dismiss>
                <i class="bi bi-check-circle-fill"></i>
                <span><strong>{{ __('ui.common.success') }}</strong> {{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" data-dismiss>
                <i class="bi bi-exclamation-octagon-fill"></i>
                <span><strong>{{ __('ui.common.error') }}</strong> {{ session('error') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning" data-dismiss>
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><strong>{{ __('ui.common.notice') }}</strong> {{ session('warning') }}</span>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-info" data-dismiss>
                <i class="bi bi-info-circle-fill"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any() && ! session('success'))
            <div class="alert alert-danger" data-dismiss>
                <i class="bi bi-exclamation-circle-fill"></i>
                <span><strong>{{ __('ui.common.review_form') }}</strong></span>
            </div>
        @endif
    </div>
@endif
