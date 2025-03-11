
@extends('layouts.frontEnd')
{{ HTML::style('css/homepage.css') }}
<style>
    .privacyWrapper ul {
        list-style-type: disc;
        margin-left: 20px; /* Optional: Adds indentation for better aesthetics */
    }
</style>
@section('content')
    <section id="privacyPolicy" style="font-family: Arial, sans-serif; line-height: 1.8; margin: 20px; margin-top: 60px">
        <div class="privacyWrapper" style="max-width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
            <p style="text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 10px;"><strong>Privacy Policy</strong></p>
            <p style="text-align: center; font-size: 14px; color: #555;"><strong>Effective Date:</strong> 11/03/2025</p>

            <h2 style="margin-top: 20px; color: #2c3e50;">1. Introduction</h2>
            <p style="margin-bottom: 15px;">
                Welcome to Trainer Workout ("we", "our", "us"). We respect your privacy and are committed to
                protecting your personal information. This Privacy Policy outlines how we collect, use, store,
                and share your data when you use our mobile application and services.
            </p>

            <h2 style="margin-top: 20px; color: #2c3e50;">2. Information We Collect</h2>
            <p style="margin-bottom: 10px;">We collect the following types of information to enhance your experience:</p>
            <ul style="list-style-type: disc; margin-left: 40px; margin-bottom: 15px;">
                <li><b>Account Information:</b> Name, email, profile details</li>
                <li><b>Workout Data:</b> Workouts you create, share, or track</li>
                <li><b>Uploaded Content:</b> Images or media you upload</li>
                <li><b>Usage Data:</b> App interactions, device type, IP address</li>
            </ul>

            <h2 style="margin-top: 20px; color: #2c3e50;">3. How We Use Your Information</h2>
            <p style="margin-bottom: 10px;">We use your information to:</p>
            <ul style="list-style-type: disc; margin-left: 40px; margin-bottom: 15px;">
                <li>Provide and improve Trainer Workout services</li>
                <li>Allow you to create, track, and share workouts</li>
                <li>Enable social features like following trainers and clients</li>
                <li>Secure your account and prevent fraud</li>
                <li>Send you updates and notifications (you can opt out)</li>
            </ul>

            <h2 style="margin-top: 20px; color: #2c3e50;">4. Data Sharing and Disclosure</h2>
            <p style="margin-bottom: 10px;">We do not sell your data. However, we may share it with:</p>
            <ul style="list-style-type: disc; margin-left: 40px; margin-bottom: 15px;">
                <li><b>Service Providers:</b> For cloud hosting, analytics, or customer support</li>
                <li><b>Legal Authorities:</b> If required by law</li>
                <li><b>Other Users:</b> If you share workouts or connect with trainers/clients</li>
            </ul>

            <h2 style="margin-top: 20px; color: #2c3e50;">5. Cookies and Tracking Technologies</h2>
            <p style="margin-bottom: 15px;">
                We may use cookies and tracking tools to enhance user experience and improve app performance.
            </p>

            <h2 style="margin-top: 20px; color: #2c3e50;">6. Your Rights</h2>
            <p style="margin-bottom: 10px;">Depending on your location, you may:</p>
            <ul style="list-style-type: disc; margin-left: 40px; margin-bottom: 15px;">
                <li>Access, modify, or delete your account data</li>
                <li>Opt out of notifications and certain tracking</li>
                <li>Request data removal by contacting us at [Insert Email]</li>
            </ul>

            <h2 style="margin-top: 20px; color: #2c3e50;">7. Data Retention</h2>
            <p style="margin-bottom: 15px;">
                We retain data as long as your account is active. You can request deletion anytime.
            </p>

            <h2 style="margin-top: 20px; color: #2c3e50;">8. Security Measures</h2>
            <p style="margin-bottom: 15px;">
                We use encryption, secure servers, and access controls to protect your data.
            </p>

            <h2 style="margin-top: 20px; color: #2c3e50;">9. Updates to this Policy</h2>
            <p style="margin-bottom: 15px;">
                We may update this Privacy Policy from time to time. The latest version will always be
                available in the app.
            </p>

            <h2 style="margin-top: 20px; color: #2c3e50;">10. Contact Us</h2>
            <p style="margin-bottom: 15px;">
                For any privacy concerns, contact us at <a href="mailto:support@trainer-workout.com" style="color: #2c3e50;">support@trainer-workout.com</a>.
            </p>
        </div>
    </section>
@endsection

@section('scripts')

@endsection
