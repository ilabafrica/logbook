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
use App\Models\Checklist;
use App\Models\Sdp;
use App\Models\Survey;
use App\Models\Survey_data;
use App\Models\Survey_score;
use App\Models\Hiv_test_kit;
use App\Models\Cadre;
use App\Models\Answer;
use App\Models\Section;
use App\Models\Question;
class LogbookSeeder extends Seeder
{
    public function run()
    {
    	/* Users table */
    	$usersData = array(
            array(
                "username" => "admin", "password" => Hash::make("password"), "email" => "admin@hivlogbook.org",
                "name" => "Lucy Mbugua", "gender" => "1", "phone"=>"0722000000", "address" => "P.O. Box 59857-00200, Nairobi"
            )
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
            array("name" => "Gem", "county_id" => "38", "user_id" => "1")
        );
        foreach ($subCounties as $subCounty) {
            SubCounty::create($subCounty);
        }
        $this->command->info('subcounties table seeded');
       
         /* Facilities table */
        $facilities = array(
            array("code" => "19704", "name" => "ACK Nyandarua Medical Clinic", "sub_county_id" => "1",  "facility_type_id" => "13", "facility_owner_id" => "3", "reporting_site"=> "Test Test","nearest_town" => "Captain","landline" => " ", "mobile" => " ", "email" => "", "address" => "P.O Box 48",  "in_charge" => "Eliud Mwangi Kithaka",  "operational_status" => "1", "user_id" => "1")

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
            array("name" => "Other", "description" => "", "user_id" => "1")
        );
        foreach ($agencies as $agency) {
            Agency::create($agency);
        }
        $this->command->info('Agency table seeded');
        /* Site types table */
        $stypes = array(
            array("name" => "VCT", "description" => "Voluntary Counselling and Testing", "user_id" => "1")
        );
        foreach ($stypes as $stype) {
            SiteType::create($stype);
        }
        $this->command->info('Site types table seeded');
        /* Sites table */
        $sites = array(
            array("facility_id" => "1", "site_type_id" => "1", "local_id" => "002", "name" => "Kaptembwa",  "department" => "VCT", "mobile" => "0729333333", "email" => "lmbugua@strathmore.edu", "in_charge" => "Pius Mathii", "user_id" => "1")
        );
        foreach ($sites as $site) {
            Site::create($site);
        }
        $this->command->info('Sites table seeded');
        /* Test kits table */
        $tkits = array(
            array("full_name" => "Unigold", "short_name" => "Unigold", "manufacturer" => "Lancet Kenya", "approval_status" => "2", "approval_agency_id" => "3", "incountry_approval" => "2", "user_id" => "1")
        );
        foreach ($tkits as $tkit) {
            TestKit::create($tkit);
        }
        $this->command->info('Test kits table seeded');
        /* Site test kits table */
        $stkits = array(
            array("site_id" => "1", "kit_id" => "1", "lot_no" => "0087", "expiry_date" => "2015-08-09", "comments" => "Nothing special.", "stock_available" => "2", "user_id" => "1")
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
            array("name" => "Others", "description" => "", "user_id" => "1")

        );
        foreach ($sdps as $sdp) {
            Sdp::create($stype);
        }
        $this->command->info('SDPs table seeded');

        /* hiv_test_kits table */
        $hiv_test_kits = array(
            array("name" => "KHB", "description" => "", "user_id" => "1"),
            array("name" => "First Response", "description" => "", "user_id" => "1"),
            array("name" => "Unigold", "description" => "", "user_id" => "1"),
            array("name" => "Other", "description" => "", "user_id" => "1")
           
        );
        foreach ($hiv_test_kits as $hiv_test_kit) {
            Hiv_test_kit::create($hiv_test_kit);
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
           array("name" => "Other", "description" => "", "user_id" => "1")
        );
        foreach ($cadres as $cadre) {
            Cadre::create($cadre);
        }
        $this->command->info('HIV Test Kits table seeded');

        /* Checklists table */
        $checklists = array(
            array("name" => "HTC Lab Register (MOH 362)", "description" => "", "user_id" => "1"),
            array("name" => "M & E Checklist", "description" => "", "user_id" => "1"),
            array("name" => "SPI-RT Checklist", "description" => "", "user_id" => "1")
        
        );
        foreach ($checklists as $checklist) {
            Checklist::create($checklist);
        }
        $this->command->info('checklists table seeded');

        
         /* HTC Lab Register sections table */
        $sec_survey = Section::create(array("name" => "Survey Details", "description" => "", "checklist_id" => "1", "total_points" => "0", "user_id" => "1"));
        $sec_register = Section::create(array("name" => "Lab Register Details", "description" => "", "checklist_id" => "1", "total_points" => "0", "user_id" => "1"));
        $sec_hiv1 = Section::create(array("name" => "HIV Test 1", "description" => "", "checklist_id" => "1", "total_points" => "0", "user_id" => "1"));
        $sec_hiv2 = Section::create(array("name" => "HIV Test 2", "description" => "", "checklist_id" => "1", "total_points" => "0", "user_id" => "1"));
        $sec_hiv3 = Section::create(array("name" => "HIV Test 3", "description" => "", "checklist_id" => "1", "total_points" => "0", "user_id" => "1"));
        $sec_finalResult = Section::create(array("name" => "Final Results", "description" => "", "checklist_id" => "1", "total_points" => "0", "user_id" => "1"));
        $sec_consumption = Section::create(array("name" => "Test Kit Consumption Summary", "description" => "", "checklist_id" => "1", "total_points" => "0", "user_id" => "1"));
        $sec_other = Section::create(array("name" => "Other Details", "description" => "", "checklist_id" => "1", "total_points" => "0", "user_id" => "1"));
        /*M&E Checklist sections table*/
        $sec_MEsurvey = Section::create(array("name" => "Survey Details", "description" => "", "checklist_id" => "2", "total_points" => "0", "user_id" => "1"));
        $sec_MEregister = Section::create(array("name" => "M & E Checklist", "description" => "", "checklist_id" => "2", "total_points" => "0", "user_id" => "1"));
        $sec_sec1 = Section::create(array("name" => "Section 1.0", "label" => "Support from the MOH (i.e.,subcounty, county or national level)", "description" => "Provide information on the level of MOH engagement to support the implementation of quality assurance activities and address supply chain for HIV RTs and testing.", "checklist_id" => "2", "total_points" => "6", "user_id" => "1"));
        $sec_sec2 = Section::create(array("name" => "Section 2.0", "label" => "HR Development – Training and Certification", "description" => "Provide information below for network of HIV rapid testers at site level and link innovative hands-on training and re-training with certification process.", "checklist_id" => "2", "total_points" => "30", "user_id" => "1"));
        $sec_sec3 = Section::create(array("name" => "Section 3.0", "label" => "Proficiency Testing and QC using DTS", "description" => "Provide information on dried tube specimen (DTS)-based proficiency testing and quality control specimens, as part of routine HIV RT testing and for training purpose.", "checklist_id" => "2", "total_points" => "18", "user_id" => "1"));
        $sec_sec4 = Section::create(array("name" => "Section 4.0", "label" => "Use of Standardized HTC register", "description" => " Provide information on the use of standardized HTC register or register for the purposes of quality assurance of HIV rapid testing.", "checklist_id" => "2", "total_points" => "18", "user_id" => "1"));
        $sec_MEtotalScore = Section::create(array("name" => "Total Score", "label" => "", "description" => "", "checklist_id" => "2", "total_points" => "0",  "user_id" => "1"));
        $sec_MEother = Section::create(array("name" => "GPRS Location", "label" => "", "description" => "", "checklist_id" => "2", "total_points" => "0", "user_id" => "1"));
       /*SPI-RT Checklist sections table*/
        $sec_Spisurvey = Section::create(array("name" => "Survey Details", "description" => "", "checklist_id" => "3", "total_points" => "0", "user_id" => "1"));
        $sec_Spiregister = Section::create(array("name" => "SPI-RT Checklist", "description" => "", "checklist_id" => "3", "total_points" => "0", "user_id" => "1"));
        $sec_Spisec1 = Section::create(array("name" => "Section 1.0", "label" => "PERSONNEL TRAINING AND CERTIFICATION", "description" => "(Score = 11)", "checklist_id" => "3", "total_points" => "11", "user_id" => "1"));
        $sec_Spisec2 = Section::create(array("name" => "Section 2.0", "label" => "PHYSICAL FACILITY", "description" => "(Score = 5)", "checklist_id" => "3", "total_points" => "5",  "user_id" => "1"));
        $sec_Spisec3 = Section::create(array("name" => "Section 3.0", "label" => "SAFETY", "description" => "(Score = 9)", "checklist_id" => "3", "total_points" => "9","user_id" => "1"));
        $sec_Spisec4 = Section::create(array("name" => "Section 4.0", "label" => "PRE-TESTING PHASE", "description" => "(Score = 12)", "checklist_id" => "3", "total_points" => "12", "user_id" => "1"));       
        $sec_Spisec5 = Section::create(array("name" => "Section 5.0", "label" => "TESTING PHASE", "description" => "(Score = 9)", "checklist_id" => "3", "total_points" => "9","user_id" => "1"));       
        $sec_Spisec6 =Section::create(array("name" => "Section 6.0", "label" => "POST TESTING PHASE", "description" => "(Score = 4)", "checklist_id" => "3", "total_points" => "4", "user_id" => "1"));
        $sec_Spisec7= Section::create(array("name" => "Section 7.0", "label" => "DOCUMENTS AND RECORDS", "description" => "(Score = 7)", "checklist_id" => "3", "total_points" => "7", "user_id" => "1"));
        $sec_Spisec8= Section::create(array("name" => "Section 8.0", "label" => "EXTERNAL QUALITY ASSESSMENT", "description" => "(PT, RETESTING AND SITE SUPERVISION), (Score = 13)", "checklist_id" => "3", "total_points" => "13","user_id" => "1"));
        $sec_Spisec9= Section::create(array("name" => "Section 9.0", "label" => "Auditor’s Summation Report for SPI-RT Assessment", "description" => "", "checklist_id" => "3", "total_points" => "0", "user_id" => "1"));
        $sec_Spisec10= Section::create(array("name" => "Levels (Score in %)", "label" => "", "description" => " Level 0 = Less than 40% (needs improvement in all essential rapid HIV testing Quality areas and immediate remediation)
                                                            Level 1 =40% - 59% (needs improvement in specific essential rapid HIV testing Quality  areas)    
                                                            Level 2 =60% - 79% (partial implementation of essential rapid HIV testing Quality  areas)
                                                            Level 3 =80% - 89% (close to satisfactory implementation of  essential rapid HIV testing Quality  elements)
                                                            Level 4 =90% or higher(satisfactory implementation of essential rapid HIV testing Quality  elements)", "checklist_id" => "3", "total_points" => "0","user_id" => "1"));
        $sec_Spisec11= Section::create(array("name" => "", "label" => "", "description" => "", "checklist_id" => "3", "total_points" => "0", "user_id" => "1"));
        $sec_Spiother = Section::create(array("name" => "GPRS Location", "label" => "", "description" => "", "checklist_id" => "3", "total_points" => "0", "user_id" => "1"));
        $this->command->info('sections table seeded');
        


       /**HTC Lab Register Questions */
         /**Section 1 - main page*/
        $question_qaOfficer = Question::create(array("section_id" => $sec_survey->id, "name" => "Name of the QA Officer", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_facility = Question::create(array("section_id" => $sec_survey->id, "name" => "Facility", "description" => "Facility","question_type" =>"4",  "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
   
        $question_sdp = Question::create(array("section_id" => $sec_register->id, "name" => "Service Delivery Points (SDP)", "description" => "","question_type" =>"4",  "required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_pageStartDate = Question::create(array("section_id" => $sec_register->id, "name" => "Register Page Start Date", "description" => "", "question_type" =>"1","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_pageEndDate = Question::create(array("section_id" => $sec_register->id, "name" => "Register Page End Date", "description" => "","question_type" =>"1", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
                 //hivtest1
        $question_hivTest1 = Question::create(array("section_id" => $sec_hiv1->id, "name" => "HIV Test-1 Name", "description" => "","question_type" =>"0", "required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_test1lotNo = Question::create(array("section_id" => $sec_hiv1->id, "name" => "Lot Number", "description" => "", "question_type" =>"0","required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_test1expiryDate = Question::create(array("section_id" => $sec_hiv1->id, "name" => "Expiry Date", "description" => "","question_type" =>"0", "required" => "1", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test1TotalPositive = Question::create(array("section_id" => $sec_hiv1->id, "name" => "Test-1 Total Positive", "description" => "Test-1 Total Positive","question_type" =>"2", "required" => "1", "info" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1TotalNegative = Question::create(array("section_id" => $sec_hiv1->id, "name" => "Test-1 Total Negative", "description" => "Test-1 Total Negative", "question_type" =>"2","required" => "1", "info" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1TotalInvalid = Question::create(array("section_id" => $sec_hiv1->id, "name" => "Test-1 Total Invalid", "description" => "Test-1 Total Invalid","question_type" =>"2", "required" => "1", "info" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1Comment= Question::create(array("section_id" => $sec_hiv1->id, "name" => "HIV Test-1 Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
                //hivtest2
        $question_hivTest2 = Question::create(array("section_id" => $sec_hiv2->id, "name" => "HIV Test-2 Name", "description" => "HIV Test-1 Name","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test2lotNo = Question::create(array("section_id" => $sec_hiv2->id, "name" => "Lot Number", "description" => "Lot Number (select one)", "question_type" =>"0","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test2expiryDate = Question::create(array("section_id" => $sec_hiv2->id, "name" => "Expiry Date", "description" => "Expiry Date", "question_type" =>"0","required" => "1", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test2TotalPositive = Question::create(array("section_id" => $sec_hiv2->id, "name" => "Test-2 Total Positive", "description" => "Test-2 Total Positive", "question_type" =>"2","required" => "1", "info" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2TotalNegative = Question::create(array("section_id" => $sec_hiv2->id, "name" => "Test-2 Total Negative", "description" => "Test-2 Total Negative","question_type" =>"2", "required" => "1", "info" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2TotalInvalid = Question::create(array("section_id" => $sec_hiv2->id, "name" => "Test-2 Total Invalid", "description" => "Test-2 Total Invalid","question_type" =>"2", "required" => "1", "info" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2Comment= Question::create(array("section_id" => $sec_hiv2->id, "name" => "HIV Test-2 Comments", "description" => "Comments", "question_type" =>"3","required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
                //hivtest3
        $question_hivTest3 = Question::create(array("section_id" => $sec_hiv3->id, "name" => "HIV Test-3 Name", "description" => "HIV Test-1 Name", "question_type" =>"0","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test3lotNo = Question::create(array("section_id" => $sec_hiv3->id, "name" => "Lot Number", "description" => "Lot Number (select one)", "question_type" =>"0","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test3expiryDate = Question::create(array("section_id" => $sec_hiv3->id, "name" => "Expiry Date", "description" => "Expiry Date","question_type" =>"0", "required" => "1", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test3TotalPositive = Question::create(array("section_id" => $sec_hiv3->id, "name" => "Test-3 Total Positive", "description" => "Test-3 Total Positive","question_type" =>"2", "required" => "1", "info" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3TotalNegative = Question::create(array("section_id" => $sec_hiv3->id, "name" => "Test-3 Total Negative", "description" => "Test-3 Total Negative","question_type" =>"2", "required" => "1", "info" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3TotalInvalid = Question::create(array("section_id" => $sec_hiv3->id, "name" => "Test-3 Total Invalid", "description" => "Test-3 Total Invalid", "question_type" =>"2","required" => "1", "info" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3Comment= Question::create(array("section_id" => $sec_hiv3->id, "name" => "HIV Test-3 Comments", "description" => "Comments", "question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
      
       //finalResults
        $question_finalPositive = Question::create(array("section_id" => $sec_finalResult->id, "name" => "Total Positive (P)", "description" => "","question_type" =>"2", "required" => "1", "info" => "Count all Final Results Positive in Column 'r'", "score" => "0", "user_id" => "1"));
        $question_finalNegative = Question::create(array("section_id" => $sec_finalResult->id, "name" => "Total Negative (N)", "description" => "","question_type" =>"2", "required" => "1", "info" => "Count all Final Results Negative in Column 'r'", "score" => "0", "user_id" => "1"));
        $question_finalIndeterminate = Question::create(array("section_id" => $sec_finalResult->id, "name" => "Total Indeterminate (I)", "description" => "","question_type" =>"2", "required" => "1", "info" => "Count all Final Results Indeterminate in Column 'r'", "score" => "0", "user_id" => "1"));
      
       //test kit consumption summary
        //Test 1
        $question_totalTest1Positive = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-1 Positive", "description" => "Total Test-1 Positive","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Negative = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-1 Negative", "description" => "Total Test-1 Negative", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Invalid = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-1 Invalid", "description" => "Total Test-1 Invalid", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Wastage = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-1 Wastage", "description" => "Total Test-1 Wastage","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test1Total = Question::create(array("section_id" => $sec_consumption->id, "name" => "Test-1 Total", "description" => "Test-1 Total ","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        
        //Test 2
        $question_totalTest2Positive = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-2 Positive", "description" => "Total Test-2 Positive","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Negative = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-2 Negative", "description" => "Total Test-2 Negative","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Invalid = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-2 Invalid", "description" => "Total Test-2 Invalid", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Wastage = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-2 Wastage", "description" => "Total Test-2 Wastage", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test2Total = Question::create(array("section_id" => $sec_consumption->id, "name" => "Test-2 Total", "description" => "Test-2 Total ", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        
        //Test 3
        $question_totalTest3Positive = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-3 Positive", "description" => "Total Test-3 Positive","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Negative = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-3 Negative", "description" => "Total Test-3 Negative","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Invalid = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-3 Invalid", "description" => "Total Test-3 Invalid","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Wastage = Question::create(array("section_id" => $sec_consumption->id, "name" => "Total Test-3 Wastage", "description" => "Total Test-3 Wastage","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test3Total = Question::create(array("section_id" => $sec_consumption->id, "name" => "Test-3 Total", "description" => "Test-3 Total ", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
      
        $question_supervisorReview = Question::create(array("section_id" => $sec_other->id, "name" => "Supervisor Reviewed Done? ( check for supervisor signature)", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_algorithmFollowed = Question::create(array("section_id" => $sec_other->id, "name" => "Algorithm Followed?", "description" => "Aligorithm Followed?", "question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_lat = Question::create(array("section_id" => $sec_other->id, "name" => "GPS Latitude", "description" => "", "question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_long = Question::create(array("section_id" => $sec_other->id, "name" => "GPS Longitude", "description" => "", "question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_addComm = Question::create(array("section_id" => $sec_other->id, "name" => "Additional Comments", "description" => "", "question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
      

    /**M & E Checklist Questions */
         /** main page*/
        $question_MEqaOfficer = Question::create(array("section_id" => $sec_MEsurvey->id, "name" => "Name of the QA Officer", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MEfacility = Question::create(array("section_id" => $sec_MEsurvey->id, "name" => "Facility", "description" => "Facility","question_type" =>"4",  "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_auditType= Question::create(array("section_id" => $sec_MEregister->id, "name" => "Type of Audit", "description" => "","question_type" =>"0",  "required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_MEsupervisor = Question::create(array("section_id" => $sec_MEregister->id, "name" => "Name of supervisor being interviewed", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MEIndividualsTested = Question::create(array("section_id" => $sec_MEregister->id, "name" => "Number of individuals tested by rapid testing (previous year) at this site", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MEpersonnel = Question::create(array("section_id" => $sec_MEregister->id, "name" => "Number of personnel authorized to offer HIV testing and counseling services at the site", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MEcadres = Question::create(array("section_id" => $sec_MEregister->id, "name" => "Cadres authorized to offer HIV testing and counseling services at the site", "description" => "(Please check box where applicable)","question_type" =>"5", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MEstrategy= Question::create(array("section_id" => $sec_MEregister->id, "name" => "Current testing strategy used at the site (Serial vs. Parallel)", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_METest1= Question::create(array("section_id" => $sec_MEregister->id, "name" => "Screening or Test - 1:", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_METest2= Question::create(array("section_id" => $sec_MEregister->id, "name" => "Confirmatory or Test - 2:", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_METest3= Question::create(array("section_id" => $sec_MEregister->id, "name" => "Tie-breaker or Test - 3 (if applicable):", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        //M & E sections
        //section1
        
        $question_resources = Question::create(array("section_id" =>$sec_sec1 ->id, "name" => "1.1 Are resources (staffing, funding, etc…) available to support quality assurance activities for HIV rapid testing at the site?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_resourcesComment= Question::create(array("section_id" => $sec_sec1->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_supply = Question::create(array("section_id" =>$sec_sec1 ->id, "name" => "1.2 Is there a mechanism in place to address supply chain issues at the site (e.g., stock out, recall, expired kits, damaged, etc.)?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_supplyComment= Question::create(array("section_id" => $sec_sec1->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
       
        //section2
        $question_curricula= Question::create(array("section_id" =>$sec_sec2 ->id, "name" => "2.1 Do the training curricula contain adequate hands-on sessions for HIV testing and counseling, interpretation, and troubleshooting?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_curriculaComment= Question::create(array("section_id" => $sec_sec2->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_training= Question::create(array("section_id" =>$sec_sec2 ->id, "name" => "2.2 Have staff been trained on various aspects of practical approaches to ensure and monitor the accuracy HIV of testing at the sites (e.g., use of standardized HTC register, DTS based proficiency testing program, QA, safety, testing procedures, etc.)?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_trainingComment= Question::create(array("section_id" => $sec_sec2->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_competency = Question::create(array("section_id" =>$sec_sec2 ->id, "name" => "2.3 Is the competency of the personnel performing HIV testing and counseling assessed for certification by a MOH-recognized body?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_competencyComment= Question::create(array("section_id" => $sec_sec2->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_certificate= Question::create(array("section_id" =>$sec_sec2 ->id, "name" => "2.4 Are the certificates for competency of each testing personnel required to be on display at testing sites/laboratories?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_certificateComment= Question::create(array("section_id" => $sec_sec2->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_certified= Question::create(array("section_id" =>$sec_sec2 ->id, "name" => "2.5 Has the HIV testing and counseling personnel of the site been certified or re-certified according to the certification calendar (annually, bi-annually. etc.)?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_certifiedComment= Question::create(array("section_id" => $sec_sec2->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_performance = Question::create(array("section_id" =>$sec_sec2 ->id, "name" => "2.6 Has testing personnel been with unsatisfactory performance been re-trained on or de-certified for HIV testing and quality assurance related activities?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_performanceComment= Question::create(array("section_id" => $sec_sec2->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_criteria = Question::create(array("section_id" =>$sec_sec2 ->id, "name" => "2.7 Is the site ensuring that the criteria set by the MOH-recognized certification body are met for site certification", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_criteriaComment= Question::create(array("section_id" => $sec_sec2->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_siteCerification = Question::create(array("section_id" =>$sec_sec2 ->id, "name" => "2.8 Has the site been certified or re-certified according the certification calendar (annually, bi-annually. etc.)?* ", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_siteCerificationComment= Question::create(array("section_id" => $sec_sec2->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_display = Question::create(array("section_id" =>$sec_sec2 ->id, "name" => "2.9 Is the registration and certification certificate of the HIV testing site required to be on display at testing site?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_displayComment= Question::create(array("section_id" => $sec_sec2->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_action = Question::create(array("section_id" =>$sec_sec2 ->id, "name" => "2.10 Are corrective actions implemented at the sites/laboratories to ensure certification/re-certification of the sites?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_actionComment= Question::create(array("section_id" => $sec_sec2->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
       
        //section3
        $question_control = Question::create(array("section_id" =>$sec_sec3 ->id, "name" => "3.1 Is the site using quality control routinely to monitor the HIV test kits used?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_controlComment= Question::create(array("section_id" => $sec_sec3->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_pt = Question::create(array("section_id" =>$sec_sec3 ->id, "name" => "3.2 Is the site participating in proficiency testing (PT) program to monitor the competency of the all testing personnel performing HIV testing?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_ptComment= Question::create(array("section_id" => $sec_sec3->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_feedback = Question::create(array("section_id" =>$sec_sec3 ->id, "name" => "3.3 Is feedback of PT program provided by the reference laboratory or PT provider?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_feedbackComment= Question::create(array("section_id" => $sec_sec3->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_ack = Question::create(array("section_id" =>$sec_sec3 ->id, "name" => "3.4 Is testing personnel with satisfactory PT score acknowledged by site supervisor?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_ackComment= Question::create(array("section_id" => $sec_sec3->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_issues = Question::create(array("section_id" =>$sec_sec3 ->id, "name" => "3.5 Are issues identified by the PT program properly investigated by site supervisor and documented?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_issuesComment= Question::create(array("section_id" => $sec_sec3->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_correctiveAction = Question::create(array("section_id" =>$sec_sec3 ->id, "name" => "3.6 Are appropriate corrective actions implemented for HIV testing personnel with unsatisfactory PT score as instructed by the national reference lab?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_correctiveActionComment= Question::create(array("section_id" => $sec_sec3->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        //section4
        $question_standardRegister = Question::create(array("section_id" =>$sec_sec4 ->id, "name" => "4.1 Is the site using a standardized HTC register or register to capture key HIV testing data (e.g. kit names, lot number, expiration dates, and individual test results)?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_standardRegisterComment= Question::create(array("section_id" => $sec_sec4->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_registerTraining = Question::create(array("section_id" =>$sec_sec4 ->id, "name" => "4.2 Is training provided for all testing personnel on the use of standardized HTC register?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_registerTrainingComment= Question::create(array("section_id" => $sec_sec4->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_reviewDataTraining = Question::create(array("section_id" =>$sec_sec4 ->id, "name" => "4.3 Has site supervisors been trained to review data from standardized HTC register and apply corrective actions, if needed?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
         $question_reviewDataTrainingComment= Question::create(array("section_id" => $sec_sec4->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_reportingStructure = Question::create(array("section_id" =>$sec_sec4 ->id, "name" => "4.4 Is there a structured system in place for periodical HTC register page total data reporting?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
         $question_reportingStructureComment= Question::create(array("section_id" => $sec_sec4->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_analyze = Question::create(array("section_id" =>$sec_sec4 ->id, "name" => "4.5 Are HTC register pages and/or summary page totals reviewed and data analyzed at district level or by a laboratory or QA staff for troubleshooting, periodically?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
         $question_analyzeComment= Question::create(array("section_id" => $sec_sec4->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_reviewfeedback = Question::create(array("section_id" =>$sec_sec4 ->id, "name" => "4.6 Is feedback from the review of the HTC register data provided to the sites and site personal?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_reviewfeedbackComment= Question::create(array("section_id" => $sec_sec4->id, "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        //total
        $question_MEtotalScore= Question::create(array("section_id" =>$sec_MEtotalScore ->id, "name" => "Total Score", "description" => "", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        //other
        $question_MElat = Question::create(array("section_id" => $sec_MEother->id, "name" => "GPS Latitude", "description" => "", "question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MElong = Question::create(array("section_id" => $sec_MEother->id, "name" => "GPS Longitude", "description" => "", "question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        






        $this->command->info('Questions table seeded');

        /* Responses */
        $response_yes = Answer::create(array("name" => "Yes", "description" => "Yes(Y)", "user_id" => "1"));
        $response_no = Answer::create(array("name" => "No", "description" => "No(N)", "user_id" => "1"));
        $response_partial = Answer::create(array("name" => "Partial", "description" => "Partial(P)", "user_id" => "1"));
        $response_doesNotExist = Answer::create(array("name" => "Does Not Exist", "description" => "", "user_id" => "1"));
        $response_inDevelopment = Answer::create(array("name" => "In Development", "description" => "", "user_id" => "1"));
        $response_beingImplemented= Answer::create(array("name" => "Being Implemented", "description" => "", "user_id" => "1"));
        $response_completed = Answer::create(array("name" => "Completed", "description" => "", "user_id" => "1"));
        $response_provided = Answer::create(array("name" => "Provided", "description" => "Provided", "user_id" => "1"));
        $response_notProvided = Answer::create(array("name" => "Not Provided", "description" => "Not Provided", "user_id" => "1"));
        $this->command->info('Answers table seeded');

        /* Question-Responses*/
        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest1 ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest1 ->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test1lotNo ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test1lotNo ->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test1expiryDate ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test1expiryDate ->id, "response_id" => $response_notProvided->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest2 ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest2 ->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test2lotNo ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test2lotNo ->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test2expiryDate ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test2expiryDate ->id, "response_id" => $response_notProvided->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest3 ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest3 ->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test3lotNo ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test3lotNo ->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test3expiryDate ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test3expiryDate ->id, "response_id" => $response_notProvided->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_supervisorReview->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_supervisorReview->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmFollowed->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmFollowed->id, "response_id" => $response_no->id));
       
        $this->command->info('Question-responses table seeded');

    
    }
}