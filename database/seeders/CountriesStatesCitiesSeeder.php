<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Database\Seeder;

class CountriesStatesCitiesSeeder extends Seeder
{
    public function run(): void
    {
        // ── Country: India ──────────────────────────────────────────────
        $india = Country::updateOrCreate(
            ['iso_code' => 'IN'],
            ['name' => 'India', 'iso_code' => 'IN', 'phone_code' => '+91', 'sort_order' => 1]
        );

        $states = [
            ['name' => 'Andhra Pradesh',        'code' => 'AP', 'sort_order' => 1],
            ['name' => 'Arunachal Pradesh',     'code' => 'AR', 'sort_order' => 2],
            ['name' => 'Assam',                 'code' => 'AS', 'sort_order' => 3],
            ['name' => 'Bihar',                 'code' => 'BR', 'sort_order' => 4],
            ['name' => 'Chhattisgarh',          'code' => 'CG', 'sort_order' => 5],
            ['name' => 'Goa',                   'code' => 'GA', 'sort_order' => 6],
            ['name' => 'Gujarat',               'code' => 'GJ', 'sort_order' => 7],
            ['name' => 'Haryana',               'code' => 'HR', 'sort_order' => 8],
            ['name' => 'Himachal Pradesh',      'code' => 'HP', 'sort_order' => 9],
            ['name' => 'Jharkhand',             'code' => 'JH', 'sort_order' => 10],
            ['name' => 'Karnataka',             'code' => 'KA', 'sort_order' => 11],
            ['name' => 'Kerala',                'code' => 'KL', 'sort_order' => 12],
            ['name' => 'Madhya Pradesh',        'code' => 'MP', 'sort_order' => 13],
            ['name' => 'Maharashtra',           'code' => 'MH', 'sort_order' => 14],
            ['name' => 'Manipur',               'code' => 'MN', 'sort_order' => 15],
            ['name' => 'Meghalaya',             'code' => 'ML', 'sort_order' => 16],
            ['name' => 'Mizoram',               'code' => 'MZ', 'sort_order' => 17],
            ['name' => 'Nagaland',              'code' => 'NL', 'sort_order' => 18],
            ['name' => 'Odisha',                'code' => 'OD', 'sort_order' => 19],
            ['name' => 'Punjab',                'code' => 'PB', 'sort_order' => 20],
            ['name' => 'Rajasthan',             'code' => 'RJ', 'sort_order' => 21],
            ['name' => 'Sikkim',                'code' => 'SK', 'sort_order' => 22],
            ['name' => 'Tamil Nadu',            'code' => 'TN', 'sort_order' => 23],
            ['name' => 'Telangana',             'code' => 'TG', 'sort_order' => 24],
            ['name' => 'Tripura',               'code' => 'TR', 'sort_order' => 25],
            ['name' => 'Uttar Pradesh',         'code' => 'UP', 'sort_order' => 26],
            ['name' => 'Uttarakhand',           'code' => 'UK', 'sort_order' => 27],
            ['name' => 'West Bengal',           'code' => 'WB', 'sort_order' => 28],
            ['name' => 'Andaman and Nicobar Islands', 'code' => 'AN', 'sort_order' => 29],
            ['name' => 'Chandigarh',            'code' => 'CH', 'sort_order' => 30],
            ['name' => 'Dadra and Nagar Haveli and Daman and Diu', 'code' => 'DD', 'sort_order' => 31],
            ['name' => 'Delhi',                 'code' => 'DL', 'sort_order' => 32],
            ['name' => 'Jammu and Kashmir',     'code' => 'JK', 'sort_order' => 33],
            ['name' => 'Ladakh',                'code' => 'LA', 'sort_order' => 34],
            ['name' => 'Lakshadweep',           'code' => 'LD', 'sort_order' => 35],
            ['name' => 'Puducherry',            'code' => 'PY', 'sort_order' => 36],
        ];

        $cityMap = [
            'Andhra Pradesh'   => ['Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Kurnool', 'Tirupati', 'Rajahmundry', 'Kakinada', 'Anantapur', 'Eluru', 'Ongole', 'Kadapa', 'Chittoor', 'Machilipatnam', 'Tenali', 'Proddatur', 'Hindupur'],
            'Arunachal Pradesh'=> ['Itanagar', 'Naharlagun', 'Pasighat', 'Tawang', 'Ziro', 'Bomdila', 'Roing'],
            'Assam'            => ['Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat', 'Nagaon', 'Tinsukia', 'Tezpur', 'Bongaigaon', 'Barpeta', 'Goalpara', 'Diphu', 'Sivasagar', 'Karimganj', 'Dhemaji', 'Kokrajhar'],
            'Bihar'            => ['Patna', 'Gaya', 'Muzaffarpur', 'Bhagalpur', 'Darbhanga', 'Purnia', 'Arrah', 'Begusarai', 'Katihar', 'Munger', 'Chhapra', 'Sasaram', 'Hajipur', 'Bettiah', 'Motihari', 'Saharsa', 'Madhubani', 'Siwan', 'Nawada', 'Samastipur'],
            'Chhattisgarh'     => ['Raipur', 'Bhilai', 'Bilaspur', 'Korba', 'Durg', 'Rajnandgaon', 'Raigarh', 'Jagdalpur', 'Ambikapur', 'Mahasamund', 'Dhamtari', 'Bhatapara', 'Kanker'],
            'Goa'              => ['Panaji', 'Margao', 'Vasco da Gama', 'Mapusa', 'Ponda', 'Bicholim', 'Canacona'],
            'Gujarat'          => ['Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar', 'Jamnagar', 'Junagadh', 'Gandhinagar', 'Anand', 'Nadiad', 'Morbi', 'Mehsana', 'Bharuch', 'Navsari', 'Bhuj', 'Surendranagar', 'Gandhidham', 'Palanpur', 'Porbandar', 'Valsad', 'Patan', 'Amreli', 'Dahod', 'Vapi'],
            'Haryana'          => ['Chandigarh', 'Faridabad', 'Gurugram', 'Panipat', 'Ambala', 'Yamunanagar', 'Rohtak', 'Hisar', 'Karnal', 'Sonipat', 'Panchkula', 'Bhiwani', 'Sirsa', 'Bahadurgarh', 'Jind', 'Rewari', 'Kaithal', 'Palwal', 'Nuh', 'Kurukshetra', 'Fatehabad', 'Jhajjar'],
            'Himachal Pradesh' => ['Shimla', 'Dharamshala', 'Mandi', 'Solan', 'Kullu', 'Hamirpur', 'Bilaspur', 'Palampur', 'Nahan', 'Kangra', 'Sundarnagar', 'Chamba', 'Una', 'Keylong'],
            'Jharkhand'        => ['Ranchi', 'Jamshedpur', 'Dhanbad', 'Bokaro', 'Deoghar', 'Hazaribagh', 'Giridih', 'Ramgarh', 'Dumka', 'Phusro', 'Chaibasa', 'Gumla', 'Garhwa', 'Pakur', 'Sahebganj', 'Lohardaga'],
            'Karnataka'        => ['Bengaluru', 'Mysuru', 'Hubballi', 'Mangaluru', 'Belagavi', 'Davangere', 'Ballari', 'Vijayapura', 'Shivamogga', 'Tumakuru', 'Raichur', 'Bidar', 'Hospet', 'Hassan', 'Udupi', 'Chitradurga', 'Robertson Pet', 'Gadag-Betageri', 'Mandya', 'Kolar', 'Chikkamagaluru', 'Ranebennur', 'Ramanagara'],
            'Kerala'           => ['Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Thrissur', 'Kollam', 'Alappuzha', 'Palakkad', 'Kannur', 'Kottayam', 'Malappuram', 'Pathanamthitta', 'Idukki', 'Wayanad', 'Kasaragod', 'Munnar', 'Varkala', 'Guruvayur', 'Changanassery', 'Chengannur', 'Punalur', 'Kalamassery', 'Mattancherry'],
            'Madhya Pradesh'   => ['Bhopal', 'Indore', 'Jabalpur', 'Gwalior', 'Ujjain', 'Sagar', 'Dewas', 'Satna', 'Ratlam', 'Rewa', 'Murwara', 'Singrauli', 'Burhanpur', 'Khandwa', 'Morena', 'Bhind', 'Chhindwara', 'Guna', 'Damoh', 'Mandsaur', 'Shivpuri', 'Vidisha', 'Neemuch', 'Hoshangabad', 'Itarsi', 'Chhatarpur', 'Datia', 'Shajapur', 'Balaghat', 'Tikamgarh'],
            'Maharashtra'      => ['Mumbai', 'Pune', 'Nagpur', 'Thane', 'Nashik', 'Aurangabad', 'Solapur', 'Kolhapur', 'Amravati', 'Navi Mumbai', 'Sangli', 'Malegaon', 'Jalgaon', 'Akola', 'Latur', 'Ahmednagar', 'Dhule', 'Chandrapur', 'Parbhani', 'Ichalkaranji', 'Jalna', 'Nanded', 'Satara', 'Ratnagiri', 'Wardha', 'Bhiwandi', 'Vasai-Virar', 'Panvel', 'Mira-Bhayandar', 'Ulhasnagar'],
            'Manipur'          => ['Imphal', 'Bishnupur', 'Thoubal', 'Churachandpur', 'Ukhrul', 'Senapati', 'Kakching', 'Mayang Imphal', 'Lilong', 'Jiribam'],
            'Meghalaya'        => ['Shillong', 'Tura', 'Nongstoin', 'Jowai', 'Williamnagar', 'Baghmara', 'Mawlai', 'Nongpoh', 'Mairang', 'Cherrapunji'],
            'Mizoram'          => ['Aizawl', 'Lunglei', 'Champhai', 'Serchhip', 'Kolasib', 'Lawngtlai', 'Saiha', 'Mamit', 'Khawzawl', 'Hnahthial'],
            'Nagaland'         => ['Kohima', 'Dimapur', 'Mokokchung', 'Tuensang', 'Wokha', 'Zunheboto', 'Mon', 'Phek', 'Longleng', 'Kiphire', 'Peren', 'Chumoukedima'],
            'Odisha'           => ['Bhubaneswar', 'Cuttack', 'Rourkela', 'Berhampur', 'Sambalpur', 'Puri', 'Balasore', 'Bhadrak', 'Baripada', 'Jharsuguda', 'Jeypore', 'Barbil', 'Angul', 'Dhenkanal', 'Kendrapara', 'Paradeep', 'Kendujhar', 'Rayagada', 'Nabarangpur'],
            'Punjab'           => ['Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda', 'Mohali', 'Pathankot', 'Hoshiarpur', 'Moga', 'Abohar', 'Barnala', 'Firozpur', 'Kapurthala', 'Malerkotla', 'Muktsar', 'Ropar', 'Fazilka', 'Nawanshahr', 'Sangrur', 'Tarn Taran'],
            'Rajasthan'        => ['Jaipur', 'Jodhpur', 'Udaipur', 'Kota', 'Bikaner', 'Ajmer', 'Bhilwara', 'Alwar', 'Sikar', 'Bharatpur', 'Pali', 'Sri Ganganagar', 'Tonk', 'Kishangarh', 'Beawar', 'Hanumangarh', 'Dhaulpur', 'Gangapur City', 'Sawai Madhopur', 'Baran', 'Churu', 'Jhunjhunu', 'Jhalawar', 'Chittorgarh', 'Banswara', 'Bundi', 'Nagaur', 'Dungarpur', 'Hindaun', 'Neem Ka Thana'],
            'Sikkim'           => ['Gangtok', 'Namchi', 'Mangan', 'Gyalshing', 'Rangpo', 'Singtam', 'Jorethang', 'Rhenock', 'Pakyong', 'Soreng'],
            'Tamil Nadu'       => ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem', 'Tirunelveli', 'Tiruppur', 'Erode', 'Vellore', 'Thoothukkudi', 'Dindigul', 'Thanjavur', 'Ranipet', 'Sivakasi', 'Karur', 'Udhagamandalam', 'Hosur', 'Nagercoil', 'Kanchipuram', 'Kumbakonam', 'Cuddalore', 'Rajapalayam', 'Pollachi', 'Tiruvannamalai', 'Nagapattinam', 'Pudukkottai', 'Ambur', 'Arakkonam', 'Mettupalayam', 'Karaikudi', 'Gobichettipalayam', 'Neyveli'],
            'Telangana'        => ['Hyderabad', 'Warangal', 'Nizamabad', 'Karimnagar', 'Khammam', 'Ramagundam', 'Mahbubnagar', 'Nalgonda', 'Adilabad', 'Suryapet', 'Miryalaguda', 'Jagtial', 'Mancherial', 'Siddipet', 'Bhongir', 'Vikarabad', 'Wanaparthy', 'Kothagudem', 'Bhadrachalam', 'Medak', 'Jangaon', 'Gadwal', 'Nirmal', 'Zaheerabad', 'Mahabubabad'],
            'Tripura'          => ['Agartala', 'Udaipur', 'Dharmanagar', 'Kailashahar', 'Belonia', 'Khowai', 'Ambassa', 'Teliamura', 'Sabroom', 'Santirbazar', 'Sonamura', 'Bishalgarh', 'Kamalpur'],
            'Uttar Pradesh'    => ['Lucknow', 'Kanpur', 'Agra', 'Varanasi', 'Ghaziabad', 'Prayagraj', 'Noida', 'Meerut', 'Bareilly', 'Moradabad', 'Gorakhpur', 'Aligarh', 'Saharanpur', 'Jhansi', 'Firozabad', 'Mathura', 'Ayodhya', 'Muzaffarnagar', 'Shahjahanpur', 'Rampur', 'Sambhal', 'Amroha', 'Bulandshahr', 'Hapur', 'Loni', 'Hathras', 'Etawah', 'Mirzapur', 'Unnao', 'Rae Bareli', 'Sitapur', 'Hardoi', 'Bahraich', 'Lakhimpur Kheri', 'Azamgarh', 'Jaunpur', 'Banda', 'Orai', 'Basti', 'Gonda', 'Deoria', 'Ballia', 'Mau', 'Ghazipur', 'Chandauli', 'Barabanki', 'Sultanpur', 'Faizabad', 'Mainpuri', 'Etah', 'Kannauj', 'Farrukhabad', 'Kanpur Dehat', 'Ambedkar Nagar', 'Pratapgarh', 'Kaushambi', 'Mahoba', 'Chitrakoot', 'Hamirpur', 'Jalaun', 'Lalitpur', 'Maharajganj', 'Kushi Nagar', 'Padrauna', 'Siddharthnagar', 'Sant Kabir Nagar'],
            'Uttarakhand'      => ['Dehradun', 'Haridwar', 'Rishikesh', 'Haldwani', 'Roorkee', 'Nainital', 'Rudrapur', 'Kotdwar', 'Kashipur', 'Mussoorie', 'Pithoragarh', 'Almora', 'Ramnagar', 'Srinagar', 'Gopeshwar', 'Champawat', 'Bageshwar', 'Tehri', 'Uttarkashi', 'Joshimath', 'Pauri', 'Dwarahat', 'Jaspur', 'Kichha', 'Sitarganj', 'Bazpur', 'Tanakpur', 'Khatima', 'Muni Ki Reti', 'Doiwala', 'Herbertpur', 'Laksar', 'Lansdowne', 'Mukteshwar', 'Ranikhet', 'Narendranagar'],
            'West Bengal'      => ['Kolkata', 'Howrah', 'Durgapur', 'Asansol', 'Siliguri', 'Bardhaman', 'Krishnanagar', 'Berhampore', 'Balurghat', 'Malda', 'Jalpaiguri', 'Haldia', 'Darjeeling', 'Cooch Behar', 'Kharagpur', 'Raiganj', 'English Bazar', 'Baharampur', 'Medinipur', 'Bangaon', 'Basirhat', 'Uluberia', 'Rishra', 'Diamond Harbour', 'Bally', 'Bansberia', 'Baranagar', 'Chandannagar', 'Habra', 'Kanchrapara', 'Naihati', 'Barasat', 'Titagarh', 'Dum Dum', 'Kalyani', 'Konnagar', 'Madhyamgram', 'Serampore', 'Sodepur', 'Contai', 'Guskara', 'Jhargram', 'Katwa', 'Raghunathpur', 'Suri', 'Bishnupur', 'Beldanga', 'Dhulian', 'Gangarampur', 'Islampur', 'Jangipur', 'Memari', 'Kalimpong', 'Ranaghat', 'Tamluk', 'Bankura'],
            'Delhi'            => ['New Delhi', 'Central Delhi', 'South Delhi', 'North Delhi', 'East Delhi', 'West Delhi', 'Shahdara', 'Dwarka', 'Rohini', 'Saket', 'Karol Bagh', 'Connaught Place', 'Lajpat Nagar', 'Hauz Khas', 'Pitampura', 'Paschim Vihar', 'Janakpuri', 'Vasant Kunj', 'Nehru Place', 'Chandni Chowk'],
            'Chandigarh'       => ['Chandigarh', 'Manimajra', 'Sector 17', 'Sector 22', 'Industrial Area', 'Phase 1', 'Phase 2', 'Daria', 'Kaimbwala'],
            'Jammu and Kashmir'=> ['Srinagar', 'Jammu', 'Anantnag', 'Baramulla', 'Sopore', 'Kathua', 'Rajouri', 'Poonch', 'Udhampur', 'Kupwara', 'Pulwama', 'Budgam', 'Bandipora', 'Ganderbal', 'Kulgam', 'Shopian', 'Kishtwar', 'Ramban', 'Doda', 'Reasi', 'Samba'],
            'Ladakh'           => ['Leh', 'Kargil', 'Nubra', 'Zanskar', 'Drass', 'Diskit', 'Khaltsi', 'Nyoma'],
            'Puducherry'       => ['Puducherry', 'Karaikal', 'Yanam', 'Mahe', 'Ozhukarai'],
            'Andaman and Nicobar Islands' => ['Port Blair', 'Mayabunder', 'Diglipur', 'Rangat', 'Havelock', 'Neil Island', 'Car Nicobar', 'Campbell Bay'],
            'Dadra and Nagar Haveli and Daman and Diu' => ['Silvassa', 'Daman', 'Diu', 'Amli', 'Naroli', 'Sami', 'Kharadpada', 'Vapi'],
            'Lakshadweep'      => ['Kavaratti', 'Agatti', 'Andrott', 'Minicoy', 'Kalpeni', 'Amini', 'Kiltan', 'Chethlat', 'Kadmat'],
        ];

        foreach ($states as $s) {
            $state = State::updateOrCreate(
                ['country_id' => $india->id, 'name' => $s['name']],
                ['code' => $s['code'], 'sort_order' => $s['sort_order']]
            );

            $cities = $cityMap[$s['name']] ?? [];
            foreach ($cities as $i => $cityName) {
                City::updateOrCreate(
                    ['state_id' => $state->id, 'name' => $cityName],
                    ['sort_order' => $i + 1]
                );
            }
        }

        // ── Additional countries ────────────────────────────────────────
        $others = [
            ['name' => 'United States',       'iso_code' => 'US', 'phone_code' => '+1',  'sort_order' => 2],
            ['name' => 'United Kingdom',      'iso_code' => 'GB', 'phone_code' => '+44', 'sort_order' => 3],
            ['name' => 'Canada',              'iso_code' => 'CA', 'phone_code' => '+1',  'sort_order' => 4],
            ['name' => 'Australia',           'iso_code' => 'AU', 'phone_code' => '+61', 'sort_order' => 5],
            ['name' => 'United Arab Emirates','iso_code' => 'AE', 'phone_code' => '+971','sort_order' => 6],
            ['name' => 'Singapore',           'iso_code' => 'SG', 'phone_code' => '+65', 'sort_order' => 7],
            ['name' => 'Malaysia',            'iso_code' => 'MY', 'phone_code' => '+60', 'sort_order' => 8],
            ['name' => 'Nepal',               'iso_code' => 'NP', 'phone_code' => '+977','sort_order' => 9],
            ['name' => 'Bangladesh',          'iso_code' => 'BD', 'phone_code' => '+880','sort_order' => 10],
            ['name' => 'Pakistan',            'iso_code' => 'PK', 'phone_code' => '+92', 'sort_order' => 11],
            ['name' => 'Sri Lanka',           'iso_code' => 'LK', 'phone_code' => '+94', 'sort_order' => 12],
            ['name' => 'Other',               'iso_code' => 'OT', 'phone_code' => null,  'sort_order' => 99],
        ];

        foreach ($others as $o) {
            Country::updateOrCreate(['iso_code' => $o['iso_code']], $o);
        }
    }
}
