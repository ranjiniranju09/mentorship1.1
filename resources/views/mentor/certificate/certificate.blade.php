@extends('layouts.mentor')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container mt-5">
            <div class="card p-4">
                <div class="certificate text-center">
                    <h2>Welcome <br> Generate Your Course Completion Certificate Here </h2>
                    <button class="btn btn-primary mt-3" id="generateButton">Generate Certificate</button>

                </div>

                <div class="certificate-container mt-4" id="certificateContainer" style="display: none;">
                    <div class="card p-4 border-2 shadow-sm rounded" id="certificateContent">
                        <div class="certificate-header text-center mb-4">
                            <h3 class="certificate-title">Certificate of Completion</h3>
                        </div>
                        <div class="certificate-body">
                            <p class="text-center">This is to certify that</p>
                            <h4 class="text-center mb-4"><strong>{{ $name }}</strong></h4>
                            <p class="text-center">has successfully completed the course</p>
                            <h5 class="text-center mb-4"><strong>{{ $course }}</strong></h5>
                            <p class="text-center">on</p>
                            <h6 class="text-center mb-4"><strong>{{ $date }}</strong></h6>
                        </div>
                        <div class="certificate-footer text-center mt-4">
                            <p>Congratulations!</p>
                            <!-- Company Logos and Other Images -->
                            <div class="footer-images">
                                <img src="path_to_company_logo.png" alt="Company Logo" class="img-fluid mr-3" style="max-width: 100px;">
                                <img src="path_to_image2.png" alt="Image 2" class="img-fluid mr-3" style="max-width: 100px;">
                                <img src="path_to_image3.png" alt="Image 3" class="img-fluid" style="max-width: 100px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </br>
            </br>
            <!-- Download Button -->
            <a href="{{ route('downloadcertificate') }}" id="downloadButton" class="btn btn-success btn-lg mt-3" style="display: none;">Download Certificate</a>
        </div>


@endsection

@push('scripts')
<!-- <script>
        document.getElementById('generateButton').onclick = function() {
            document.getElementById('certificateContainer').style.display = 'block';
            document.getElementById('downloadButton').style.display = 'inline';
        };

        document.getElementById('downloadButton').onclick = function() {
            var node = document.getElementById('certificateContent');

            var scale = 2;  // Increase the scale for better quality
            var style = {
                transform: 'scale(' + scale + ')',
                transformOrigin: 'top left',
                width: node.offsetWidth + 'px',
                height: node.offsetHeight + 'px'
            };

            var param = {
                width: node.offsetWidth * scale,
                height: node.offsetHeight * scale,
                style: style
            };

            domtoimage.toBlob(node, param)
                .then(function (blob) {
                    window.saveAs(blob, 'certificate.png'); // Save as certificate.png
                });
        };
    </script> -->

<script src="https://cdn.jsdelivr.net/npm/dom-to-image@2.6.0/dist/dom-to-image.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script>
    document.getElementById('generateButton').onclick = function () {
        document.getElementById('certificateContainer').style.display = 'block';
        document.getElementById('downloadButton').style.display = 'inline';
    };

    document.getElementById('downloadButton').onclick = function () {
        const certificateNode = document.getElementById('certificateContent');

        domtoimage
            .toBlob(certificateNode, { quality: 0.95 })
            .then(function (blob) {
                window.saveAs(blob, 'certificate.png');
            })
            .catch(function (error) {
                console.error('An error occurred while generating the certificate image:', error);
            });
    };
</script>


@endpush
