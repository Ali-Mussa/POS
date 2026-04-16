<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        .scanner-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .qr-reader {
            border: 2px solid #007bff;
            border-radius: 10px;
            overflow: hidden;
        }
        .manual-lookup {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .feature-card {
            text-align: center;
            padding: 20px;
            margin: 10px 0;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .hero-section {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 40px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="scanner-container">
            <div class="hero-section">
                <h2><i class="fas fa-qrcode"></i> Purchase Verification</h2>
                <p>Scan the QR code on your receipt to verify your purchase details</p>
            </div>

            <div class="text-center mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="fas fa-camera fa-2x text-primary mb-2"></i>
                            <h6>Scan QR Code</h6>
                            <small>Use your camera to scan</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="fas fa-search fa-2x text-success mb-2"></i>
                            <h6>View Details</h6>
                            <small>See purchase information</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="fas fa-check-circle fa-2x text-info mb-2"></i>
                            <h6>Verify Purchase</h6>
                            <small>Confirm transaction</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">
                        <i class="fas fa-camera"></i> QR Code Scanner
                    </h5>
                    <div id="qr-reader" class="qr-reader"></div>
                    <div class="text-center mt-3">
                        <button id="start-scan" class="btn btn-primary">
                            <i class="fas fa-play"></i> Start Scanner
                        </button>
                        <button id="stop-scan" class="btn btn-secondary" style="display: none;">
                            <i class="fas fa-stop"></i> Stop Scanner
                        </button>
                    </div>
                </div>
            </div>

            <div class="manual-lookup">
                <h5 class="text-center mb-3">
                    <i class="fas fa-keyboard"></i> Manual Lookup
                </h5>
                <p class="text-center text-muted">Or enter your receipt ID manually</p>
                <form id="manual-lookup-form">
                    @csrf
                    <div class="input-group">
                        <input type="number" name="receipt_id" class="form-control" placeholder="Enter Receipt ID" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Lookup
                        </button>
                    </div>
                </form>
            </div>

            @if (session('error'))
                <div class="alert alert-danger mt-3">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif

            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home"></i> Back to Store
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let html5QrCode = null;

        document.getElementById('start-scan').addEventListener('click', function() {
            startScanner();
        });

        document.getElementById('stop-scan').addEventListener('click', function() {
            stopScanner();
        });

        function startScanner() {
            html5QrCode = new Html5Qrcode("qr-reader");
            
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    const cameraId = devices[0].id;
                    
                    html5QrCode.start(
                        cameraId,
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 }
                        },
                        (decodedText, decodedResult) => {
                            handleScanSuccess(decodedText);
                        },
                        (errorMessage) => {
                            // Handle scan error silently
                        }
                    ).then(() => {
                        document.getElementById('start-scan').style.display = 'none';
                        document.getElementById('stop-scan').style.display = 'inline-block';
                    }).catch((err) => {
                        console.error(`Unable to start scanning: ${err}`);
                        alert('Unable to access camera. Please use manual lookup instead.');
                    });
                }
            }).catch(err => {
                console.error(`Camera access error: ${err}`);
                alert('Camera not available. Please use manual lookup instead.');
            });
        }

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    document.getElementById('start-scan').style.display = 'inline-block';
                    document.getElementById('stop-scan').style.display = 'none';
                }).catch((err) => {
                    console.error(`Unable to stop scanning: ${err}`);
                });
            }
        }

        function handleScanSuccess(decodedText) {
            try {
                // Check if it's a direct receipt ID or JSON
                let receiptId;
                try {
                    const data = JSON.parse(decodedText);
                    receiptId = data.receipt_id;
                } catch (e) {
                    // If not JSON, treat as direct receipt ID
                    receiptId = decodedText;
                }
                
                if (receiptId) {
                    stopScanner();
                    fetchReceiptData(receiptId);
                }
            } catch (e) {
                alert('Invalid QR code format');
            }
        }

        function fetchReceiptData(receiptId) {
            fetch(`/api/verify/${receiptId}`)
                .then(response => {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw new Error('Receipt not found');
                    }
                })
                .then(data => {
                    displayReceiptData(data);
                })
                .catch(error => {
                    alert('Receipt not found. Please check the receipt ID and try again.');
                    console.error('Error:', error);
                });
        }

        function displayReceiptData(data) {
            // Hide scanner and show results
            document.querySelector('.card').style.display = 'none';
            document.querySelector('.manual-lookup').style.display = 'none';
            
            // Create result display
            const resultHtml = `
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">
                            <i class="fas fa-check-circle text-success"></i> Receipt Details
                        </h5>
                        <div class="receipt-result">
                            <pre style="background: #f8f9fa; padding: 20px; border-radius: 10px; font-size: 16px; line-height: 1.6; white-space: pre-wrap; font-family: 'Courier New', monospace;">${data}</pre>
                        </div>
                        <div class="text-center mt-4">
                            <button onclick="resetScanner()" class="btn btn-primary me-2">
                                <i class="fas fa-camera"></i> Scan Another
                            </button>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home"></i> Back to Store
                            </a>
                        </div>
                    </div>
                </div>
            `;
            
            document.querySelector('.scanner-container').insertAdjacentHTML('beforeend', resultHtml);
        }

        function resetScanner() {
            location.reload();
        }

        // Handle manual lookup form submission
        document.getElementById('manual-lookup-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const receiptId = document.querySelector('input[name="receipt_id"]').value;
            if (receiptId) {
                fetchReceiptData(receiptId);
            }
        });
    </script>
</body>
</html>
