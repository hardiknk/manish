@extends('admin.layouts.app')

@push('breadcrumb')
    {!! Breadcrumbs::render('coupan_update', $coupan->id) !!}
@endpush

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fa fa-tag text-primary"></i>
                    </span>
                    <h3 class="card-label text-uppercase">Edit {{ $custom_title }}</h3>
                </div>
            </div>

            <!--begin::Form-->
            <form id="frmUpdateCoupanCode" method="POST" action="{{ route('admin.coupans.update', $coupan->custom_id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="card-body">

                    {{-- Code --}}
                    <div class="form-group">
                        <label for="code">{!! $mend_sign !!} Code: </label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                            name="code" value="{{ old('code') != null ? old('code') : $coupan->code }}"
                            placeholder="Enter first name" autocomplete="code" spellcheck="false" autocapitalize="sentences"
                            tabindex="0" autofocus />
                        @if ($errors->has('code'))
                            <span class="help-block">
                                <strong class="form-text">{{ $errors->first('code') }}</strong>
                            </span>
                        @endif
                    </div>

                    {{-- Percentage --}}
                    <div class="form-group">
                        <label for="percentage">{!! $mend_sign !!} Percentage: </label>
                        <input type="text" class="form-control @error('percentage') is-invalid @enderror" id="percentage"
                            name="percentage"
                            value="{{ old('percentage') != null ? old('percentage') : $coupan->percentage }}"
                            placeholder="Enter first name" autocomplete="percentage" spellcheck="false"
                            autocapitalize="sentences" tabindex="0" />
                        @if ($errors->has('percentage'))
                            <span class="help-block">
                                <strong class="form-text">{{ $errors->first('percentage') }}</strong>
                            </span>
                        @endif
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary mr-2">Update {{ $custom_title }}</button>
                    <a href="{{ route('admin.coupans.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
@endsection

@push('extra-js-scripts')
    <script>
        $('#code').on('keyup keypress change', function(e) {
            $(this).val($(this).val().toUpperCase());
            if (e.which == 32) {
                return false;
            }
        });

        $(document).ready(function() {
            $("#frmUpdateCoupanCode").validate({
                rules: {
                    code: {
                        required: true,
                        not_empty: true,
                        minlength: 3,
                        maxlength: 10,
                        alphanum: true,
                        remote: {
                            url: "{{ route('admin.check.coupan') }}",
                            type: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: "{{ $coupan->id }}"
                            }
                        },
                    },
                    percentage: {
                        required: true,
                        not_empty: true,
                        alpha_dots: true,
                    },
                },
                messages: {
                    code: {
                        required: "@lang('validation.required', ['attribute' => 'Coupan code'])",
                        not_empty: "@lang('validation.not_empty', ['attribute' => 'Coupan code'])",
                        minlength: "@lang('validation.min.string', ['attribute' => 'Coupan code', 'min' => 3])",
                        maxlength: "@lang('validation.max.string', ['attribute' => 'Coupan Code', 'max' => 10])",
                        alphanum: "@lang('validation.alpha_num', ['attribute' => 'Coupan Code'])",
                        remote: "Coupan code is already taken",
                    },
                    percentage: {
                        required: "@lang('validation.required', ['attribute' => 'Percentage'])",
                        not_empty: "@lang('validation.not_empty', ['attribute' => 'Percentage'])",
                        alpha_dots: "@lang('validation.alpha_dots', ['attribute' => 'Percentage'])",
                    },
                },
                errorClass: 'invalid-feedback',
                errorElement: 'span',
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                    $(element).siblings('label').addClass('text-danger'); // For Label
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                    $(element).siblings('label').removeClass('text-danger');
                },
                errorPlacement: function(error, element) {
                    if (element.attr("data-error-container")) {
                        error.appendTo(element.attr("data-error-container"));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
            $('#frmUpdateCoupanCode').submit(function() {
                if ($(this).valid()) {
                    addOverlay();
                    $("input[type=submit], input[type=button], button[type=submit]").prop("disabled",
                        "disabled");
                    return true;
                } else {
                    return false;
                }
            });

        });
    </script>
@endpush
