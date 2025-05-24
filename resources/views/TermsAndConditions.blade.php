@php
    use App\Http\Libraries\Helper;
@endphp
@php
    $layout = Helper::getDeviceTypeCookie() === 'ios' ? 'layouts.trainer' : 'layouts.frontEnd';
@endphp
@extends($layout)
{{ HTML::style('css/homepage.css') }}
<style>
    .termsWrapper ul {
        list-style-type: disc;
        margin-left: 20px;
        /* Optional: Adds indentation for better aesthetics */
    }
</style>
@section('content')
    <section id="termsConditions" style="font-family: Arial, sans-serif; line-height: 1.8; margin: 20px; margin-top: 60px">
        <div class="termsWrapper p-20" style="max-width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
            <p style="text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 10px;"><strong>TERMS OF SERVICE</strong></p>
            <p style="text-align: center; font-size: 14px; color: #555;"><strong>Last Updated as of </strong> August 23, 2024</p>
            <h4>
                1. Definitions and Interpretation
            </h4>
            <p>
                Unless otherwise defined herein, or the context otherwise dictates, capitalized terms used in the Terms of
                Service shall have the indicated meanings set
                forth in Exhibit “A” attached hereto.
            </p>
            <h4>
                2. Acceptance
            </h4>
            <p>
                a. By directly or indirectly accessing or making use of the Services of Trainer Workout Inc (the
                “<strong>Vendor</strong>”), and/or by clicking the
                acceptance button, you: (i) signify your agreement to be bound by the terms and conditions set out in this
                terms of service (including its Exhibit)
                incorporated herein by reference (collectively, the “<strong>Terms of Service</strong>”); and (ii) represent
                and warrant that you are older than 18 years
                of age and that you have, and at all times shall have, the necessary power, capacity and authority to enter
                into, abide by, comply with and perform your
                obligations under the Terms of Service.
            </p>
            <p>
                b. The Vendor reserves the right to, at its sole discretion, amend the Terms of Service at any time and
                without notice, the most current version of which
                shall always be available at http:<strong>//</strong>trainer-workout.com/legal.aspx. You acknowledge and
                agree that the continued use of the Services by you
                or your Agents, following any amendment of the Terms of Service, shall signify your assent to, and
                acceptance of, such amended terms and conditions.
            </p>
            <p>
                c. Subject to the Terms of Service, if you do not agree to the Terms of Service, or any subsequently amended
                term or condition thereof, you and your Agents
                cannot use the Services, and any Terms of Service previously entered into must forthwith be terminated by
                you pursuant to Section 10(a).
            </p>
            <h4>
                3. Special Consents and Acknowledgements
            </h4>
            <p>
                a. YOU ACKNOWLEDGE AND AGREE THAT:
            </p>
            <p>
                i. IF YOU HAVE AN EMERGENCY, HAVE AN URGENT HEALTH CONCERN OR NEED TO OBTAIN MEDICAL ADVICE, YOU SHOULD
                REFRAIN FROM USING THE SERVICES AND THE CONTENT AND
                SHOULD IMMEDIATELY CONTACT YOUR PHYSICIAN OR GO TO THE NEAREST HOSPITAL;
            </p>
            <p>
                ii. THE INFORMATION CONTAINED WITHIN THE SERVICES AND THE CONTENT IS INTENDED TO BE GENERAL IN NATURE,
                NOTHING CONTAINED WITHIN THE SERVICES OR THE CONTENT
                CONSTITUTES MEDICAL ADVICE AND YOU SHOULD NOT RELY ON ANYTHING CONTAINED WITHIN THE SERVICES OR THE CONTENT
                AS A SUBSTITUTE FOR APPROPRIATE AND TIMELY
                CONTACT WITH YOUR PHYSICIAN;
            </p>
            <p>
                iii. THE VENDOR DOES NOT MAKE ANY REPRESENTATIONS OR WARRANTIES WITH RESPECT TO TRAINERS, INCLUDING WITHOUT
                LIMITATION THE QUALITY OR CERTIFICATION LEVELS
                THEREOF, AND THAT INTERACTIONS WITH TRAINERS THROUGH THE SERVICES OR OTHERWISE IS AT YOUR SOLE DISCRETION
                AND RISK;
            </p>
            <p>
                iv. YOU SHOULD NEVER CHANGE OR STOP ANY COURSE OF MEDICAL TREATMENT WITHOUT FIRST CONSULTING YOUR PHYSICIAN;
                AND
            </p>
            <p>
                v. PARTICIPATING IN AN EXERCISE PROGRAM OR DIET CAN CAUSE INJURY, AND YOU ELECT TO DO SO ENTIRELY AT YOUR
                OWN RISK.
            </p>
            <h4>
                4. License to Use Services
            </h4>
            <p>
                a. Subject to your compliance with the terms and conditions of the Terms of Service, the Vendor hereby
                grants to you a revocable, personal, non-exclusive,
                non-sublicensable, non-assignable and non-transferable license to use the Services procured and/or purchased
                by you, or for you, exclusively in the manner
                set out in the Terms of Service.
            </p>
            <p>
                b. All right, title, interest, ownership rights and intellectual property rights in and to the Services and
                the trademarks of the Vendor, are and shall
                remain the property of the Vendor and its licensors, as applicable.
            </p>
            <p>
                c. The Vendor reserves all rights to the Services not expressly granted to you herein, and without limiting
                the generality of the foregoing, nothing in the
                Terms of Service grants to you, by implication, estoppel, or otherwise, any license or right to use the
                Services, any Content other than Your Content
                and/or the Vendor’s name, domain names, trademarks, logos, or other distinctive brand features, other than
                as expressly set out in the Terms of Service.
            </p>
            <h4>
                5. Information and Access IDs
            </h4>
            <p>
                a. In order to use the Services, you must provide certain information through the Services, including
                without limitation your full legal name, physical
                address, email address and phone number. If you are a TSR Customer you may furthermore be asked to choose a
                Usage Plan to subscribe to and disclose your
                credit card details and certain other information. You represent and warrant that all information you
                provide to the Vendor through the Services, and
                otherwise, shall be true, accurate, current and complete, and you shall update such information as necessary
                to maintain its truth and accuracy. You
                furthermore represent and warrant that at no point shall you impersonate any person or entity or
                misrepresent any affiliation of a person or entity.
            </p>
            <p>
                b. You acknowledge and agree that you shall: (i) maintain the security and confidentiality of your Access
                IDs; (ii) use commercially reasonable efforts to
                prevent unauthorized access to, or use of, the Services (iii) notify the Vendor promptly of any unauthorized
                access to, or use of the Services; (iv) not
                share your Access IDs with any other person unless agreed to in writing by the Vendor; (v) if you are a TSR
                Customer, ensure that only the Trainers and
                Trainees who have been authorised to do so obtain Access IDs from the Vendor, subject to, and in compliance
                with, the Usage Plan you subscribe to at such
                time; and (vi) if you are a TSR Customer, ensure that the Access IDs are not shared between any Trainers,
                Trainees and/or third-parties, unless agreed to
                in writing by the Vendor.
            </p>
            <h4>
                6. Obligations Specific to TSR Customers
            </h4>
            <p>
                a. If you are a TSR Customer, then you acknowledge and agree that:
            </p>
            <p>
                i. UNTIL THE TERMS OF SERVICE IS TERMINATED BY YOU OR THE VENDOR IN ACCORDANCE WITH SECTION 10, YOU SHALL
                PAY TO THE VENDOR MONTHLY OR ANNUAL FEES IN
                ADVANCE BASED ON THE USAGE PLAN SUBSCRIBED TO BY YOU DURING THE APPLICABLE PERIOD AND ALL IN-APPLICATION
                PURCHASES MADE BY YOU AND YOUR AGENTS DURING SUCH
                PERIOD, CALCULATED IN ACCORDANCE WITH THE FEE SCHEDULE (collectively, the “<strong>Fees</strong>”);
            </p>
            <p>
                ii. THE FEES SHALL BE PAID BY CREDIT CARD OR IN ANOTHER FORM OF IMMEDIATELY AVAILABLE FUNDS ACCEPTABLE TO
                THE VENDOR, ACTING REASONABLY, AND IF YOU PROVIDE
                YOUR CREDIT CARD DETAILS THROUGH THE SERVICES OR OTHERWISE, YOU AGREE TO THE VENDOR CHARGING THE FEES TO
                YOUR CREDIT CARD WITHOUT REQUIRING ANY FURTHER
                NOTICE TO, OR CONSENT FROM, YOU, AND YOU FURTHERMORE REPRESENT AND WARRANT THAT SUCH FEE PAYMENTS SHALL BE
                MADE WHEN DUE;
            </p>
            <p>
                iii. if you fail to pay Fees when due, the Vendor shall be entitled to take any action set out in Section
                10(b), including without limitation changing your
                Usage Plan to a Trial Usage Plan, and all overdue Fees shall accrue interest at the rate of 10% per annum,
                or at the highest legal interest rate, if less,
                and you shall reimburse the Vendor for all expenses (including reasonable attorneys’ fees) incurred by the
                Vendor to collect any amount that is not paid
                when due;
            </p>
            <p>
                iv. you shall be responsible for any and all currency conversion charges as well as sales, service,
                value-added, use, excise, consumption and any other
                taxes, duties and charges of any kind, if any, imposed by any federal, provincial or local governmental
                entity on any Fees other than any taxes imposed on,
                or with respect to, the Vendor’s income;
            </p>
            <p>
                v. notwithstanding termination of the Terms of Service, you shall not be entitled to a refund from the
                Vendor for any Fees or any pro rata portion of any
                Fees paid or payable to the Vendor pursuant to the Terms of Service: (A) in respect of any monthly billing
                cycle that had already commenced at the date of
                such termination, if you are subscribed to a monthly Usage Plan; and (B) in respect of any annual billing
                cycle that had already commenced at the date of
                such termination, if you are subscribed to an annual Usage Plan (by way of example, if you subscribe for an
                annual Usage Plan on July 5, 2024 and terminate
                the Terms of Service on July 10, 2024, then you shall be liable to pay the annual Fee for July 5, 2024 to
                July 4, 2024 and the annual Fee for July 5, 2024
                to July 4, 2024, and you shall not be entitled to a refund from the Vendor in relation to any such Fees);
            </p>
            <p>
                vi. NOTWITHSTANDING ANY OTHER TERM OF THE TERMS OF SERVICE, THE VENDOR SHALL BE ENTITLED TO AMEND THE FEE
                SCHEDULE FROM TIME TO TIME, BY GIVING YOU THIRTY
                (30) DAYS WRITTEN NOTICE OF SUCH AMENDMENT, WHICH NOTICE SHALL AMEND THE FEE SCHEDULE ACCORDINGLY, AND SHALL
                BE BINDING ON YOU, AS OF YOUR NEXT MONTHLY OR
                ANNUAL BILLING CYCLE, AS APPLICABLE (the “<strong>Amended Fee Schedule</strong>”).
            </p>
            <p>
                vii. IF YOU AGREE TO THE TERMS OF SERVICE AS AMENDED FROM TIME TO TIME BUT DO NOT AGREE TO A SPECIFIC
                AMENDED FEE SCHEDULE, YOU AND ALL TRAINERS AND
                TRAINEES WHO HAVE BEEN AUTHORISED TO ACCESS AND USE THE SERVICES PROCURED BY YOU CANNOT USE THE SERVICES
                AFTER THE END OF YOUR CURRENT MONTHLY OR ANNUAL
                BILLING CYCLE, AS APPLICABLE, AND THE TERMS OF SERVICE MUST BE TERMINATED BY YOU PURSUANT TO SECTION 10(a)
                ON OR BEFORE THE LAST DAY OF YOUR CURRENT
                MONTHLY OR ANNUAL BILLING CYCLE, AS APPLICABLE;
            </p>
            <p>
                viii. you acknowledge and agree that the Vendor may, from time to time in its sole discretion, offer Trial
                Usage Plans, and that: (i) you shall comply with
                any and all additional terms, restrictions and/or limitations imposed by the Vendor on any such Trial Usage
                Plan; and (ii) the Vendor may at any time and
                for any reason, without liability to you or any other person alter, amend, modify or cancel any aspect of
                such Trial Usage Plans, including without
                limitation, the term, access rights, Fees, nature, scope, features, functionality, operation and Content
                associated therewith; and
            </p>
            <p>
                ix. notwithstanding any other term of the Terms of Service, including without limitation Section 4(a), no
                TSR Customer, Trainer or Trainee shall be allowed
                or permitted to access or make use of the Services, until such TSR Customer, Trainer or Trainee has entered
                into the most current version of the Terms of
                Service.
            </p>
            <h4>
                7. General Use of the Services - Permissions and Restrictions
            </h4>
            <p>
                a. You shall not use the Services to violate, infringe or appropriate any person’s privacy rights, publicity
                rights, defamation rights, copyrights,
                trademark rights, contractual rights or any other legal right.
            </p>
            <p>
                b. You shall not copy, modify, alter, change, translate, decrypt, obtain or extract the source code of,
                create derivative works from, reverse engineer,
                reverse assemble, decompile, disassemble or reverse compile any part of the Services.
            </p>
            <p>
                c. You shall not use or launch any automated system, including without limitation any “robot” or “spider”
                that accesses the Services. You shall not collect
                or harvest any information in an automatic, bulk or systematic way, including any personally identifiable
                information, from the Services or Content.
            </p>
            <p>
                d. You shall not interfere with, or attempt to interfere with, the Services or the networks or services
                connected to the Services, whether through the use
                of viruses, bots, worms, or any other computer code, file or program that interrupts, destroys or limits the
                functionality of any computer software or
                hardware, or otherwise permit such activity.
            </p>
            <p>
                e. You shall use the Services in accordance with the Terms of Service and any and all applicable laws and
                regulations. The Vendor reserves the right to
                investigate and take appropriate action against anyone who, in the Vendor’s sole discretion, violates this
                provision, including without limitation, taking
                legal action or any action set out in Section 10(b).
            </p>
            <h4>
                8. Content
            </h4>
            <p>
                a. Unless otherwise expressly set out in the Terms of Service, all right, title, interest, ownership rights
                and intellectual property rights in and to Your
                Content, and your trademarks, are and shall remain your property, your Agents’ property and/or the property
                of its or their respective licensors, as
                applicable. Notwithstanding the foregoing, you hereby acknowledge and agree that some or all of Your Content
                may be inaccessible on or through the
                Services, including without limitation, due to an event set out in Sections 10 and 16(a)(iv).
            </p>
            <p>
                b. You hereby acknowledge and agree that Your Content may be disclosed to others in accordance with the
                selected privacy settings, utilized features and
                general functionality of the Services, and as such may be accessible to others including without limitation
                to: (i) your Agents; (ii) other users of the
                Services; (iii) the Vendor and the Vendor’s Agents; (iv) third-party service providers and their Agents; and
                (v) any other person to whom any of the
                foregoing persons have granted access to Your Content. The Vendor shall take commercially reasonable steps
                to ensure that Content is not shared between TSR
                Customers (unless you select otherwise), but you acknowledge and agree that the Vendor cannot and does not
                guarantee any confidentiality with respect to
                Your Content whatsoever.
            </p>
            <p>
                c. You represent and warrant that you own or have all of the necessary licenses, rights, consents and
                permissions to use and authorize the Vendor to use
                all patent, trademark, trade secret, copyright and other proprietary rights in and to any and all of Your
                Content, to permit inclusion and use of Your
                Content in the manner contemplated by the Services and the Terms of Service without violating, infringing or
                appropriating any person’s privacy rights,
                publicity rights, copyrights, trademark rights, contractual rights or any other legal right. You hereby
                grant the Vendor an irrevocable, perpetual,
                worldwide, royalty-free, sublicensable and transferable license to use, host, reproduce, distribute,
                license, display, perform, modify and create
                derivative works of Your Content, exclusively for the purpose of providing the Services.
            </p>
            <p>
                d. The Vendor reserves the right to, with or without notice, remove Your Content for any reason whatsoever,
                including without limitation any of Your
                Content that: (i) allegedly infringes on another’s intellectual property rights; (ii) is patently offensive,
                exploitative, criminal, or promotes racism,
                bigotry, hatred or physical harm of any kind against any group or individual; (iii) is considered adult or
                pornographic; (iv) harasses or advocates
                harassment of another person, or promotes illicit or criminal activity; (v) solicits personal information
                from anyone under 18; (vi) constitutes or
                promotes information that you know is false or misleading or promotes illegal activities or conduct that is
                abusive, threatening, obscene, defamatory or
                libelous; (vii) involves the transmission of “junk mail,” “chain letters,” or unsolicited mass mailing,
                instant messaging, or “spamming”; or (viii)
                interferes or attempts to interfere with the proper working of the Services, disrupts or attempts to disrupt
                the normal flow of dialogue with an excessive
                number of messages (flooding attack) to the Services, prevents or attempts to prevent others from using the
                Services or otherwise negatively affects other
                persons’ ability to use the Services.
            </p>
            <p>
                e. You acknowledge and agree that the Vendor typically does not, and has no obligation to, review, censor or
                edit Your Content or any other Content, or the
                contents of any third-party site or application, but may at the Vendor’s sole discretion do so at any time.
                The Vendor does not make any guarantees about
                the accuracy, currency, reliability, suitability, effectiveness, quality or correct use of Your Content
                (including without limitation any fitness or health
                tracking data, nutritional data or other data or information that you may make available to the Services
                from a third party site, application or product
                and any information you upload or otherwise make available through any document management features of the
                Services). You acknowledge and agree that the
                Vendor does not endorse Your Content or any other Content, the contents of any third-party site or
                application or any opinion, recommendation, or advice
                expressed therein, and the Vendor expressly disclaims any and all liability in connection therewith. You
                acknowledge and agree that the Vendor assumes no
                responsibility for the content, privacy policies, or practices of any third-party, including without
                limitation, any third-party service provider which may
                host Content.
            </p>
            <p>
                f. The Vendor contracts a third party to store Content, including Your Content, and, while these Terms of
                Service are in effect and you have an active
                account for access to the Services, will use commercially reasonable efforts to store and back up such
                Content at reasonable intervals as may be determined
                by the Vendor in its sole discretion. However, you should make your own interim back-ups of all of Your
                Content, including without limitation any and all
                Content you upload or otherwise make available through the document management features of the Services.
                Following any cancellation of your account, the
                Vendor is under no obligation to store Your Content and may delete your account and Your Content immediately
                upon such cancellation. Notwithstanding
                anything to the contrary set forth in these Terms of Service, the Vendor shall have no liability or
                responsibility for any loss or damage, however caused,
                arising from any loss of Your Content.
            </p>
            <h4>
                9. Feedback
            </h4>
            <p>
                a. You acknowledge and agree that any ideas, suggestions, concepts, processes, techniques, enhancement
                requests, recommendations, test results, data,
                information and other output or feedback which you and your Agents provide to the Vendor related to the
                Services, the Vendor or the Vendor’s business,
                including without limitation in any user forums made available by the Vendor, and any and all metadata,
                anonymized data, raw data and other information
                reflecting the access or use of the Services by you and your Agents (“<strong>Feedback</strong>”), shall
                become the Vendor’s property without any
                compensation or other consideration payable to you or your Agents, and you do so of your own free will and
                volition. The Vendor may or may not, in its sole
                discretion, use the Feedback, commercialize the Feedback and/or incorporate the Feedback in whatever form or
                derivative into the Services, its other
                products and services, or any future versions or derivatives of the foregoing. You shall and do hereby
                assign, and shall cause the assignment of, all
                rights on a worldwide basis in perpetuity to the Vendor in any and all Feedback and, as applicable, shall
                and do hereby waive, and shall cause the waiver
                of, all moral rights therein and thereto.
            </p>
            <h4>
                10. Termination, Modification and Suspension
            </h4>
            <p>
                a. SUBJECT TO SECTION 6(a)(v), YOU MAY TERMINATE THE TERMS OF SERVICE AT ANY TIME AND FOR ANY REASON BY
                INITIATING AND COMPLETING THE ACCOUNT CANCELLATION
                PROCESS SET OUT by sending an email at <a href="mailto:info@trainer-workout.com">info@trainer-workout.com</a>
                AND DISCONTINUING YOUR USE OF THE SERVICES.
            </p>
            <p>
                b. THE VENDOR MAY AT ITS SOLE DISCRETION AT ANY TIME AND FOR ANY REASON, WITH OR WITHOUT NOTICE: (I) BAN ANY
                COMPUTER OR DEVICE FROM ACCESSING THE
                SERVICES; (II) PREVENT ANY PERSON FROM ACCESSING THE SERVICES; (III) TERMINATE, MODIFY, SUSPEND OR
                DISCONTINUE ANY USAGE PLAN, ACCESS ID, TERMS OF SERVICE
                OR SERVICES; (IV) REMOVE ANY OF YOUR CONTENT; AND/OR (V) CHANGE YOUR USAGE PLAN TO A TRIAL USAGE PLAN.
            </p>
            <h4>
                11. Warranty Disclaimer
            </h4>
            <p>
                a. THE SERVICES IS PROVIDED ON AN “AS IS” AND “AS AVAILABLE” BASIS AND YOU ACKNOWLEDGE AND AGREE THAT YOUR
                USE OF THE SERVICES AND ALL CONTENT FORMING PART
                OF OR RELATED TO THE SERVICES, AND ANY AND ALL INTERACTIONS BETWEEN YOU AND TRAINERS THROUGH THE SERVICES OR
                OTHERWISE, SHALL IN ALL CASES BE AT YOUR SOLE
                DISCRETION AND RISK. TO THE FULLEST EXTENT PERMITTED BY LAW, THE VENDOR AND ITS OFFICERS, DIRECTORS,
                EMPLOYEES, AND AGENTS, DISCLAIM ALL WARRANTIES AND
                CONDITIONS, EXPRESS OR IMPLIED OR STATUTORY, IN CONNECTION WITH THE SERVICES AND YOUR USE THEREOF, INCLUDING
                WITHOUT LIMITATION ANY IMPLIED WARRANTIES OR
                CONDITIONS OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, ACCURACY, COMPLETENESS, PERFORMANCE,
                HARDWARE COMPATIBILITY, QUIET ENJOYMENT, TITLE AND
                NON-INFRINGEMENT. NO ADVICE OR INFORMATION, WHETHER WRITTEN OR ORAL, OBTAINED FROM THE VENDOR OR ITS
                OFFICERS, DIRECTORS, EMPLOYEES OR AGENTS OR THROUGH
                THE SERVICES SHALL CREATE ANY WARRANTY OR CONDITION NOT EXPRESSLY STATED IN THE TERMS OF SERVICE.
            </p>
            <p>
                b. THE VENDOR MAKES NO WARRANTIES OR REPRESENTATIONS OF ANY KIND ABOUT THE ACCURACY OR COMPLETENESS OF ANY
                SITES, APPLICATIONS, PAGES OR SERVICES LINKED TO
                OR THROUGH THE SERVICES. THE VENDOR DOES NOT WARRANT, ENDORSE, GUARANTEE, OR ASSUME RESPONSIBILITY FOR, ANY
                PRODUCT OR SERVICE ADVERTISED OR OFFERED BY A
                THIRD-PARTY THROUGH THE SERVICES OR ANY HYPERLINKED SERVICE OR WEBSITE FEATURED IN ANY USER SUBMISSION,
                BANNER, SPONSOR MESSAGE OR OTHER ADVERTISING. THE
                VENDOR SHALL NOT BE A PARTY TO OR IN ANY WAY BE RESPONSIBLE FOR MONITORING ANY TRANSACTION BETWEEN YOU AND
                ANY OTHER USER OF THE SERVICES OR ANY
                THIRD-PARTY PROVIDERS OF ANY PRODUCT OR SERVICE.
            </p>
            <h4>
                12. Limitation of Liability
            </h4>
            <p>
                a. IN NO EVENT SHALL THE VENDOR AND ITS OFFICERS, DIRECTORS, EMPLOYEES, OR AGENTS DIRECTLY OR INDIRECTLY, BE
                LIABLE TO YOU FOR ANY INDIRECT, INCIDENTAL,
                SPECIAL, PUNITIVE OR CONSEQUENTIAL DAMAGES OR LOSS OF REVENUE, INCOME, PROFIT, REPUTATION, GOODWILL OR
                CUSTOMERS WHATSOEVER RESULTING FROM YOUR USE OF OR
                ACCESS TO THE SERVICES OR ANY CONTENT, INCLUDING WITHOUT LIMITATION RESULTING FROM ANY: (I) ERRORS,
                MISTAKES, INACCURACIES OR OMISSIONS IN THE SERVICES;
                (II) PERSONAL INJURY OR PROPERTY DAMAGE, OF ANY NATURE WHATSOEVER, RESULTING FROM YOUR ACCESS TO OR USE OF
                THE SERVICES OR ANY CONTENT; (III) UNAUTHORIZED
                ACCESS TO OR USE OF THE VENDOR’S SERVERS AND/OR ANY AND ALL PERSONAL INFORMATION OR OTHER INFORMATION STORED
                THEREIN OR THEREON; (IV) INTERRUPTION OR
                CESSATION OF TRANSMISSION TO OR FROM THE SERVICES; (V) TERMINATION OF ACCESS TO THE SERVICES OR REMOVAL OF
                ANY CONTENT BY THE VENDOR; (VI) BUGS, VIRUSES,
                TROJAN HORSES, OR THE LIKE, WHICH MAY BE TRANSMITTED TO OR THROUGH THE SERVICES BY ANY THIRD-PARTY; OR (VII)
                ERRORS, MISTAKES, INACCURACIES OR OMISSIONS IN
                ANY CONTENT OR FOR ANY LOSS OR DAMAGE OF ANY KIND INCURRED AS A RESULT OF YOUR USE OF ANY CONTENT, WHETHER
                THE FOREGOING IS BASED ON WARRANTY, CONTRACT,
                TORT, MISREPRESENTATION OR ANY OTHER LEGAL THEORY, AND WHETHER OR NOT THE AFFECTED PARTIES ARE AWARE OR HAVE
                BEEN ADVISED OF THE POSSIBILITY OF SUCH
                DAMAGES.
            </p>
            <p>
                b. THE TOTAL AGGREGATE LIABILITY OF THE VENDOR FOR ANY AND ALL CLAIMS RELATED TO THE TERMS OF SERVICE AND/OR
                USE OF, OR ACCESS TO, THE SERVICES SHALL BE
                LIMITED TO DIRECT DAMAGES SUFFERED BY YOU, NOT TO EXCEED THE LESSER OF CAD $100.00 AND THE AMOUNT ACTUALLY
                RECEIVED BY THE VENDOR FROM YOU PURSUANT TO THE
                TERMS OF SERVICE DURING THE THREE (3) MONTHS IMMEDIATELY PRECEDING THE EVENT GIVING RISE TO THE CLAIM. ANY
                ACTION COMMENCED AGAINST THE VENDOR FOR ANY AND
                ALL CLAIMS RELATED TO THE TERMS OF SERVICE, SHALL BE BROUGHT WITHIN TWELVE (12) MONTHS AFTER SUCH CAUSE OF
                ACTION SHALL HAVE FIRST ARISEN.
            </p>
            <p>
                c. THE VENDOR DOES NOT CONTROL CONTENT AND DOES NOT GUARANTEE THE ACCURACY OR INTEGRITY OF SUCH CONTENT. YOU
                SPECIFICALLY ACKNOWLEDGE AND AGREE THAT THE
                VENDOR SHALL NOT BE LIABLE IN ANY WAY FOR ANY CONTENT INCLUDING BUT NOT LIMITED TO ANY ERRORS OR OMISSIONS
                OR THE DEFAMATORY, OFFENSIVE, OR ILLEGAL CONDUCT
                OF ANY THIRD-PARTY AND THAT THE RISK OF HARM OR DAMAGE FROM THE FOREGOING RESTS ENTIRELY WITH YOU.
            </p>
            <p>
                d. YOU ACKNOWLEDGE AND AGREE THAT, WITH RESPECT TO ANY DISPUTE RELATED TO THE TERMS OF SERVICE YOU HEREBY
                GIVE UP YOUR RIGHT TO (I) HAVE A TRIAL BY JURY;
                AND (II) PARTICIPATE AS A MEMBER OF A CLASS OF CLAIMANTS, IN ANY LAWSUIT INCLUDING BUT NOT LIMITED TO CLASS
                ACTION LAWSUITS INVOLVING ANY DISPUTE RELATED
                TO THE TERMS OF SERVICE.
            </p>
            <p>
                e. ALL FOREGOING LIMITATIONS AND EXCLUSIONS OF LIABILITY SHALL APPLY TO THE FULLEST EXTENT PERMITTED BY LAW
                IN THE APPLICABLE JURISDICTION.
            </p>
            <h4>
                13. Indemnity by You
            </h4>
            <p>
                YOU AGREE TO DEFEND, INDEMNIFY AND HOLD HARMLESS THE VENDOR AND ITS OFFICERS, DIRECTORS, EMPLOYEES AND
                AGENTS, FROM AND AGAINST ANY AND ALL CLAIMS,
                DAMAGES, OBLIGATIONS, LOSSES, LIABILITIES, COSTS, DEBT, AND EXPENSES (INCLUDING BUT NOT LIMITED TO
                ATTORNEY'S FEES) ARISING FROM: (I) YOUR OR YOUR AGENTS’
                USE OF OR ACCESS TO THE SERVICES; (II) ANY THIRD PARTY USE OF, OR ACCESS TO, YOUR ACCESS ID; (III) YOUR OR
                YOUR AGENTS’ VIOLATION OF ANY TERM OF THE TERMS
                OF SERVICE; (IV) YOUR OR YOUR AGENTS’ VIOLATION OF ANY THIRD-PARTY RIGHT, INCLUDING WITHOUT LIMITATION ANY
                COPYRIGHT, PROPERTY OR PRIVACY RIGHT; OR (V) ANY
                CLAIM THAT YOUR CONTENT CAUSED DAMAGE TO A THIRD-PARTY. THIS DEFENCE AND INDEMNIFICATION OBLIGATION SHALL
                SURVIVE THE TERMS OF SERVICE AND YOUR AND YOUR
                AGENTS’ USE OF THE SERVICES.
            </p>
            <h4>
                14. Assignment
            </h4>
            <p>
                The Terms of Service, and any rights and licenses granted hereunder, may not be transferred, assigned or
                sold by you, but may be transferred, assigned and
                sold by the Vendor without restriction.
            </p>
            <h4>
                15. Data Usage and Charges
            </h4>
            <p>
                The Services may use information and data transmission networks operated by third-parties to send data,
                information and Content from a computer or device
                to the Vendor’s servers, and to serve data, information and Content back to such computer or device.
                Depending on your wired or wireless data or similar
                plan with such third-party operators, you may incur charges from such third-party operators for use of its
                information and data transmission networks. You
                are solely responsible for any and all costs, including without limitation wireless and cellular data costs,
                you may incur as a result of the usage of the
                Services and/or as a result of data, information and Content submitted or received by your computer or
                device through the Services.
            </p>
            <h4>
                16. Updates and Availability of Services
            </h4>
            <p>
                a. You acknowledge and agree that:
            </p>
            <p>
                i. the Vendor may from time to time, at its sole discretion, make Updates available to you, but is under no
                obligation to do so;
            </p>
            <p>
                ii. Updates may alter, amend or modify the Services, including without limitation, its nature, scope,
                features, functionality, operation and Content, and
                you agree to such Updates being made to the Services from time to time, at the sole discretion of the
                Vendor;
            </p>
            <p>
                iii. Updates may require you to enter into new terms of service or, alternatively, shall be subject to all
                terms and conditions of the Terms of Service;
                and
            </p>
            <p>
                iv. there may be occasions when the Services may be interrupted, including without limitation, for scheduled
                maintenance or upgrades, for emergency
                repairs, or due to failure of telecommunications links and/or equipment.
            </p>
            <h4>
                17. General
            </h4>
            <p>
                a. Nothing in the Terms of Service shall be construed to constitute the Vendor and yourself as principal and
                agent, employer and employee, franchisor and
                franchisee, partners, joint venturers, co-owners or otherwise as participants in a joint undertaking. You
                shall have no right or authority to assume or
                create any obligation of any kind, express or implied, on behalf of the Vendor or waive any right, interest
                or claim that the Vendor may have, other than
                as expressly set out herein, or with the prior written consent of the Vendor.
            </p>
            <p>
                b. If there is any dispute between you and the Vendor about or involving the Services or the Terms of
                Service, you hereby acknowledge and agree that the
                dispute shall be governed by and construed in accordance with the laws of the Province of British Columbia,
                Canada, without regard to its conflict of law
                provisions. You hereby agree to submit to the exclusive jurisdiction of the courts in Vancouver, British
                Columbia with respect to any claim, proceeding or
                action relating to or otherwise arising out of the Terms of Service or your access to or use of the
                Services, howsoever arising, provided always that the
                Vendor may seek and obtain injunctive relief (or an equivalent type of urgent legal relief) in any
                jurisdiction.
            </p>
            <p>
                c. The Terms of Service constitutes the whole legal agreement between you and the Vendor and governs your
                use of the Services (but excluding any services
                which the Vendor may provide to you under a separate written agreement), and completely replaces and
                supersedes any prior and contemporaneous agreements
                between you and the Vendor in relation to the Services. Notwithstanding the foregoing, you and the Vendor
                shall be entitled to enter into an additional
                superseding agreement which by its terms may expressly alter, amend or terminate the Terms of Service.
            </p>
            <p>
                d. If any provision of the Terms of Service is deemed invalid by a court of competent jurisdiction, the
                invalidity of such provision shall not affect the
                validity of the remaining provisions of the Terms of Service, which shall remain in full force and effect.
                No waiver of any term of the Terms of Service
                shall be deemed a further or continuing waiver of such term or any other term, and the Vendor’s failure to
                assert any right or provision under the Terms of
                Service shall not constitute a waiver of such right or provision.
            </p>
            <p>
                e. Sections 4(b), 4(c), 6(a)(i), 6(a)(ii), 6(a)(iii), 6(a)(iv), 6(a)(v), 7(a), 7(b), 7(c), 7(d), 8 through
                14, 17 and such other provisions of the Terms of
                Service which by implication from its nature is intended to survive the termination or expiration of the
                Terms of Service, shall survive termination or
                expiration of the Terms of Service.
            </p>
            <h4>
                18. Contact the Vendor
            </h4>
            <p>
                You may direct any questions, complaints or claims with respect to the general functionality and operation
                of the Services to the Vendor at
                info@trainer-workout.com.
            </p>
            <p>
                <strong><u>EXHIBIT “A”</u></strong>
            </p>
            <p>
                <strong>DEFINITIONS AND INTERPRETATION</strong>
            </p>
            <p>
                “<strong>Access IDs</strong>” means the unique identification names and corresponding passwords assigned to
                a TSR Customer and the Trainers and Trainees
                who have been authorised to access and use the Services procured by such TSR Customer, and allowing such
                persons to access and use the Services, and
                “Access ID” shall be construed accordingly.
            </p>
            <p>
                “<strong>Agents</strong>” means, with respect to a Party, such Party’s agents, employees, consultants,
                contractors and/or other authorized representatives,
                and “Agent” shall be construed accordingly.
            </p>
            <p>
                “<strong>Content</strong>” means any material posted on, submitted on, uploaded to, made available to and/or
                appearing on the Services, including without
                limitation, data, information, text, graphics, photos, videos, charts, or location information.
            </p>
            <p>
                “<strong>Fee Schedule</strong>” means the Vendor’s fee schedule, as provided by the Vendor to certain users
                of the Services from time to time, setting out
                the cost of the respective Usage Plans, and/or the cost of the respective In-Application Purchases, as
                applicable.
            </p>
            <p>
                “<strong>In-Application Purchases</strong>” means the supplementary products, services and/or functionality
                offered for sale by the Vendor, which is not
                otherwise included in the cost of the Usage Plan subscribed to by a TSR Customer.
            </p>
            <p>
                “<strong>Parties</strong>” means the parties to the Terms of Service, and “Party” shall be construed
                accordingly.
            </p>
            <p>
                “<strong>Services</strong>” means the services offered or made available by the Vendor, including without
                limitation the Trainer Workout software as a
                service platform, and any website, application or widget associated therewith, as modified by the Vendor by
                way of Updates from time to time.
            </p>
            <p>
                “<strong>Trainee</strong>” means a person directly or indirectly accessing or making use of the Services
                procured by a TSR Customer as a trainee, including
                without limitation, for the purpose of accessing, obtaining or otherwise acquiring fitness training services
                from the TSR Customer and/or a Trainer, and
                “Trainees” shall be construed accordingly.
            </p>
            <p>
                “<strong>Trainer</strong>” means a person directly or indirectly accessing or making use of the Services
                procured by a TSR Customer as a trainer, including
                without limitation, for the purpose of offering, providing or otherwise furnishing fitness training services
                to one or more Trainees, and “Trainers” shall
                be construed accordingly.
            </p>
            <p>
                “<strong>Trial Usage Plans</strong>” means the free or discounted Usage Plans, and “Trial Usage Plan” shall
                be construed accordingly.
            </p>
            <p>
                “<strong>TSR Customer</strong>” means a person who procures Services from the Vendor, including without
                limitation, by placing an order for a specific
                Usage Plan with the Vendor, thereby allowing access to, and use of, such Services by the TSR Customer and
                the related Trainers and Trainees, and “TSR
                Customers” shall be construed accordingly.
            </p>
            <p>
                “<strong>Updates</strong>” means versions of the Services that contain functional enhancements,
                modifications, error corrections and/or fixes relating to
                the Services, and “Update” shall be construed accordingly.
            </p>
            <p>
                “<strong>Usage Plans</strong>” means the access plans to the Services offered for sale or, with respect to
                certain Trial Usage Plans, on a no-charge basis,
                by the Vendor to TSR Customers, each such plan allowing the TSR Customer and a certain set number of
                Trainers and Trainees to access and use the Services
                or certain features thereof procured by the TSR Customer, through the use of Access IDs, and “Usage Plan”
                shall be construed accordingly.
            </p>
            <p>
                “<strong>you</strong>”, “<strong>your</strong>” and/or “<strong>yourself</strong>” means either the TSR
                Customer, Trainer or Trainee entering into the
                Terms of Service, as applicable.
            </p>
            <p>
                “<strong>Your Content</strong>” means Content posted, submitted, made available, uploaded and/or displayed
                on or through the Services by you or your
                Agents, whether directly or through third party sites, applications or products (including without
                limitation any fitness or health tracking data,
                nutritional data or other data or information that you may make available to the Services from a third party
                site, application or product and any and all
                information you upload or make available through any document management features of the Services).
            </p>
        </div>
    </section>
@endsection

@section('scripts')
@endsection
