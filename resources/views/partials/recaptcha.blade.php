@php
    $setting = \App\Models\RecaptchaSetting::current();
@endphp

@if($setting->is_enabled && $setting->site_key)
    @if($setting->version === 'v2_checkbox')
        <!-- reCAPTCHA v2 Checkbox -->
        <div class="form-group mb-3 mt-3">
            <div class="g-recaptcha" data-sitekey="{{ $setting->site_key }}"></div>
        </div>

        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    @elseif($setting->version === 'v2_invisible')
        <!-- reCAPTCHA v2 Invisible -->
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

        <script src="https://www.google.com/recaptcha/api.js?render={{ $setting->site_key }}"></script>
        <script>
            // Submit button click handler
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('form[data-recaptcha="true"]');

                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        // Skip reCAPTCHA for forms that don't need it
                        if (form.dataset.recaptcha !== "true") {
                            return;
                        }

                        e.preventDefault(); // Prevent default submission

                        grecaptcha.ready(function() {
                            grecaptcha.execute('{{ $setting->site_key }}', {action: 'submit'})
                                .then(function(token) {
                                    // Add the token to the form
                                    document.getElementById('g-recaptcha-response').value = token;

                                    // Submit the form
                                    form.submit();
                                });
                        });
                    });
                });
            });
        </script>

    @elseif($setting->version === 'v3')
        <!-- reCAPTCHA v3 -->
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

        <script src="https://www.google.com/recaptcha/api.js?render={{ $setting->site_key }}"></script>
        <script>
            // Execute reCAPTCHA v3 when form is submitted
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('form[data-recaptcha="true"]');

                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        // Skip reCAPTCHA for forms that don't need it
                        if (form.dataset.recaptcha !== "true") {
                            return;
                        }

                        e.preventDefault(); // Prevent default submission

                        grecaptcha.ready(function() {
                            grecaptcha.execute('{{ $setting->site_key }}', {action: 'submit'})
                                .then(function(token) {
                                    // Add the token to the form
                                    document.getElementById('g-recaptcha-response').value = token;

                                    // Submit the form
                                    form.submit();
                                });
                        });
                    });
                });
            });
        </script>
    @endif
@endif