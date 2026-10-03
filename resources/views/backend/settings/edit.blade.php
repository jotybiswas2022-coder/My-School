@extends('backend.layouts.app')

@section('title', 'Website Settings')

@section('content')
    <div class="page-header">
        <div>
            <h1>Website Settings</h1>
            <p>Manage school identity, contact details and homepage content.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="b-grid" style="grid-template-columns:1fr 340px;align-items:start;">
            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="b-card">
                    <div class="b-card-head"><h3>School Identity</h3></div>
                    <div class="b-card-body">
                        <div class="form-grid">
                            <div class="form-row">
                                <label for="school_name">School Name <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="school_name" id="school_name" class="form-control @error('school_name') is-invalid @enderror"
                                       value="{{ old('school_name', $values['school_name'] ?? 'My School') }}" required>
                                @error('school_name')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-row">
                                <label for="tagline">Tagline</label>
                                <input type="text" name="tagline" id="tagline" class="form-control" value="{{ old('tagline', $values['tagline']) }}" placeholder="Empowering Students. Inspiring Futures.">
                            </div>
                            <div class="form-row">
                                <label for="established_year">Established Year</label>
                                <input type="number" name="established_year" id="established_year" class="form-control @error('established_year') is-invalid @enderror"
                                       value="{{ old('established_year', $values['established_year']) }}" min="1800" max="{{ now()->year }}">
                                @error('established_year')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-row">
                                <label for="office_hours">Office Hours</label>
                                <input type="text" name="office_hours" id="office_hours" class="form-control" value="{{ old('office_hours', $values['office_hours']) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-head"><h3>Contact Information</h3></div>
                    <div class="b-card-body">
                        <div class="form-grid">
                            <div class="form-row">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $values['email']) }}">
                                @error('email')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-row">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $values['phone']) }}">
                            </div>
                            <div class="form-row span-2">
                                <label for="address">Address</label>
                                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $values['address']) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-head"><h3>Homepage Content</h3></div>
                    <div class="b-card-body">
                        <div class="form-row">
                            <label for="about_description">About Description</label>
                            <textarea name="about_description" id="about_description" class="form-control">{{ old('about_description', $values['about_description']) }}</textarea>
                        </div>
                        <div class="form-grid">
                            <div class="form-row">
                                <label for="mission">Mission</label>
                                <textarea name="mission" id="mission" class="form-control">{{ old('mission', $values['mission']) }}</textarea>
                            </div>
                            <div class="form-row">
                                <label for="vision">Vision</label>
                                <textarea name="vision" id="vision" class="form-control">{{ old('vision', $values['vision']) }}</textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="footer_text">Footer Text</label>
                            <input type="text" name="footer_text" id="footer_text" class="form-control" value="{{ old('footer_text', $values['footer_text']) }}">
                        </div>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-head"><h3>Principal</h3></div>
                    <div class="b-card-body">
                        <div class="form-grid">
                            <div class="form-row">
                                <label for="principal_name">Principal Name</label>
                                <input type="text" name="principal_name" id="principal_name" class="form-control" value="{{ old('principal_name', $values['principal_name']) }}">
                            </div>
                            <div class="form-row">
                                <label for="principal_designation">Designation</label>
                                <input type="text" name="principal_designation" id="principal_designation" class="form-control" value="{{ old('principal_designation', $values['principal_designation']) }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="principal_message">Principal's Message</label>
                            <textarea name="principal_message" id="principal_message" class="form-control" style="min-height:160px;">{{ old('principal_message', $values['principal_message']) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-head"><h3>Social Links</h3></div>
                    <div class="b-card-body">
                        <div class="form-grid">
                            @foreach (['facebook' => 'Facebook URL', 'twitter' => 'Twitter / X URL', 'instagram' => 'Instagram URL', 'youtube' => 'YouTube URL', 'linkedin' => 'LinkedIn URL'] as $key => $label)
                                <div class="form-row">
                                    <label for="{{ $key }}">{{ $label }}</label>
                                    <input type="url" name="{{ $key }}" id="{{ $key }}" class="form-control @error($key) is-invalid @enderror" value="{{ old($key, $values[$key]) }}" placeholder="https://">
                                    @error($key)<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="b-card">
                    <div class="b-card-head"><h3>Logo</h3></div>
                    <div class="b-card-body">
                        <div class="img-field" data-preview>
                            <div class="img-preview{{ $logo ? '' : ' is-empty' }}" data-preview-box>
                                @if ($logo)
                                    <img src="{{ asset('storage/' . $logo) }}" alt="Logo" data-preview-img>
                                @else
                                    <i class="bi bi-image" data-preview-icon></i>
                                @endif
                            </div>
                            <div class="img-actions">
                                <input type="file" name="logo" class="form-control" accept="image/*" data-preview-input>
                                @error('logo')<div class="form-error">{{ $message }}</div>@enderror
                                @if ($logo)
                                    <button type="button" class="b-btn b-btn-danger b-btn-sm b-btn-block"
                                            data-form="deleteLogoForm" data-confirm="Delete the current logo?">
                                        <i class="bi bi-trash"></i> Delete Logo
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="form-hint">PNG or SVG with a transparent background works best. Max 4MB.</div>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-head"><h3>Favicon</h3></div>
                    <div class="b-card-body">
                        <div class="img-field" data-preview>
                            <div class="img-preview img-preview-sm{{ $favicon ? '' : ' is-empty' }}" data-preview-box>
                                @if ($favicon)
                                    <img src="{{ asset('storage/' . $favicon) }}" alt="Favicon" data-preview-img>
                                @else
                                    <i class="bi bi-image" data-preview-icon></i>
                                @endif
                            </div>
                            <div class="img-actions">
                                <input type="file" name="favicon" class="form-control" accept="image/*" data-preview-input>
                                @error('favicon')<div class="form-error">{{ $message }}</div>@enderror
                                @if ($favicon)
                                    <button type="button" class="b-btn b-btn-danger b-btn-sm b-btn-block"
                                            data-form="deleteFaviconForm" data-confirm="Delete the current favicon?">
                                        <i class="bi bi-trash"></i> Delete Favicon
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="form-hint">Square image, 512x512 or larger. Max 4MB.</div>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-head"><h3>Principal Photo</h3></div>
                    <div class="b-card-body">
                        <div class="img-field" data-preview>
                            <div class="img-preview{{ $principalPhoto ? '' : ' is-empty' }}" data-preview-box>
                                @if ($principalPhoto)
                                    <img src="{{ asset('storage/' . $principalPhoto) }}" alt="Principal" data-preview-img>
                                @else
                                    <i class="bi bi-person-badge" data-preview-icon></i>
                                @endif
                            </div>
                            <div class="img-actions">
                                <input type="file" name="principal_photo" class="form-control" accept="image/*" data-preview-input>
                                @error('principal_photo')<div class="form-error">{{ $message }}</div>@enderror
                                @if ($principalPhoto)
                                    <button type="button" class="b-btn b-btn-danger b-btn-sm b-btn-block"
                                            data-form="deletePrincipalPhotoForm" data-confirm="Delete the principal photo?">
                                        <i class="bi bi-trash"></i> Delete Photo
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="form-hint">A portrait ratio (3:4) looks best on the principal page. Max 4MB.</div>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-body">
                        <label class="checkbox-row" style="margin-bottom:16px;">
                            <input type="checkbox" name="admission_open" value="1" @checked($admissionOpen)> Admissions open
                        </label>
                        <button type="submit" class="b-btn b-btn-primary b-btn-block">Save Settings</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Image delete forms live outside the settings form: HTML forbids nesting forms. --}}
    @if ($logo)
        <form id="deleteLogoForm" method="POST" action="{{ route('admin.settings.images.destroy', 'logo') }}" hidden>
            @csrf @method('DELETE')
        </form>
    @endif
    @if ($favicon)
        <form id="deleteFaviconForm" method="POST" action="{{ route('admin.settings.images.destroy', 'favicon') }}" hidden>
            @csrf @method('DELETE')
        </form>
    @endif
    @if ($principalPhoto)
        <form id="deletePrincipalPhotoForm" method="POST" action="{{ route('admin.settings.images.destroy', 'principal_photo') }}" hidden>
            @csrf @method('DELETE')
        </form>
    @endif

    <style>
        @media (max-width: 900px) { .b-grid[style*="340px"] { grid-template-columns: 1fr !important; } }
    </style>
@endsection
