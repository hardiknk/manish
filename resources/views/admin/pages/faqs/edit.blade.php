@extends('admin.layouts.app')

@push('breadcrumb')
    {!! Breadcrumbs::render('faqs_update', $faq->id) !!}
@endpush

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fas fa-faq-edit text-primary"></i>
                    </span>
                    <h3 class="card-label text-uppercase">Edit {{ $custom_title }}</h3>
                </div>
            </div>

            <!--begin::Form-->
            <form id="frmEditFaq" method="POST" action="{{ route('admin.faqs.update', $faq->custom_id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="card-body">

                    {{-- Title --}}
                    <div class="form-group">
                        <label for="title">{!! $mend_sign !!}Title:</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title') ?? $faq->title }}" placeholder="Enter Title"
                            autocomplete="title" spellcheck="false" autocapitalize="sentences" tabindex="0" autofocus />
                        @if ($errors->has('title'))
                            <span class="help-block">
                                <strong class="form-text">{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div class="form-group">
                        <label for="title">{!! $mend_sign !!}Description:</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="5"
                            name="description" placeholder="Enter Description" autocomplete="description" spellcheck="false"
                            autocapitalize="sentences" tabindex="0" autofocus>{{ old('description') ?? $faq->description }}</textarea>
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong class="form-text">{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary mr-2">Update {{ $custom_title }}</button>
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
@endsection

@push('extra-js-scripts')
    <script>
        $(document).ready(function() {
            $("#frmEditFaq").validate({
                rules: {
                    title: {
                        required: true,
                        not_empty: true,
                        minlength: 3,
                    },
                    description: {
                        required: true,
                        not_empty: true,
                        minlength: 3,
                    },
                },
                messages: {
                    title: {
                        required: "@lang('validation.required', ['attribute' => 'title'])",
                        not_empty: "@lang('validation.not_empty', ['attribute' => 'title'])",
                        minlength: "@lang('validation.min.string', ['attribute' => 'title', 'min' => 3])",
                    },
                    description: {
                        required: "@lang('validation.required', ['attribute' => 'description'])",
                        not_empty: "@lang('validation.not_empty', ['attribute' => 'description'])",
                        minlength: "@lang('validation.min.string', ['attribute' => 'description', 'min' => 3])",
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
                    $(element).siblings('label').removeClass('text-danger'); // For Label
                },
                errorPlacement: function(error, element) {
                    if (element.attr("data-error-container")) {
                        error.appendTo(element.attr("data-error-container"));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
            $('#frmEditFaq').submit(function() {
                if ($(this).valid()) {
                    addOverlay();
                    $("input[type=submit], input[type=button], button[type=submit]").prop("disabled",
                        "disabled");
                    return true;
                } else {
                    return false;
                }
            });

            //remove the imaegs
            $(".remove-img").on('click', function(e) {
                e.preventDefault();
                $(this).parents(".symbol").remove();
                $('#frmEditFaq').append(
                    '<input type="hidden" name="remove_profie_photo" id="remove_image" value="removed">'
                );
            });
        });
    </script>
@endpush
