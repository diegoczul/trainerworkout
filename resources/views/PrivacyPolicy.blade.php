@extends('layouts.frontEnd')
{{ HTML::style('css/homepage.css') }}
<style>
    .privacyWrapper ul {
        list-style-type: disc;
        margin-left: 20px;
        /* Optional: Adds indentation for better aesthetics */
    }
</style>

@section('content')
    <section id="privacyPolicy" style="font-family: Arial, sans-serif; line-height: 1.8; margin: 20px; margin-top: 60px">
        <div class="privacyWrapper"
            style="max-width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
            <p style="text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 10px;"><strong>Privacy
                    Policy</strong></p>
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

            <h2 style="margin-top: 20px; color: #2c3e50;">10. Premium subscription and Prices</h2>
            <p style="margin-bottom: 15px;">
                <b>TrainerWorkout</b> is a freemium app which both provides basic features at free of cost and premium features with a subscription. You can unlock premium or paid features by buying plans as per your need or choice. Subscriptions are concluded for the term specified in your order. You can choose between 1 month, 12 months plans. Prices are displayed in your local currency (if supported) and always include the applicable VAT (Value Added Tax) by the App Store/Play Store. We do not guarantee that the features of TrainerWorkout will always be free and reserves the right, at its sole discretion, to change the pricing. Downloading or using TrainerWorkout may incur additional costs for the internet connection according to the standard rates of your ISP. You shall bear all such costs.
            </p>

            <h2 style="margin-top: 20px; color: #2c3e50;">10. Canceling Your Subscription</h2>
            <p style="margin-bottom: 15px;">
                You can cancel or manage your subscription at any time via your Apple ID account settings. Here's how:
            </p>
            <ul style="list-style-type: disc; margin-left: 40px; margin-bottom: 15px;">
                <li>Open the Settings app on your iPhone or iPad</li>
                <li>Tap your name at the top, then tap Subscriptions</li>
                <li>Select TrainerWorkout from your list of subscriptions</li>
                <li>Tap Cancel Subscription and confirm.</li>
            </ul>
            <p style="margin-bottom: 15px;"> Please Note: </p>
            <ul style="list-style-type: disc; margin-left: 40px; margin-bottom: 15px;">
                <li>Canceling the subscription will stop future payments, but you will continue to have access to the premium features until the current billing period ends.</li>
                <li>No refunds will be issued for the remainder of the subscription period after cancellation.</li>
                <li>If you wish to resubscribe, you can do so at any time by purchasing a new subscription through the Play Store.</li>
            </ul>

            <h2 style="margin-top: 20px; color: #2c3e50;">11. Payments and Refunds</h2>
            <p style="margin-bottom: 15px;">
                If you purchase a subscription, it will automatically renew itself for another term of the same length, until you cancel it before the current term runs out. The subscription price is charged on the first day of the new term. You can cancel your subscription at any time. The cancellation will take effect the day after the last day of the term of the current subscription.
            </p>

            <h2 style="margin-top: 20px; color: #2c3e50;">12.  Refunds</h2>
            <p style="margin-bottom: 15px;">
                We generally do not provide refunds for in-app purchases. It is in our discretion to provide refunds.
            </p>

            <h2 style="margin-top: 20px; color: #2c3e50;">13. Contact Us</h2>
            <p style="margin-bottom: 15px;">
                For any privacy concerns, contact us at <a href="mailto:support@trainer-workout.com"
                    style="color: #2c3e50;">support@trainer-workout.com</a>.
            </p>
        </div>
    </section>
@endsection

@section('scripts')
@endsection
