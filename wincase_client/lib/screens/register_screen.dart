import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import '../widgets/wc_logo.dart';
import '../services/api_service.dart';
import 'login_screen.dart';
import 'main_shell.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});
  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  int _step = 0;
  bool _loading = false;
  final _formKeys = List.generate(7, (_) => GlobalKey<FormState>());

  // Step 1 — Personal
  final _firstName = TextEditingController();
  final _lastName = TextEditingController();
  final _dob = TextEditingController();
  final _birthPlace = TextEditingController();
  String _gender = 'Male';
  String _nationality = 'Ukrainian';
  final _phone = TextEditingController();
  final _email = TextEditingController();
  final _password = TextEditingController();
  bool _obscure = true;

  // Step 2 — Documents
  final _passportSeries = TextEditingController();
  final _passportNumber = TextEditingController();
  final _passportIssued = TextEditingController();
  final _passportExpiry = TextEditingController();
  final _passportAuthority = TextEditingController();
  final _pesel = TextEditingController();
  final _nip = TextEditingController();

  // Step 3 — Address
  final _street = TextEditingController();
  final _building = TextEditingController();
  final _apartment = TextEditingController();
  final _postalCode = TextEditingController();
  final _city = TextEditingController();
  String _voivodeship = 'Mazowieckie';
  bool _zameldowanie = false;
  final _homeCountryAddress = TextEditingController();

  // Step 4 — Immigration
  String _immigrationStatus = 'Visa';
  String _purposeOfStay = 'Work';
  final _entryDate = TextEditingController();
  final _currentPermitExpiry = TextEditingController();
  bool _previousApplications = false;
  String _serviceType = 'Temporary Residence Permit';

  // Step 5 — Family
  String _maritalStatus = 'Single';
  int _childrenCount = 0;
  final List<Map<String, TextEditingController>> _familyMembers = [];

  // Step 6 — Education & Work
  String _educationLevel = 'Higher (Bachelor)';
  final _employerName = TextEditingController();
  final _position = TextEditingController();
  String _contractType = 'Umowa o pracę';
  final _salary = TextEditingController();
  bool _hasWorkPermit = false;
  bool _hasHealthInsurance = true;

  // Step 7 — Agreements
  bool _agreeTerms = false;
  bool _agreeRodo = false;
  bool _agreePoa = false;
  bool _agreeDataShare = false;
  bool _agreeMarketing = false;

  final _stepTitles = [
    'Personal Data',
    'Documents',
    'Address',
    'Immigration',
    'Family',
    'Education & Work',
    'Agreements',
  ];

  final _stepIcons = [
    Icons.person_outline,
    Icons.badge_outlined,
    Icons.home_outlined,
    Icons.flight_land,
    Icons.family_restroom,
    Icons.school_outlined,
    Icons.gavel,
  ];

  final _nationalities = ['Ukrainian', 'Belarusian', 'Russian', 'Georgian', 'Indian', 'Moldovan', 'Vietnamese', 'Turkish', 'Chinese', 'Other'];
  final _voivodeships = ['Dolnośląskie', 'Kujawsko-Pomorskie', 'Lubelskie', 'Lubuskie', 'Łódzkie', 'Małopolskie', 'Mazowieckie', 'Opolskie', 'Podkarpackie', 'Podlaskie', 'Pomorskie', 'Śląskie', 'Świętokrzyskie', 'Warmińsko-Mazurskie', 'Wielkopolskie', 'Zachodniopomorskie'];
  final _immStatuses = ['Visa', 'Visa-Free Stay', 'Temporary Residence Permit', 'Permanent Residence Permit', 'EU Long-Term Resident', 'Refugee Status', 'Tolerated Stay', 'Humanitarian Visa', 'Seasonal Work', 'Student Visa', 'No Legal Status', 'Other'];
  final _purposes = ['Work', 'Business Activity', 'Family Reunification', 'Studies', 'Seasonal Work', 'Job Search', 'Settlement', 'Humanitarian', 'Victim of Trafficking', 'Other'];
  final _services = ['Temporary Residence Permit', 'Permanent Residence Permit', 'EU Long-Term Resident', 'Polish Citizenship', 'Work Permit', 'Visa Extension', 'PESEL Registration', 'Business Registration', 'Family Reunification', 'Other'];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: WC.bg,
      appBar: AppBar(
        title: const WcLogo(fontSize: 22),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => _step > 0 ? setState(() => _step--) : Navigator.pop(context),
        ),
        actions: [
          // TEMP: Skip step without validation
          TextButton.icon(
            onPressed: () {
              if (_step < 6) {
                setState(() => _step++);
              } else {
                Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const MainShell()));
              }
            },
            icon: Icon(_step < 6 ? Icons.skip_next : Icons.login, color: WC.warning, size: 16),
            label: Text(_step < 6 ? 'SKIP →' : 'ENTER', style: const TextStyle(color: WC.warning, fontSize: 12, fontWeight: FontWeight.w700)),
          ),
        ],
      ),
      body: Column(
        children: [
          // Step Progress
          Container(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
            color: Colors.white,
            child: Column(
              children: [
                Row(
                  children: List.generate(7, (i) => Expanded(
                    child: Container(
                      height: 3,
                      margin: EdgeInsets.only(right: i < 6 ? 4 : 0),
                      decoration: BoxDecoration(
                        color: i <= _step ? WC.primary : Colors.grey[200],
                        borderRadius: BorderRadius.circular(2),
                      ),
                    ),
                  )),
                ),
                const SizedBox(height: 8),
                Row(
                  children: [
                    Icon(_stepIcons[_step], size: 18, color: WC.primary),
                    const SizedBox(width: 8),
                    Text('Step ${_step + 1}/7: ${_stepTitles[_step]}',
                        style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600)),
                    const Spacer(),
                    Text('${((_step + 1) / 7 * 100).round()}%',
                        style: const TextStyle(fontSize: 12, color: WC.primary, fontWeight: FontWeight.w600)),
                  ],
                ),
              ],
            ),
          ),

          // Form Body
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Card(
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                child: Padding(
                  padding: const EdgeInsets.all(20),
                  child: Form(
                    key: _formKeys[_step],
                    child: _buildStep(),
                  ),
                ),
              ),
            ),
          ),

          // Navigation Buttons
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.white,
            child: SafeArea(
              top: false,
              child: Row(
                children: [
                  if (_step > 0)
                    Expanded(
                      child: OutlinedButton(
                        onPressed: () => setState(() => _step--),
                        child: const Text('Back'),
                      ),
                    ),
                  if (_step > 0) const SizedBox(width: 12),
                  Expanded(
                    flex: _step == 0 ? 1 : 1,
                    child: ElevatedButton(
                      onPressed: _loading ? null : _next,
                      child: _loading
                          ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                          : Text(_step == 6 ? 'Submit Registration' : 'Continue'),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStep() {
    switch (_step) {
      case 0: return _step1Personal();
      case 1: return _step2Documents();
      case 2: return _step3Address();
      case 3: return _step4Immigration();
      case 4: return _step5Family();
      case 5: return _step6Education();
      case 6: return _step7Agreements();
      default: return const SizedBox();
    }
  }

  // ==== STEP 1: Personal ====
  Widget _step1Personal() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _sectionTitle('Personal Information'),
        const SizedBox(height: 12),
        _field('First Name *', _firstName, validator: _req),
        _field('Last Name *', _lastName, validator: _req),
        _field('Date of Birth *', _dob, hint: 'DD.MM.YYYY', icon: Icons.calendar_today, validator: _req),
        _field('Place of Birth', _birthPlace),
        _dropdown('Gender', _gender, ['Male', 'Female', 'Other'], (v) => setState(() => _gender = v!)),
        _dropdown('Nationality *', _nationality, _nationalities, (v) => setState(() => _nationality = v!)),
        _field('Phone *', _phone, keyboard: TextInputType.phone, icon: Icons.phone, validator: _req),
        _field('Email *', _email, keyboard: TextInputType.emailAddress, icon: Icons.email, validator: _req),
        const SizedBox(height: 8),
        TextFormField(
          controller: _password,
          obscureText: _obscure,
          validator: (v) => v != null && v.length >= 6 ? null : 'Min 6 characters',
          decoration: InputDecoration(
            labelText: 'Password *',
            prefixIcon: const Icon(Icons.lock_outline, size: 20),
            suffixIcon: IconButton(
              icon: Icon(_obscure ? Icons.visibility_off : Icons.visibility, size: 20),
              onPressed: () => setState(() => _obscure = !_obscure),
            ),
          ),
        ),
      ],
    );
  }

  // ==== STEP 2: Documents ====
  Widget _step2Documents() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _sectionTitle('Passport Details'),
        const SizedBox(height: 12),
        Row(children: [
          Expanded(child: _field('Series', _passportSeries)),
          const SizedBox(width: 10),
          Expanded(flex: 2, child: _field('Number *', _passportNumber, validator: _req)),
        ]),
        _field('Date of Issue', _passportIssued, hint: 'DD.MM.YYYY', icon: Icons.calendar_today),
        _field('Expiry Date', _passportExpiry, hint: 'DD.MM.YYYY', icon: Icons.calendar_today),
        _field('Issuing Authority', _passportAuthority),
        const SizedBox(height: 16),
        _sectionTitle('Polish Identifiers'),
        const SizedBox(height: 12),
        _field('PESEL', _pesel, keyboard: TextInputType.number, hint: '11 digits'),
        _field('NIP (Tax ID)', _nip, keyboard: TextInputType.number, hint: '10 digits'),
      ],
    );
  }

  // ==== STEP 3: Address ====
  Widget _step3Address() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _sectionTitle('Address in Poland'),
        const SizedBox(height: 12),
        _field('Street *', _street, validator: _req),
        Row(children: [
          Expanded(child: _field('Building *', _building, validator: _req)),
          const SizedBox(width: 10),
          Expanded(child: _field('Apartment', _apartment)),
        ]),
        Row(children: [
          Expanded(child: _field('Postal Code *', _postalCode, hint: '00-000', validator: _req)),
          const SizedBox(width: 10),
          Expanded(flex: 2, child: _field('City *', _city, validator: _req)),
        ]),
        _dropdown('Voivodeship *', _voivodeship, _voivodeships, (v) => setState(() => _voivodeship = v!)),
        const SizedBox(height: 8),
        SwitchListTile(
          value: _zameldowanie,
          onChanged: (v) => setState(() => _zameldowanie = v),
          title: const Text('Registered at this address (zameldowanie)', style: TextStyle(fontSize: 13)),
          activeColor: WC.primary,
          contentPadding: EdgeInsets.zero,
        ),
        const SizedBox(height: 12),
        _sectionTitle('Home Country Address'),
        const SizedBox(height: 12),
        _field('Full Address', _homeCountryAddress, maxLines: 2),
      ],
    );
  }

  // ==== STEP 4: Immigration ====
  Widget _step4Immigration() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _sectionTitle('Current Immigration Status'),
        const SizedBox(height: 12),
        _dropdown('Status *', _immigrationStatus, _immStatuses, (v) => setState(() => _immigrationStatus = v!)),
        _dropdown('Purpose of Stay *', _purposeOfStay, _purposes, (v) => setState(() => _purposeOfStay = v!)),
        _field('Entry Date to Poland', _entryDate, hint: 'DD.MM.YYYY', icon: Icons.flight_land),
        _field('Current Permit Expiry', _currentPermitExpiry, hint: 'DD.MM.YYYY', icon: Icons.calendar_today),
        const SizedBox(height: 8),
        SwitchListTile(
          value: _previousApplications,
          onChanged: (v) => setState(() => _previousApplications = v),
          title: const Text('Previous applications to Urząd?', style: TextStyle(fontSize: 13)),
          activeColor: WC.primary,
          contentPadding: EdgeInsets.zero,
        ),
        const SizedBox(height: 12),
        _sectionTitle('Requested Service'),
        const SizedBox(height: 12),
        _dropdown('Service Type *', _serviceType, _services, (v) => setState(() => _serviceType = v!)),
      ],
    );
  }

  // ==== STEP 5: Family ====
  Widget _step5Family() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _sectionTitle('Family Status'),
        const SizedBox(height: 12),
        _dropdown('Marital Status', _maritalStatus, ['Single', 'Married', 'Divorced', 'Widowed', 'Partnership'], (v) => setState(() => _maritalStatus = v!)),
        const SizedBox(height: 8),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            const Text('Number of Children', style: TextStyle(fontSize: 13)),
            Row(
              children: [
                IconButton(
                  onPressed: _childrenCount > 0 ? () => setState(() => _childrenCount--) : null,
                  icon: const Icon(Icons.remove_circle_outline),
                  color: WC.danger,
                  iconSize: 22,
                ),
                Text('$_childrenCount', style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w600)),
                IconButton(
                  onPressed: () => setState(() => _childrenCount++),
                  icon: const Icon(Icons.add_circle_outline),
                  color: WC.primary,
                  iconSize: 22,
                ),
              ],
            ),
          ],
        ),
        const SizedBox(height: 16),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            _sectionTitle('Family Members in Poland'),
            TextButton.icon(
              onPressed: () {
                setState(() {
                  _familyMembers.add({
                    'name': TextEditingController(),
                    'relation': TextEditingController(),
                    'dob': TextEditingController(),
                  });
                });
              },
              icon: const Icon(Icons.add, size: 16),
              label: const Text('Add', style: TextStyle(fontSize: 12)),
            ),
          ],
        ),
        if (_familyMembers.isEmpty)
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(color: Colors.grey[50], borderRadius: BorderRadius.circular(12)),
            child: const Center(child: Text('No family members added', style: TextStyle(fontSize: 12, color: Colors.grey))),
          ),
        ..._familyMembers.asMap().entries.map((e) => Container(
          margin: const EdgeInsets.only(bottom: 10),
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            border: Border.all(color: Colors.grey[200]!),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Column(
            children: [
              Row(
                children: [
                  Text('Member ${e.key + 1}', style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                  const Spacer(),
                  IconButton(
                    onPressed: () => setState(() => _familyMembers.removeAt(e.key)),
                    icon: const Icon(Icons.close, size: 18, color: WC.danger),
                    constraints: const BoxConstraints(),
                    padding: EdgeInsets.zero,
                  ),
                ],
              ),
              const SizedBox(height: 8),
              _field('Full Name', e.value['name']!),
              _field('Relationship', e.value['relation']!, hint: 'e.g. Spouse, Child, Parent'),
              _field('Date of Birth', e.value['dob']!, hint: 'DD.MM.YYYY'),
            ],
          ),
        )),
      ],
    );
  }

  // ==== STEP 6: Education & Work ====
  Widget _step6Education() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _sectionTitle('Education'),
        const SizedBox(height: 12),
        _dropdown('Education Level', _educationLevel,
            ['Primary', 'Secondary', 'Vocational', 'Higher (Bachelor)', 'Higher (Master)', 'PhD', 'Other'],
            (v) => setState(() => _educationLevel = v!)),
        const SizedBox(height: 16),
        _sectionTitle('Employment'),
        const SizedBox(height: 12),
        _field('Employer Name', _employerName),
        _field('Position / Title', _position),
        _dropdown('Contract Type', _contractType,
            ['Umowa o pracę', 'Umowa zlecenie', 'Umowa o dzieło', 'Kontrakt B2B', 'Self-Employed', 'Other'],
            (v) => setState(() => _contractType = v!)),
        _field('Monthly Salary (PLN)', _salary, keyboard: TextInputType.number, icon: Icons.attach_money),
        const SizedBox(height: 8),
        SwitchListTile(
          value: _hasWorkPermit,
          onChanged: (v) => setState(() => _hasWorkPermit = v),
          title: const Text('Have current work permit?', style: TextStyle(fontSize: 13)),
          activeColor: WC.primary,
          contentPadding: EdgeInsets.zero,
        ),
        SwitchListTile(
          value: _hasHealthInsurance,
          onChanged: (v) => setState(() => _hasHealthInsurance = v),
          title: const Text('Have health insurance (NFZ/ZUS)?', style: TextStyle(fontSize: 13)),
          activeColor: WC.primary,
          contentPadding: EdgeInsets.zero,
        ),
      ],
    );
  }

  // ==== STEP 7: Agreements ====
  Widget _step7Agreements() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _sectionTitle('Required Agreements'),
        const SizedBox(height: 12),
        _agreementTile(
          'Terms and Conditions *',
          'I agree to the WinCase Client Portal Terms and Conditions including service scope, responsibilities, and obligations.',
          _agreeTerms,
          (v) => setState(() => _agreeTerms = v!),
        ),
        _agreementTile(
          'RODO / GDPR Consent *',
          'I consent to the processing of my personal data in accordance with GDPR (Regulation 2016/679) for the purpose of immigration services.',
          _agreeRodo,
          (v) => setState(() => _agreeRodo = v!),
        ),
        _agreementTile(
          'Power of Attorney *',
          'I authorize WinCase sp. z o.o. to represent me before the Voivodeship Office and other government institutions in immigration matters.',
          _agreePoa,
          (v) => setState(() => _agreePoa = v!),
        ),
        _agreementTile(
          'Data Sharing Consent *',
          'I consent to sharing my data with relevant government agencies, translators, and partners necessary for processing my case.',
          _agreeDataShare,
          (v) => setState(() => _agreeDataShare = v!),
        ),
        const Divider(height: 24),
        _agreementTile(
          'Marketing (optional)',
          'I agree to receive marketing communications and updates from WinCase via email and SMS.',
          _agreeMarketing,
          (v) => setState(() => _agreeMarketing = v!),
          required: false,
        ),
        const SizedBox(height: 16),
        Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(color: WC.info.withAlpha(15), borderRadius: BorderRadius.circular(12)),
          child: const Row(
            children: [
              Icon(Icons.info_outline, size: 18, color: WC.info),
              SizedBox(width: 10),
              Expanded(child: Text('By submitting, you confirm that all provided information is accurate and complete.', style: TextStyle(fontSize: 11, color: Colors.black87))),
            ],
          ),
        ),
      ],
    );
  }

  // ==== Helpers ====
  Widget _sectionTitle(String text) {
    return Text(text, style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w600, color: WC.textPrimary));
  }

  Widget _field(String label, TextEditingController ctrl, {
    String? hint, IconData? icon, TextInputType? keyboard,
    String? Function(String?)? validator, int maxLines = 1,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: TextFormField(
        controller: ctrl,
        keyboardType: keyboard,
        validator: validator,
        maxLines: maxLines,
        decoration: InputDecoration(
          labelText: label,
          hintText: hint,
          prefixIcon: icon != null ? Icon(icon, size: 20) : null,
        ),
      ),
    );
  }

  Widget _dropdown(String label, String value, List<String> items, ValueChanged<String?> onChanged) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: DropdownButtonFormField<String>(
        value: value,
        isExpanded: true,
        decoration: InputDecoration(labelText: label),
        items: items.map((e) => DropdownMenuItem(value: e, child: Text(e, style: const TextStyle(fontSize: 14)))).toList(),
        onChanged: onChanged,
      ),
    );
  }

  Widget _agreementTile(String title, String desc, bool value, ValueChanged<bool?> onChanged, {bool required = true}) {
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        border: Border.all(color: value ? WC.primary.withAlpha(60) : Colors.grey[200]!),
        borderRadius: BorderRadius.circular(12),
        color: value ? WC.primary.withAlpha(8) : null,
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 24, height: 24,
            child: Checkbox(
              value: value,
              onChanged: onChanged,
              activeColor: WC.primary,
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: TextStyle(
                  fontWeight: FontWeight.w600, fontSize: 13,
                  color: required && !value ? WC.danger : WC.textPrimary,
                )),
                const SizedBox(height: 4),
                Text(desc, style: TextStyle(fontSize: 11, color: Colors.grey[600], height: 1.4)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  String? _req(String? v) => v != null && v.isNotEmpty ? null : 'Required';

  void _next() {
    if (!_formKeys[_step].currentState!.validate()) return;

    if (_step == 6) {
      // Validate agreements
      if (!_agreeTerms || !_agreeRodo || !_agreePoa || !_agreeDataShare) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Please accept all required agreements'), backgroundColor: WC.danger),
        );
        return;
      }
      _submit();
    } else {
      setState(() => _step++);
    }
  }

  Future<void> _submit() async {
    setState(() => _loading = true);
    try {
      final data = {
        'first_name': _firstName.text,
        'last_name': _lastName.text,
        'date_of_birth': _dob.text,
        'birth_place': _birthPlace.text,
        'gender': _gender.toLowerCase(),
        'nationality': _nationality.toLowerCase(),
        'phone': _phone.text,
        'email': _email.text,
        'password': _password.text,
        'passport_series': _passportSeries.text,
        'passport_number': _passportNumber.text,
        'passport_issue_date': _passportIssued.text,
        'passport_expiry_date': _passportExpiry.text,
        'passport_authority': _passportAuthority.text,
        'pesel': _pesel.text,
        'nip': _nip.text,
        'street': _street.text,
        'building_number': _building.text,
        'apartment_number': _apartment.text,
        'postal_code': _postalCode.text,
        'city': _city.text,
        'voivodeship': _voivodeship.toLowerCase(),
        'zameldowanie': _zameldowanie,
        'home_country_address': _homeCountryAddress.text,
        'immigration_status': _immigrationStatus.toLowerCase(),
        'purpose_of_stay': _purposeOfStay.toLowerCase(),
        'entry_date': _entryDate.text,
        'current_permit_expiry': _currentPermitExpiry.text,
        'previous_applications': _previousApplications,
        'service_type': _serviceType,
        'marital_status': _maritalStatus.toLowerCase(),
        'children_count': _childrenCount,
        'family_members': _familyMembers.map((m) => {
          'name': m['name']!.text,
          'relation': m['relation']!.text,
          'dob': m['dob']!.text,
        }).toList(),
        'education_level': _educationLevel,
        'employer_name': _employerName.text,
        'position': _position.text,
        'contract_type': _contractType,
        'salary': _salary.text,
        'has_work_permit': _hasWorkPermit,
        'has_health_insurance': _hasHealthInsurance,
        'agree_terms': _agreeTerms,
        'agree_rodo': _agreeRodo,
        'agree_poa': _agreePoa,
        'agree_data_share': _agreeDataShare,
        'agree_marketing': _agreeMarketing,
      };

      final result = await ApiService.register(data);

      if (!mounted) return;

      if (result['id'] != null) {
        _showSuccess();
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(result['message'] ?? 'Registration failed'), backgroundColor: WC.danger),
        );
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $e'), backgroundColor: WC.danger),
      );
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  void _showSuccess() {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 64, height: 64,
              decoration: BoxDecoration(color: WC.primary.withAlpha(20), shape: BoxShape.circle),
              child: const Icon(Icons.check_circle, color: WC.primary, size: 40),
            ),
            const SizedBox(height: 16),
            const Text('Registration Successful!', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700)),
            const SizedBox(height: 8),
            Text('Your registration has been submitted. A manager will review it and contact you shortly.',
                textAlign: TextAlign.center, style: TextStyle(fontSize: 13, color: Colors.grey[600])),
            const SizedBox(height: 20),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  Navigator.pop(ctx);
                  Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const LoginScreen()));
                },
                child: const Text('Go to Login'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
