@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.moduleresourcebank.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.moduleresourcebanks.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="module_id">{{ trans('cruds.moduleresourcebank.fields.module') }}</label>
                <select class="form-control select2 {{ $errors->has('module') ? 'is-invalid' : '' }}" name="module_id" id="module_id" required>
                    @foreach($modules as $id => $entry)
                        <option value="{{ $id }}" {{ old('module_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('module'))
                    <div class="invalid-feedback">
                        {{ $errors->first('module') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.moduleresourcebank.fields.module_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="chapterid_id">{{ trans('cruds.moduleresourcebank.fields.chapterid') }}</label>
                <select class="form-control select2 {{ $errors->has('chapterid') ? 'is-invalid' : '' }}" name="chapterid_id" id="chapterid_id" required>
                    @foreach($chapterids as $id => $entry)
                        <option value="{{ $id }}" {{ old('chapterid_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('chapterid'))
                    <div class="invalid-feedback">
                        {{ $errors->first('chapterid') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.moduleresourcebank.fields.chapterid_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.moduleresourcebank.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.moduleresourcebank.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="link">{{ trans('cruds.moduleresourcebank.fields.link') }}</label>
                <input class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" type="text" name="link" id="link" value="{{ old('link', '') }}">
                @if($errors->has('link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.moduleresourcebank.fields.link_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="descriptivematerial">{{ trans('cruds.moduleresourcebank.fields.descriptivematerial') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('descriptivematerial') ? 'is-invalid' : '' }}" name="descriptivematerial" id="descriptivematerial">{!! old('descriptivematerial') !!}</textarea>
                @if($errors->has('descriptivematerial'))
                    <div class="invalid-feedback">
                        {{ $errors->first('descriptivematerial') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.moduleresourcebank.fields.descriptivematerial_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="resourcefile">{{ trans('cruds.moduleresourcebank.fields.resourcefile') }}</label>
                <div class="needsclick dropzone {{ $errors->has('resourcefile') ? 'is-invalid' : '' }}" id="resourcefile-dropzone">
                </div>
                @if($errors->has('resourcefile'))
                    <div class="invalid-feedback">
                        {{ $errors->first('resourcefile') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.moduleresourcebank.fields.resourcefile_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="resource_photos">{{ trans('cruds.moduleresourcebank.fields.resource_photos') }}</label>
                <div class="needsclick dropzone {{ $errors->has('resource_photos') ? 'is-invalid' : '' }}" id="resource_photos-dropzone">
                </div>
                @if($errors->has('resource_photos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('resource_photos') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.moduleresourcebank.fields.resource_photos_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.moduleresourcebanks.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $moduleresourcebank->id ?? 0 }}');
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
    var uploadedResourcefileMap = {}
Dropzone.options.resourcefileDropzone = {
    url: '{{ route('admin.moduleresourcebanks.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="resourcefile[]" value="' + response.name + '">')
      uploadedResourcefileMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedResourcefileMap[file.name]
      }
      $('form').find('input[name="resourcefile[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($moduleresourcebank) && $moduleresourcebank->resourcefile)
          var files =
            {!! json_encode($moduleresourcebank->resourcefile) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="resourcefile[]" value="' + file.file_name + '">')
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
    var uploadedResourcePhotosMap = {}
Dropzone.options.resourcePhotosDropzone = {
    url: '{{ route('admin.moduleresourcebanks.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="resource_photos[]" value="' + response.name + '">')
      uploadedResourcePhotosMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedResourcePhotosMap[file.name]
      }
      $('form').find('input[name="resource_photos[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($moduleresourcebank) && $moduleresourcebank->resource_photos)
      var files = {!! json_encode($moduleresourcebank->resource_photos) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="resource_photos[]" value="' + file.file_name + '">')
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