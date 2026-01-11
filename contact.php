<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/includes/header.php'; ?>

<div class="container content py-5">
    <h2 class="text-center mb-4">Contact Us</h2>
    <p class="text-center mb-5">If you have any questions or inquiries, please feel free to contact us using the information below:</p>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title mb-4">Our Contact Information</h3>
                    <ul class="list-unstyled contact-list">
                        <li class="mb-3"><i class="fas fa-envelope fa-fw me-3 text-primary"></i> <strong>Email:</strong>hanzlagmail.com</li>
                        <li class="mb-3"><i class="fas fa-phone-alt fa-fw me-3 text-primary"></i> <strong>Phone:</strong>03287299206</li>
                        <li class="mb-3"><i class="fas fa-map-marker-alt fa-fw me-3 text-primary"></i> <strong>Address:</strong>Hasilpur</li>
                    </ul>
                </div>
    </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title mb-4">Find Us on the Map</h3>
                    <div class="map-container" style="position: relative; overflow: hidden; padding-top: 56.25%;">
                        <!-- Replace the iframe below with your actual Google Maps embed code -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3401.7820794507116!2d74.30396007559286!3d31.52844197422119!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391904a11b6190dd%3A0x8c7e2b17a6c9d7a!2sLahore!5e0!3m2!1sen!2sPK!4v1700000000000" width="600" height="450" style="border:0; position: absolute; top: 0; left: 0; width: 100%; height: 100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <p class="mt-3 text-muted text-center">
                        <small>Hasilpur street No:04</small>
                    </p>
                </div>
        </div>
        </div>
        </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?> 