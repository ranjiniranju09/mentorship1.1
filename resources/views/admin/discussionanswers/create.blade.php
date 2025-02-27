@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.question.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.questions.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="question_id">Questions</label>
                <select class="form-control select2 {{ $errors->has('question_id') ? 'is-invalid' : '' }}" name="question_id" id="question_id">
                    <option value="Select_Here"> Select Here </option>
                    @foreach($questions as $id => $entry)
                        <option value="{{ $id }}" {{ old('question_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('question_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('question_id') }}
                    </div>
                @endif
                <!-- <span class="help-block">Select a question for this discussion</span> -->
            </div>

            <div class="form-group">
                <label class="required" for="discussion_answer">Discussion Answer</label>
                <textarea 
                    class="form-control {{ $errors->has('discussion_answer') ? 'is-invalid' : '' }}" 
                    name="discussion_answer" 
                    id="discussion_answer" 
                    rows="4" 
                    required>{{ old('discussion_answer', '') }}</textarea>
                @if($errors->has('discussion_answer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discussion_answer') }}
                    </div>
                @endif
                <!-- <span class="help-block">Provide your answer for the selected question</span> -->
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')

<script>
    Dropzone.options.questionImageDropzone = {
        {{--url: '{{ route('admin.discussions.storeMedia') }}'--}}, // Updated route to match discussions logic
        maxFilesize: 2, // MB
        acceptedFiles: '', // Removed accepted files as no images are required
        maxFiles: 0, // Set to 0 to disallow any file uploads
        addRemoveLinks: false, // Disable file-related links
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {},
        success: function (file, response) {
            // No action required as file upload is removed
        },
        removedfile: function (file) {
            // No action required as file upload is removed
        },
        init: function () {
            @if(isset($discussion))
                // Add question_id field value for existing discussions
                if (!$('form').find('input[name="question_id"]').length) {
                    const questionId = {{ $discussion->question_id ?? 'null' }}; // Use default value if question_id is not set
                    $('form').append('<input type="hidden" name="question_id" value="' + questionId + '">');
                }
            @endif
        },
        error: function (file, response) {
            // Handle errors if any (this section is retained in case of validation needs)
            let message;
            if ($.type(response) === 'string') {
                message = response; // Dropzone sends its own error messages as strings
            } else {
                message = response.errors.file;
            }
            file.previewElement.classList.add('dz-error');
            const errorNodes = file.previewElement.querySelectorAll('[data-dz-errormessage]');
            errorNodes.forEach(node => node.textContent = message);
        }
    };
</script>



<!-- <script>
    Dropzone.options.questionImageDropzone = {
    url: '{{ route('admin.questions.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="question_image"]').remove()
      $('form').append('<input type="hidden" name="question_image" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="question_image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($question) && $question->question_image)
      var file = {!! json_encode($question->question_image) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="question_image" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}

</script> -->
@endsection