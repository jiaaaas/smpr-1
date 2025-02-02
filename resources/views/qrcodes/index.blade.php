<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="text-center my-4" style="font-size: 2em; font-weight: bold;">QR Code Attendance</h1>
                <hr class="mb-4">
                <!-- Timer display -->
                <div class="my-2 text-center">
                    <p class="fs-4">
                        Next refresh in <span id="countdown-timer" class="fw-bold">15</span> seconds.
                    </p>
                </div>

                <!-- QR Code display container -->
                <div id="qr-code" class="qr-code-container mx-auto d-flex justify-content-center align-items-center my-4">
                    <img src="data:image/svg+xml;base64,{{ base64_encode($qrCode) }}" alt="QR Code" width="250" />
                </div>
                

                <!-- OTP display -->
                <p class="text-center mt-4 fs-4 fw-semibold">
                    OTP: <span id="otp-display" class="text-primary">{{ $otp }}</span>
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    let countdown = 15; // Initialize the countdown timer

    // Function to regenerate the QR code
    async function regenerateQRCode() {
        try {
            const response = await fetch('{{ route('qrcodes.regenerate') }}', {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();

            if (data.qrCode && data.otp) {
                // Set the QR code using the Base64 encoded data
                document.getElementById('qr-code').innerHTML = `<img src="data:image/svg+xml;base64,${data.qrCode}" alt="QR Code" width="250" />`;

                // Update OTP on the page
                document.getElementById('otp-display').innerText = data.otp;

                // Reset the countdown to 15
                countdown = 15;
                document.getElementById('countdown-timer').innerText = countdown;
            } else {
                throw new Error('Invalid response format');
            }
        } catch (error) {
            console.error('Error regenerating QR code:', error);
            document.getElementById('qr-code').innerHTML = '<p class="text-danger">Failed to load QR code.</p>';
        }
    }

    // Function to handle the countdown
    function updateCountdown() {
        if (countdown > 0) {
            countdown--; // Decrease countdown by 1
            document.getElementById('countdown-timer').innerText = countdown;
        } else {
            regenerateQRCode(); // Regenerate QR code when countdown reaches 0
        }
    }

    // Start the countdown timer
    function startCountdown() {
        clearInterval(window.countdownInterval); // Prevent multiple intervals from running
        window.countdownInterval = setInterval(updateCountdown, 1000); // Run every second
    }

    // Start the countdown when the page loads
    document.addEventListener('DOMContentLoaded', function () {
        startCountdown();
    });



    </script>
    @endpush

    <style>
        .qr-code-container {
            padding: 10px;
            border: 5px solid #000; /* Black border around the QR code */
            border-radius: 10px; /* Optional: rounded corners */
            max-width: 250px;
            max-height: 250px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 1.5em;
            }

            .qr-code-container {
                max-width: 200px;
                max-height: 200px;
            }

            p {
                font-size: 0.9em;
            }
        }

        @media (min-width: 768px) and (max-width: 992px) {
            h1 {
                font-size: 1.8em;
            }

            p {
                font-size: 1em;
            }
        }
    </style>
</x-app-layout>