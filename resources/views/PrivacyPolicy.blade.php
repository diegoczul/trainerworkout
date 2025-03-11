
@extends('layouts.frontEnd')
{{ HTML::style('css/homepage.css') }}
<style>
    .privacyWrapper ul {
        list-style-type: disc;
        margin-left: 20px; /* Optional: Adds indentation for better aesthetics */
    }
</style>
@section('content')

    <section id="privacyPolicy">
        <div class="privacyWrapper">
            <p><strong>Privacy Policy</strong></p>
            <p><strong>Effective Date:</strong> [Insert Date]</p>

            <h2>1. Introduction</h2>
            <p>
                Welcome to Trainer Workout ("we", "our", "us"). We respect your privacy and are committed to
                protecting your personal information. This Privacy Policy outlines how we collect, use, store,
                and share your data when you use our mobile application and services.
            </p>

            <h2>2. Information We Collect</h2>
            <p>We collect the following types of information to enhance your experience:</p>
            <ul style="list-style-type: disc;">
                <li><b>Account Information:</b> Name, email, profile details</li>
                <li><b>Workout Data:</b> Workouts you create, share, or track</li>
                <li><b>Uploaded Content:</b> Images or media you upload</li>
                <li><b>Usage Data:</b> App interactions, device type, IP address</li>
            </ul>

            <h2>3. How We Use Your Information</h2>
            <p>We use your information to:</p>
            <ul style="list-style-type: disc;">
                <li>Provide and improve Trainer Workout services</li>
                <li>Allow you to create, track, and share workouts</li>
                <li>Enable social features like following trainers and clients</li>
                <li>Secure your account and prevent fraud</li>
                <li>Send you updates and notifications (you can opt out)</li>
            </ul>

            <h2>4. Data Sharing and Disclosure</h2>
            <p>We do not sell your data. However, we may share it with:</p>
            <ul style="list-style-type: disc;">
                <li><b>Service Providers:</b> For cloud hosting, analytics, or customer support</li>
                <li><b>Legal Authorities:</b> If required by law</li>
                <li><b>Other Users:</b> If you share workouts or connect with trainers/clients</li>
            </ul>

            <h2>5. Cookies and Tracking Technologies</h2>
            <p>
                We may use cookies and tracking tools to enhance user experience and improve app performance.
            </p>

            <h2>6. Your Rights</h2>
            <p>Depending on your location, you may:</p>
            <ul style="list-style-type: disc;">
                <li>Access, modify, or delete your account data</li>
                <li>Opt out of notifications and certain tracking</li>
                <li>Request data removal by contacting us at [Insert Email]</li>
            </ul>

            <h2>7. Data Retention</h2>
            <p>
                We retain data as long as your account is active. You can request deletion anytime.
            </p>

            <h2>8. Security Measures</h2>
            <p>
                We use encryption, secure servers, and access controls to protect your data.
            </p>

            <h2>9. Updates to this Policy</h2>
            <p>
                We may update this Privacy Policy from time to time. The latest version will always be
                available in the app.
            </p>

            <h2>10. Contact Us</h2>
            <p>
                For any privacy concerns, contact us at <a href="mailto:support@trainer-workout.com">support@trainer-workout.com</a>.
            </p>
        </div>
    </section>
@endsection

@section('scripts')

@endsection
