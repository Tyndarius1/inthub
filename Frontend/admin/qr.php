<?php include '../admin/includes/sidebar.php'; ?>


   <button id="generateQrBtn">Generate QR Code</button>
    <div id="qrCodeContainer" style="display: none;">
        <img id="qrCodeImage" alt="QR Code" />
    </div>
    <p id="message"></p>


    <script>
       document.getElementById('generateQrBtn').addEventListener('click', function() {
            const message = document.getElementById('message');
            const adminToken = localStorage.getItem('admin_token'); // Assuming the admin token is stored in localStorage

            if (!adminToken) {
                message.style.color = 'red';
                message.textContent = 'Admin token is required to generate QR.';
                return;
            }

            // Make the API call to generate the QR code
            fetch('http://127.0.0.1:8000/api/admin/generate-qr-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${adminToken}`
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.qr_code) {
                    // Display the QR code
                    document.getElementById('qrCodeContainer').style.display = 'block';
                    document.getElementById('qrCodeImage').src = data.qr_code;
                    message.style.color = 'green';
                    message.textContent = 'QR Code generated! Scan it to log in.';
                } else {
                    message.style.color = 'red';
                    message.textContent = 'Error generating QR code.';
                }
            })
            .catch(err => {
                message.style.color = 'red';
                message.textContent = 'Error generating QR code.';
                console.error(err);
            });
        });
    </script>
</body>
</html>
