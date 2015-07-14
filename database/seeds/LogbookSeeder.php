<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use App\Models\FacilityType;
use App\Models\FacilityOwner;
use App\Models\County;
use App\Models\SubCounty;
use App\Models\Town;
use App\Models\Title;
use App\Models\Facility;
use App\Models\Agency;
use App\Models\SiteType;
use App\Models\Site;
use App\Models\TestKit;
use App\Models\SiteKit;
use App\Models\Sdp;
use App\Models\Section;
use App\Models\Question;
use App\Models\Cadre;
use App\Models\Checklist;
use App\Models\Response;
use App\Models\HivTestKit;
class LogbookSeeder extends Seeder
{
    public function run()
    {
    	/* Users table */
    	$usersData = array(
            array(
                "username" => "admin", "password" => Hash::make("password"), "email" => "admin@hivlogbook.org",
                "name" => "Lucy Mbugua", "gender" => "1", "phone"=>"0722000000", "address" => "P.O. Box 59857-00200, Nairobi"
            ),
        );

        foreach ($usersData as $user)
        {
            $users[] = User::create($user);
        }
        $this->command->info('Users table seeded');
    	
        /* Roles table */
        $roles = array(
            array("name" => "Superadmin", "display_name" => "Overall Administrator"),
            array("name" => "Manager", "display_name" => "Manager"),
            array("name" => "Data Manager", "display_name" => "Data Manager"),
            array("name" => "County Lab Coordinator", "display_name" => "County Lab Coordinator"),
            array("name" => "Sub-County Lab Coordinator", "display_name" => "Sub-County Lab Coordinator"),
            array("name" => "QA Supervisor", "display_name" => "QA Supervisor")
        );
        foreach ($roles as $role) {
            Role::create($role);
        }
        $this->command->info('Roles table seeded');

        $role1 = Role::find(1);
        $permissions = Permission::all();

        //Assign all permissions to role administrator
        foreach ($permissions as $permission) {
            $role1->attachPermission($permission);
        }
        //Assign role Superadmin to all permissions
        User::find(1)->attachRole($role1);

        $role2 = Role::find(4);//Assessor

        //Assign technologist's permissions to role technologist
        $role2->attachPermission(Permission::find(2));
        $role2->attachPermission(Permission::find(3));
        $role2->attachPermission(Permission::find(8));

        //Assign roles to the other users
       
        /* MFL seeds */
        //  Facility Types
        $facilityTypes = array(
            array("name" => "Medical Clinic", "user_id" => "1"),
            array("name" => "Training Institution in Health (Stand-alone)", "user_id" => "1"),
            array("name" => "Dispensary", "user_id" => "1"),
            array("name" => "VCT Centre (Stand-Alone)", "user_id" => "1"),
            array("name" => "Nursing Home", "user_id" => "1"),
            array("name" => "Sub-District Hospital", "user_id" => "1"),
            array("name" => "Health Centre", "user_id" => "1"),
            array("name" => "Dental Clinic", "user_id" => "1"),
            array("name" => "Laboratory (Stand-alone)", "user_id" => "1"),
            array("name" => "Eye Centre", "user_id" => "1"),
            array("name" => "Maternity Home", "user_id" => "1"),
            array("name" => "Radiology Unit", "user_id" => "1"),
            array("name" => "District Hospital", "user_id" => "1"),
            array("name" => "Provincial General Hospital", "user_id" => "1"),
            array("name" => "Other Hospital", "user_id" => "1")
        );
        foreach ($facilityTypes as $facilityType) {
            FacilityType::create($facilityType);
        }
        $this->command->info('Facility Types table seeded');

        //  Facility Owners
        $facilityOwners = array(
            array("name" => "Christian Health Association of Kenya", "user_id" => "1"),
            array("name" => "Private Enterprise (Institution)", "user_id" => "1"),
            array("name" => "Ministry of Health", "user_id" => "1"),
            array("name" => "Non-Governmental Organization", "user_id" => "1"),
            array("name" => "Private Practice - Nurse / Midwife", "user_id" => "1"),
            array("name" => "Private Practice - General Practitioner", "user_id" => "1"),
            array("name" => "Kenya Episcopal Conference-Catholic Secretariat", "user_id" => "1"),
            array("name" => "Company Medical Service", "user_id" => "1"),
            array("name" => "Other Faith Based", "user_id" => "1")
        );
        foreach ($facilityOwners as $facilityOwner) {
            FacilityOwner::create($facilityOwner);
        }
        $this->command->info('Facility Owners table seeded');
        //  Counties
        $counties = array(
            array("name" => "Baringo", "hq" => "Kabarnet", "user_id" => "1"),
            array("name" => "Bomet", "hq" => "Bomet", "user_id" => "1"),
            array("name" => "Bungoma", "hq" => "Bungoma", "user_id" => "1"),
            array("name" => "Busia", "hq" => "Busia", "user_id" => "1"),
            array("name" => "Elgeyo Marakwet", "hq" => "Iten", "user_id" => "1"),
            array("name" => "Embu", "hq" => "Embu", "user_id" => "1"),
            array("name" => "Garissa", "hq" => "Garissa", "user_id" => "1"),
            array("name" => "Homa Bay", "hq" => "Homa Bay", "user_id" => "1"),
            array("name" => "Isiolo", "hq" => "Isiolo", "user_id" => "1"),
            array("name" => "Kajiado", "hq" => "Kajiado", "user_id" => "1"),
            array("name" => "Kakamega", "hq" => "Kakamega", "user_id" => "1"),
            array("name" => "Kericho", "hq" => "Kericho", "user_id" => "1"),
            array("name" => "Kiambu", "hq" => "Kiambu", "user_id" => "1"),
            array("name" => "Kilifi", "hq" => "Kilifi", "user_id" => "1"),
            array("name" => "Kirinyaga", "hq" => "Kerugoya", "user_id" => "1"),
            array("name" => "Kisii", "hq" => "Kisii", "user_id" => "1"),
            array("name" => "Kisumu", "hq" => "Kisumu", "user_id" => "1"),
            array("name" => "Kitui", "hq" => "Kitui Town", "user_id" => "1"),
            array("name" => "Kwale", "hq" => "Kwale", "user_id" => "1"),
            array("name" => "Laikipia", "hq" => "Nanyuki", "user_id" => "1"),
            array("name" => "Lamu", "hq" => "Lamu", "user_id" => "1"),
            array("name" => "Machakos", "hq" => "Machakos", "user_id" => "1"),
            array("name" => "Makueni", "hq" => "Wote", "user_id" => "1"),
            array("name" => "Mandera", "hq" => "Mandera", "user_id" => "1"),
            array("name" => "Marsabit", "hq" => "Marsabit", "user_id" => "1"),
            array("name" => "Meru", "hq" => "Meru", "user_id" => "1"),
            array("name" => "Migori", "hq" => "Migori", "user_id" => "1"),
            array("name" => "Mombasa", "hq" => "Mombasa", "user_id" => "1"),
            array("name" => "Muranga", "hq" => "Muranga", "user_id" => "1"),
            array("name" => "Nairobi", "hq" => "Nairobi", "user_id" => "1"),
            array("name" => "Nakuru", "hq" => "Nakuru", "user_id" => "1"),
            array("name" => "Nandi", "hq" => "Kapsabet", "user_id" => "1"),
            array("name" => "Narok", "hq" => "Narok", "user_id" => "1"),
            array("name" => "Nyamira", "hq" => "Nyamira", "user_id" => "1"),
            array("name" => "Nyandarua", "hq" => "Ol Kalou", "user_id" => "1"),
            array("name" => "Nyeri", "hq" => "Nyeri", "user_id" => "1"),
            array("name" => "Samburu", "hq" => "Maralal", "user_id" => "1"),
            array("name" => "Siaya", "hq" => "Siaya", "user_id" => "1"),
            array("name" => "Taita Taveta", "hq" => "Voi", "user_id" => "1"),
            array("name" => "Tana River", "hq" => "Hola", "user_id" => "1"),
            array("name" => "Tharaka Nithi", "hq" => "Chuka", "user_id" => "1"),
            array("name" => "Trans Nzoia", "hq" => "Kitale", "user_id" => "1"),
            array("name" => "Turkana", "hq" => "Lodwar", "user_id" => "1"),
            array("name" => "Uasin Gishu", "hq" => "Eldoret", "user_id" => "1"),
            array("name" => "Vihiga", "hq" => "Mbale", "user_id" => "1"),
            array("name" => "Wajir", "hq" => "Wajir", "user_id" => "1"),
            array("name" => "West Pokot", "hq" => "Kapenguria", "user_id" => "1")

        );
        foreach ($counties as $county) {
            County::create($county);
        }
        $this->command->info('Counties table seeded');

        /* sub-counties table */
        $subCounties = array(
            array("name" => "Kiharu", "county_id" => "29", "user_id" => "1"),
            array("name" => "Kandara", "county_id" => "29", "user_id" => "1"),
            array("name" => "Kangema", "county_id" => "29", "user_id" => "1"),
            array("name" => "Muranga", "county_id" => "29", "user_id" => "1"),
            array("name" => "Gatanga", "county_id" => "29", "user_id" => "1"),
            array("name" => "Kigumo", "county_id" => "29", "user_id" => "1"),
            array("name" => "Maragua", "county_id" => "29", "user_id" => "1"),
            array("name" => "Mathioya", "county_id" => "29", "user_id" => "1"),
            array("name" => "Embakasi", "county_id" => "30", "user_id" => "1"),
            array("name" => "Westlands", "county_id" => "30", "user_id" => "1"),
            array("name" => "Starehe", "county_id" => "30", "user_id" => "1"),
            array("name" => "Ruaraka", "county_id" => "30", "user_id" => "1"),
            array("name" => "Kasarani", "county_id" => "30", "user_id" => "1"),
            array("name" => "Langata", "county_id" => "30", "user_id" => "1"),
            array("name" => "Kamukunji", "county_id" => "30", "user_id" => "1"),
            array("name" => "Makandara", "county_id" => "30", "user_id" => "1"),
            array("name" => "Dagoretti", "county_id" => "30", "user_id" => "1"),
            array("name" => "Bomet East", "county_id" => "2", "user_id" => "1"),
            array("name" => "Bomet Central", "county_id" => "2", "user_id" => "1"),
            array("name" => "Sotik", "county_id" => "2", "user_id" => "1"),
            array("name" => "Chepalungu", "county_id" => "2", "user_id" => "1"),
            array("name" => "Konoin", "county_id" => "2", "user_id" => "1"),
            array("name" => "Alego Usonga", "county_id" => "38", "user_id" => "1"),
            array("name" => "Bondo", "county_id" => "38", "user_id" => "1"),
            array("name" => "Rarieda", "county_id" => "38", "user_id" => "1"),
            array("name" => "Ugenya", "county_id" => "38", "user_id" => "1"),
            array("name" => "Ugunja", "county_id" => "38", "user_id" => "1"),
            array("name" => "Gem", "county_id" => "38", "user_id" => "1"),
        );
        foreach ($subCounties as $subCounty) {
            SubCounty::create($subCounty);
        }
        $this->command->info('Sub-counties table seeded');
       
         /* Facilities table */
        $facilities = array(
            array("code" => "19704", "name" => "ACK Nyandarua Medical Clinic", "sub_county_id" => "1",  "facility_type_id" => "13", "facility_owner_id" => "3", "reporting_site"=> "Test Test","nearest_town" => "Captain","landline" => " ", "mobile" => " ", "email" => "", "address" => "P.O Box 48",  "in_charge" => "Eliud Mwangi Kithaka",  "operational_status" => "1", "user_id" => "1"),

            );
        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
        $this->command->info('Facilities table seeded');

        /* Approval Agencies table */
        $agencies = array(
            array("name" => "NA", "description" => "", "user_id" => "1"),
            array("name" => "USAID", "description" => "", "user_id" => "1"),
            array("name" => "WHO and National", "description" => "", "user_id" => "1"),
            array("name" => "Other", "description" => "", "user_id" => "1"),
        );
        foreach ($agencies as $agency) {
            Agency::create($agency);
        }
        $this->command->info('Agency table seeded');
        /* Site types table */
        $stypes = array(
            array("name" => "VCT", "description" => "Voluntary Counselling and Testing", "user_id" => "1"),
        );
        foreach ($stypes as $stype) {
            SiteType::create($stype);
        }
        $this->command->info('Site types table seeded');
        /* Sites table */
        $sites = array(
            array("facility_id" => "1", "site_type_id" => "1", "local_id" => "002", "name" => "Kaptembwa",  "department" => "VCT", "mobile" => "0729333333", "email" => "lmbugua@strathmore.edu", "in_charge" => "Pius Mathii", "user_id" => "1"),
        );
        foreach ($sites as $site) {
            Site::create($site);
        }
        $this->command->info('Sites table seeded');
        /* Test kits table */
        $tkits = array(
            array("full_name" => "Unigold", "short_name" => "Unigold", "manufacturer" => "Lancet Kenya", "approval_status" => "2", "approval_agency_id" => "3", "incountry_approval" => "2", "user_id" => "1"),
        );
        foreach ($tkits as $tkit) {
            TestKit::create($tkit);
        }
        $this->command->info('Test kits table seeded');
        /* Site test kits table */
        $stkits = array(
            array("site_id" => "1", "kit_id" => "1", "lot_no" => "0087", "expiry_date" => "2015-08-09", "comments" => "Nothing special.", "stock_available" => "2", "user_id" => "1"),
        );
        foreach ($stkits as $stkit) {
            SiteKit::create($stkit);
        }
        $this->command->info('Site test kits table seeded');

        /* SDPs table */
        $sdps= array(
            array("name" => "Laboratory", "description" => "", "user_id" => "1"),
            array("name" => "TB Clinic", "description" => "", "user_id" => "1"),          
            array("name" => "CCC", "description" => "", "user_id" => "1"),  
            array("name" => "OPD", "description" => "", "user_id" => "1"),  
            array("name" => "STI Clinic", "description" => "", "user_id" => "1"),  
            array("name" => "PMTCT", "description" => "", "user_id" => "1"),  
            array("name" => "IPD (Ward)", "description" => "", "user_id" => "1"),  
            array("name" => "Patient Support Center (PSC)", "description" => "", "user_id" => "1"),  
            array("name" => "VCT", "description" => "Voluntary Counselling and Testing", "user_id" => "1"),
            array("name" => "VMMC", "description" => "", "user_id" => "1"),
            array("name" => "Pediatric department", "description" => "", "user_id" => "1"),
            array("name" => "Youth Centre", "description" => "", "user_id" => "1"),
            array("name" => "Others", "description" => "", "user_id" => "1"),
        );
        foreach ($sdps as $sdp) {
            Sdp::create($sdp);
        }
        $this->command->info('SDPs table seeded');

        /* hiv_test_kits table */
        $hiv_test_kits = array(
            array("name" => "KHB", "description" => "", "user_id" => "1"),
            array("name" => "First Response", "description" => "", "user_id" => "1"),
            array("name" => "Unigold", "description" => "", "user_id" => "1"),
            array("name" => "Other", "description" => "", "user_id" => "1"),           
        );
        foreach ($hiv_test_kits as $hiv_test_kit) {
            HivTestKit::create($hiv_test_kit);
        }
        $this->command->info('HIV Test Kits table seeded');

         /* cadres*/
        $cadres = array(
            array("name" => "Counselor", "description" => "", "user_id" => "1"),
            array("name" => "Nurse", "description" => "", "user_id" => "1"),
            array("name" => "Laboratory", "description" => "", "user_id" => "1"),
            array("name" => "Lay Workers", "description" => "", "user_id" => "1"),
            array("name" => "Clinical Officer", "description" => "", "user_id" => "1"),
            array("name" => "Doctor", "description" => "", "user_id" => "1"),
            array("name" => "Mid wives", "description" => "", "user_id" => "1"),
            array("name" => "Other", "description" => "", "user_id" => "1"),
        );
        foreach ($cadres as $cadre) {
            Cadre::create($cadre);
        }
        $this->command->info('HIV Test Kits table seeded');

        /* Checklists table */
        $checklists = array(
            array("name" => "HTC Lab Register MOH(362)", "description" => "", "user_id" => "1"),
            array("name" => "M & E Checklist", "description" => "", "user_id" => "1"),
            array("name" => "SPI-RT Checklist", "description" => "", "user_id" => "1"),           
        );
        foreach ($checklists as $checklist) {
            Checklist::create($checklist);
        }
        $this->command->info('checklists table seeded');

        
         /* sections table */
        $sec_mainPage = Section::create(array("name" => "Main Page", "label" => "HTC Lab Register MOH (362)", "description" => "", "checklist_id" => "1", "total_points" => "0", "user_id" => "1"));
        $sec_sdp = Section::create(array("name" => "SDP", "label" => "Service Delivery Points", "description" => "", "checklist_id" => "1", "total_points" => "0", "user_id" => "1"));
        $sec_location = Section::create(array("name" => "GPRS Location", "label" => "GPRS Location", "description" => "", "checklist_id" => "1", "total_points" => "0", "user_id" => "1"));
        
        $this->command->info('sections table seeded');

       /** Questions */
         /**Section 1 - main page*/
        $question_qaOfficer = Question::create(array("section_id" => $sec_mainPage->id, "name" => "Name of the QA Officer", "description" => "Name of the QA Officer*","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_county = Question::create(array("section_id" => $sec_mainPage->id, "name" => "County", "description" => "County","question_type" =>"0",  "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_subCounty = Question::create(array("section_id" => $sec_mainPage->id, "name" => "Sub County", "description" => "Sub County", "question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_facility = Question::create(array("section_id" => $sec_mainPage->id, "name" => "Facility", "description" => "Facility","question_type" =>"0",  "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
   
        /**Section 2-SDP*/
        $question_sdp = Question::create(array("section_id" => $sec_sdp->id, "name" => "Service Delivery Points (SDP)", "description" => "Service Delivery Points (SDP) (Select One)","question_type" =>"0",  "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_pageStartDate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Register Page Start Date", "description" => "Register Page Start Date", "question_type" =>"1","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_pageEndDate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Register Page End Date", "description" => "Register Page End Date","question_type" =>"1", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
                 //hivtest1
        $question_hivTest1 = Question::create(array("section_id" => $sec_sdp->id, "name" => "HIV Test-1 Name",, "description" => "HIV Test-1 Name","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test1lotNo = Question::create(array("section_id" => $sec_sdp->id, "name" => "Lot Number", "description" => "Lot Number (select one)", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test1expiryDate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Expiry Date", "description" => "Expiry Date","question_type" =>"1", "required" => "1", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test1TotalPositive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-1 Total Positive", "description" => "Test-1 Total Positive","question_type" =>"2", "required" => "1", "info" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1TotalNegative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-1 Total Negative", "description" => "Test-1 Total Negative", "question_type" =>"2","required" => "1", "info" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1TotalInvalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-1 Total Invalid", "description" => "Test-1 Total Invalid","question_type" =>"2", "required" => "1", "info" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1Comment= Question::create(array("section_id" => $sec_sdp->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
                //hivtest2
        $question_hivTest2 = Question::create(array("section_id" => $sec_sdp->id, "name" => "HIV Test-2 Name", "description" => "HIV Test-1 Name","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test2lotNo = Question::create(array("section_id" => $sec_sdp->id, "name" => "Lot Number", "description" => "Lot Number (select one)", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test2expiryDate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Expiry Date", "description" => "Expiry Date", "question_type" =>"1","required" => "1", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test2TotalPositive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-2 Total Positive", "description" => "Test-2 Total Positive", "question_type" =>"2","required" => "1", "info" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2TotalNegative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-2 Total Negative", "description" => "Test-2 Total Negative","question_type" =>"2", "required" => "1", "info" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2TotalInvalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-2 Total Invalid", "description" => "Test-2 Total Invalid","question_type" =>"2", "required" => "1", "info" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2Comment= Question::create(array("section_id" => $sec_sdp->id, "name" => "Comments", "description" => "Comments", "question_type" =>"3","required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
                //hivtest3
        $question_hivTest3 = Question::create(array("section_id" => $sec_sdp->id, "name" => "HIV Test-3 Name", "description" => "HIV Test-1 Name", "question_type" =>"0","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test3lotNo = Question::create(array("section_id" => $sec_sdp->id, "name" => "Lot Number", "description" => "Lot Number (select one)", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test3expiryDate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Expiry Date", "description" => "Expiry Date","question_type" =>"1", "required" => "1", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test3TotalPositive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-3 Total Positive", "description" => "Test-3 Total Positive","question_type" =>"2", "required" => "1", "info" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3TotalNegative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-3 Total Negative", "description" => "Test-3 Total Negative","question_type" =>"2", "required" => "1", "info" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3TotalInvalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-3 Total Invalid", "description" => "Test-3 Total Invalid", "question_type" =>"2","required" => "1", "info" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3Comment= Question::create(array("section_id" => $sec_sdp->id, "name" => "Comments", "description" => "Comments", "question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
      
       //finalResults
        $question_finalPositive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Positive(column “r”)", "description" => "Total Positive(column “r”)","question_type" =>"2", "required" => "1", "info" => "Count all Final Results Positive in Column “r”", "score" => "0", "user_id" => "1"));
        $question_finalNegative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Negative(column “r”)", "description" => "Total Negative(column “r”)","question_type" =>"2", "required" => "1", "info" => "Count all Final Results Negative in Column “r”", "score" => "0", "user_id" => "1"));
        $question_finalIndeterminate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Indeterminate(column “r”)", "description" => "Total Indeterminate(column “r”)","question_type" =>"2", "required" => "1", "info" => "Count all Final Results Indeterminate in Column “r”", "score" => "0", "user_id" => "1"));
      
       //test kit consumption summary
        //Test 1
        $question_totalTest1Positive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-1 Positive", "description" => "Total Test-1 Positive","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Negative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-1 Negative", "description" => "Total Test-1 Negative", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Invalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-1 Invalid", "description" => "Total Test-1 Invalid", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Wastage = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-1 Wastage", "description" => "Total Test-1 Wastage","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test1Total = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-1 Total", "description" => "Test-1 Total ","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        
        //Test 2
        $question_totalTest2Positive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-2 Positive", "description" => "Total Test-2 Positive","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Negative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-2 Negative", "description" => "Total Test-2 Negative","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Invalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-2 Invalid", "description" => "Total Test-2 Invalid", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Wastage = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-2 Wastage", "description" => "Total Test-2 Wastage", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test2Total = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-2 Total", "description" => "Test-2 Total ", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        
        //Test 3
        $question_totalTest3Positive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-3 Positive", "description" => "Total Test-3 Positive","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Negative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-3 Negative", "description" => "Total Test-3 Negative","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Invalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-3 Invalid", "description" => "Total Test-3 Invalid","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Wastage = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-3 Wastage", "description" => "Total Test-3 Wastage","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test3Total = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-3 Total", "description" => "Test-3 Total ", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
      
        $question_supervisorReview = Question::create(array("section_id" => $sec_sdp->id, "name" => "Supervisor Review", "description" => "Supervisor Reviewed Done? ( check for supervisor signature)", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_algorithmFollowed = Question::create(array("section_id" => $sec_sdp->id, "name" => "Algorithm Followed", "description" => "Aligorithm Followed?", "question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
       
        $this->command->info('Questions table seeded');

        /* Responses */
        $response_yes = Response::create(array("name" => "Yes", "description" => "Yes(Y)", "user_id" => "1"));
        $response_no = Response::create(array("name" => "No", "description" => "No(N)", "user_id" => "1"));
        $response_partial = Response::create(array("name" => "Partial", "description" => "Partial(P)", "user_id" => "1"));
        $response_doesNotExist = Response::create(array("name" => "Does Not Exist", "description" => "", "user_id" => "1"));
        $response_inDevelopment = Response::create(array("name" => "In Development", "description" => "", "user_id" => "1"));
        $response_beingImplemented= Response::create(array("name" => "Being Implemented", "description" => "", "user_id" => "1"));
        $response_completed = Response::create(array("name" => "Completed", "description" => "", "user_id" => "1"));

        $this->command->info('Responses table seeded');

        /* Question-Responses*/
        DB::table('question_responses')->insert(
            array("question_id" => $question_supervisorReview ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_supervisorReview ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmFollowed->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmFollowed->id, "response_id" => $response_no->id));
       
        $this->command->info('Question-responses table seeded');
    }
}