@extends('partials.layouts.master-client')
@section('title', 'Register — WinCase Client Portal')

@section('nav')<div></div>@endsection
@section('nav-right')
<a href="/client-login" class="btn btn-sm btn-outline-primary" data-lang="wc-lg-sign-in">Sign In</a>
@endsection

@section('css')
<style>
    .reg-card { max-width: 820px; margin: 0 auto; }
    .reg-card .card { border: none; box-shadow: 0 2px 20px rgba(0,0,0,.06); border-radius: 1rem; }
    [data-bs-theme="dark"] .reg-card .card { background: #1a1d23; }
    .form-label { font-weight: 500; font-size: .8125rem; margin-bottom: .25rem; }
    .form-label .text-danger { font-size: .75rem; }
    .input-hint { font-size: .7rem; color: #6c757d; margin-top: .15rem; }
    .agreement-box { border: 1px solid #dee2e6; border-radius: .5rem; padding: 1rem; max-height: 160px; overflow-y: auto; font-size: .8rem; line-height: 1.5; color: #495057; background: #f8f9fa; margin-bottom: .5rem; }
    [data-bs-theme="dark"] .agreement-box { background: #151821; border-color: #495057; color: #adb5bd; }
    .family-row { background: #f8f9fa; border-radius: .5rem; padding: 1rem; margin-bottom: .75rem; position: relative; }
    [data-bs-theme="dark"] .family-row { background: #151821; }
    .family-row .btn-remove-family { position: absolute; top: .5rem; right: .5rem; }
    .required-badge { font-size: .65rem; padding: .15em .4em; }
</style>
@endsection

@section('content')
<div class="reg-card">
    <div class="text-center mb-4">
        <img src="{{ asset('assets/images/wincase-logo.png') }}" alt="WinCase" class="wc-page-logo">
        <h5 class="fw-semibold mt-2 mb-1" data-lang="wc-reg-create-account">Create Your Account</h5>
        <p class="text-muted small"><span data-lang="wc-reg-create-desc">Complete all steps to register. All required fields are marked with</span> <span class="text-danger">*</span></p>
    </div>

    <!-- Wizard Steps -->
    <div class="wizard-steps mb-4" id="wizardSteps">
        <div class="wizard-step active" data-step="1">
            <span class="step-num">1</span>
            <span class="step-label" data-lang="wc-reg-step-personal">Personal Info</span>
        </div>
        <div class="wizard-step" data-step="2">
            <span class="step-num">2</span>
            <span class="step-label" data-lang="wc-reg-step-documents">Documents</span>
        </div>
        <div class="wizard-step" data-step="3">
            <span class="step-num">3</span>
            <span class="step-label" data-lang="wc-reg-step-address">Address</span>
        </div>
        <div class="wizard-step" data-step="4">
            <span class="step-num">4</span>
            <span class="step-label" data-lang="wc-reg-step-immigration">Immigration</span>
        </div>
        <div class="wizard-step" data-step="5">
            <span class="step-num">5</span>
            <span class="step-label" data-lang="wc-reg-step-family">Family</span>
        </div>
        <div class="wizard-step" data-step="6">
            <span class="step-num">6</span>
            <span class="step-label" data-lang="wc-reg-step-education">Education & Work</span>
        </div>
        <div class="wizard-step" data-step="7">
            <span class="step-num">7</span>
            <span class="step-label" data-lang="wc-reg-step-agreements">Agreements</span>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-4 p-md-5">

            <!-- ═══════════ STEP 1 — Personal Information ═══════════ -->
            <div class="wizard-panel active" data-panel="1">
                <h5 class="fw-semibold mb-1"><i class="ri-user-3-line me-2 text-success"></i><span data-lang="wc-cp-personal-info">Personal Information</span></h5>
                <p class="text-muted small mb-4" data-lang="wc-reg-personal-desc">Basic personal data as in your passport / ID document</p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-first-name">First Name</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="firstName" placeholder="e.g. Olena" data-lang-placeholder="wc-reg-ph-first-name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-last-name">Last Name</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lastName" placeholder="e.g. Kovalenko" data-lang-placeholder="wc-reg-ph-last-name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-middle-name">Middle Name / Patronymic</label>
                        <input type="text" class="form-control" id="middleName" placeholder="If applicable" data-lang-placeholder="wc-reg-ph-if-applicable">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-maiden-name">Maiden Name</label>
                        <input type="text" class="form-control" id="maidenName" placeholder="Previous surname if changed" data-lang-placeholder="wc-reg-ph-maiden">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span data-lang="wc-reg-dob">Date of Birth</span> <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="dob" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span data-lang="wc-reg-birth-place">Place of Birth</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="birthPlace" placeholder="City, Country" data-lang-placeholder="wc-reg-ph-birth-place">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span data-lang="wc-reg-gender">Gender</span> <span class="text-danger">*</span></label>
                        <select class="form-select" id="gender" required>
                            <option value="" data-lang="wc-reg-select">Select...</option>
                            <option value="Male" data-lang="wc-reg-male">Male</option>
                            <option value="Female" data-lang="wc-reg-female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-nationality">Nationality</span> <span class="text-danger">*</span></label>
                        <select class="form-select" id="nationality" required>
                            <option value="" data-lang="wc-reg-select-country">Select country...</option>
                            <option value="Ukraine">Ukraine</option><option value="Belarus">Belarus</option><option value="Georgia">Georgia</option><option value="India">India</option>
                            <option value="Bangladesh">Bangladesh</option><option value="Philippines">Philippines</option><option value="Vietnam">Vietnam</option><option value="Nepal">Nepal</option>
                            <option value="Turkey">Turkey</option><option value="Brazil">Brazil</option><option value="Colombia">Colombia</option><option value="Mexico">Mexico</option>
                            <option value="Nigeria">Nigeria</option><option value="Pakistan">Pakistan</option><option value="Sri Lanka">Sri Lanka</option><option value="Indonesia">Indonesia</option>
                            <option value="Uzbekistan">Uzbekistan</option><option value="Kazakhstan">Kazakhstan</option><option value="Moldova">Moldova</option><option value="Russia">Russia</option>
                            <option value="Armenia">Armenia</option><option value="Azerbaijan">Azerbaijan</option><option value="Tajikistan">Tajikistan</option><option value="Kyrgyzstan">Kyrgyzstan</option>
                            <option value="China">China</option><option value="South Korea">South Korea</option><option value="Thailand">Thailand</option><option value="Egypt">Egypt</option>
                            <option value="Morocco">Morocco</option><option value="Tunisia">Tunisia</option><option value="Other" data-lang="wc-reg-other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-citizenship">Citizenship</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="citizenship" placeholder="Current citizenship" data-lang-placeholder="wc-reg-ph-citizenship">
                        <div class="input-hint" data-lang="wc-reg-hint-citizenship">If different from nationality</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span data-lang="wc-reg-phone">Phone</span> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">+48</span>
                            <input type="tel" class="form-control" id="phone" placeholder="579 266 493" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-phone2">Additional Phone</label>
                        <input type="tel" class="form-control" id="phone2" placeholder="Another number" data-lang-placeholder="wc-reg-ph-phone2">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-messenger">WhatsApp / Telegram</label>
                        <input type="text" class="form-control" id="messenger" placeholder="Preferred messenger" data-lang-placeholder="wc-reg-ph-messenger">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-lg-email">Email</span> <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" placeholder="your@email.com" data-lang-placeholder="wc-lg-email-ph" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-pref-lang">Preferred Language</span> <span class="text-danger">*</span></label>
                        <select class="form-select" id="prefLang" required>
                            <option value="" data-lang="wc-reg-select">Select...</option>
                            <option value="English">English</option><option value="Polish">Polski</option><option value="Ukrainian">Українська</option><option value="Russian">Русский</option>
                            <option value="Spanish">Español</option><option value="Portuguese">Português</option><option value="Hindi">Hindi</option><option value="Bengali">Bengali</option>
                            <option value="Vietnamese">Vietnamese</option><option value="Other" data-lang="wc-reg-other">Other</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label"><span data-lang="wc-reg-create-password">Create Password</span> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="regPassword" placeholder="Min. 8 characters" data-lang-placeholder="wc-reg-ph-password" required>
                            <button class="btn btn-outline-secondary toggle-pass-reg" type="button"><i class="ri-eye-off-line"></i></button>
                        </div>
                        <div class="input-hint" data-lang="wc-reg-hint-password">At least 8 characters, one uppercase, one number</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label"><span data-lang="wc-reg-confirm-password">Confirm Password</span> <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="regPasswordConfirm" placeholder="Repeat password" data-lang-placeholder="wc-reg-ph-confirm-password" required>
                    </div>
                </div>
            </div>

            <!-- ═══════════ STEP 2 — Identity Documents ═══════════ -->
            <div class="wizard-panel" data-panel="2">
                <h5 class="fw-semibold mb-1"><i class="ri-passport-line me-2 text-success"></i><span data-lang="wc-reg-identity-docs">Identity Documents</span></h5>
                <p class="text-muted small mb-4" data-lang="wc-reg-identity-desc">Passport, PESEL and other identification documents</p>

                <div class="row g-3">
                    <div class="col-12"><h6 class="fw-semibold border-bottom pb-2 mb-0" data-lang="wc-reg-travel-passport">Travel Passport</h6></div>
                    <div class="col-md-4">
                        <label class="form-label"><span data-lang="wc-reg-passport-number">Passport Number</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="passportNo" placeholder="e.g. FE123456" data-lang-placeholder="wc-reg-ph-passport" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span data-lang="wc-reg-issue-date">Issue Date</span> <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="passportIssue" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span data-lang="wc-reg-expiry-date">Expiry Date</span> <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="passportExpiry" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-issuing-authority">Issuing Authority</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="passportAuthority" placeholder="Who issued the passport" data-lang-placeholder="wc-reg-ph-authority">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-issuing-country">Issuing Country</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="passportCountry" placeholder="e.g. Ukraine" data-lang-placeholder="wc-reg-ph-country">
                    </div>

                    <div class="col-12 mt-4"><h6 class="fw-semibold border-bottom pb-2 mb-0" data-lang="wc-reg-polish-docs">Polish Documents</h6></div>
                    <div class="col-md-4">
                        <label class="form-label">PESEL</label>
                        <input type="text" class="form-control" id="pesel" placeholder="11-digit number" data-lang-placeholder="wc-reg-ph-pesel" maxlength="11">
                        <div class="input-hint" data-lang="wc-reg-hint-pesel">If already assigned in Poland</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-nip">NIP (Tax ID)</label>
                        <input type="text" class="form-control" id="nip" placeholder="10-digit number" data-lang-placeholder="wc-reg-ph-nip" maxlength="10">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">REGON</label>
                        <input type="text" class="form-control" id="regon" placeholder="If self-employed" data-lang-placeholder="wc-reg-ph-regon">
                    </div>

                    <div class="col-12 mt-4"><h6 class="fw-semibold border-bottom pb-2 mb-0" data-lang="wc-reg-other-docs">Other Documents</h6></div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-national-id">National ID Number</label>
                        <input type="text" class="form-control" id="nationalId" placeholder="Home country ID if applicable" data-lang-placeholder="wc-reg-ph-national-id">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-driver-license">Driver's License Number</label>
                        <input type="text" class="form-control" id="driverLicense" placeholder="If applicable" data-lang-placeholder="wc-reg-ph-if-applicable">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-prev-passport">Previous Passport Number</label>
                        <input type="text" class="form-control" id="prevPassport" placeholder="If you had an older passport" data-lang-placeholder="wc-reg-ph-prev-passport">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-zus">Social Insurance (ZUS)</label>
                        <input type="text" class="form-control" id="zus" placeholder="ZUS number if applicable" data-lang-placeholder="wc-reg-ph-zus">
                    </div>
                </div>
            </div>

            <!-- ═══════════ STEP 3 — Address ═══════════ -->
            <div class="wizard-panel" data-panel="3">
                <h5 class="fw-semibold mb-1"><i class="ri-map-pin-line me-2 text-success"></i><span data-lang="wc-reg-address-info">Address Information</span></h5>
                <p class="text-muted small mb-4" data-lang="wc-reg-address-desc">Your current address in Poland and home country address</p>

                <div class="row g-3">
                    <div class="col-12"><h6 class="fw-semibold border-bottom pb-2 mb-0"><i class="ri-flag-line me-1"></i><span data-lang="wc-reg-address-poland">Address in Poland</span> <span class="badge bg-danger-subtle text-danger required-badge" data-lang="wc-reg-required">Required</span></h6></div>
                    <div class="col-md-8">
                        <label class="form-label"><span data-lang="wc-reg-street">Street</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="plStreet" placeholder="e.g. ul. Marszalkowska 10/5" data-lang-placeholder="wc-reg-ph-street" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-apartment">Apartment</label>
                        <input type="text" class="form-control" id="plApt" placeholder="m. 5">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span data-lang="wc-reg-postal-code">Postal Code</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="plPostal" placeholder="00-000" maxlength="6" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span data-lang="wc-reg-city">City</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="plCity" placeholder="e.g. Warszawa" data-lang-placeholder="wc-reg-ph-city" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span data-lang="wc-reg-voivodeship">Voivodeship</span> <span class="text-danger">*</span></label>
                        <select class="form-select" id="plVoivodeship" required>
                            <option value="" data-lang="wc-reg-select">Select...</option>
                            <option value="dolnoslaskie">dolnoslaskie</option><option value="kujawsko-pomorskie">kujawsko-pomorskie</option><option value="lubelskie">lubelskie</option>
                            <option value="lubuskie">lubuskie</option><option value="lodzkie">lodzkie</option><option value="malopolskie">malopolskie</option>
                            <option value="mazowieckie">mazowieckie</option><option value="opolskie">opolskie</option><option value="podkarpackie">podkarpackie</option>
                            <option value="podlaskie">podlaskie</option><option value="pomorskie">pomorskie</option><option value="slaskie">slaskie</option>
                            <option value="swietokrzyskie">swietokrzyskie</option><option value="warminsko-mazurskie">warminsko-mazurskie</option><option value="wielkopolskie">wielkopolskie</option>
                            <option value="zachodniopomorskie">zachodniopomorskie</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-zameldowanie">Residence Registration (Zameldowanie)</label>
                        <select class="form-select" id="zameldowanie">
                            <option value="" data-lang="wc-reg-select">Select...</option>
                            <option value="Permanent" data-lang="wc-reg-permanent">Permanent (stale)</option>
                            <option value="Temporary" data-lang="wc-reg-temporary">Temporary (czasowe)</option>
                            <option value="Not registered" data-lang="wc-reg-not-registered">Not registered</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-living-since">Living Since</label>
                        <input type="date" class="form-control" id="plLivingSince">
                    </div>

                    <div class="col-12 mt-4"><h6 class="fw-semibold border-bottom pb-2 mb-0"><i class="ri-global-line me-1"></i><span data-lang="wc-reg-home-address">Home Country Address</span></h6></div>
                    <div class="col-12">
                        <label class="form-label"><span data-lang="wc-reg-full-address">Full Address</span> <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="homeAddress" rows="2" placeholder="Street, city, postal code, region" data-lang-placeholder="wc-reg-ph-full-address" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-country">Country</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="homeCountry" placeholder="e.g. Ukraine" data-lang-placeholder="wc-reg-ph-country" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-home-phone">Phone in Home Country</label>
                        <input type="tel" class="form-control" id="homePhone" placeholder="With country code" data-lang-placeholder="wc-reg-ph-home-phone">
                    </div>

                    <div class="col-12 mt-4"><h6 class="fw-semibold border-bottom pb-2 mb-0"><i class="ri-mail-line me-1"></i><span data-lang="wc-reg-corr-address">Correspondence Address</span> <small class="text-muted fw-normal">(<span data-lang="wc-reg-corr-diff">if different from Poland address</span>)</small></h6></div>
                    <div class="col-12">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="sameAddr" checked>
                            <label class="form-check-label small" for="sameAddr" data-lang="wc-reg-same-addr">Same as Poland address</label>
                        </div>
                    </div>
                    <div id="corrAddrBlock" style="display:none;">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label" data-lang="wc-reg-street">Street</label>
                                <input type="text" class="form-control" id="corrStreet">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" data-lang="wc-reg-city">City</label>
                                <input type="text" class="form-control" id="corrCity">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══════════ STEP 4 — Immigration Details ═══════════ -->
            <div class="wizard-panel" data-panel="4">
                <h5 class="fw-semibold mb-1"><i class="ri-flight-takeoff-line me-2 text-success"></i><span data-lang="wc-reg-immigration-title">Immigration Details</span></h5>
                <p class="text-muted small mb-4" data-lang="wc-reg-immigration-desc">Your current immigration status and purpose of stay in Poland</p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-imm-status">Current Immigration Status</span> <span class="text-danger">*</span></label>
                        <select class="form-select" id="immStatus" required>
                            <option value="" data-lang="wc-reg-select">Select...</option>
                            <option value="Visa (national D)" data-lang="wc-reg-visa-d">Visa (national D)</option>
                            <option value="Visa (Schengen C)" data-lang="wc-reg-visa-c">Visa (Schengen C)</option>
                            <option value="Temporary Residence Permit" data-lang="wc-reg-temp-permit">Temporary Residence Permit</option>
                            <option value="Permanent Residence Permit" data-lang="wc-reg-perm-permit">Permanent Residence Permit</option>
                            <option value="EU Long-Term Resident" data-lang="wc-reg-eu-longterm">EU Long-Term Resident</option>
                            <option value="Refugee Status" data-lang="wc-reg-refugee">Refugee Status</option>
                            <option value="Subsidiary Protection" data-lang="wc-reg-subsidiary">Subsidiary Protection</option>
                            <option value="Tolerated Stay" data-lang="wc-reg-tolerated">Tolerated Stay</option>
                            <option value="Humanitarian Stay" data-lang="wc-reg-humanitarian">Humanitarian Stay</option>
                            <option value="Visa-Free (Stamp)" data-lang="wc-reg-visa-free">Visa-Free (Stamp)</option>
                            <option value="Polish Card (Karta Polaka)" data-lang="wc-reg-karta-polaka">Polish Card (Karta Polaka)</option>
                            <option value="EU/EEA Citizen" data-lang="wc-reg-eu-citizen">EU/EEA Citizen</option>
                            <option value="Pending Application" data-lang="wc-reg-pending-app">Pending Application</option>
                            <option value="No Legal Status" data-lang="wc-reg-no-status">No Legal Status</option>
                            <option value="Other" data-lang="wc-reg-other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-purpose-stay">Purpose of Stay</span> <span class="text-danger">*</span></label>
                        <select class="form-select" id="stayPurpose" required>
                            <option value="" data-lang="wc-reg-select">Select...</option>
                            <option value="Employment (Work Permit)" data-lang="wc-reg-purp-work-permit">Employment (Work Permit)</option>
                            <option value="Employment (Declaration)" data-lang="wc-reg-purp-declaration">Employment (Employer's Declaration)</option>
                            <option value="Self-Employment / Business" data-lang="wc-reg-purp-business">Self-Employment / Business</option>
                            <option value="Family Reunification" data-lang="wc-reg-purp-family">Family Reunification</option>
                            <option value="Studies" data-lang="wc-reg-purp-studies">Studies</option>
                            <option value="Research / Scientific" data-lang="wc-reg-purp-research">Research / Scientific</option>
                            <option value="Polish Origin (Karta Polaka)" data-lang="wc-reg-purp-polish-origin">Polish Origin (Karta Polaka)</option>
                            <option value="Refugee / Asylum" data-lang="wc-reg-purp-refugee">Refugee / Asylum</option>
                            <option value="Victim of Trafficking" data-lang="wc-reg-purp-victim">Victim of Trafficking</option>
                            <option value="Other Circumstances" data-lang="wc-reg-purp-other">Other Circumstances</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-arrival-date">Date of First Arrival in Poland</label>
                        <input type="date" class="form-control" id="arrivalDate">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-permit-from">Current Permit Valid From</label>
                        <input type="date" class="form-control" id="permitFrom">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-permit-until">Current Permit Valid Until</label>
                        <input type="date" class="form-control" id="permitUntil">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-permit-number">Permit / Visa Number</label>
                        <input type="text" class="form-control" id="permitNo" placeholder="If applicable" data-lang-placeholder="wc-reg-ph-if-applicable">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-karta-pobytu">Karta Pobytu Number</label>
                        <input type="text" class="form-control" id="kartaPobytu" placeholder="If you have one" data-lang-placeholder="wc-reg-ph-karta-pobytu">
                    </div>

                    <div class="col-12 mt-3"><h6 class="fw-semibold border-bottom pb-2 mb-0" data-lang="wc-reg-prev-apps">Previous Applications</h6></div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-prev-app-q">Have you applied for a permit before in Poland?</label>
                        <select class="form-select" id="prevApp">
                            <option value="No" data-lang="wc-reg-no">No</option>
                            <option value="Yes — Approved" data-lang="wc-reg-yes-approved">Yes — Approved</option>
                            <option value="Yes — Denied" data-lang="wc-reg-yes-denied">Yes — Denied</option>
                            <option value="Yes — Pending" data-lang="wc-reg-yes-pending">Yes — Pending</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-prev-app-details">Previous Application Details</label>
                        <input type="text" class="form-control" id="prevAppDetails" placeholder="Type, year, outcome" data-lang-placeholder="wc-reg-ph-prev-app">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-entry-ban">Entry bans or deportation history?</label>
                        <select class="form-select" id="entryBan">
                            <option value="No" data-lang="wc-reg-no">No</option>
                            <option value="Yes" data-lang="wc-reg-yes">Yes</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-criminal">Criminal record in Poland or abroad?</label>
                        <select class="form-select" id="criminal">
                            <option value="No" data-lang="wc-reg-no">No</option>
                            <option value="Yes" data-lang="wc-reg-yes">Yes</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label"><span data-lang="wc-reg-service-needed">Service You Need</span> <span class="text-danger">*</span></label>
                        <select class="form-select" id="serviceNeeded" required>
                            <option value="" data-lang="wc-reg-select-service">Select main service...</option>
                            <option value="Temporary Residence Permit" data-lang="wc-reg-svc-temp">Temporary Residence Permit (Karta Pobytu)</option>
                            <option value="Permanent Residence Permit" data-lang="wc-reg-svc-perm">Permanent Residence Permit (Karta Stalego Pobytu)</option>
                            <option value="EU Long-Term Resident Permit" data-lang="wc-reg-svc-eu">EU Long-Term Resident Permit</option>
                            <option value="Work Permit" data-lang="wc-reg-svc-work">Work Permit (Type A/B/C)</option>
                            <option value="Business Registration" data-lang="wc-reg-svc-business">Business Registration (Firma)</option>
                            <option value="Family Reunification" data-lang="wc-reg-svc-family">Family Reunification</option>
                            <option value="Polish Citizenship" data-lang="wc-reg-svc-citizenship">Polish Citizenship</option>
                            <option value="Polish Card" data-lang="wc-reg-svc-polcard">Polish Card (Karta Polaka)</option>
                            <option value="Visa Extension" data-lang="wc-reg-svc-visa">Visa Extension</option>
                            <option value="PESEL Registration" data-lang="wc-reg-svc-pesel">PESEL Registration</option>
                            <option value="Appeal / Complaint" data-lang="wc-reg-svc-appeal">Appeal / Complaint</option>
                            <option value="Document Translation" data-lang="wc-reg-svc-translation">Document Translation & Legalization</option>
                            <option value="Full Immigration Package" data-lang="wc-reg-svc-full">Full Immigration Package</option>
                            <option value="Consultation Only" data-lang="wc-reg-svc-consult">Consultation Only</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-lang="wc-reg-additional-notes">Additional Notes</label>
                        <textarea class="form-control" id="immNotes" rows="2" placeholder="Any additional information about your immigration situation..." data-lang-placeholder="wc-reg-ph-notes"></textarea>
                    </div>
                </div>
            </div>

            <!-- ═══════════ STEP 5 — Family Members ═══════════ -->
            <div class="wizard-panel" data-panel="5">
                <h5 class="fw-semibold mb-1"><i class="ri-group-line me-2 text-success"></i><span data-lang="wc-reg-family-title">Family Members</span></h5>
                <p class="text-muted small mb-4" data-lang="wc-reg-family-desc">Information about family members (spouse, children, dependents). Important for family reunification cases.</p>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label"><span data-lang="wc-reg-marital-status">Marital Status</span> <span class="text-danger">*</span></label>
                        <select class="form-select" id="marital" required>
                            <option value="" data-lang="wc-reg-select">Select...</option>
                            <option value="Single" data-lang="wc-reg-single">Single</option>
                            <option value="Married" data-lang="wc-reg-married">Married</option>
                            <option value="Divorced" data-lang="wc-reg-divorced">Divorced</option>
                            <option value="Widowed" data-lang="wc-reg-widowed">Widowed</option>
                            <option value="Separated" data-lang="wc-reg-separated">Separated</option>
                            <option value="Civil Partnership" data-lang="wc-reg-civil-partnership">Civil Partnership</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-num-children">Number of Children</label>
                        <input type="number" class="form-control" id="numChildren" min="0" max="20" value="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-dependents">Dependents in Poland</label>
                        <input type="number" class="form-control" id="dependents" min="0" max="20" value="0">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0" data-lang="wc-reg-family-title">Family Members</h6>
                    <button class="btn btn-sm btn-outline-success" id="btnAddFamily"><i class="ri-add-line me-1"></i><span data-lang="wc-reg-add-member">Add Member</span></button>
                </div>

                <div id="familyContainer">
                    <!-- Spouse template -->
                    <div class="family-row" data-idx="0">
                        <button class="btn btn-sm btn-outline-danger btn-remove-family" title="Remove"><i class="ri-close-line"></i></button>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="form-label" data-lang="wc-reg-relation">Relation</label>
                                <select class="form-select form-select-sm">
                                    <option value="Spouse" data-lang="wc-reg-rel-spouse">Spouse</option>
                                    <option value="Child" data-lang="wc-reg-rel-child">Child</option>
                                    <option value="Parent" data-lang="wc-reg-rel-parent">Parent</option>
                                    <option value="Sibling" data-lang="wc-reg-rel-sibling">Sibling</option>
                                    <option value="Other" data-lang="wc-reg-other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" data-lang="wc-cp-full-name">Full Name</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Full name" data-lang-placeholder="wc-reg-ph-fullname">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" data-lang="wc-reg-dob-short">DOB</label>
                                <input type="date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" data-lang="wc-reg-nationality">Nationality</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Country" data-lang-placeholder="wc-reg-ph-country-short">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" data-lang="wc-reg-in-poland">In Poland?</label>
                                <select class="form-select form-select-sm">
                                    <option value="Yes" data-lang="wc-reg-yes">Yes</option>
                                    <option value="No" data-lang="wc-reg-no">No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" data-lang="wc-reg-passport-number">Passport Number</label>
                                <input type="text" class="form-control form-control-sm" placeholder="If applicable" data-lang-placeholder="wc-reg-ph-if-applicable">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">PESEL</label>
                                <input type="text" class="form-control form-control-sm" placeholder="If has one" data-lang-placeholder="wc-reg-ph-if-has">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" data-lang="wc-reg-imm-status-short">Immigration Status</label>
                                <select class="form-select form-select-sm">
                                    <option value="" data-lang="wc-reg-na">N/A</option>
                                    <option value="Visa" data-lang="wc-reg-fam-visa">Visa</option>
                                    <option value="Residence Permit" data-lang="wc-reg-fam-permit">Residence Permit</option>
                                    <option value="EU Citizen" data-lang="wc-reg-fam-eu">EU Citizen</option>
                                    <option value="Pending" data-lang="wc-reg-fam-pending">Pending</option>
                                    <option value="Other" data-lang="wc-reg-other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info small mt-3 mb-0">
                    <i class="ri-information-line me-1"></i>
                    <span data-lang="wc-reg-family-info">If you don't have family members or this doesn't apply, you can skip to the next step. Family information is essential for family reunification applications.</span>
                </div>
            </div>

            <!-- ═══════════ STEP 6 — Education & Work ═══════════ -->
            <div class="wizard-panel" data-panel="6">
                <h5 class="fw-semibold mb-1"><i class="ri-graduation-cap-line me-2 text-success"></i><span data-lang="wc-reg-edu-title">Education & Employment</span></h5>
                <p class="text-muted small mb-4" data-lang="wc-reg-edu-desc">Your educational background and current employment in Poland</p>

                <div class="row g-3">
                    <div class="col-12"><h6 class="fw-semibold border-bottom pb-2 mb-0" data-lang="wc-reg-education">Education</h6></div>
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-edu-level">Highest Education Level</span> <span class="text-danger">*</span></label>
                        <select class="form-select" id="education" required>
                            <option value="" data-lang="wc-reg-select">Select...</option>
                            <option value="Primary School" data-lang="wc-reg-edu-primary">Primary School</option>
                            <option value="Secondary School" data-lang="wc-reg-edu-secondary">Secondary School / Lyceum</option>
                            <option value="Vocational" data-lang="wc-reg-edu-vocational">Vocational / Technical</option>
                            <option value="Bachelor" data-lang="wc-reg-edu-bachelor">Bachelor's Degree</option>
                            <option value="Master" data-lang="wc-reg-edu-master">Master's Degree</option>
                            <option value="PhD" data-lang="wc-reg-edu-phd">PhD / Doctorate</option>
                            <option value="Other" data-lang="wc-reg-other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-field-study">Field of Study / Specialization</label>
                        <input type="text" class="form-control" id="fieldOfStudy" placeholder="e.g. Computer Science, Medicine" data-lang-placeholder="wc-reg-ph-field">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-institution">Institution Name</label>
                        <input type="text" class="form-control" id="institution" placeholder="University / School name" data-lang-placeholder="wc-reg-ph-institution">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" data-lang="wc-reg-grad-year">Graduation Year</label>
                        <input type="number" class="form-control" id="gradYear" min="1970" max="2026" placeholder="e.g. 2020">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" data-lang="wc-reg-country">Country</label>
                        <input type="text" class="form-control" id="eduCountry" placeholder="Country" data-lang-placeholder="wc-reg-ph-country-short">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-polish-level">Polish Language Level</label>
                        <select class="form-select" id="polishLevel">
                            <option value="" data-lang="wc-reg-select">Select...</option>
                            <option value="None" data-lang="wc-reg-lang-none">None</option>
                            <option value="A1">A1 — Beginner</option>
                            <option value="A2">A2 — Elementary</option>
                            <option value="B1">B1 — Intermediate</option>
                            <option value="B2">B2 — Upper-Intermediate</option>
                            <option value="C1">C1 — Advanced</option>
                            <option value="C2">C2 — Proficiency</option>
                            <option value="Native" data-lang="wc-reg-lang-native">Native</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-other-langs">Other Languages</label>
                        <input type="text" class="form-control" id="otherLangs" placeholder="e.g. English B2, Russian C1" data-lang-placeholder="wc-reg-ph-other-langs">
                    </div>

                    <div class="col-12 mt-4"><h6 class="fw-semibold border-bottom pb-2 mb-0" data-lang="wc-reg-emp-poland">Employment in Poland</h6></div>
                    <div class="col-md-6">
                        <label class="form-label"><span data-lang="wc-reg-emp-status">Employment Status</span> <span class="text-danger">*</span></label>
                        <select class="form-select" id="empStatus" required>
                            <option value="" data-lang="wc-reg-select">Select...</option>
                            <option value="Umowa o prace" data-lang="wc-reg-emp-prace">Employed (Umowa o prace)</option>
                            <option value="Umowa zlecenie" data-lang="wc-reg-emp-zlecenie">Employed (Umowa zlecenie)</option>
                            <option value="Umowa o dzielo" data-lang="wc-reg-emp-dzielo">Employed (Umowa o dzielo)</option>
                            <option value="Self-Employed" data-lang="wc-reg-emp-self">Self-Employed (Dzialalnosc)</option>
                            <option value="Business Owner" data-lang="wc-reg-emp-owner">Business Owner (Spolka)</option>
                            <option value="Unemployed" data-lang="wc-reg-emp-unemployed">Unemployed</option>
                            <option value="Student" data-lang="wc-reg-emp-student">Student</option>
                            <option value="Retired" data-lang="wc-reg-emp-retired">Retired</option>
                            <option value="Other" data-lang="wc-reg-other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-profession">Profession / Job Title</label>
                        <input type="text" class="form-control" id="profession" placeholder="e.g. Software Developer, Chef" data-lang-placeholder="wc-reg-ph-profession">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-employer">Employer / Company Name</label>
                        <input type="text" class="form-control" id="employer" placeholder="Current employer" data-lang-placeholder="wc-reg-ph-employer">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-emp-nip">Employer NIP (Tax ID)</label>
                        <input type="text" class="form-control" id="empNip" placeholder="10-digit employer NIP" data-lang-placeholder="wc-reg-ph-emp-nip">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-emp-address">Employer Address</label>
                        <input type="text" class="form-control" id="empAddress" placeholder="Company address" data-lang-placeholder="wc-reg-ph-emp-address">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" data-lang="wc-reg-emp-since">Employment Since</label>
                        <input type="date" class="form-control" id="empSince">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" data-lang="wc-reg-salary">Monthly Salary (PLN)</label>
                        <input type="number" class="form-control" id="salary" placeholder="Gross, PLN" data-lang-placeholder="wc-reg-ph-salary">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-work-permit">Work Permit Type</label>
                        <select class="form-select" id="workPermit">
                            <option value="" data-lang="wc-reg-not-applicable">Not applicable</option>
                            <option value="Type A" data-lang="wc-reg-wp-a">Work Permit Type A</option>
                            <option value="Type B" data-lang="wc-reg-wp-b">Work Permit Type B</option>
                            <option value="Type C" data-lang="wc-reg-wp-c">Work Permit Type C</option>
                            <option value="Declaration" data-lang="wc-reg-wp-declaration">Employer's Declaration (Oswiadczenie)</option>
                            <option value="Seasonal" data-lang="wc-reg-wp-seasonal">Seasonal Permit</option>
                            <option value="Exempt" data-lang="wc-reg-wp-exempt">Exempt (EU / Karta Polaka)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-lang="wc-reg-wp-expiry">Work Permit Valid Until</label>
                        <input type="date" class="form-control" id="wpExpiry">
                    </div>

                    <div class="col-12 mt-3"><h6 class="fw-semibold border-bottom pb-2 mb-0" data-lang="wc-reg-financial-info">Financial Information</h6></div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-health-ins">Health Insurance</label>
                        <select class="form-select" id="healthIns">
                            <option value="" data-lang="wc-reg-select">Select...</option>
                            <option value="ZUS" data-lang="wc-reg-ins-zus">ZUS (through employer)</option>
                            <option value="KRUS">KRUS</option>
                            <option value="Private" data-lang="wc-reg-ins-private">Private Insurance</option>
                            <option value="EHIC" data-lang="wc-reg-ins-ehic">EU Health Card (EHIC)</option>
                            <option value="None" data-lang="wc-reg-ins-none">No Insurance</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-bank-account">Bank Account in Poland?</label>
                        <select class="form-select" id="bankAccount">
                            <option value="Yes" data-lang="wc-reg-yes">Yes</option>
                            <option value="No" data-lang="wc-reg-no">No</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-lang="wc-reg-tax-residency">Tax Residency</label>
                        <select class="form-select" id="taxRes">
                            <option value="Poland" data-lang="wc-reg-tax-poland">Poland</option>
                            <option value="Home Country" data-lang="wc-reg-tax-home">Home Country</option>
                            <option value="Both" data-lang="wc-reg-tax-both">Both</option>
                            <option value="Other" data-lang="wc-reg-other">Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ═══════════ STEP 7 — Agreements ═══════════ -->
            <div class="wizard-panel" data-panel="7">
                <h5 class="fw-semibold mb-1"><i class="ri-shield-check-line me-2 text-success"></i><span data-lang="wc-reg-agreements-title">Agreements & Consent</span></h5>
                <p class="text-muted small mb-4" data-lang="wc-reg-agreements-desc">Please read and accept all agreements to complete registration</p>

                <!-- Agreement 1: Terms of Service -->
                <div class="mb-4">
                    <h6 class="fw-semibold"><i class="ri-file-text-line me-1"></i><span data-lang="wc-reg-tos-title">1. Terms of Service</span></h6>
                    <div class="agreement-box">
                        <strong>WinCase Immigration Bureau — Terms of Service</strong><br><br>
                        By creating an account and using WinCase Client Portal, you agree to the following terms:<br><br>
                        <strong>1. Services.</strong> WinCase provides immigration consulting and document preparation services for foreigners in Poland. We assist with applications for residence permits, work permits, business registration, and related immigration matters.<br><br>
                        <strong>2. No Guarantee of Outcome.</strong> While we provide professional assistance, we cannot guarantee the approval of any application. Decisions are made solely by the competent Polish authorities (Urzad Wojewodzki, Urzad do Spraw Cudzoziemcow).<br><br>
                        <strong>3. Client Obligations.</strong> The Client agrees to: (a) provide truthful and complete information; (b) submit all required documents in a timely manner; (c) inform WinCase of any changes in personal circumstances; (d) attend scheduled appointments at immigration offices.<br><br>
                        <strong>4. Fees & Payment.</strong> Service fees are agreed upon before commencement. Consultation fees are non-refundable. If an application is rejected through no fault of WinCase, fees for work already performed are non-refundable.<br><br>
                        <strong>5. Limitation of Liability.</strong> WinCase shall not be liable for delays caused by government agencies, missing documents not provided by the client, or changes in immigration law.<br><br>
                        <strong>6. Termination.</strong> Either party may terminate the agreement with 14 days written notice. Fees for services already rendered are non-refundable.<br><br>
                        <strong>7. Governing Law.</strong> These terms are governed by the laws of the Republic of Poland. Disputes shall be resolved by the competent court in Warsaw.
                    </div>
                    <div class="form-check">
                        <input class="form-check-input agree-check" type="checkbox" id="agreeTerms" data-agree="terms">
                        <label class="form-check-label fw-medium" for="agreeTerms"><span data-lang="wc-reg-agree-terms">I have read and accept the Terms of Service</span> <span class="text-danger">*</span></label>
                    </div>
                </div>

                <!-- Agreement 2: RODO / GDPR -->
                <div class="mb-4">
                    <h6 class="fw-semibold"><i class="ri-shield-user-line me-1"></i><span data-lang="wc-reg-rodo-title">2. RODO / GDPR — Data Protection</span></h6>
                    <div class="agreement-box">
                        <strong>Information on Processing of Personal Data (RODO)</strong><br>
                        Pursuant to Article 13 of the Regulation (EU) 2016/679 (GDPR):<br><br>
                        <strong>Data Controller:</strong> WinCase Sp. z o.o., ul. Marszalkowska 27/35, 00-639 Warszawa, NIP: 5252XXXXXX, contact: rodo@wincase.eu<br><br>
                        <strong>Purpose of Processing:</strong> Your personal data will be processed for: (a) provision of immigration consulting services; (b) preparation and submission of applications to Polish authorities; (c) communication regarding your case; (d) legal obligations (tax, accounting); (e) legitimate interests (debt recovery, legal claims).<br><br>
                        <strong>Legal Basis:</strong> Art. 6(1)(a) — consent; Art. 6(1)(b) — contract performance; Art. 6(1)(c) — legal obligation; Art. 6(1)(f) — legitimate interests.<br><br>
                        <strong>Data Recipients:</strong> Your data may be shared with: Polish immigration authorities (Urzad Wojewodzki), employer (if relevant), sworn translators, tax advisors, and IT service providers under data processing agreements.<br><br>
                        <strong>Retention Period:</strong> Data will be stored for the duration of the service agreement plus 5 years for legal claims, or as required by law.<br><br>
                        <strong>Your Rights:</strong> You have the right to: access, rectify, erase, restrict processing, data portability, object to processing, and withdraw consent at any time. Withdrawal does not affect lawfulness of processing before withdrawal.<br><br>
                        <strong>Contact DPO:</strong> rodo@wincase.eu<br>
                        <strong>Complaints:</strong> You may lodge a complaint with UODO (Urzad Ochrony Danych Osobowych), ul. Stawki 2, 00-193 Warszawa.
                    </div>
                    <div class="form-check">
                        <input class="form-check-input agree-check" type="checkbox" id="agreeRodo" data-agree="rodo">
                        <label class="form-check-label fw-medium" for="agreeRodo"><span data-lang="wc-reg-agree-rodo">I acknowledge the RODO/GDPR information and consent to data processing</span> <span class="text-danger">*</span></label>
                    </div>
                </div>

                <!-- Agreement 3: Power of Attorney -->
                <div class="mb-4">
                    <h6 class="fw-semibold"><i class="ri-draft-line me-1"></i><span data-lang="wc-reg-poa-title">3. Power of Attorney (Pelnomocnictwo)</span></h6>
                    <div class="agreement-box">
                        <strong>Authorization / Power of Attorney</strong><br><br>
                        I hereby authorize WinCase Sp. z o.o. and its designated representatives to act on my behalf in all matters related to my immigration case in Poland, including but not limited to:<br><br>
                        1. Submitting applications and documents to the Voivodeship Office (Urzad Wojewodzki) and other relevant government institutions.<br>
                        2. Collecting correspondence, decisions, and documents issued in my name.<br>
                        3. Representing me in administrative proceedings related to my residence/work permit application.<br>
                        4. Communicating with government officials regarding the status of my case.<br><br>
                        This authorization is valid from the date of acceptance until: (a) the conclusion of the immigration case; (b) written revocation by the Client; (c) termination of the service agreement.<br><br>
                        <em>Note: A separate, signed and notarized Power of Attorney document (Pelnomocnictwo) will be prepared by WinCase for submission to the relevant authority. This digital consent serves as a preliminary authorization.</em>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input agree-check" type="checkbox" id="agreePoa" data-agree="poa">
                        <label class="form-check-label fw-medium" for="agreePoa"><span data-lang="wc-reg-agree-poa">I authorize WinCase to act on my behalf</span> <span class="text-danger">*</span></label>
                    </div>
                </div>

                <!-- Agreement 4: Data Processing -->
                <div class="mb-4">
                    <h6 class="fw-semibold"><i class="ri-database-2-line me-1"></i><span data-lang="wc-reg-data-title">4. Data Sharing & Processing Consent</span></h6>
                    <div class="agreement-box">
                        <strong>Consent for Data Sharing with Third Parties</strong><br><br>
                        I consent to WinCase sharing my personal data with the following third parties as necessary for the performance of immigration services:<br><br>
                        1. <strong>Government Authorities:</strong> Urzad Wojewodzki, Urzad do Spraw Cudzoziemcow, Straz Graniczna, ZUS, Urzad Skarbowy.<br>
                        2. <strong>Employer:</strong> For the purpose of obtaining or renewing work permits.<br>
                        3. <strong>Sworn Translators:</strong> For document translation and certification.<br>
                        4. <strong>Notary:</strong> For notarization of documents and powers of attorney.<br>
                        5. <strong>Legal Advisors:</strong> In case of appeals or legal proceedings.<br><br>
                        I understand that I may withdraw this consent at any time by contacting rodo@wincase.eu, though this may affect WinCase's ability to provide services.
                    </div>
                    <div class="form-check">
                        <input class="form-check-input agree-check" type="checkbox" id="agreeData" data-agree="data">
                        <label class="form-check-label fw-medium" for="agreeData"><span data-lang="wc-reg-agree-data">I consent to sharing my data with relevant third parties</span> <span class="text-danger">*</span></label>
                    </div>
                </div>

                <!-- Optional: Marketing -->
                <div class="mb-4 p-3 border rounded">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="agreeMarketing">
                        <label class="form-check-label" for="agreeMarketing">
                            <span class="fw-medium" data-lang="wc-reg-marketing">Marketing Communications</span> <span class="badge bg-secondary-subtle text-secondary" data-lang="wc-reg-optional">Optional</span><br>
                            <small class="text-muted" data-lang="wc-reg-marketing-desc">I agree to receive news, updates, and promotional offers from WinCase via email and SMS.</small>
                        </label>
                    </div>
                </div>

                <!-- Digital Signature -->
                <div class="card bg-light border-0 p-3 mb-3">
                    <h6 class="fw-semibold mb-2"><i class="ri-pen-nib-line me-1"></i><span data-lang="wc-reg-digital-sig">Digital Signature</span></h6>
                    <p class="text-muted small mb-2" data-lang="wc-reg-sig-desc">Type your full legal name as a digital signature to confirm all agreements above.</p>
                    <div class="row g-2">
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="digitalSignature" placeholder="Full Name (as in passport)" data-lang-placeholder="wc-reg-ph-signature">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control bg-light" id="signDate" readonly>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning small mb-0" id="agreeAlert" style="display:none;">
                    <i class="ri-error-warning-line me-1"></i>
                    <span data-lang="wc-reg-agree-alert">Please accept all required agreements and enter your digital signature to complete registration.</span>
                </div>
            </div>

            <!-- ═══════════ Navigation Buttons ═══════════ -->
            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                <button class="btn btn-light px-4" id="btnPrev" style="display:none;">
                    <i class="ri-arrow-left-line me-1"></i><span data-lang="wc-reg-previous">Previous</span>
                </button>
                <div class="ms-auto d-flex gap-2">
                    <button class="btn btn-outline-secondary" id="btnSaveDraft">
                        <i class="ri-save-line me-1"></i><span data-lang="wc-reg-save-draft">Save Draft</span>
                    </button>
                    <button class="btn btn-success px-4" id="btnNext">
                        <span data-lang="wc-reg-next-step">Next Step</span><i class="ri-arrow-right-line ms-1"></i>
                    </button>
                    <button class="btn btn-success px-4" id="btnSubmit" style="display:none;">
                        <i class="ri-check-line me-1"></i><span data-lang="wc-reg-complete">Complete Registration</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Progress -->
    <div class="text-center mt-3">
        <small class="text-muted"><span data-lang="wc-reg-step-label">Step</span> <span id="currentStepNum">1</span> <span data-lang="wc-reg-of">of</span> 7</small>
        <div class="progress mt-1" style="height:4px;max-width:300px;margin:0 auto;">
            <div class="progress-bar bg-success" id="progressBar" style="width:14.28%"></div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// Redirect if already logged in
if (localStorage.getItem('wc_token')) {
    window.location.href = '/client-dashboard';
}

// Global skip function for admin preview bar
var _skipMode = false;
function skipTo(n){
    _skipMode = true;
    _goToStepForce(n);
    _skipMode = false;
}
function _goToStepForce(n){}

(function(){
    var currentStep = 1;
    var totalSteps = 7;
    var t = (window.WcI18n && WcI18n.t) ? WcI18n.t : function(k,f){ return f; };
    var DRAFT_KEY = 'wc_register_draft';

    var steps = document.querySelectorAll('.wizard-step');
    var panels = document.querySelectorAll('.wizard-panel');
    var btnPrev = document.getElementById('btnPrev');
    var btnNext = document.getElementById('btnNext');
    var btnSubmit = document.getElementById('btnSubmit');
    var progressBar = document.getElementById('progressBar');
    var stepNum = document.getElementById('currentStepNum');

    // Set today's date for signature
    var today = new Date();
    document.getElementById('signDate').value = today.toLocaleDateString('en-GB', {day:'2-digit',month:'2-digit',year:'numeric'});

    function goToStepDirect(n) {
        currentStep = n;
        if(currentStep < 1) currentStep = 1;
        if(currentStep > totalSteps) currentStep = totalSteps;

        steps.forEach(function(s){
            var sn = parseInt(s.getAttribute('data-step'));
            s.classList.remove('active','done');
            if(sn < currentStep) s.classList.add('done');
            if(sn === currentStep) s.classList.add('active');
        });
        panels.forEach(function(p){
            p.classList.remove('active');
            if(parseInt(p.getAttribute('data-panel')) === currentStep) p.classList.add('active');
        });
        btnPrev.style.display = currentStep === 1 ? 'none' : '';
        btnNext.style.display = currentStep === totalSteps ? 'none' : '';
        btnSubmit.style.display = currentStep === totalSteps ? '' : 'none';
        progressBar.style.width = ((currentStep / totalSteps) * 100) + '%';
        stepNum.textContent = currentStep;
        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    // Expose for admin skip bar
    _goToStepForce = goToStepDirect;

    function goToStep(n) {
        // validate current before moving forward
        if(n > currentStep && !_skipMode && !validateStep(currentStep)) return;

        currentStep = n;
        if(currentStep < 1) currentStep = 1;
        if(currentStep > totalSteps) currentStep = totalSteps;

        // update steps
        steps.forEach(function(s){
            var sn = parseInt(s.getAttribute('data-step'));
            s.classList.remove('active','done');
            if(sn < currentStep) s.classList.add('done');
            if(sn === currentStep) s.classList.add('active');
        });

        // update panels
        panels.forEach(function(p){
            p.classList.remove('active');
            if(parseInt(p.getAttribute('data-panel')) === currentStep) p.classList.add('active');
        });

        // buttons
        btnPrev.style.display = currentStep === 1 ? 'none' : '';
        btnNext.style.display = currentStep === totalSteps ? 'none' : '';
        btnSubmit.style.display = currentStep === totalSteps ? '' : 'none';

        // progress
        progressBar.style.width = ((currentStep / totalSteps) * 100) + '%';
        stepNum.textContent = currentStep;

        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    function validateStep(step) {
        var panel = document.querySelector('.wizard-panel[data-panel="' + step + '"]');
        var required = panel.querySelectorAll('[required]');
        var valid = true;
        required.forEach(function(inp){
            if(!inp.value || !inp.value.trim()) {
                inp.classList.add('is-invalid');
                valid = false;
            } else {
                inp.classList.remove('is-invalid');
            }
        });

        // Special: Step 1 password match
        if(step === 1) {
            var p1 = document.getElementById('regPassword').value;
            var p2 = document.getElementById('regPasswordConfirm').value;
            if(p1 && p2 && p1 !== p2) {
                document.getElementById('regPasswordConfirm').classList.add('is-invalid');
                valid = false;
            }
            if(p1 && p1.length < 8) {
                document.getElementById('regPassword').classList.add('is-invalid');
                valid = false;
            }
        }

        if(!valid) {
            var first = panel.querySelector('.is-invalid');
            if(first) first.focus();
            showToast(t('wc-reg-toast-required','Please fill all required fields'), 'warning');
        }
        return valid;
    }

    btnNext.addEventListener('click', function(){ goToStep(currentStep + 1); });
    btnPrev.addEventListener('click', function(){ goToStep(currentStep - 1); });

    // Clicking wizard step
    steps.forEach(function(s){
        s.addEventListener('click', function(){
            var target = parseInt(this.getAttribute('data-step'));
            if(target < currentStep) { goToStep(target); return; }
            // only go forward if previous steps validated
            for(var i = currentStep; i < target; i++){
                if(!validateStep(i)) return;
            }
            goToStep(target);
        });
    });

    // Helper: get value by id
    function v(id){ var el = document.getElementById(id); return el ? (el.value || '') : ''; }

    // Map of field errors to wizard step numbers for navigation on error
    var fieldStepMap = {
        first_name: 1, last_name: 1, email: 1, password: 1, phone: 1,
        middle_name: 1, maiden_name: 1, date_of_birth: 1, place_of_birth: 1,
        gender: 1, nationality: 1, citizenship: 1, preferred_language: 1,
        passport_number: 2, passport_issue_date: 2, passport_expiry_date: 2,
        passport_authority: 2, passport_country: 2, pesel: 2, nip: 2,
        pl_street: 3, pl_postal_code: 3, pl_city: 3, pl_voivodeship: 3,
        address: 3, city: 3, postal_code: 3, voivodeship: 3,
        immigration_status: 4, stay_purpose: 4, service_needed: 4,
        marital_status: 5,
        education_level: 6, employment_status: 6
    };

    // Collect all form data and build FormData
    function collectFormData() {
        var fd = new FormData();

        // Step 1 — Personal
        fd.append('first_name', v('firstName'));
        fd.append('last_name', v('lastName'));
        fd.append('email', v('email'));
        fd.append('password', v('regPassword'));
        fd.append('phone', v('phone'));
        if(v('middleName')) fd.append('middle_name', v('middleName'));
        if(v('maidenName')) fd.append('maiden_name', v('maidenName'));
        if(v('dob')) fd.append('date_of_birth', v('dob'));
        if(v('birthPlace')) fd.append('place_of_birth', v('birthPlace'));
        if(v('gender')) fd.append('gender', v('gender'));
        if(v('nationality')) fd.append('nationality', v('nationality'));
        if(v('citizenship')) fd.append('citizenship', v('citizenship'));
        if(v('phone2')) fd.append('phone2', v('phone2'));
        if(v('messenger')) fd.append('messenger', v('messenger'));
        if(v('prefLang')) fd.append('preferred_language', v('prefLang'));

        // Step 2 — Documents
        if(v('passportNo')) fd.append('passport_number', v('passportNo'));
        if(v('passportIssue')) fd.append('passport_issue_date', v('passportIssue'));
        if(v('passportExpiry')) fd.append('passport_expiry_date', v('passportExpiry'));
        if(v('passportAuthority')) fd.append('passport_authority', v('passportAuthority'));
        if(v('passportCountry')) fd.append('passport_country', v('passportCountry'));
        if(v('pesel')) fd.append('pesel', v('pesel'));
        if(v('nip')) fd.append('nip', v('nip'));
        if(v('regon')) fd.append('regon', v('regon'));
        if(v('nationalId')) fd.append('national_id', v('nationalId'));
        if(v('driverLicense')) fd.append('driver_license', v('driverLicense'));
        if(v('prevPassport')) fd.append('prev_passport', v('prevPassport'));
        if(v('zus')) fd.append('zus_number', v('zus'));

        // Step 3 — Address (compose address/city/postal_code/voivodeship for the API)
        if(v('plStreet')) fd.append('address', v('plStreet') + (v('plApt') ? ', ' + v('plApt') : ''));
        if(v('plCity')) fd.append('city', v('plCity'));
        if(v('plPostal')) fd.append('postal_code', v('plPostal'));
        if(v('plVoivodeship')) fd.append('voivodeship', v('plVoivodeship'));
        if(v('plStreet')) fd.append('pl_street', v('plStreet'));
        if(v('plApt')) fd.append('pl_apartment', v('plApt'));
        if(v('zameldowanie')) fd.append('zameldowanie', v('zameldowanie'));
        if(v('plLivingSince')) fd.append('pl_living_since', v('plLivingSince'));
        if(v('homeAddress')) fd.append('home_address', v('homeAddress'));
        if(v('homeCountry')) fd.append('home_country', v('homeCountry'));
        if(v('homePhone')) fd.append('home_phone', v('homePhone'));
        fd.append('same_correspondence_address', document.getElementById('sameAddr').checked ? '1' : '0');
        if(v('corrStreet')) fd.append('corr_street', v('corrStreet'));
        if(v('corrCity')) fd.append('corr_city', v('corrCity'));

        // Step 4 — Immigration
        if(v('immStatus')) fd.append('immigration_status', v('immStatus'));
        if(v('stayPurpose')) fd.append('stay_purpose', v('stayPurpose'));
        if(v('arrivalDate')) fd.append('arrival_date', v('arrivalDate'));
        if(v('permitFrom')) fd.append('permit_from', v('permitFrom'));
        if(v('permitUntil')) fd.append('permit_until', v('permitUntil'));
        if(v('permitNo')) fd.append('permit_number', v('permitNo'));
        if(v('kartaPobytu')) fd.append('karta_pobytu', v('kartaPobytu'));
        if(v('prevApp')) fd.append('previous_application', v('prevApp'));
        if(v('prevAppDetails')) fd.append('prev_app_details', v('prevAppDetails'));
        fd.append('entry_ban', v('entryBan') === 'Yes' ? '1' : '0');
        fd.append('criminal_record', v('criminal') === 'Yes' ? '1' : '0');
        if(v('serviceNeeded')) fd.append('service_needed', v('serviceNeeded'));
        if(v('immNotes')) fd.append('immigration_notes', v('immNotes'));

        // Step 5 — Family
        if(v('marital')) fd.append('marital_status', v('marital'));
        fd.append('num_children', v('numChildren') || '0');
        fd.append('dependents_in_poland', v('dependents') || '0');

        // Family members as JSON array
        var fm = [];
        document.querySelectorAll('.family-row').forEach(function(row){
            var selects = row.querySelectorAll('select');
            var inputs = row.querySelectorAll('input');
            var member = {
                relation: selects[0] ? selects[0].value : '',
                full_name: inputs[0] ? inputs[0].value : '',
                dob: inputs[1] ? inputs[1].value : '',
                nationality: inputs[2] ? inputs[2].value : '',
                in_poland: selects[1] ? selects[1].value : '',
                passport: inputs[3] ? inputs[3].value : '',
                pesel: inputs[4] ? inputs[4].value : '',
                immigration_status: selects[2] ? selects[2].value : ''
            };
            // Only add if at least name is filled
            if(member.full_name) fm.push(member);
        });
        if(fm.length > 0) fd.append('family_members', JSON.stringify(fm));

        // Step 6 — Education & Work
        if(v('education')) fd.append('education_level', v('education'));
        if(v('fieldOfStudy')) fd.append('field_of_study', v('fieldOfStudy'));
        if(v('institution')) fd.append('institution', v('institution'));
        if(v('gradYear')) fd.append('graduation_year', v('gradYear'));
        if(v('eduCountry')) fd.append('education_country', v('eduCountry'));
        if(v('polishLevel')) fd.append('polish_level', v('polishLevel'));
        if(v('otherLangs')) fd.append('other_languages', v('otherLangs'));
        if(v('empStatus')) fd.append('employment_status', v('empStatus'));
        if(v('profession')) fd.append('profession', v('profession'));
        if(v('employer')) fd.append('employer_name', v('employer'));
        if(v('empNip')) fd.append('employer_nip', v('empNip'));
        if(v('empAddress')) fd.append('employer_address', v('empAddress'));
        if(v('empSince')) fd.append('employment_since', v('empSince'));
        if(v('salary')) fd.append('salary', v('salary'));
        if(v('workPermit')) fd.append('work_permit_type', v('workPermit'));
        if(v('wpExpiry')) fd.append('work_permit_expiry', v('wpExpiry'));
        if(v('healthIns')) fd.append('health_insurance', v('healthIns'));
        fd.append('bank_account_poland', v('bankAccount') === 'Yes' ? '1' : '0');
        if(v('taxRes')) fd.append('tax_residency', v('taxRes'));

        // Step 7 — Agreements
        fd.append('agreed_terms', document.getElementById('agreeTerms').checked ? '1' : '0');
        fd.append('agreed_rodo', document.getElementById('agreeRodo').checked ? '1' : '0');
        fd.append('agreed_poa', document.getElementById('agreePoa').checked ? '1' : '0');
        fd.append('agreed_data_sharing', document.getElementById('agreeData').checked ? '1' : '0');
        fd.append('agreed_marketing', document.getElementById('agreeMarketing').checked ? '1' : '0');
        if(v('digitalSignature')) fd.append('digital_signature', v('digitalSignature'));

        return fd;
    }

    // Submit handler
    btnSubmit.addEventListener('click', function(){
        // Validate all steps 1-6 first
        for(var s = 1; s <= 6; s++) {
            if(!validateStep(s)) {
                goToStepDirect(s);
                showToast(t('wc-reg-toast-required','Please fill all required fields in step ') + s, 'warning');
                return;
            }
        }

        // Validate step 7 agreements
        var checks = document.querySelectorAll('.agree-check');
        var allChecked = true;
        checks.forEach(function(c){ if(!c.checked) allChecked = false; });
        var sig = document.getElementById('digitalSignature').value.trim();

        if(!allChecked || !sig) {
            document.getElementById('agreeAlert').style.display = '';
            showToast(t('wc-reg-toast-agreements','Please accept all agreements and sign'), 'warning');
            return;
        }
        document.getElementById('agreeAlert').style.display = 'none';

        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + t('wc-reg-registering','Registering...');
        var self = this;

        var formData = collectFormData();

        fetch('/api/v1/auth/register-client', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(function(resp){ return resp.json(); })
        .then(function(result){
            if(result.success) {
                // Store auth data
                localStorage.setItem('wc_token', result.token);
                if(result.user) {
                    localStorage.setItem('wc_user', JSON.stringify(result.user));
                }
                // Clear draft
                localStorage.removeItem(DRAFT_KEY);

                Swal.fire({
                    icon: 'success',
                    title: t('wc-reg-swal-success','Registration Complete!'),
                    html: '<p>' + t('wc-reg-swal-success-text','Your account has been created successfully.') + '</p><p class="text-muted small">' + t('wc-reg-swal-success-desc','A confirmation email has been sent. Our team will review your information and contact you within 1-2 business days.') + '</p>',
                    confirmButtonColor: '#015EA7',
                    confirmButtonText: t('wc-reg-swal-go-dashboard','Go to Dashboard'),
                    allowOutsideClick: false
                }).then(function(){
                    window.location.href = result.redirect || '/client-dashboard';
                });
            } else {
                // Determine which step has errors and navigate there
                var errorStep = 7;
                var errs = result.errors ? Object.values(result.errors).flat().join('<br>') : (result.message || t('wc-reg-swal-fail','Registration failed'));

                if(result.errors) {
                    var errorFields = Object.keys(result.errors);
                    for(var i = 0; i < errorFields.length; i++) {
                        var s = fieldStepMap[errorFields[i]];
                        if(s && s < errorStep) errorStep = s;
                    }
                }

                Swal.fire({
                    icon: 'error',
                    title: t('wc-reg-swal-error','Error'),
                    html: errs,
                    confirmButtonColor: '#015EA7'
                }).then(function(){
                    if(errorStep < 7) goToStepDirect(errorStep);
                });
            }
            self.disabled = false;
            self.innerHTML = '<i class="ri-check-line me-1"></i><span data-lang="wc-reg-complete">' + t('wc-reg-complete','Complete Registration') + '</span>';
        })
        .catch(function(err){
            Swal.fire({icon:'error', title: t('wc-reg-swal-error','Error'), text: err.message || t('wc-reg-swal-network','Network error'), confirmButtonColor:'#015EA7'});
            self.disabled = false;
            self.innerHTML = '<i class="ri-check-line me-1"></i><span data-lang="wc-reg-complete">' + t('wc-reg-complete','Complete Registration') + '</span>';
        });
    });

    // Save Draft — persist to localStorage
    document.getElementById('btnSaveDraft').addEventListener('click', function(){
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + t('wc-reg-saving','Saving...');
        var self = this;

        // Collect all field values into a plain object for localStorage
        var draft = {};
        var ids = [
            'firstName','lastName','middleName','maidenName','dob','birthPlace','gender',
            'nationality','citizenship','phone','phone2','messenger','email','prefLang',
            'regPassword','regPasswordConfirm',
            'passportNo','passportIssue','passportExpiry','passportAuthority','passportCountry',
            'pesel','nip','regon','nationalId','driverLicense','prevPassport','zus',
            'plStreet','plApt','plPostal','plCity','plVoivodeship','zameldowanie','plLivingSince',
            'homeAddress','homeCountry','homePhone','corrStreet','corrCity',
            'immStatus','stayPurpose','arrivalDate','permitFrom','permitUntil','permitNo',
            'kartaPobytu','prevApp','prevAppDetails','entryBan','criminal','serviceNeeded','immNotes',
            'marital','numChildren','dependents',
            'education','fieldOfStudy','institution','gradYear','eduCountry','polishLevel','otherLangs',
            'empStatus','profession','employer','empNip','empAddress','empSince','salary',
            'workPermit','wpExpiry','healthIns','bankAccount','taxRes',
            'digitalSignature'
        ];
        ids.forEach(function(id){ draft[id] = v(id); });
        draft.sameAddr = document.getElementById('sameAddr').checked;
        draft.currentStep = currentStep;

        try {
            localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
        } catch(e) {}

        setTimeout(function(){
            self.disabled = false;
            self.innerHTML = '<i class="ri-save-line me-1"></i><span data-lang="wc-reg-save-draft">' + t('wc-reg-save-draft','Save Draft') + '</span>';
            showToast(t('wc-reg-toast-draft','Draft saved successfully'), 'success');
        }, 400);
    });

    // Restore draft on load
    (function restoreDraft(){
        try {
            var raw = localStorage.getItem(DRAFT_KEY);
            if(!raw) return;
            var draft = JSON.parse(raw);
            Object.keys(draft).forEach(function(id){
                if(id === 'sameAddr' || id === 'currentStep') return;
                var el = document.getElementById(id);
                if(el) el.value = draft[id] || '';
            });
            if(typeof draft.sameAddr !== 'undefined') {
                document.getElementById('sameAddr').checked = !!draft.sameAddr;
                document.getElementById('corrAddrBlock').style.display = draft.sameAddr ? 'none' : '';
            }
        } catch(e) {}
    })();

    // Add family member
    var familyIdx = 1;
    document.getElementById('btnAddFamily').addEventListener('click', function(){
        var tpl = document.querySelector('.family-row').cloneNode(true);
        tpl.setAttribute('data-idx', familyIdx++);
        var inputs = tpl.querySelectorAll('input');
        inputs.forEach(function(i){ i.value = ''; });
        var selects = tpl.querySelectorAll('select');
        selects.forEach(function(s){ s.selectedIndex = 0; });
        document.getElementById('familyContainer').appendChild(tpl);
    });

    // Remove family member
    document.getElementById('familyContainer').addEventListener('click', function(e){
        var btn = e.target.closest('.btn-remove-family');
        if(!btn) return;
        var rows = document.querySelectorAll('.family-row');
        if(rows.length <= 1) {
            showToast(t('wc-reg-toast-family-min','At least one family member row must remain'), 'info');
            return;
        }
        btn.closest('.family-row').remove();
    });

    // Correspondence address toggle
    document.getElementById('sameAddr').addEventListener('change', function(){
        document.getElementById('corrAddrBlock').style.display = this.checked ? 'none' : '';
    });

    // Toggle password
    var togBtn = document.querySelector('.toggle-pass-reg');
    if(togBtn) {
        togBtn.addEventListener('click', function(){
            var inp = document.getElementById('regPassword');
            var icon = this.querySelector('i');
            if(inp.type === 'password'){
                inp.type = 'text'; icon.className = 'ri-eye-line';
            } else {
                inp.type = 'password'; icon.className = 'ri-eye-off-line';
            }
        });
    }

    // Clear invalid on input
    document.addEventListener('input', function(e){
        if(e.target.classList.contains('is-invalid')) e.target.classList.remove('is-invalid');
    });

    function showToast(msg, type){
        var tt = document.createElement('div');
        tt.className = 'position-fixed top-0 end-0 m-3 alert alert-' + (type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info') + ' shadow-sm';
        tt.style.zIndex = '9999';
        tt.innerHTML = '<i class="ri-' + (type==='success'?'check-line':type==='warning'?'error-warning-line':'information-line') + ' me-1"></i>' + msg;
        document.body.appendChild(tt);
        setTimeout(function(){ tt.style.opacity = '0'; tt.style.transition = 'opacity .3s'; setTimeout(function(){ tt.remove(); }, 300); }, 3000);
    }
})();
</script>
@endsection
