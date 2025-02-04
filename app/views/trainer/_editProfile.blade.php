@extends('layouts.trainer')

@section('content')
<section id="content" class="clearfix contenttoptouch">
{{ Form::open(array('url' => 'Trainer/EditProfile', "files"=>true)); }}
<div class="bannerholder">
            <div class="wrapper clearfix" style="margin-top:100px;">
            <input type="file" name="image" class="inputbox-middle"  style="position:absolute; top:190px; left:6px; color:#fff"/>
                <div class="profileimage" style="position:relative">
                   <img src="/{{ Helper::image(Auth::user()->thumb) }}" alt="profile image">
                   

                </div>
                
                <div class="profieldetails">
                
                    <h1><input  autocomplete="off"  name="firstName" placeholder="First Name"  class="inputbox-middle"  type="text" value="{{ Auth::user()->firstName }}" /> </h1> 
                     <h3>
                        <input type="submit" class="bluebtn fltright" value="Save Profile"/>
                        Training with TrainerWorkout since: {{ Helper::date(Auth::user()->created_at) }}  <fieldset style="display:inline"> <label><input type="radio" {{ (Auth::user()->gender == "male") ? "checked='checked'" : "" }} name="gender" id="gender" value="male"> Male </label><label><input type="radio"  {{ (Auth::user()->gender == "female") ? "checked='checked'" : "" }} name="gender" id="gender" value="female"> Female </label></fieldset>
                     </h3>
                     <ul class="clearfix">
                        <li><input  autocomplete="off"  name="lastName" placeholder="Last Name"  class="inputbox-middle" type="text" value="{{ Auth::user()->lastName }}" /></li>
                        <li>
                        
                        <fieldset>
                        <?php
                        $birthday = "";
                        if(Auth::user()->birthday != ""){
                           $birthday = date('Y-m-d', strtotime(Auth::user()->birthday));
                         } 
                       
                        ?>    
                          <input  autocomplete="off"  name="birthday" placeholder="birthday"  type="text" id="birthday" class="datepicker inputbox-middle" readonly value="{{ $birthday }}" /> <a href="#"></a>
                      </fieldset>
                        <!--<input name="birthdate" class="inputbox-middle hasDatePicker"  type="text" value="" /> --></li>
                        <li><fieldset><input  autocomplete="off"  name="email" placeholder="email"  class="inputbox-middle"  type="text" value="{{ Auth::user()->email }}"/></li></fieldset>
                        <li><fieldset><input  autocomplete="off"  name="phone" placeholder="Phone" class="inputbox-middle"  type="text" value="{{ Auth::user()->phone }}" /></li></fieldset>
                        <li><fieldset><input  autocomplete="off" name="password" placeholder="Password" class="inputbox-middle"  type="password" value="" /></li></fieldset>
                        <li><fieldset><input  autocomplete="off" name="password_confirmation" placeholder="Confirm Password" class="inputbox-middle"  type="password" value="" /></li></fieldset>
                        <li><input  name="id" type="hidden" name="id" value="{{ Auth::user()->id }}" /></li>
                     </ul>
                
                </div>
                
            </div>
            
        </div>
        
    <div class="wrapper">
     <div class="widgets fullwidthwidget shadow clearfix ">
       <h1>Address</h1>
       <div class="editform" style="width:585px">
       <fieldset>
            <label>Address </label>
            <input  autocomplete="off"  name="address" placeholder="address"  class="inputbox-middle"  type="text" value="{{ Auth::user()->address }}"/>
          </fieldset>
          <fieldset>
            <label>Street </label>
            <input  autocomplete="off"  name="street" placeholder="street"  class="inputbox-middle"  type="text" value="{{ Auth::user()->street }}"/>
          </fieldset>
          <fieldset>
            <label>Suite </label>
            <input  autocomplete="off"  name="suite" placeholder="suite"  class="inputbox-middle"  type="text" value="{{ Auth::user()->suite }}"/>
          </fieldset>
          <fieldset>
            <label>City </label>
            <input  autocomplete="off"  name="city" placeholder="city"  class="inputbox-middle"  type="text" value="{{ Auth::user()->city }}"/>
          </fieldset>
          <fieldset>
            <label>Province </label>
            <select name="province" id="province" class="chosen-select"> 
                         <optgroup label="US">
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
                            <optgroup label="Canada">
                            <option>Alberta</option>
                            <option>British Columbia</option>
                            <option>Manitoba</option>
                            <option>New Brunswick</option>
                            <option>Newfoundland and Labrador</option>
                            <option>Nova Scotia</option>
                            <option>Ontario</option>
                            <option>Prince Edward Island</option>
                            <option>Quebec</option>
                            <option>Saskatchewan</option>
                            <option>Northwest Territories</option>
                            <option>Nunavut</option>
                            <option>Yukon</option>
                            </optgroup>
                            <optgroup label="Other">
                            <option>Other</option>
                        </optgroup>
                      </select>
          </fieldset>
          <fieldset>
            <label>Country </label>
            <select name="country" class="chosen-select validate[required]">
               <option value="" selected="selected">Select Country</option> 
              <option value="United States">United States</option> 
              <option value="United Kingdom">United Kingdom</option> 
              <option value="Afghanistan">Afghanistan</option> 
              <option value="Albania">Albania</option> 
              <option value="Algeria">Algeria</option> 
              <option value="American Samoa">American Samoa</option> 
              <option value="Andorra">Andorra</option> 
              <option value="Angola">Angola</option> 
              <option value="Anguilla">Anguilla</option> 
              <option value="Antarctica">Antarctica</option> 
              <option value="Antigua and Barbuda">Antigua and Barbuda</option> 
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
              <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option> 
              <option value="Botswana">Botswana</option> 
              <option value="Bouvet Island">Bouvet Island</option> 
              <option value="Brazil">Brazil</option> 
              <option value="British Indian Ocean Territory">British Indian Ocean Territory</option> 
              <option value="Brunei Darussalam">Brunei Darussalam</option> 
              <option value="Bulgaria">Bulgaria</option> 
              <option value="Burkina Faso">Burkina Faso</option> 
              <option value="Burundi">Burundi</option> 
              <option value="Cambodia">Cambodia</option> 
              <option value="Cameroon">Cameroon</option> 
              <option value="Canada">Canada</option> 
              <option value="Cape Verde">Cape Verde</option> 
              <option value="Cayman Islands">Cayman Islands</option> 
              <option value="Central African Republic">Central African Republic</option> 
              <option value="Chad">Chad</option> 
              <option value="Chile">Chile</option> 
              <option value="China">China</option> 
              <option value="Christmas Island">Christmas Island</option> 
              <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option> 
              <option value="Colombia">Colombia</option> 
              <option value="Comoros">Comoros</option> 
              <option value="Congo">Congo</option> 
              <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option> 
              <option value="Cook Islands">Cook Islands</option> 
              <option value="Costa Rica">Costa Rica</option> 
              <option value="Cote D'ivoire">Cote D'ivoire</option> 
              <option value="Croatia">Croatia</option> 
              <option value="Cuba">Cuba</option> 
              <option value="Cyprus">Cyprus</option> 
              <option value="Czech Republic">Czech Republic</option> 
              <option value="Denmark">Denmark</option> 
              <option value="Djibouti">Djibouti</option> 
              <option value="Dominica">Dominica</option> 
              <option value="Dominican Republic">Dominican Republic</option> 
              <option value="Ecuador">Ecuador</option> 
              <option value="Egypt">Egypt</option> 
              <option value="El Salvador">El Salvador</option> 
              <option value="Equatorial Guinea">Equatorial Guinea</option> 
              <option value="Eritrea">Eritrea</option> 
              <option value="Estonia">Estonia</option> 
              <option value="Ethiopia">Ethiopia</option> 
              <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option> 
              <option value="Faroe Islands">Faroe Islands</option> 
              <option value="Fiji">Fiji</option> 
              <option value="Finland">Finland</option> 
              <option value="France">France</option> 
              <option value="French Guiana">French Guiana</option> 
              <option value="French Polynesia">French Polynesia</option> 
              <option value="French Southern Territories">French Southern Territories</option> 
              <option value="Gabon">Gabon</option> 
              <option value="Gambia">Gambia</option> 
              <option value="Georgia">Georgia</option> 
              <option value="Germany">Germany</option> 
              <option value="Ghana">Ghana</option> 
              <option value="Gibraltar">Gibraltar</option> 
              <option value="Greece">Greece</option> 
              <option value="Greenland">Greenland</option> 
              <option value="Grenada">Grenada</option> 
              <option value="Guadeloupe">Guadeloupe</option> 
              <option value="Guam">Guam</option> 
              <option value="Guatemala">Guatemala</option> 
              <option value="Guinea">Guinea</option> 
              <option value="Guinea-bissau">Guinea-bissau</option> 
              <option value="Guyana">Guyana</option> 
              <option value="Haiti">Haiti</option> 
              <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option> 
              <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option> 
              <option value="Honduras">Honduras</option> 
              <option value="Hong Kong">Hong Kong</option> 
              <option value="Hungary">Hungary</option> 
              <option value="Iceland">Iceland</option> 
              <option value="India">India</option> 
              <option value="Indonesia">Indonesia</option> 
              <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option> 
              <option value="Iraq">Iraq</option> 
              <option value="Ireland">Ireland</option> 
              <option value="Israel">Israel</option> 
              <option value="Italy">Italy</option> 
              <option value="Jamaica">Jamaica</option> 
              <option value="Japan">Japan</option> 
              <option value="Jordan">Jordan</option> 
              <option value="Kazakhstan">Kazakhstan</option> 
              <option value="Kenya">Kenya</option> 
              <option value="Kiribati">Kiribati</option> 
              <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option> 
              <option value="Korea, Republic of">Korea, Republic of</option> 
              <option value="Kuwait">Kuwait</option> 
              <option value="Kyrgyzstan">Kyrgyzstan</option> 
              <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option> 
              <option value="Latvia">Latvia</option> 
              <option value="Lebanon">Lebanon</option> 
              <option value="Lesotho">Lesotho</option> 
              <option value="Liberia">Liberia</option> 
              <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option> 
              <option value="Liechtenstein">Liechtenstein</option> 
              <option value="Lithuania">Lithuania</option> 
              <option value="Luxembourg">Luxembourg</option> 
              <option value="Macao">Macao</option> 
              <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option> 
              <option value="Madagascar">Madagascar</option> 
              <option value="Malawi">Malawi</option> 
              <option value="Malaysia">Malaysia</option> 
              <option value="Maldives">Maldives</option> 
              <option value="Mali">Mali</option> 
              <option value="Malta">Malta</option> 
              <option value="Marshall Islands">Marshall Islands</option> 
              <option value="Martinique">Martinique</option> 
              <option value="Mauritania">Mauritania</option> 
              <option value="Mauritius">Mauritius</option> 
              <option value="Mayotte">Mayotte</option> 
              <option value="Mexico">Mexico</option> 
              <option value="Micronesia, Federated States of">Micronesia, Federated States of</option> 
              <option value="Moldova, Republic of">Moldova, Republic of</option> 
              <option value="Monaco">Monaco</option> 
              <option value="Mongolia">Mongolia</option> 
              <option value="Montserrat">Montserrat</option> 
              <option value="Morocco">Morocco</option> 
              <option value="Mozambique">Mozambique</option> 
              <option value="Myanmar">Myanmar</option> 
              <option value="Namibia">Namibia</option> 
              <option value="Nauru">Nauru</option> 
              <option value="Nepal">Nepal</option> 
              <option value="Netherlands">Netherlands</option> 
              <option value="Netherlands Antilles">Netherlands Antilles</option> 
              <option value="New Caledonia">New Caledonia</option> 
              <option value="New Zealand">New Zealand</option> 
              <option value="Nicaragua">Nicaragua</option> 
              <option value="Niger">Niger</option> 
              <option value="Nigeria">Nigeria</option> 
              <option value="Niue">Niue</option> 
              <option value="Norfolk Island">Norfolk Island</option> 
              <option value="Northern Mariana Islands">Northern Mariana Islands</option> 
              <option value="Norway">Norway</option> 
              <option value="Oman">Oman</option> 
              <option value="Pakistan">Pakistan</option> 
              <option value="Palau">Palau</option> 
              <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option> 
              <option value="Panama">Panama</option> 
              <option value="Papua New Guinea">Papua New Guinea</option> 
              <option value="Paraguay">Paraguay</option> 
              <option value="Peru">Peru</option> 
              <option value="Philippines">Philippines</option> 
              <option value="Pitcairn">Pitcairn</option> 
              <option value="Poland">Poland</option> 
              <option value="Portugal">Portugal</option> 
              <option value="Puerto Rico">Puerto Rico</option> 
              <option value="Qatar">Qatar</option> 
              <option value="Reunion">Reunion</option> 
              <option value="Romania">Romania</option> 
              <option value="Russian Federation">Russian Federation</option> 
              <option value="Rwanda">Rwanda</option> 
              <option value="Saint Helena">Saint Helena</option> 
              <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
              <option value="Saint Lucia">Saint Lucia</option> 
              <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option> 
              <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option> 
              <option value="Samoa">Samoa</option> 
              <option value="San Marino">San Marino</option> 
              <option value="Sao Tome and Principe">Sao Tome and Principe</option> 
              <option value="Saudi Arabia">Saudi Arabia</option> 
              <option value="Senegal">Senegal</option> 
              <option value="Serbia and Montenegro">Serbia and Montenegro</option> 
              <option value="Seychelles">Seychelles</option> 
              <option value="Sierra Leone">Sierra Leone</option> 
              <option value="Singapore">Singapore</option> 
              <option value="Slovakia">Slovakia</option> 
              <option value="Slovenia">Slovenia</option> 
              <option value="Solomon Islands">Solomon Islands</option> 
              <option value="Somalia">Somalia</option> 
              <option value="South Africa">South Africa</option> 
              <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option> 
              <option value="Spain">Spain</option> 
              <option value="Sri Lanka">Sri Lanka</option> 
              <option value="Sudan">Sudan</option> 
              <option value="Suriname">Suriname</option> 
              <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option> 
              <option value="Swaziland">Swaziland</option> 
              <option value="Sweden">Sweden</option> 
              <option value="Switzerland">Switzerland</option> 
              <option value="Syrian Arab Republic">Syrian Arab Republic</option> 
              <option value="Taiwan, Province of China">Taiwan, Province of China</option> 
              <option value="Tajikistan">Tajikistan</option> 
              <option value="Tanzania, United Republic of">Tanzania, United Republic of</option> 
              <option value="Thailand">Thailand</option> 
              <option value="Timor-leste">Timor-leste</option> 
              <option value="Togo">Togo</option> 
              <option value="Tokelau">Tokelau</option> 
              <option value="Tonga">Tonga</option> 
              <option value="Trinidad and Tobago">Trinidad and Tobago</option> 
              <option value="Tunisia">Tunisia</option> 
              <option value="Turkey">Turkey</option> 
              <option value="Turkmenistan">Turkmenistan</option> 
              <option value="Turks and Caicos Islands">Turks and Caicos Islands</option> 
              <option value="Tuvalu">Tuvalu</option> 
              <option value="Uganda">Uganda</option> 
              <option value="Ukraine">Ukraine</option> 
              <option value="United Arab Emirates">United Arab Emirates</option> 
              <option value="United Kingdom">United Kingdom</option> 
              <option value="United States">United States</option> 
              <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option> 
              <option value="Uruguay">Uruguay</option> 
              <option value="Uzbekistan">Uzbekistan</option> 
              <option value="Vanuatu">Vanuatu</option> 
              <option value="Venezuela">Venezuela</option> 
              <option value="Viet Nam">Viet Nam</option> 
              <option value="Virgin Islands, British">Virgin Islands, British</option> 
              <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option> 
              <option value="Wallis and Futuna">Wallis and Futuna</option> 
              <option value="Western Sahara">Western Sahara</option> 
              <option value="Yemen">Yemen</option> 
              <option value="Zambia">Zambia</option> 
              <option value="Zimbabwe">Zimbabwe</option>
            </select>
            
          </fieldset>
          </div>
          <div class="clearfix"></div>
          <input style="margin-top:20px;" type="submit" class="bluebtn" value="Save">
      </div>
      {{ Form::close() }}
     <div class="widgets fullwidthwidget shadow clearfix">
       <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addvideoword');" class="bluebtn"><i class="fa fa-plus"></i>Edit</a></div>
       <h1>A Word from {{ $user->firstName }}</h1>
            <div id="w_addvideoword" class="add">
                  {{ View::make("widgets.addfull.addVideoWord",array("user"=>$user,"permissions"=>$permissions))}}
                </div>
                    <div id="w_video_word_full" >
                      
                    </div>
                </div>
               
                <div  class="widgets fullwidthwidget shadow clearfix">
                               <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addbiography');" class="bluebtn"><i class="fa fa-plus"></i>Edit</a></div>
       <div id="w_addbiography" class="add">
                 {{ View::make("widgets.addfull.biography",array("user"=>$user,"permissions"=>$permissions))}}
                </div>
                  <div id="w_biography_full" >
                    {{ View::make("widgets.full.biography",array("user"=>$user,"permissions"=>$permissions))}}
                  </div>
      
            </div>
         <div class="widgets fullwidthwidget shadow customertestimonial clearfix">
             <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addtestimonials');" class="bluebtn"><i class="fa fa-plus"></i>Edit</a></div>
              <h1>Testimonials</h1>
              <div id="w_addtestimonials" class="add">
                 {{ View::make("widgets.addfull.testimonials",array("user"=>$user,"permissions"=>$permissions))}}
                </div>
               
               <div id="w_testimonials_full">
                 
               </div>
         </div> 
          <div class="widgets fullwidthwidget shadow clearfix">
              <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addobjectives');" class="bluebtn"><i class="fa fa-plus"></i>Add Objective</a></div>
              <h1>Objective</h1>
              <div id="w_addobjectives" class="add">
              {{ View::make("widgets.addfull.objectives")}}
              </div>
               <div id="w_objectives_full">
         
              </div>
                
                
              <!-- bottom button holder -->
                
          </div>
          <div class="widgets fullwidthwidget shadow clearfix">
              <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addweights');" class="bluebtn"><i class="fa fa-plus"></i>Add Weight</a></div>
              <h1>Weight</h1>
               
                <div id="w_addweights" class="add">
                {{ View::make("widgets.addfull.weight")}}
                </div>
                <div id="w_weights_full">
              
                </div>
                
          </div>
          
          
          <div class="widgets fullwidthwidget shadow marginleftnone">
              <div class="fltright"><a class="bluebtn" href="javascript:void(0)"  onClick="w_addmeasurements($(this))"><i class="fa fa-plus"></i>Add Body  Measurement</a></div>
              <h1>Body Measurement</h1>
              <div class="bodymeasurements clearfix">
              <div id="w_addmeasurements" class="add">
             
         
                </div>
                
               <div id="w_measurements">
                <div id="w_body">
       
                </div>
                </div>
               

                </div>
            </div>
            <div class="widgets fullwidthwidget shadow marginleftnone">
              <div class="fltright"><a class="bluebtn" href="javascript:void(0)" onClick="w_addpictures($(this))"><i class="fa fa-plus"></i>Add Pictures</a></div>
              <h1>Pictures</h1>
                <div id="w_addpictures" class="add">
                 {{ View::make("widgets.addfull.pictures")}}
                </div>
                <div id="w_pictures_full">
   

                </div>
            </div>
            <div class="widgets fullwidthwidget shadow marginleftnone" style="display:none">
              
              <h1>Past Transactions</h1>
                <table width="100%" border="0" class="tabulardata transactionlist" cellspacing="0" cellpadding="0">
                      <tr>
                        <th width="15%">Transaction Id</th>
                        <th width="20%">Type</th>
                        <th width="25%">Item</th>
                        <th width="15%">Status</th>
                        <th width="25%">Payment method</th>
                      </tr>
                      <tr class="row0">
                        <td>134987</td>
                        <td>workout</td>
                        <td>Marathon essential</td>
                        <td>Paid</td>
                        <td>Master Card</td>
                      </tr>
                      <tr class="row1">
                        <td>134987</td>
                        <td>workout</td>
                        <td>Marathon essential</td>
                        <td>Paid</td>
                        <td>Master Card</td>
                      </tr>
                      <tr class="row0">
                        <td>134987</td>
                        <td>workout</td>
                        <td>Marathon essential</td>
                        <td>Paid</td>
                        <td>Master Card</td>
                      </tr>
                      <tr class="row1">
                        <td>134987</td>
                        <td>workout</td>
                        <td>Marathon essential</td>
                        <td>Paid</td>
                        <td>Master Card</td>
                      </tr>
                      <tr class="row0">
                        <td>134987</td>
                        <td>workout</td>
                        <td>Marathon essential</td>
                        <td>Paid</td>
                        <td>Master Card</td>
                      </tr>
                    </table>
                
                <!-- bottom button holder -->
                <div class="btmbuttonholder">
                  <span class="hrborder"></span>
                  <a href="#" class="greybtn">More Transactions</a>
                </div>
            </div>
            
        </div>
</section>
@endsection

@section('scripts')
<script>callWidget("w_video_word_full");</script> 
<script>callWidget("w_testimonials_full");</script> 
<script>callWidget("w_biography_full");</script> 
<script>callWidget("w_weights_full");</script> 
<script>callWidget("w_objectives_full");</script> 
<script>callWidget("w_pictures_full");</script> 
<script>
  $("#country").val('{{ Auth::user()->country }}');
  $("#province").val('{{ Auth::user()->province }}');
  $('#country').trigger("chosen:updated");
  $('#province').trigger("chosen:updated");
  $
</script>


<script type="text/javascript">
$(document).ready(function(){ $("#m_exercises").addClass('active'); });
</script>



@endsection