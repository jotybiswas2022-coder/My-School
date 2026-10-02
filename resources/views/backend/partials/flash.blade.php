@if (session('success'))
    <div class="b-alert b-alert-success" data-dismiss>
        <strong>Success.</strong>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="b-alert b-alert-danger" data-dismiss>
        <strong>Error.</strong>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if (session('status'))
    <div class="b-alert b-alert-info" data-dismiss>
        <span>{{ session('status') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="b-alert b-alert-danger" data-dismiss>
        <div>
            <strong>Please fix the following:</strong>
            <ul style="margin-top:6px;padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
