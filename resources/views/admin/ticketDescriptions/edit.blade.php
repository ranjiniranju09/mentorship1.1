@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.ticketDescription.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ticket-descriptions.update", [$ticketDescription->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="ticket_category_id">{{ trans('cruds.ticketDescription.fields.ticket_category') }}</label>
                <select class="form-control select2 {{ $errors->has('ticket_category') ? 'is-invalid' : '' }}" name="ticket_category_id" id="ticket_category_id" required>
                    @foreach($ticket_categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('ticket_category_id') ? old('ticket_category_id') : $ticketDescription->ticket_category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('ticket_category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ticket_category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ticketDescription.fields.ticket_category_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ticket_description">{{ trans('cruds.ticketDescription.fields.ticket_description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('ticket_description') ? 'is-invalid' : '' }}" name="ticket_description" id="ticket_description">{!! old('ticket_description', $ticketDescription->ticket_description) !!}</textarea>
                @if($errors->has('ticket_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ticket_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ticketDescription.fields.ticket_description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="supporting_files">{{ trans('cruds.ticketDescription.fields.supporting_files') }}</label>
                <div class="needsclick dropzone {{ $errors->has('supporting_files') ? 'is-invalid' : '' }}" id="supporting_files-dropzone">
                </div>
                @if($errors->has('supporting_files'))
                    <div class="invalid-feedback">
                        {{ $errors->first('supporting_files') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ticketDescription.fields.supporting_files_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="supporting_photo">{{ trans('cruds.ticketDescription.fields.supporting_photo') }}</label>
                <div class="needsclick dropzone {{ $errors->has('supporting_photo') ? 'is-invalid' : '' }}" id="supporting_photo-dropzone">
                </div>
                @if($errors->has('supporting_photo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('supporting_photo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ticketDescription.fields.supporting_photo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="ticket_title">{{ trans('cruds.ticketDescription.fields.ticket_title') }}</label>
                <input class="form-control {{ $errors->has('ticket_title') ? 'is-invalid' : '' }}" type="text" name="ticket_title" id="ticket_title" value="{{ old('ticket_title', $ticketDescription->ticket_title) }}" required>
                @if($errors->has('ticket_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ticket_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ticketDescription.fields.ticket_title_helper') }}</span>
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
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.ticket-descriptions.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $ticketDescription->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    var uploadedSupportingFilesMap = {}
Dropzone.options.supportingFilesDropzone = {
    url: '{{ route('admin.ticket-descriptions.storeMedia') }}',
    maxFilesize: 2, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="supporting_files[]" value="' + response.name + '">')
      uploadedSupportingFilesMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedSupportingFilesMap[file.name]
      }
      $('form').find('input[name="supporting_files[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ticketDescription) && $ticketDescription->supporting_files)
          var files =
            {!! json_encode($ticketDescription->supporting_files) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="supporting_files[]" value="' + file.file_name + '">')
            }
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
</script>
<script>
    var uploadedSupportingPhotoMap = {}
Dropzone.options.supportingPhotoDropzone = {
    url: '{{ route('admin.ticket-descriptions.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
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
      $('form').append('<input type="hidden" name="supporting_photo[]" value="' + response.name + '">')
      uploadedSupportingPhotoMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedSupportingPhotoMap[file.name]
      }
      $('form').find('input[name="supporting_photo[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ticketDescription) && $ticketDescription->supporting_photo)
      var files = {!! json_encode($ticketDescription->supporting_photo) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="supporting_photo[]" value="' + file.file_name + '">')
        }
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

</script>
@endsection