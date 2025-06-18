<div class="text-center" id="certificateContainer">
        {{-- A4 Landscape size (11.69in x 8.27in) --}}
        <div id="certificateContent"
            style="
                width: 11.69in; height: 8.27in; margin: auto;
                background: linear-gradient(to bottom right, #ffffff 60%, #f7f7f7);
                position: relative;
                font-family: 'Georgia', serif;
                border: 10px solid #333;
                padding: 60px;
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.25);
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            ">

            

            <h1 style="font-size: 42px; font-weight: bold; margin-bottom: 10px; color: #2c3e50;">
                Certificate of Appreciation
            </h1>
            <p style="font-size: 20px;">This is to proudly certify that</p>

            <h2 style="font-size: 30px; margin: 10px 0; color: #34495e;">
                <strong>{{ $name }}</strong>
            </h2>

            <p style="font-size: 20px; margin-top: 10px;">has successfully completed their role as a</p>

            <h3 style="font-size: 26px; margin: 10px 0; color: #555;">
                <strong>Mentor in the Mentorship Program</strong>
            </h3>

            <p style="font-size: 20px;">on</p>
            <h4 style="font-size: 22px; margin: 10px 0; color: #333;">
                <strong>{{ $date }}</strong>
            </h4>

            <p style="margin-top: 25px; font-size: 18px; font-style: italic; color: #555;">
                Thank you for your valuable guidance and support!
            </p>

            {{-- Signature --}}
            <div style="
                position: absolute;
                bottom: 50px;
                left: 60px;
                right: 60px;
                display: flex;
                justify-content: space-around;
                align-items: flex-end;
                text-align: center;
                font-size: 16px;
                color: #222;
            ">
                <div style="flex: 1; max-width: 200px;">
                    <hr style="width: 180px; border-top: 1px solid #000; margin: 4px auto;">
                    <p>Dr. A. Kumar<br><strong>Program Director</strong></p>
                </div>
                <div style="flex: 1; max-width: 200px;">
                    <hr style="width: 180px; border-top: 1px solid #000; margin: 4px auto;">
                    <p>Ms. B. Sharma<br><strong>Dean of Academics</strong></p>
                </div>
                <div style="flex: 1; max-width: 200px;">
                    <hr style="width: 180px; border-top: 1px solid #000; margin: 4px auto;">
                    <p>Mr. C. Singh<br><strong>Head of Mentorship</strong></p>
                </div>
            </div>

        </div>

        {{-- PDF Download Button --}}
        <a href="{{ route('mentor.download.certificate') }}" class="btn btn-success mt-4" target="_blank">
            Download PDF
        </a>
    </div>