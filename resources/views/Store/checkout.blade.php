@php

    use App\Models\Memberships;

    if (Auth::check()) {
        $layoutToLoad = strtolower(Auth::user()->userType);
    } else {
        $layoutToLoad = 'visitor';
    }

    $total = 0;
    foreach ($cart['items'] as $cartObject) {
        $membership = \App\Models\Memberships::find($cartObject['id']);
        $total += $membership->price;
    }

@endphp
@extends('layouts.' . $layoutToLoad)


@section('content')
    <script type="text/javascript">
        // alternative to DOMContentLoaded
        document.onreadystatechange = function() {
            document.body.style.display = "none";
            document.getElementById("main_header").classList.add("main_header_account");
            document.body.classList.add("trainer_account");
            document.body.style.display = "block";
        }
    </script>

    <body class="upgradeBackground">

        <section class="checkout clearfix">
            {{ Form::open(['url' => Lang::get('routes./Store/ProcessPayment'), 'id' => 'payment-form']) }}
            <div class="wrapper">
                <div class="checkoutBox payCard">
                    <div class="checkoutHeader">
                        <h1>{{ Lang::get('content.Payment') }}</h1>
                    </div>
                    <div class="payHeader">
                        <div class="payMembership">
                            @foreach ($cart['items'] as $cartObject)
                                <?php $membership = Memberships::find($cartObject['id']); ?>
                                <h2>{{ $membership->name }}
                                    @if ($membership->price != '')
                                        ${{ number_format($membership->price, 2) }}
                                    @else
                                        Free
                                    @endif
                                </h2>
                            @endforeach
                        </div>
                        <div class="payPlanBtn">
                            <a href="{{ Lang::get('routes./UpgradePlan') }}">{{ Lang::get('content.changeplan') }}</a>
                        </div>
                    </div>
                </div>
                <div class="upgradeBox payCard" id="paylowerContainer">
                    @if ($total > 0)
                        <div class="payBlocks">

                            <div class="payBlock payBilling">
                                <div class="payHeader">
                                    <h2>{{ Lang::get('content.Billinginformation') }}</h2>
                                </div>

                                <div class="payInfoContainer" id="payRightContainer">
                                    <form enctype="multipart/form-data" name="payform" id="payform"
                                        class="formholder clearfix">
                                        @if (!empty(auth()->user()->stripeCheckoutToken))
                                            <input type="hidden" name="oldCustomer"
                                                value="{{ auth()->user()->stripeCheckoutToken }}">
                                        @endif
                                        <!-- <div class="payFormFull"> -->
                                        <fieldset class="payForm payFormFull">
                                            <label for="biStreet">{{ Lang::get('content.street') }}</label>
                                            <input id="biStreet" value="{{ Auth::user()->street }}" name="street"
                                                type="text" class="payFormInput validate[required]"
                                                placeholder="123 acme street">
                                        </fieldset>
                                        <!-- </div> -->

                                        <div class="payFormFull">
                                            <fieldset class="payForm payFormHalf">
                                                <label for="biCity">{{ Lang::get('content.city') }}</label>
                                                <input id="biCity" value="{{ Auth::user()->city }}"
                                                    placeholder="New York City" name="city" type="text"
                                                    class="payFormInput validate[required]">
                                            </fieldset>


                                            <fieldset class="payForm payFormHalf payFormRight">
                                                <label for="biPostCode">{{ Lang::get('content.postalcode') }}</label>
                                                <input id="biPostCode" value="{{ Auth::user()->postalcode }}"
                                                    placeholder="23560" name="postal" type="text"
                                                    class="payFormInput validate[required]">
                                            </fieldset>
                                        </div>

                                        <div class="payFormFull">
                                            <fieldset class="payForm payFormHalf">
                                                <label for="countrySelect">{{ Lang::get('content.country') }}</label>
                                                <select name="country" class="payFormInput Gselect validate[required]"
                                                    id="countrySelect">
                                                    <option value="">{{ Lang::get('content.country') }}...</option>
                                                    <option value="United States of America">United States of America
                                                    </option>
                                                    <option value="Canada">Canada</option>
                                                    <option value="United Kingdom">United Kingdom</option>
                                                    <option value="Australia">Australia</option>
                                                    <option value="">--------------</option>
                                                    <option value="Afganistan">Afghanistan</option>
                                                    <option value="Albania">Albania</option>
                                                    <option value="Algeria">Algeria</option>
                                                    <option value="American Samoa">American Samoa</option>
                                                    <option value="Andorra">Andorra</option>
                                                    <option value="Angola">Angola</option>
                                                    <option value="Anguilla">Anguilla</option>
                                                    <option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
                                                    <option value="Argentina">Argentina</option>
                                                    <option value="Armenia">Armenia</option>
                                                    <option value="Aruba">Aruba</option>
                                                    <option value="Australia">Australia</option>
                                                    <option value="Austria">Austria</option>
                                                    <option value="Azerbaijan">Azerbaijan</option>
                                                    <option value="Bahamas">Bahamas</option>
                                                    <option value="Bahrain">Bahrain</option>
                                                    <option value="Bangladesh">Bangladesh</option>
                                                    <option value="Barbados">Barbados</option>
                                                    <option value="Belarus">Belarus</option>
                                                    <option value="Belgium">Belgium</option>
                                                    <option value="Belize">Belize</option>
                                                    <option value="Benin">Benin</option>
                                                    <option value="Bermuda">Bermuda</option>
                                                    <option value="Bhutan">Bhutan</option>
                                                    <option value="Bolivia">Bolivia</option>
                                                    <option value="Bonaire">Bonaire</option>
                                                    <option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina
                                                    </option>
                                                    <option value="Botswana">Botswana</option>
                                                    <option value="Brazil">Brazil</option>
                                                    <option value="British Indian Ocean Ter">British Indian Ocean Ter
                                                    </option>
                                                    <option value="Brunei">Brunei</option>
                                                    <option value="Bulgaria">Bulgaria</option>
                                                    <option value="Burkina Faso">Burkina Faso</option>
                                                    <option value="Burundi">Burundi</option>
                                                    <option value="Cambodia">Cambodia</option>
                                                    <option value="Cameroon">Cameroon</option>
                                                    <option value="Canada">Canada</option>
                                                    <option value="Canary Islands">Canary Islands</option>
                                                    <option value="Cape Verde">Cape Verde</option>
                                                    <option value="Cayman Islands">Cayman Islands</option>
                                                    <option value="Central African Republic">Central African Republic
                                                    </option>
                                                    <option value="Chad">Chad</option>
                                                    <option value="Channel Islands">Channel Islands</option>
                                                    <option value="Chile">Chile</option>
                                                    <option value="China">China</option>
                                                    <option value="Christmas Island">Christmas Island</option>
                                                    <option value="Cocos Island">Cocos Island</option>
                                                    <option value="Colombia">Colombia</option>
                                                    <option value="Comoros">Comoros</option>
                                                    <option value="Congo">Congo</option>
                                                    <option value="Cook Islands">Cook Islands</option>
                                                    <option value="Costa Rica">Costa Rica</option>
                                                    <option value="Cote DIvoire">Cote D'Ivoire</option>
                                                    <option value="Croatia">Croatia</option>
                                                    <option value="Cuba">Cuba</option>
                                                    <option value="Curaco">Curacao</option>
                                                    <option value="Cyprus">Cyprus</option>
                                                    <option value="Czech Republic">Czech Republic</option>
                                                    <option value="Denmark">Denmark</option>
                                                    <option value="Djibouti">Djibouti</option>
                                                    <option value="Dominica">Dominica</option>
                                                    <option value="Dominican Republic">Dominican Republic</option>
                                                    <option value="East Timor">East Timor</option>
                                                    <option value="Ecuador">Ecuador</option>
                                                    <option value="Egypt">Egypt</option>
                                                    <option value="El Salvador">El Salvador</option>
                                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                    <option value="Eritrea">Eritrea</option>
                                                    <option value="Estonia">Estonia</option>
                                                    <option value="Ethiopia">Ethiopia</option>
                                                    <option value="Falkland Islands">Falkland Islands</option>
                                                    <option value="Faroe Islands">Faroe Islands</option>
                                                    <option value="Fiji">Fiji</option>
                                                    <option value="Finland">Finland</option>
                                                    <option value="France">France</option>
                                                    <option value="French Guiana">French Guiana</option>
                                                    <option value="French Polynesia">French Polynesia</option>
                                                    <option value="French Southern Ter">French Southern Ter</option>
                                                    <option value="Gabon">Gabon</option>
                                                    <option value="Gambia">Gambia</option>
                                                    <option value="Georgia">Georgia</option>
                                                    <option value="Germany">Germany</option>
                                                    <option value="Ghana">Ghana</option>
                                                    <option value="Gibraltar">Gibraltar</option>
                                                    <option value="Great Britain">Great Britain</option>
                                                    <option value="Greece">Greece</option>
                                                    <option value="Greenland">Greenland</option>
                                                    <option value="Grenada">Grenada</option>
                                                    <option value="Guadeloupe">Guadeloupe</option>
                                                    <option value="Guam">Guam</option>
                                                    <option value="Guatemala">Guatemala</option>
                                                    <option value="Guinea">Guinea</option>
                                                    <option value="Guyana">Guyana</option>
                                                    <option value="Haiti">Haiti</option>
                                                    <option value="Hawaii">Hawaii</option>
                                                    <option value="Honduras">Honduras</option>
                                                    <option value="Hong Kong">Hong Kong</option>
                                                    <option value="Hungary">Hungary</option>
                                                    <option value="Iceland">Iceland</option>
                                                    <option value="India">India</option>
                                                    <option value="Indonesia">Indonesia</option>
                                                    <option value="Iran">Iran</option>
                                                    <option value="Iraq">Iraq</option>
                                                    <option value="Ireland">Ireland</option>
                                                    <option value="Isle of Man">Isle of Man</option>
                                                    <option value="Israel">Israel</option>
                                                    <option value="Italy">Italy</option>
                                                    <option value="Jamaica">Jamaica</option>
                                                    <option value="Japan">Japan</option>
                                                    <option value="Jordan">Jordan</option>
                                                    <option value="Kazakhstan">Kazakhstan</option>
                                                    <option value="Kenya">Kenya</option>
                                                    <option value="Kiribati">Kiribati</option>
                                                    <option value="Korea North">Korea North</option>
                                                    <option value="Korea Sout">Korea South</option>
                                                    <option value="Kuwait">Kuwait</option>
                                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                    <option value="Laos">Laos</option>
                                                    <option value="Latvia">Latvia</option>
                                                    <option value="Lebanon">Lebanon</option>
                                                    <option value="Lesotho">Lesotho</option>
                                                    <option value="Liberia">Liberia</option>
                                                    <option value="Libya">Libya</option>
                                                    <option value="Liechtenstein">Liechtenstein</option>
                                                    <option value="Lithuania">Lithuania</option>
                                                    <option value="Luxembourg">Luxembourg</option>
                                                    <option value="Macau">Macau</option>
                                                    <option value="Macedonia">Macedonia</option>
                                                    <option value="Madagascar">Madagascar</option>
                                                    <option value="Malaysia">Malaysia</option>
                                                    <option value="Malawi">Malawi</option>
                                                    <option value="Maldives">Maldives</option>
                                                    <option value="Mali">Mali</option>
                                                    <option value="Malta">Malta</option>
                                                    <option value="Marshall Islands">Marshall Islands</option>
                                                    <option value="Martinique">Martinique</option>
                                                    <option value="Mauritania">Mauritania</option>
                                                    <option value="Mauritius">Mauritius</option>
                                                    <option value="Mayotte">Mayotte</option>
                                                    <option value="Mexico">Mexico</option>
                                                    <option value="Midway Islands">Midway Islands</option>
                                                    <option value="Moldova">Moldova</option>
                                                    <option value="Monaco">Monaco</option>
                                                    <option value="Mongolia">Mongolia</option>
                                                    <option value="Montserrat">Montserrat</option>
                                                    <option value="Morocco">Morocco</option>
                                                    <option value="Mozambique">Mozambique</option>
                                                    <option value="Myanmar">Myanmar</option>
                                                    <option value="Nambia">Nambia</option>
                                                    <option value="Nauru">Nauru</option>
                                                    <option value="Nepal">Nepal</option>
                                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                                    <option value="Nevis">Nevis</option>
                                                    <option value="New Caledonia">New Caledonia</option>
                                                    <option value="New Zealand">New Zealand</option>
                                                    <option value="Nicaragua">Nicaragua</option>
                                                    <option value="Niger">Niger</option>
                                                    <option value="Nigeria">Nigeria</option>
                                                    <option value="Niue">Niue</option>
                                                    <option value="Norfolk Island">Norfolk Island</option>
                                                    <option value="Norway">Norway</option>
                                                    <option value="Oman">Oman</option>
                                                    <option value="Pakistan">Pakistan</option>
                                                    <option value="Palau Island">Palau Island</option>
                                                    <option value="Palestine">Palestine</option>
                                                    <option value="Panama">Panama</option>
                                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                                    <option value="Paraguay">Paraguay</option>
                                                    <option value="Peru">Peru</option>
                                                    <option value="Phillipines">Philippines</option>
                                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                                    <option value="Poland">Poland</option>
                                                    <option value="Portugal">Portugal</option>
                                                    <option value="Puerto Rico">Puerto Rico</option>
                                                    <option value="Qatar">Qatar</option>
                                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                                    <option value="Reunion">Reunion</option>
                                                    <option value="Romania">Romania</option>
                                                    <option value="Russia">Russia</option>
                                                    <option value="Rwanda">Rwanda</option>
                                                    <option value="St Barthelemy">St Barthelemy</option>
                                                    <option value="St Eustatius">St Eustatius</option>
                                                    <option value="St Helena">St Helena</option>
                                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                                    <option value="St Lucia">St Lucia</option>
                                                    <option value="St Maarten">St Maarten</option>
                                                    <option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon
                                                    </option>
                                                    <option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines
                                                    </option>
                                                    <option value="Saipan">Saipan</option>
                                                    <option value="Samoa">Samoa</option>
                                                    <option value="Samoa American">Samoa American</option>
                                                    <option value="San Marino">San Marino</option>
                                                    <option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe
                                                    </option>
                                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                                    <option value="Senegal">Senegal</option>
                                                    <option value="Serbia">Serbia</option>
                                                    <option value="Seychelles">Seychelles</option>
                                                    <option value="Sierra Leone">Sierra Leone</option>
                                                    <option value="Singapore">Singapore</option>
                                                    <option value="Slovakia">Slovakia</option>
                                                    <option value="Slovenia">Slovenia</option>
                                                    <option value="Solomon Islands">Solomon Islands</option>
                                                    <option value="Somalia">Somalia</option>
                                                    <option value="South Africa">South Africa</option>
                                                    <option value="Spain">Spain</option>
                                                    <option value="Sri Lanka">Sri Lanka</option>
                                                    <option value="Sudan">Sudan</option>
                                                    <option value="Suriname">Suriname</option>
                                                    <option value="Swaziland">Swaziland</option>
                                                    <option value="Sweden">Sweden</option>
                                                    <option value="Switzerland">Switzerland</option>
                                                    <option value="Syria">Syria</option>
                                                    <option value="Tahiti">Tahiti</option>
                                                    <option value="Taiwan">Taiwan</option>
                                                    <option value="Tajikistan">Tajikistan</option>
                                                    <option value="Tanzania">Tanzania</option>
                                                    <option value="Thailand">Thailand</option>
                                                    <option value="Togo">Togo</option>
                                                    <option value="Tokelau">Tokelau</option>
                                                    <option value="Tonga">Tonga</option>
                                                    <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
                                                    <option value="Tunisia">Tunisia</option>
                                                    <option value="Turkey">Turkey</option>
                                                    <option value="Turkmenistan">Turkmenistan</option>
                                                    <option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
                                                    <option value="Tuvalu">Tuvalu</option>
                                                    <option value="Uganda">Uganda</option>
                                                    <option value="Ukraine">Ukraine</option>
                                                    <option value="United Arab Erimates">United Arab Emirates</option>
                                                    <option value="United Kingdom">United Kingdom</option>
                                                    <option value="United States of America">United States of America
                                                    </option>
                                                    <option value="Uraguay">Uruguay</option>
                                                    <option value="Uzbekistan">Uzbekistan</option>
                                                    <option value="Vanuatu">Vanuatu</option>
                                                    <option value="Vatican City State">Vatican City State</option>
                                                    <option value="Venezuela">Venezuela</option>
                                                    <option value="Vietnam">Vietnam</option>
                                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                                    <option value="Wake Island">Wake Island</option>
                                                    <option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
                                                    <option value="Yemen">Yemen</option>
                                                    <option value="Zaire">Zaire</option>
                                                    <option value="Zambia">Zambia</option>
                                                    <option value="Zimbabwe">Zimbabwe</option>
                                                </select>
                                            </fieldset>
                                            <fieldset class="payForm payFormHalf payFormRight ">
                                                <label for="stateSelect">{{ Lang::get('content.state') }}</label>
                                                <select id="stateSelect" name="province"
                                                    class="Gselect payFormInput validate[required]">
                                                    <option>Select provice / State</option>
                                                    <optgroup class="options" label="United States of America">
                                                        <option>Alabama</option>
                                                        <option>Alaska</option>
                                                        <option>Arizona</option>
                                                        <option>Arkansas</option>
                                                        <option>California</option>
                                                        <option>Colorado</option>
                                                        <option>Connecticut</option>
                                                        <option>Delaware</option>
                                                        <option>Florida</option>
                                                        <option>Georgia</option>
                                                        <option>Hawaii</option>
                                                        <option>Idaho</option>
                                                        <option>Illinois</option>
                                                        <option>Indiana</option>
                                                        <option>Iowa</option>
                                                        <option>Kansas</option>
                                                        <option>Kentucky</option>
                                                        <option>Louisiana</option>
                                                        <option>Maine</option>
                                                        <option>Maryland</option>
                                                        <option>Massachusetts</option>
                                                        <option>Michigan</option>
                                                        <option>Minnesota</option>
                                                        <option>Mississippi</option>
                                                        <option>Missouri</option>
                                                        <option>Montana</option>
                                                        <option>Nebraska</option>
                                                        <option>Nevada</option>
                                                        <option>New Hampshire</option>
                                                        <option>New Jersey</option>
                                                        <option>New Mexico</option>
                                                        <option>New York</option>
                                                        <option>North Carolina</option>
                                                        <option>North Dakota</option>
                                                        <option>Ohio</option>
                                                        <option>Oklahoma</option>
                                                        <option>Oregon</option>
                                                        <option>Pennsylvania</option>
                                                        <option>Rhode Island</option>
                                                        <option>South Carolina</option>
                                                        <option>South Dakota</option>
                                                        <option>Tennessee</option>
                                                        <option>Texas</option>
                                                        <option>Utah</option>
                                                        <option>Vermont</option>
                                                        <option>Virginia</option>
                                                        <option>Washington</option>
                                                        <option>West Virginia</option>
                                                        <option>Wisconsin</option>
                                                        <option>Wyoming</option>
                                                    </optgroup>
                                                    <optgroup class="options" label="Canada">
                                                        <option value="Alberta">Alberta</option>
                                                        <option value="British Columbia">British Columbia</option>
                                                        <option value="Manitoba">Manitoba</option>
                                                        <option value="New Brunswick">New Brunswick</option>
                                                        <option value="Newfoundland">Newfoundland</option>
                                                        <option value="Northwest Territories">Northwest Territories
                                                        </option>
                                                        <option value="Nova Scotia">Nova Scotia</option>
                                                        <option value="Nunavut">Nunavut</option>
                                                        <option value="Ontario">Ontario</option>
                                                        <option value="Prince Edward Island">Prince Edward Island</option>
                                                        <option value="Quebec">Quebec</option>
                                                        <option value="Saskatchewan">Saskatchewan</option>
                                                        <option value="Yukon Territory">Yukon Territory</option>
                                                    </optgroup>
                                                    <optgroup label="Other">
                                                        <option value="other">other</option>
                                                    </optgroup>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="payBlock payCredit">
                                <div class="payHeader">
                                    <h2>{{ Lang::get('content.CreditCardinformation') }}</h2>
                                </div>
                                <div class="payInfoContainer" id="payInfoContainer"></div>
                            </div>
                        </div>
                    @else
                        <div class="payBlock payCredit">
                            <div class="payHeader">
                                <h2>{{ Lang::get('content.Confirmation') }}</h2>
                            </div>
                            <div class="p-10" id="">
                                <p>{{ Lang::get('content.renewal_note') }}</p>
                            </div>
                        </div>
                    @endif
                    <div class="PayCompleteButton">
                        <fieldset>
                            <button type="submit" id="payform_submit" class="send"><span
                                    id="payform_submit_lbl">{{ Lang::get('content.CompleteSubscription') }}</span></button>
                        </fieldset>
                    </div>
                </div>
            </div>

            <div class="holder">
                <div class="fltleft checkbox">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            {{ Form::close() }}
        </section>
    </body>
@endsection

@section('scripts')
    @if ($total > 0)
        {{ HTML::script('https://js.stripe.com/v3/') }}
        <script type="text/javascript">
            function switchToNewCreditCard() {
                $("#newcard").show();
                $("#oldcard").hide();
                $("#oldCustomer").val("");
            }

            // This identifies your website in the createToken call below

            @if ($debug)
                var Stripe = Stripe('{{ Config::get('constants.STRIPETestpublishable_key') }}');
            @else
                var Stripe = Stripe('{{ Config::get('constants.STRIPEpublishable_key') }}');
            @endif
            // ...
        </script>

        <script>
            var elements = Stripe.elements();
            var card = elements.create('card',{
                hidePostalCode: true,
            });
            card.mount('#payInfoContainer');
            jQuery(function($) {
                $('#payment-form').submit(function(event) {
                    event.preventDefault();
                    Stripe.createPaymentMethod({
                        type: 'card',
                        card: card,
                        billing_details: {
                            name: '{{ auth()->user()->firstName ?? 'N/A' }} {{ auth()->user()->lastName ?? 'N/A' }}',
                        }
                    }).then(function(response) {
                        var $form = $('#payment-form');
                        if (response.error) {
                            // Show the errors on the form
                            $form.find('.payment-errors').text(response.error.message);
                            $form.find('button').prop('disabled', false);
                            errorMessage(response.error.message);
                        } else {
                            var token = response.paymentMethod.id;
                            $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                            var formData = new FormData($form[0]);
                            let preLoad;
                            var el = $("#payform_submit_lbl");
                            $.ajax({
                                url: "{{ route('process-subscription-payment') }}",
                                method: 'POST',
                                dataType: "json",
                                data: formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                beforeSend: function() {
                                    preLoad = showLoadWithElement(el, 40, 'center');
                                    $("#payform_submit").attr('disabled', true);
                                },
                                success: function(data) {
                                    if(data.data.hasOwnProperty('clientSecret') && data.data.clientSecret != null){
                                        Stripe.confirmCardPayment(data.data.clientSecret).then(
                                        function(result) {
                                            if (result.error) {
                                                errorMessage(response.error.message);
                                                $('#submit-button').prop('disabled',
                                                    false);
                                            } else {
                                                if (result.paymentIntent.status ===
                                                    'succeeded') {
                                                    window.location.href =
                                                        "{{ route('subscription-success') }}";
                                                }
                                            }
                                        });
                                    }else{
                                        window.location.href = "{{ route('subscription-success') }}";
                                    }
                                },
                                error: function(data) {
                                    hideLoadWithElement(preLoad);
                                    $("#payform_submit").attr('disabled', false);
                                    errorMessage(data.responseJSON.message);
                                }
                            });
                        }
                    });
                    return false;
                });
            });
        </script>
    @else
        <script>
            // Simple direct POST if total is 0
            jQuery(function($) {
                $('#payment-form').submit(function(event) {
                    event.preventDefault();
                    let formData = new FormData(this);
                    let el = $("#payform_submit_lbl");
                    let preLoad = showLoadWithElement(el, 40, 'center');
                    $.ajax({
                        url: "{{ route('process-subscription-payment') }}",
                        method: 'POST',
                        dataType: "json",
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function(data) {
                            window.location.href = "{{ route('subscription-success') }}";
                        },
                        error: function(data) {
                            hideLoadWithElement(preLoad);
                            $("#payform_submit").attr('disabled', false);
                            errorMessage(data.responseJSON.message);
                        }
                    });
                    return false;
                });
            });
        </script>
    @endif
    <script>
        $(function() {
            // var states = $("#stateSelect").html();
            // var emptyState = "<option value=''>"+ $("#stateSelect").find("option[value='']").html() +"</option>";
            // $("#stateSelect").html(emptyState);

            var $states = $("#stateSelect").find(".options").find("option");
            $states.each(function() {
                var $value = $(this).text();
                $(this).attr("value", $value);
            })


            $('#countrySelect').change(function() {
                var country = $('#countrySelect :selected').val()
                var options = $(states).filter("optgroup[label='" + country + "']").html()
                var $states = $('#stateSelect')
                if (options) {
                    $states.html(options)
                    $states.parent().show()
                } else {
                    $states.html(emptyState)
                    $states.parent().show()
                }
            })
        });
    </script>
@endsection
