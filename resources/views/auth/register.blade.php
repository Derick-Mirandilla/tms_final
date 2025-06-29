<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Public+Sans:wght@700&display=swap" rel="stylesheet">

  <style>
    /* Font family */
    body {
      font-family: 'Open Sans', sans-serif;
      background-color: #f3f4f6; /* Changed background-color for consistency */
      position: relative;
      overflow-x: hidden;
      /* Smooth transition for body opacity */
      opacity: 0; /* Start hidden */
      transition: opacity 0.5s ease-in-out; /* Fade duration */
    }

    body.fade-in {
      opacity: 1; /* Fade in on load */
    }

    body.fade-out {
      opacity: 0; /* Fade out on navigation */
    }

    h2 {
      font-family: 'Public Sans', sans-serif;
    }

    .custom-card {
      border-radius: 40px;
      max-width: 1100px; 
      max-height: 710px;
      width: 100%;
      height: auto;
      overflow: hidden;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .custom-card {
        border-radius: 20px;
        margin: 15px;
        max-width: calc(100% - 30px);
        max-height: none;
      }
    }

    @media (max-width: 576px) {
      .custom-card {
        border-radius: 15px;
        margin: 10px;
        max-width: calc(100% - 20px);
      }
    }

    .form-image {
      object-fit: cover;
      width: 100%;
      height: 100%;
      border-top-left-radius: 40px;
      border-bottom-left-radius: 40px;
    }

    /* Mobile image adjustments - Image stays visible on mobile but smaller */
    @media (max-width: 768px) {
      .form-image {
        border-radius: 20px 20px 0 0;
        height: 200px;
      }
      
      .col-md-6:first-child {
        order: 1; /* Image goes to top on mobile */
      }
      
      .col-md-6:last-child {
        order: 2; /* Form goes below image on mobile */
      }
    }

    @media (max-width: 576px) {
      .form-image {
        border-radius: 15px 15px 0 0;
        height: 150px;
      }
    }

    .form-control {
      border-radius: 10px !important; 
      border-color: black;
      font-size: 16px; 
    }

    .form-section {
      background-color: #FFC107;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    /* Responsive form section padding */
    @media (max-width: 768px) {
      .form-section {
        padding: 1.5rem;
      }
    }

    @media (max-width: 576px) {
      .form-section {
        padding: 1rem;
        justify-content: center;
      }
    }

    .form-control:focus {
      border-color: #000;
      box-shadow: 0 0 0 0.2rem rgba(0,0,0,0.2);
    }

    .eye-icon {
      position: absolute;
      top: 35px;
      right: 15px;
      cursor: pointer;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.9);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    .dot-anim {
      z-index: 0;
      position: absolute;
    }

    .top-dots {
      top: 0;
      right: -10px;
      width: 350px;
    }

    .bottom-dot {
      bottom: -10px;
      left: 0;
      width: 120px;
    }

    /* Mobile dots adjustments */
    @media (max-width: 768px) {
      .top-dots {
        width: 200px;
      }
      .bottom-dot {
        width: 80px;
      }
    }

    @media (max-width: 576px) {
      .top-dots {
        width: 150px;
      }
      .bottom-dot {
        width: 60px;
      }
    }

    .btn-register {
      background-color: #000;
      color: #fff;
      font-weight: bolder;
      border-radius: 10px;
      padding: 5px 40px;
      transition: all 0.3s ease; 
    }

    .btn-register:hover {
      background-color: #e3442f;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(227, 68, 47, 0.3); 
    }

    @media (max-width: 576px) {
      .btn-register {
        width: 100%;
        margin-top: 15px;
      }
      
      .d-flex.justify-content-between {
        flex-direction: column;
        align-items: center !important;
        text-align: center;
      }
    }

    .form-label {
      margin-bottom: 0.25rem; 
    }

    .already-link {
      color: #000;
      text-decoration: underline;
      transition: all 0.3s ease; 
    }

    .already-link:hover {
      font-weight: bolder;
      color: #e3442f; 
    }

    body, label, input, h2, .already-link, .form-label, .form-control {
      color: #0c3338;
    }

    @media (max-width: 768px) {
      .display-2 {
        font-size: 2rem;
      }
    }

    @media (max-width: 576px) {
      .display-2 {
        font-size: 1.75rem;
        margin-bottom: 1.5rem !important;
      }
    }

    @media (min-width: 769px) {
      .name-row {
        display: flex;
        gap: 15px;
      }
      .name-row .mb-3 {
        flex: 1;
      }
    }

    .form-control {
      transition: transform 0.2s ease;
    }
    
    .form-control:focus {
      transform: scale(1.02);
    }

  </style>
</head>
<body>
  <img src="{{ asset('assets/2dots.png') }}" alt="Top Dots" class="dot-anim top-dots">
  <img src="{{ asset('assets/1dot.png') }}" alt="Bottom Dot" class="dot-anim bottom-dot">

  <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card custom-card bg-warning">
      <div class="row g-0">
        
        <div class="col-md-6 position-relative p-4">
          <img src="{{ asset('assets/assetreglog_tms.png') }}" alt="Register Background" class="form-image">
        </div>

        <div class="col-md-6 d-flex align-items-center justify-content-center p-5 form-section"> {{-- Applied form-section class here --}}
          <div class="w-100">
            <h2 class="mb-4 text-center display-2 fw-bold">Welcome!</h2>
            <form method="POST" action="{{ route('register') }}">
              @csrf

              <div class="name-row">
                <div class="mb-3">
                  <label for="first_name" class="form-label fw-semibold">FIRST NAME</label>
                  <input type="text" class="form-control" id="first_name" name="first_name" 
                         value="{{ old('first_name') }}" required autofocus autocomplete="given-name">
                  @error('first_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="last_name" class="form-label fw-semibold">LAST NAME</label>
                  <input type="text" class="form-control" id="last_name" name="last_name" 
                         value="{{ old('last_name') }}" autocomplete="family-name">
                  @error('last_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">EMAIL</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="{{ old('email') }}" required autocomplete="username">
                @error('email')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="phone" class="form-label fw-semibold">PHONE (OPTIONAL)</label>
                <input type="text" class="form-control" id="phone" name="phone" 
                       value="{{ old('phone') }}" autocomplete="tel">
                @error('phone')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 position-relative">
                <label for="password" class="form-label fw-semibold">PASSWORD</label>
                <input type="password" class="form-control" id="password" name="password" 
                       required autocomplete="new-password">
                <i class="bi bi-eye-slash eye-icon toggle-password" data-target="password"></i>
                @error('password')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 position-relative">
                <label for="password_confirmation" class="form-label fw-semibold">CONFIRM PASSWORD</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                       required autocomplete="new-password">
                <i class="bi bi-eye-slash eye-icon toggle-password" data-target="password_confirmation"></i>
                @error('password_confirmation')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="{{ route('login') }}" class="already-link small">Already Registered?</a>
                <button type="submit" class="btn btn-register">Register</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.querySelectorAll('.toggle-password').forEach(icon => {
      icon.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        if (input.type === 'password') {
          input.type = 'text';
          this.classList.remove('bi-eye-slash');
          this.classList.add('bi-eye');
        } else {
          input.type = 'password';
          this.classList.remove('bi-eye');
          this.classList.add('bi-eye-slash');
        }
      });
    });

    // Page transition logic
    function handlePageShow() {
        document.body.classList.remove('fade-out'); // Ensure fade-out is removed if navigating back
        document.body.classList.add('fade-in');    // Add fade-in for fresh load or bfcache restore
    }

    // Attach to pageshow for initial load and bfcache restores
    window.addEventListener('pageshow', handlePageShow);

    document.addEventListener('DOMContentLoaded', () => {
        // Fallback for very fast initial loads if pageshow hasn't fired yet
        if (!document.body.classList.contains('fade-in')) {
            document.body.classList.add('fade-in');
        }

        document.querySelectorAll('a').forEach(link => {
            // Only apply custom transition to internal links that are not intended to be "no-fade"
            if (link.hostname === window.location.hostname && !link.classList.contains('no-fade')) {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default navigation
                    const targetUrl = this.href;

                    document.body.classList.remove('fade-in'); // Remove fade-in before starting fade-out
                    document.body.classList.add('fade-out'); // Start fade-out

                    setTimeout(() => {
                        window.location.href = targetUrl; // Navigate after fade-out
                    }, 500); // Match CSS transition duration
                });
            }
        });

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default submission
                
                document.body.classList.remove('fade-in'); // Remove fade-in before starting fade-out
                document.body.classList.add('fade-out'); // Start fade-out

                setTimeout(() => {
                    form.submit(); // Submit form after fade-out
                }, 500); // Match CSS transition duration
            });
        });
    });
  </script>

</body>
</html>