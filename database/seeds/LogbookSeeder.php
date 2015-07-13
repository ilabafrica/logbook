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
            array("name" => "Murang\'a", "hq" => "Murang\'a", "user_id" => "1"),
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
            array("name" => "Ganze", "county_id" => "13", "user_id" => "1"),
        );
        foreach ($subCounties as $subCounty) {
            SubCounty::create($subCounty);
        }
        $this->command->info('Constituencies table seeded');
       
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
        $sections = array(
           // HTC Lab Register MOH (362)
        $sec_mainPage = Section::create(array("name" => "Main Page", "label" => "HTC Lab Register MOH (362)", "description" => "", "checklist_id" => "1", "total_points" => "0", "order" => 0, "user_id" => "1"));
        $sec_sdp = Section::create(array("name" => "SDP", "label" => "Service Delivery Points", "description" => "", "checklist_id" => "1", "total_points" => "0", "order" => $sec_mainPage->id, "user_id" => "1"));
        $sec_location = Section::create(array("name" => "GPRS Location", "label" => "GPRS Location", "description" => "", "checklist_id" => "1", "total_points" => "0", "order" => 0, "user_id" => "1"));
        //M&E checklist
        //SPI-RT checklist           
        );
        foreach ($sections as $section) {
            Section::create($section);
        }
        $this->command->info('sections table seeded');

       /** Questions */
         /**Section 1 - main page*/
        $question_qaOfficer = Question::create(array("section_id" => $sec_mainPage->id, "name" => "Name of the QA Officer", "title" => "HTC Lab Register MOH (362)", "description" => "Name of the QA Officer*","question_type" =>"2", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_county = Question::create(array("section_id" => $sec_mainPage->id, "name" => "County", "title" => "", "description" => "County","question_type" =>"0",  "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_subCounty = Question::create(array("section_id" => $sec_mainPage->id, "name" => "Sub County", "title" => "", "description" => "Sub County", "question_type" =>"0", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_facility = Question::create(array("section_id" => $sec_mainPage->id, "name" => "Facility", "title" => "", "description" => "Facility","question_type" =>"0",  "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
   
        /**Section 2-SDP*/
        $question_sdp = Question::create(array("section_id" => $sec_sdp->id, "name" => "Service Delivery Points (SDP)", "title" => "SDP", "description" => "Service Delivery Points (SDP) (Select One)","question_type" =>"0",  "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_pageStartDate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Register Page Start Date", "title" => "Page", "description" => "Register Page Start Date", "question_type" =>"1","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_pageEndDate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Register Page End Date", "title" => "", "description" => "Register Page End Date","question_type" =>"1", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
                 //hivtest1
        $question_hivTest1 = Question::create(array("section_id" => $sec_sdp->id, "name" => "HIV Test-1 Name", "title" => "Sections", "description" => "HIV Test-1 Name","question_type" =>"0", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_test1lotNo = Question::create(array("section_id" => $sec_sdp->id, "name" => "Lot Number", "title" => "", "description" => "Lot Number (select one)", "question_type" =>"2","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_test1expiryDate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Expiry Date", "title" => "", "description" => "Expiry Date","question_type" =>"1", "required" => "1", "info" => "", "comment" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test1TotalPositive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-1 Total Positive", "title" => "", "description" => "Test-1 Total Positive","question_type" =>"2", "required" => "1", "info" => "", "comment" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1TotalNegative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-1 Total Negative", "title" => "", "description" => "Test-1 Total Negative", "question_type" =>"2","required" => "1", "info" => "", "comment" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1TotalInvalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-1 Total Invalid", "title" => "", "description" => "Test-1 Total Invalid","question_type" =>"2", "required" => "1", "info" => "", "comment" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1Comment= Question::create(array("section_id" => $sec_sdp->id, "name" => "Comments", "title" => "", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
                //hivtest2
        $question_hivTest2 = Question::create(array("section_id" => $sec_sdp->id, "name" => "HIV Test-2 Name", "title" => "Sections", "description" => "HIV Test-1 Name","question_type" =>"0", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_test2lotNo = Question::create(array("section_id" => $sec_sdp->id, "name" => "Lot Number", "title" => "", "description" => "Lot Number (select one)", "question_type" =>"2","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_test2expiryDate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Expiry Date", "title" => "", "description" => "Expiry Date", "question_type" =>"1","required" => "1", "info" => "", "comment" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test2TotalPositive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-2 Total Positive", "title" => "", "description" => "Test-2 Total Positive", "question_type" =>"2","required" => "1", "info" => "", "comment" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2TotalNegative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-2 Total Negative", "title" => "", "description" => "Test-2 Total Negative","question_type" =>"2", "required" => "1", "info" => "", "comment" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2TotalInvalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-2 Total Invalid", "title" => "", "description" => "Test-2 Total Invalid","question_type" =>"2", "required" => "1", "info" => "", "comment" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2Comment= Question::create(array("section_id" => $sec_sdp->id, "name" => "Comments", "title" => "", "description" => "Comments", "question_type" =>"3","required" => "0", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
                //hivtest3
        $question_hivTest3 = Question::create(array("section_id" => $sec_sdp->id, "name" => "HIV Test-3 Name", "title" => "Sections", "description" => "HIV Test-1 Name", "question_type" =>"0","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_test3lotNo = Question::create(array("section_id" => $sec_sdp->id, "name" => "Lot Number", "title" => "", "description" => "Lot Number (select one)", "question_type" =>"2","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_test3expiryDate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Expiry Date", "title" => "", "description" => "Expiry Date","question_type" =>"1", "required" => "1", "info" => "", "comment" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test3TotalPositive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-3 Total Positive", "title" => "", "description" => "Test-3 Total Positive","question_type" =>"2", "required" => "1", "info" => "", "comment" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3TotalNegative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-3 Total Negative", "title" => "", "description" => "Test-3 Total Negative","question_type" =>"2", "required" => "1", "info" => "", "comment" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3TotalInvalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-3 Total Invalid", "title" => "", "description" => "Test-3 Total Invalid", "question_type" =>"2","required" => "1", "info" => "", "comment" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3Comment= Question::create(array("section_id" => $sec_sdp->id, "name" => "Comments", "title" => "", "description" => "Comments", "question_type" =>"3", "required" => "0", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
      
       //finalResults
        $question_finalPositive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Positive(column “r”)", "title" => "Final Results", "description" => "Total Positive(column “r”)","question_type" =>"2", "required" => "1", "info" => "", "comment" => "Count all Final Results Positive in Column “r”", "score" => "0", "user_id" => "1"));
        $question_finalNegative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Negative(column “r”)", "title" => "", "description" => "Total Negative(column “r”)","question_type" =>"2", "required" => "1", "info" => "", "comment" => "Count all Final Results Negative in Column “r”", "score" => "0", "user_id" => "1"));
        $question_finalIndeterminate = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Indeterminate(column “r”)", "title" => "Final Results", "description" => "Total Indeterminate(column “r”)","question_type" =>"2", "required" => "1", "info" => "", "comment" => "Count all Final Results Indeterminate in Column “r”", "score" => "0", "user_id" => "1"));
      
       //test kit consumption summary
        //Test 1
        $question_totalTest1Positive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-1 Positive", "title" => "Total Test-1 Positive", "description" => "Total Test-1 Positive","question_type" =>"2", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Negative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-1 Negative", "title" => "Total Test-1 Negative", "description" => "Total Test-1 Negative", "question_type" =>"2","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Invalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-1 Invalid", "title" => "Total Test-1 Invalid", "description" => "Total Test-1 Invalid", "question_type" =>"2","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Wastage = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-1 Wastage", "title" => "Total Test-1 Wastage", "description" => "Total Test-1 Wastage","question_type" =>"2", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_test1Total = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-1 Total ", "title" => "Test-1 Total ", "description" => "Test-1 Total ","question_type" =>"2", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        
        //Test 2
        $question_totalTest2Positive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-2 Positive", "title" => "Total Test-2 Positive", "description" => "Total Test-2 Positive","question_type" =>"2", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Negative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-2 Negative", "title" => "Total Test-2 Negative", "description" => "Total Test-2 Negative","question_type" =>"2", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Invalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-2 Invalid", "title" => "Total Test-2 Invalid", "description" => "Total Test-2 Invalid", "question_type" =>"2","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Wastage = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-2 Wastage", "title" => "Total Test-2 Wastage", "description" => "Total Test-2 Wastage", "question_type" =>"2","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_test2Total = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-2 Total ", "title" => "Test-2 Total ", "description" => "Test-2 Total ", "question_type" =>"2","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        
        //Test 3
        $question_totalTest3Positive = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-3 Positive", "title" => "Total Test-3 Positive", "description" => "Total Test-3 Positive","question_type" =>"2", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Negative = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-3 Negative", "title" => "Total Test-3 Negative", "description" => "Total Test-3 Negative","question_type" =>"2", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Invalid = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-3 Invalid", "title" => "Total Test-3 Invalid", "description" => "Total Test-3 Invalid","question_type" =>"2", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Wastage = Question::create(array("section_id" => $sec_sdp->id, "name" => "Total Test-3 Wastage", "title" => "Total Test-3 Wastage", "description" => "Total Test-3 Wastage","question_type" =>"2", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_test3Total = Question::create(array("section_id" => $sec_sdp->id, "name" => "Test-3 Total ", "title" => "Test-3 Total ", "description" => "Test-3 Total ", "question_type" =>"2","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
      
        $question_supervisorReview = Question::create(array("section_id" => $sec_sdp->id, "name" => "Supervisor Review", "title" => "", "description" => "Supervisor Reviewed Done? ( check for supervisor signature)", "question_type" =>"2","required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
        $question_algorithmFollowed = Question::create(array("section_id" => $sec_sdp->id, "name" => "Algorithm Followed", "title" => "", "description" => "Aligorithm Followed?", "question_type" =>"2", "required" => "1", "info" => "", "comment" => "", "score" => "0", "user_id" => "1"));
       
        $this->command->info('Questions table seeded');
    }
    }
}