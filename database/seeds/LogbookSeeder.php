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
use App\Models\SurveyData;
use App\Models\SurveyScore;
use App\Models\HivTestKit;
use App\Models\Affiliation;
use App\Models\Algorithm;
use App\Models\AuditType;
use App\Models\Cadre;
use App\Models\Answer;
use App\Models\Section;
use App\Models\Question;
use App\Models\Level;
use App\Models\Designation;
use App\Models\Pt;
class LogbookSeeder extends Seeder
{
    public function run()
    {
        /* Users table */
        $usersData = array(
            array(
                "username" => "admin", "password" => Hash::make("password"), "email" => "admin@hivlogbook.org",
                "name" => "RTQII Administrator", "gender" => "1", "phone"=>"0722000000", "address" => "P.O. Box 59857-00200, Nairobi"
            )
        );

        foreach ($usersData as $user)
        {
            $users[] = User::create($user);
        }
        $this->command->info('Users table seeded');

        /* Permissions table */
        $permissions = array(
            array("name" => "all", "display_name" => "All"),
            
            array("name" => "create-checklist", "display_name" => "Can create checklist"),
            array("name" => "manage-checklist", "display_name" => "Can manage checklist"),
            array("name" => "create-section", "display_name" => "Can create section"),
            array("name" => "manage-section", "display_name" => "Can manage section"),
            array("name" => "create-question", "display_name" => "Can create question"),
            array("name" => "manage-question", "display_name" => "Can manage question"),
            array("name" => "create-response", "display_name" => "Can create response"),
            array("name" => "manage-response", "display_name" => "Can manage response"),
            array("name" => "create-algorithm", "display_name" => "Can create algorithm"),
            array("name" => "manage-algorithm", "display_name" => "Can manage algorithm"),
            array("name" => "create-audit-type", "display_name" => "Can create audit type"),
            array("name" => "manage-audit-type", "display_name" => "Can manage audit type"),
            array("name" => "create-affiliation", "display_name" => "Can create affiliation"),
            array("name" => "manage-affiliation", "display_name" => "Can manage affiliation"),
            array("name" => "create-level", "display_name" => "Can create level"),
            array("name" => "manage-level", "display_name" => "Can manage level"),
            array("name" => "create-facility", "display_name" => "Can create facility"),
            array("name" => "manage-facility", "display_name" => "Can manage facility"),
            array("name" => "import-facility-data", "display_name" => "Can import facility data"),
            array("name" => "create-facility-type", "display_name" => "Can create facility type"),
            array("name" => "manage-facility-type", "display_name" => "Can manage facility type"),
            array("name" => "create-facility-owner", "display_name" => "Can create facility owner"),
            array("name" => "manage-facility-owner", "display_name" => "Can manage facility owner"),
            array("name" => "create-sub-county", "display_name" => "Can create sub county"),
            array("name" => "manage-sub-county", "display_name" => "Can manage sub county"),
            array("name" => "create-site", "display_name" => "Can create site"),
            array("name" => "manage-site", "display_name" => "Can manage site"),
            array("name" => "create-site-type", "display_name" => "Can create site type"),
            array("name" => "manage-site-type", "display_name" => "Can manage site type"),
            array("name" => "create-test-kit", "display_name" => "Can create test kit"),
            array("name" => "manage-test-kit", "display_name" => "Can manage test kit"),
            array("name" => "create-user", "display_name" => "Can create user"),
            array("name" => "manage-user", "display_name" => "Can manage user"),
            array("name" => "create-permission", "display_name" => "Can create permission"),
            array("name" => "manage-permission", "display_name" => "Can manage permission"),
            array("name" => "create-role", "display_name" => "Can create role"),
            array("name" => "manage-role", "display_name" => "Can manage role"),
            array("name" => "create-privilege", "display_name" => "Can create privilege"),
            array("name" => "manage-privilege", "display_name" => "Can manage privilege"),
            
            array("name" => "view-summary", "display_name" => "Can view summary"),
            array("name" => "view-reports", "display_name" => "Can view reports"),
            array("name" => "fill-questionnaire", "display_name" => "Can fill questionnaire"),
            array("name" => "edit-checklist-data", "display_name" => "Can edit checklist data"),
            array("name" => "import-data", "display_name" => "Can import submitted data"),
            //managing sidebar permissions
            array("name" => "access-checklist-config", "display_name" => "Can manage checklist configuration"),
            array("name" => "access-facility-config", "display_name" => "Can manage facility configuration"),
            array("name" => "access-site-catalog", "display_name" => "Can manage site catalog"),
            array("name" => "access-testkits", "display_name" => "Can manage testkits"),
            array("name" => "access-users", "display_name" => "Can manage users"),
            array("name" => "access-access-controls", "display_name" => "Can manage access controls"),
            array("name" => "access-data-analysis", "display_name" => "Can manage data analysis"),
            array("name" => "access-dashboard", "display_name" => "Can access dashboard")
            
        );
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
        $this->command->info('Permissions table seeded');

        /* Roles table */
        $roles = array(
            array("name" => "Superadmin", "display_name" => "Overall Administrator"),
            array("name" => "Manager", "display_name" => "Manager"),
            array("name" => "Data Manager", "display_name" => "Data Manager"),
            array("name" => "County Lab Coordinator", "display_name" => "County Lab Coordinator"),
            array("name" => "Sub-County Lab Coordinator", "display_name" => "Sub-County Lab Coordinator"),
            array("name" => "QA Supervisor", "display_name" => "QA Supervisor"),
            array("name" => "QA Officer", "display_name" => "QA Officer")
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

        $role2 = Role::find(6);//QA supervisor

        //Assign technologist's permissions to role technologist
        $role2->attachPermission(Permission::find(6));
        $role2->attachPermission(Permission::find(7));
        $role2->attachPermission(Permission::find(11));

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
            array("name" => "Makadara", "county_id" => "30", "user_id" => "1"),
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
        /*$facilities = array(
            array("code" => "19704", "name" => "ACK Nyandarua Medical Clinic", "sub_county_id" => "1",  "facility_type_id" => "13", "facility_owner_id" => "3", "reporting_site"=> "Test Test","nearest_town" => "Captain","landline" => " ", "mobile" => " ", "email" => "", "address" => "P.O Box 48",  "in_charge" => "Eliud Mwangi Kithaka",  "operational_status" => "1", "user_id" => "1")

            );
        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
        $this->command->info('Facilities table seeded');*/

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
        /*$sites = array(
            array("facility_id" => "1", "site_type_id" => "1", "local_id" => "002", "name" => "Kaptembwa",  "department" => "VCT", "mobile" => "0729333333", "email" => "lmbugua@strathmore.edu", "in_charge" => "Pius Mathii", "user_id" => "1")
        );
        foreach ($sites as $site) {
            Site::create($site);
        }
        $this->command->info('Sites table seeded');*/
        /* Test kits table */
        /*$tkits = array(
            array("full_name" => "Unigold", "short_name" => "Unigold", "manufacturer" => "Lancet Kenya", "approval_status" => "2", "approval_agency_id" => "3", "incountry_approval" => "2", "user_id" => "1")
        );
        foreach ($tkits as $tkit) {
            TestKit::create($tkit);
        }
        $this->command->info('Test kits table seeded');*/
        /* Site test kits table */
        /*$stkits = array(
            array("site_id" => "1", "kit_id" => "1", "lot_no" => "0087", "expiry_date" => "2015-08-09", "comments" => "Nothing special.", "stock_available" => "2", "user_id" => "1")
        );
        foreach ($stkits as $stkit) {
            SiteKit::create($stkit);
        }
        $this->command->info('Site test kits table seeded');*/

        /* SDPs table */
        $sdps= array(
            array("name" => "Laboratory", "description" => "", "identifier" => "Laboratory", "user_id" => "1"),
            array("name" => "TB Clinic", "description" => "", "identifier" => "TBClinic", "user_id" => "1"),          
            array("name" => "ART Clinic", "description" => "", "identifier" => "art", "user_id" => "1"), 
            array("name" => "CCC", "description" => "", "identifier" => "CCC", "user_id" => "1"),  
            array("name" => "OPD", "description" => "", "identifier" => "OPD", "user_id" => "1"),  
            array("name" => "STI Clinic", "description" => "", "identifier" => "STIclinic", "user_id" => "1"),  
            array("name" => "PMTCT", "description" => "", "identifier" => "PMTCT", "user_id" => "1"),  
            array("name" => "IPD (Ward)", "description" => "", "identifier" => "IPD", "user_id" => "1"),  
            array("name" => "Patient Support Center (PSC)/CCC", "description" => "", "identifier" => "PatientSupportCenter", "user_id" => "1"),  
            array("name" => "VCT", "description" => "Voluntary Counselling and Testing", "identifier" => "VCT", "user_id" => "1"),
            array("name" => "VMMC", "description" => "", "identifier" => "VMMC", "user_id" => "1"),
            array("name" => "Pediatric department", "description" => "", "identifier" => "Pediatricdepartment", "user_id" => "1"),
            array("name" => "Youth Centre", "description" => "", "identifier" => "YouthCentre", "user_id" => "1"),
            array("name" => "Others", "description" => "", "identifier" => "Other", "user_id" => "1")

        );
        foreach ($sdps as $sdp) {
            Sdp::create($sdp);
        }
        $this->command->info('SDPs table seeded');

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
        $this->command->info('Cadres table seeded');

        /* pt-programs*/
        $pts = array(
            array("name" => "PMTCT", "description" => "", "user_id" => "1"),
            array("name" => "VCT", "description" => "", "user_id" => "1"),
            array("name" => "PITC", "description" => "", "user_id" => "1"),
            array("name" => "HBTC", "description" => "", "user_id" => "1")
        );
        foreach ($pts as $pt) {
            Pt::create($pt);
        }
        $this->command->info('PT Programs table seeded');
        /* Designations */
        $designations = array(
            array("name" => "Nurse", "description" => "", "user_id" => "1"),
            array("name" => "Doctor", "description" => "", "user_id" => "1"),
            array("name" => "Counselor", "description" => "", "user_id" => "1"),
            array("name" => "Clinical Officer", "description" => "", "user_id" => "1")
        );
        foreach ($designations as $designation) {
            Designation::create($designation);
        }
        $this->command->info('Designations table seeded');

        /* Checklists table */
        $checklists = array(
            array("name" => "HTC Lab Register (MOH 362)", "description" => "", "user_id" => "1"),
            array("name" => "M & E Checklist", "description" => "", "user_id" => "1"),
            array("name" => "SPI-RT Checklist", "description" => "", "user_id" => "1"),
            array("name" => "PT Enrollment Tool", "description" => "", "user_id" => "1")
        
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
        $sec_Spiother = Section::create(array("name" => "GPRS Location", "label" => "", "description" => "", "checklist_id" => "3", "total_points" => "0", "user_id" => "1"));
        /**
        *   PT Enrollment
        */
        $sec_ptEnrollment = Section::create(array("name" => "PT Enrollment Details", "description" => "", "checklist_id" => "4", "total_points" => "0", "user_id" => "1"));
        $this->command->info('sections table seeded');
        
       
       /**HTC Lab Register Questions */
         /**Section 1 - main page*/
        $question_qaOfficer = Question::create(array("section_id" => $sec_survey->id, "identifier"=>"", "name" => "Name of the QA Officer", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_facility = Question::create(array("section_id" => $sec_survey->id, "identifier"=>"","name" => "Facility", "description" => "Facility","question_type" =>"4",  "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
   
        $question_sdp = Question::create(array("section_id" => $sec_register->id,"identifier"=>"", "name" => "Service Delivery Points (SDP)", "description" => "","question_type" =>"4",  "required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_pageStartDate = Question::create(array("section_id" => $sec_register->id, "identifier"=>"registerstartdate", "name" => "Register Page Start Date", "description" => "", "question_type" =>"1","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_pageEndDate = Question::create(array("section_id" => $sec_register->id,"identifier"=>"enddate", "name" => "Register Page End Date", "description" => "","question_type" =>"1", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
                 //hivtest1
        $question_hivTest1Status = Question::create(array("section_id" => $sec_hiv1->id,"identifier"=>"tezt", "name" => "HIV Test-1 Name Status", "description" => "","question_type" =>"0", "required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_hivTest1Name = Question::create(array("section_id" => $sec_hiv1->id,"identifier"=>"screen", "name" => "HIV Test-1 Name", "description" => "","question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test1lotNoStatus = Question::create(array("section_id" => $sec_hiv1->id,"identifier"=>"lots1", "name" => "Lot Number Status", "description" => "", "question_type" =>"0","required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_test1lotNo = Question::create(array("section_id" => $sec_hiv1->id,"identifier"=>"lotno", "name" => "Lot Number", "description" => "","question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test1expiryDateStatus = Question::create(array("section_id" => $sec_hiv1->id,"identifier"=>"note1", "name" => "Expiry Date Status", "description" => "","question_type" =>"0", "required" => "1", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test1expiryDate = Question::create(array("section_id" => $sec_hiv1->id,"identifier"=>"expirydate", "name" => "Expiry Date", "description" => "","question_type" =>"1", "required" => "0", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test1TotalPositive = Question::create(array("section_id" => $sec_hiv1->id,"identifier"=>"testreactive", "name" => "Test-1 Total Positive", "description" => "Test-1 Total Positive","question_type" =>"2", "required" => "1", "info" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1TotalNegative = Question::create(array("section_id" => $sec_hiv1->id,"identifier"=>"nonreactive", "name" => "Test-1 Total Negative", "description" => "Test-1 Total Negative", "question_type" =>"2","required" => "1", "info" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1TotalInvalid = Question::create(array("section_id" => $sec_hiv1->id,"identifier"=>"totalinvalid", "name" => "Test-1 Total Invalid", "description" => "Test-1 Total Invalid","question_type" =>"2", "required" => "1", "info" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test1Comment= Question::create(array("section_id" => $sec_hiv1->id,"identifier"=>"comments","name" => "HIV Test-1 Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
                //hivtest2
        $question_hivTest2Status = Question::create(array("section_id" => $sec_hiv2->id, "identifier"=>"tezt1", "name" => "HIV Test-2 Name Status", "description" => "","question_type" =>"0", "required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_hivTest2Name = Question::create(array("section_id" => $sec_hiv2->id,"identifier"=>"testing1", "name" => "HIV Test-2 Name", "description" => "","question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test2lotNoStatus = Question::create(array("section_id" => $sec_hiv2->id,"identifier"=>"lots2", "name" => "Lot Number Status", "description" => "", "question_type" =>"0","required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_test2lotNo = Question::create(array("section_id" => $sec_hiv2->id,"identifier"=>"lotno1", "name" => "Lot Number", "description" => "","question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test2expiryDateStatus = Question::create(array("section_id" => $sec_hiv2->id,"identifier"=>"note2", "name" => "Expiry Date Status", "description" => "","question_type" =>"0", "required" => "1", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test2expiryDate = Question::create(array("section_id" => $sec_hiv2->id, "identifier"=>"expirydate1","name" => "Expiry Date", "description" => "","question_type" =>"1", "required" => "0", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test2TotalPositive = Question::create(array("section_id" => $sec_hiv2->id, "identifier"=>"testreactive1","name" => "Test-2 Total Positive", "description" => "Test-2 Total Positive", "question_type" =>"2","required" => "1", "info" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2TotalNegative = Question::create(array("section_id" => $sec_hiv2->id,"identifier"=>"nonreactive1", "name" => "Test-2 Total Negative", "description" => "Test-2 Total Negative","question_type" =>"2", "required" => "1", "info" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2TotalInvalid = Question::create(array("section_id" => $sec_hiv2->id,"identifier"=>"totalinvalid1", "name" => "Test-2 Total Invalid", "description" => "Test-2 Total Invalid","question_type" =>"2", "required" => "1", "info" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test2Comment= Question::create(array("section_id" => $sec_hiv2->id,"identifier"=>"coments1", "name" => "HIV Test-2 Comments", "description" => "Comments", "question_type" =>"3","required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
                //hivtest3
        $question_hivTest3Status = Question::create(array("section_id" => $sec_hiv3->id, "identifier"=>"tezt2","name" => "HIV Test-3 Name Status", "description" => "","question_type" =>"0", "required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_hivTest3Name = Question::create(array("section_id" => $sec_hiv3->id,"identifier"=>"testing2", "name" => "HIV Test-3 Name", "description" => "","question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test3lotNoStatus = Question::create(array("section_id" => $sec_hiv3->id, "identifier"=>"lots3","name" => "Lot Number Status", "description" => "", "question_type" =>"0","required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_test3lotNo = Question::create(array("section_id" => $sec_hiv3->id,"identifier"=>"lotno2", "name" => "Lot Number", "description" => "","question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test3expiryDateStatus = Question::create(array("section_id" => $sec_hiv3->id,"identifier"=>"note6", "name" => "Expiry Date ", "description" => "","question_type" =>"0", "required" => "1", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test3expiryDate = Question::create(array("section_id" => $sec_hiv3->id,"identifier"=>"expirydate2", "name" => "Expiry Date", "description" => "","question_type" =>"1", "required" => "0", "info" => "For expiry dates with mm/yyyy format, select the last date of that month as the expiry date", "score" => "0", "user_id" => "1"));
        $question_test3TotalPositive = Question::create(array("section_id" => $sec_hiv3->id, "identifier"=>"testreactive2", "name" => "Test-3 Total Positive", "description" => "Test-3 Total Positive","question_type" =>"2", "required" => "1", "info" => "Count all Positives (P or R) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3TotalNegative = Question::create(array("section_id" => $sec_hiv3->id,"identifier"=>"nonreactive2", "name" => "Test-3 Total Negative", "description" => "Test-3 Total Negative","question_type" =>"2", "required" => "1", "info" => "Count all Negatives (N or NR) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3TotalInvalid = Question::create(array("section_id" => $sec_hiv3->id,"identifier"=>"totalinvalid2", "name" => "Test-3 Total Invalid", "description" => "Test-3 Total Invalid", "question_type" =>"2","required" => "1", "info" => "Count all Invalids (I or INV) in Column “o”", "score" => "0", "user_id" => "1"));
        $question_test3Comment= Question::create(array("section_id" => $sec_hiv3->id, "identifier"=>"coments2","name" => "HIV Test-3 Comments", "description" => "Comments", "question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
      
       //finalResults
        $question_finalPositive = Question::create(array("section_id" => $sec_finalResult->id,"identifier"=>"tpostive4", "name" => "Total Positive (P)", "description" => "","question_type" =>"2", "required" => "1", "info" => "Count all Final Results Positive in Column 'r'", "score" => "0", "user_id" => "1"));
        $question_finalNegative = Question::create(array("section_id" => $sec_finalResult->id,"identifier"=>"tnegative4", "name" => "Total Negative (N)", "description" => "","question_type" =>"2", "required" => "1", "info" => "Count all Final Results Negative in Column 'r'", "score" => "0", "user_id" => "1"));
        $question_finalIndeterminate = Question::create(array("section_id" => $sec_finalResult->id,"identifier"=>"tinvalid4", "name" => "Total Indeterminate (I)", "description" => "","question_type" =>"2", "required" => "1", "info" => "Count all Final Results Indeterminate in Column 'r'", "score" => "0", "user_id" => "1"));
      
       //test kit consumption summary
        //Test 1
        $question_consumption_summary = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"summary", "name" => "Copy the test kit consumption summary", "description" => "Copy the test kit consumption summary","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Positive = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"tpostive1", "name" => "Total Test-1 Positive", "description" => "Total Test-1 Positive","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Negative = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"tnegative1", "name" => "Total Test-1 Negative", "description" => "Total Test-1 Negative", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Invalid = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"tinvalid1", "name" => "Total Test-1 Invalid", "description" => "Total Test-1 Invalid", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest1Wastage = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"twastage1", "name" => "Total Test-1 Wastage", "description" => "Total Test-1 Wastage","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test1Total = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"total1", "name" => "Test-1 Total", "description" => "Test-1 Total ","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        
        //Test 2
        $question_totalTest2Positive = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"tpostive2", "name" => "Total Test-2 Positive", "description" => "Total Test-2 Positive","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Negative = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"tnegative2", "name" => "Total Test-2 Negative", "description" => "Total Test-2 Negative","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Invalid = Question::create(array("section_id" => $sec_consumption->id, "identifier"=>"tinvalid2","name" => "Total Test-2 Invalid", "description" => "Total Test-2 Invalid", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest2Wastage = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"twastage2", "name" => "Total Test-2 Wastage", "description" => "Total Test-2 Wastage", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test2Total = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"total2", "name" => "Test-2 Total", "description" => "Test-2 Total ", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        
        //Test 3
        $question_totalTest3Positive = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"tpostive3", "name" => "Total Test-3 Positive", "description" => "Total Test-3 Positive","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Negative = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"tnegative3", "name" => "Total Test-3 Negative", "description" => "Total Test-3 Negative","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Invalid = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"tinvalid3", "name" => "Total Test-3 Invalid", "description" => "Total Test-3 Invalid","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_totalTest3Wastage = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"twastage3", "name" => "Total Test-3 Wastage", "description" => "Total Test-3 Wastage","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_test3Total = Question::create(array("section_id" => $sec_consumption->id,"identifier"=>"total3", "name" => "Test-3 Total", "description" => "Test-3 Total ", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
      
        $question_supervisorReview = Question::create(array("section_id" => $sec_other->id,"identifier"=>"surpervisor","name" => "Supervisor Review Done? ( check for supervisor signature)", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_algorithmFollowed = Question::create(array("section_id" => $sec_other->id,"identifier"=>"algorithm", "name" => "Algorithm Followed?", "description" => "Aligorithm Followed?", "question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_algorithmNotFollowed = Question::create(array("section_id" => $sec_other->id,"identifier"=>"no", "name" => "If no, select  reason below", "description" => "If no, select  reason below", "question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_nocomments = Question::create(array("section_id" => $sec_other->id, "identifier"=>"nocomments","name" => "Comments", "description" => "", "question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_lat = Question::create(array("section_id" => $sec_other->id, "identifier"=>"","name" => "GPS Latitude", "description" => "", "question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_long = Question::create(array("section_id" => $sec_other->id,"identifier"=>"", "name" => "GPS Longitude", "description" => "", "question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_addComm = Question::create(array("section_id" => $sec_other->id, "identifier"=>"","name" => "Additional Comments", "description" => "", "question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
      

    /**M & E Checklist Questions */
         /** main page*/
        $question_MEqaOfficer = Question::create(array("section_id" => $sec_MEsurvey->id,"identifier"=>"",  "name" => "Name of the QA Officer", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MEfacility = Question::create(array("section_id" => $sec_MEsurvey->id,"identifier"=>"",  "name" => "Facility", "description" => "Facility","question_type" =>"4",  "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MEsdp = Question::create(array("section_id" => $sec_MEregister->id, "identifier"=>"hh_testing_site", "name" => "Service Delivery Points (SDP)", "description" => "","question_type" =>"4",  "required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_auditType= Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"audittype",  "name" => "Type of Audit", "description" => "","question_type" =>"4",  "required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_MEsupervisor = Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"namesurp",  "name" => "Name of supervisor being interviewed", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MEIndividualsTested = Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"noofindividuals",  "name" => "Number of individuals tested by rapid testing (previous year) at this site", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_otherSpecify = Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"otherspecify",  "name" => "If Others: specify", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MEpersonnel = Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"personnelno",  "name" => "Number of personnel authorized to offer HIV testing and counseling services at the site", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));

        $question_MEcadres = Question::create(array("section_id" => $sec_MEregister->id, "identifier"=>"cadres", "name" => "Cadres authorized to offer HIV testing and counseling services at the site", "description" => "(Please check box where applicable)","question_type" =>"5", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_otherSpecify19 = Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"otherspecify19",  "name" => "If Others: specify", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MEstrategy= Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"algorithm",  "name" => "Current testing strategy used at the site (Serial vs. Parallel)", "description" => "","question_type" =>"4", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_METest1= Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"screen",  "name" => "Screening or Test - 1:", "description" => "","question_type" =>"4", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_METest1Other= Question::create(array("section_id" => $sec_MEregister->id, "identifier"=>"other22", "name" => "Screening or Test - 1 Other:", "description" => "","question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_METest2= Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"contirmatory",  "name" => "Confirmatory or Test - 2:", "description" => "","question_type" =>"4", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_METest2Other= Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"other34",  "name" => "Screening or Test - 2 Other:", "description" => "","question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_METest3= Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"tiebreaker",  "name" => "Tie-breaker or Test - 3 (if applicable):", "description" => "","question_type" =>"4", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_METest3Other= Question::create(array("section_id" => $sec_MEregister->id,"identifier"=>"other56",  "name" => "Screening or Test - 3 Other:", "description" => "","question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        
        //M & E sections
        //section1
        
        $question_resources = Question::create(array("section_id" =>$sec_sec1 ->id, "identifier"=>"resources_available", "name" => "1.1 Are resources (staffing, funding, etc…) available to support quality assurance activities for HIV rapid testing at the site?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_resourcesComment= Question::create(array("section_id" => $sec_sec1->id,"identifier"=>"coments1", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_supply = Question::create(array("section_id" =>$sec_sec1 ->id,"identifier"=>"supplychain", "name" => "1.2 Is there a mechanism in place to address supply chain issues at the site (e.g., stock out, recall, expired kits, damaged, etc.)?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_supplyComment= Question::create(array("section_id" => $sec_sec1->id,"identifier"=>"coments2", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
       
        //section2
        $question_curricula= Question::create(array("section_id" =>$sec_sec2 ->id,"identifier"=>"handon", "name" => "2.1 Do the training curricula contain adequate hands-on sessions for HIV testing and counseling, interpretation, and troubleshooting?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_curriculaComment= Question::create(array("section_id" => $sec_sec2->id,"identifier"=>"coments3", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_training= Question::create(array("section_id" =>$sec_sec2 ->id,"identifier"=>"variousaspect", "name" => "2.2 Have staff been trained on various aspects of practical approaches to ensure and monitor the accuracy HIV of testing at the sites (e.g., use of standardized HTC register, DTS based proficiency testing program, QA, safety, testing procedures, etc.)?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_trainingComment= Question::create(array("section_id" => $sec_sec2->id,"identifier"=>"coments4", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_competency = Question::create(array("section_id" =>$sec_sec2 ->id,"identifier"=>"competency", "name" => "2.3 Is the competency of the personnel performing HIV testing and counseling assessed for certification by a MOH-recognized body?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_competencyComment= Question::create(array("section_id" => $sec_sec2->id, "identifier"=>"coments5","name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_certificate= Question::create(array("section_id" =>$sec_sec2 ->id,"identifier"=>"certificates", "name" => "2.4 Are the certificates for competency of each testing personnel required to be on display at testing sites/laboratories?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_certificateComment= Question::create(array("section_id" => $sec_sec2->id,"identifier"=>"coments6", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_certified= Question::create(array("section_id" =>$sec_sec2 ->id,"identifier"=>"counseling", "name" => "2.5 Has the HIV testing and counseling personnel of the site been certified or re-certified according to the certification calendar (annually, bi-annually. etc.)?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_certifiedComment= Question::create(array("section_id" => $sec_sec2->id,"identifier"=>"coments7", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_performance = Question::create(array("section_id" =>$sec_sec2 ->id,"identifier"=>"perfomance", "name" => "2.6 Has testing personnel been with unsatisfactory performance been re-trained on or de-certified for HIV testing and quality assurance related activities?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_performanceComment= Question::create(array("section_id" => $sec_sec2->id,"identifier"=>"coments8", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_criteria = Question::create(array("section_id" =>$sec_sec2 ->id,"identifier"=>"criteria", "name" => "2.7 Is the site ensuring that the criteria set by the MOH-recognized certification body are met for site certification", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_criteriaComment= Question::create(array("section_id" => $sec_sec2->id,"identifier"=>"coments9", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_siteCertification = Question::create(array("section_id" =>$sec_sec2 ->id, "identifier"=>"re-certified","name" => "2.8 Has the site been certified or re-certified according the certification calendar (annually, bi-annually. etc.)?* ", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_siteCertificationComment= Question::create(array("section_id" => $sec_sec2->id,"identifier"=>"coments10", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_display = Question::create(array("section_id" =>$sec_sec2 ->id,"identifier"=>"registration", "name" => "2.9 Is the registration and certification certificate of the HIV testing site required to be on display at testing site?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_displayComment= Question::create(array("section_id" => $sec_sec2->id,"identifier"=>"coments11", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_action = Question::create(array("section_id" =>$sec_sec2 ->id,"identifier"=>"correctiveactions", "name" => "2.10 Are corrective actions implemented at the sites/laboratories to ensure certification/re-certification of the sites?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_actionComment= Question::create(array("section_id" => $sec_sec2->id,"identifier"=>"coments12", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
       
        //section3
        $question_control = Question::create(array("section_id" =>$sec_sec3 ->id,"identifier"=>"qualitycontrol", "name" => "3.1 Is the site using quality control routinely to monitor the HIV test kits used?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_controlComment= Question::create(array("section_id" => $sec_sec3->id,"identifier"=>"coments13", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_pt = Question::create(array("section_id" =>$sec_sec3 ->id,"identifier"=>"participatingPt", "name" => "3.2 Is the site participating in proficiency testing (PT) program to monitor the competency of the all testing personnel performing HIV testing?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_ptComment= Question::create(array("section_id" => $sec_sec3->id, "identifier"=>"coments14","name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_feedback = Question::create(array("section_id" =>$sec_sec3 ->id, "identifier"=>"Ptprogram","name" => "3.3 Is feedback of PT program provided by the reference laboratory or PT provider?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_feedbackComment= Question::create(array("section_id" => $sec_sec3->id,"identifier"=>"coments15", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_ack = Question::create(array("section_id" =>$sec_sec3 ->id,"identifier"=>"testingpersonnel", "name" => "3.4 Is testing personnel with satisfactory PT score acknowledged by site supervisor?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_ackComment= Question::create(array("section_id" => $sec_sec3->id, "identifier"=>"coments16","name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_issues = Question::create(array("section_id" =>$sec_sec3 ->id,"identifier"=>"properly", "name" => "3.5 Are issues identified by the PT program properly investigated by site supervisor and documented?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_issuesComment= Question::create(array("section_id" => $sec_sec3->id,"identifier"=>"coments17", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_correctiveAction = Question::create(array("section_id" =>$sec_sec3 ->id,"identifier"=>"Ptscore", "name" => "3.6 Are appropriate corrective actions implemented for HIV testing personnel with unsatisfactory PT score as instructed by the national reference lab?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_correctiveActionComment= Question::create(array("section_id" => $sec_sec3->id,"identifier"=>"coments18", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        //section4
        $question_standardRegister = Question::create(array("section_id" =>$sec_sec4 ->id,"identifier"=>"htcregister", "name" => "4.1 Is the site using a standardized HTC register or register to capture key HIV testing data (e.g. kit names, lot number, expiration dates, and individual test results)?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_standardRegisterComment= Question::create(array("section_id" => $sec_sec4->id,"identifier"=>"coments19", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_registerTraining = Question::create(array("section_id" =>$sec_sec4 ->id,"identifier"=>"trainingprovided", "name" => "4.2 Is training provided for all testing personnel on the use of standardized HTC register?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_registerTrainingComment= Question::create(array("section_id" => $sec_sec4->id,"identifier"=>"coments20", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_reviewDataTraining = Question::create(array("section_id" =>$sec_sec4 ->id,"identifier"=>"reviewdata", "name" => "4.3 Has site supervisors been trained to review data from standardized HTC register and apply corrective actions, if needed?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
         $question_reviewDataTrainingComment= Question::create(array("section_id" => $sec_sec4->id,"identifier"=>"coments21", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_reportingStructure = Question::create(array("section_id" =>$sec_sec4 ->id,"identifier"=>"structured", "name" => "4.4 Is there a structured system in place for periodical HTC register page total data reporting?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
         $question_reportingStructureComment= Question::create(array("section_id" => $sec_sec4->id, "identifier"=>"coments22","name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_analyze = Question::create(array("section_id" =>$sec_sec4 ->id,"identifier"=>"summarypage", "name" => "4.5 Are HTC register pages and/or summary page totals reviewed and data analyzed at district level or by a laboratory or QA staff for troubleshooting, periodically?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
         $question_analyzeComment= Question::create(array("section_id" => $sec_sec4->id,"identifier"=>"coments23", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_reviewfeedback = Question::create(array("section_id" =>$sec_sec4 ->id, "identifier"=>"feedback","name" => "4.6 Is feedback from the review of the HTC register data provided to the sites and site personal?", "description" => "", "question_type" =>"0","required" => "1", "info" => "", "score" => "3", "user_id" => "1"));
        $question_reviewfeedbackComment= Question::create(array("section_id" => $sec_sec4->id,"identifier"=>"coments24", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        //total
        $question_MEtotalScore= Question::create(array("section_id" =>$sec_MEtotalScore ->id,"identifier"=>"sec5sum", "name" => "Total Score", "description" => "", "question_type" =>"2","required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        //other
        $question_MElat = Question::create(array("section_id" => $sec_MEother->id,"identifier"=>"", "name" => "GPS Latitude", "description" => "", "question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_MElong = Question::create(array("section_id" => $sec_MEother->id, "identifier"=>"","name" => "GPS Longitude", "description" => "", "question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        


        /**SPI-RT Questions
         /** main page*/
        $question_SpiqaOfficer = Question::create(array("section_id" => $sec_Spisurvey->id,"identifier"=>"", "name" => "Name of the QA Officer", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_Spifacility = Question::create(array("section_id" => $sec_Spisurvey->id,"identifier"=>"", "name" => "Facility", "description" => "Facility","question_type" =>"4",  "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_Spisdp = Question::create(array("section_id" => $sec_Spiregister->id,"identifier"=>"hh_testing_site", "name" => "Service Delivery Points (SDP)", "description" => "","question_type" =>"4",  "required" => "1", "info" => "Select One", "score" => "0", "user_id" => "1"));
        $question_affiliation= Question::create(array("section_id" => $sec_Spiregister->id,"identifier"=>"affiliation", "name" => "Affilliation (Circle One)", "description" => "","question_type" =>"4", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_otheraffiliation = Question::create(array("section_id" => $sec_Spiregister->id,"identifier"=>"otheraffiliation",  "name" => "If Others: specify", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_collectionDate= Question::create(array("section_id" => $sec_Spiregister->id,"identifier"=>"dateofsubmission", "name" => "Date of Collection", "description" => "","question_type" =>"1", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
         //section1
        $question_comprehensiveTraining= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"providers_recieved_training", "name" => "1.1 Have the providers received a comprehensive training on HIV rapid test?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_comprehensiveTrainingComment= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"comments1", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_stdRegisterTraining= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"providers_trained_logbooks", "name" => "1.2 Are the providers trained on the use of standardized registers/logbooks?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_stdRegisterTrainingComment= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"comments2", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_ptTraining= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"providers_trained_pt", "name" => "1.3 Are the providers trained on proficiency testing (PT) process?* ", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_ptTrainingComment= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"comments3", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_qc= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"providers_trained_qc", "name" => "1.4 Are the providers trained on quality control (QC) process?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_qcComment= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"comments4", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_safety= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"providers_trained_safety", "name" => "1.5 Are the providers trained on safety and waste management procedures?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_safetyComment= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"comments5", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_signedRecord= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"procedures_read", "name" => "1.6 Are there signed records of all procedures read and understood by HIV rapid testing personnel?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_signedRecordComment= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"comments6", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_periodicTraining= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"is_periodic", "name" => "1.7 Is periodic (i.e. every two years) HIV rapid test refresher training offered for testing personnel?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_periodicTrainingComment= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"comments7", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_evidence= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"evidence_adequate", "name" => "1.8 Is there evidence that providers received adequate, specific training prior to patient testing to ensure competence?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_evidenceComment= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"comments8", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_certificationProgram= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"certification_program", "name" => "1.9 If there is a national certification program, are providers certified?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_certificationProgramComment= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"comments9", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_provider= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"only_certified_providers", "name" => "1.10 Are only certified providers allowed to perform testing?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_providerComment= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"comments10", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_reCertification= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"providers_required", "name" => "1.11 Are certified providers required re-certifications periodically (i.e. every two years)?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_reCertificationComment= Question::create(array("section_id" => $sec_Spisec1->id,"identifier"=>"comments11", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        //section2
        $question_area= Question::create(array("section_id" => $sec_Spisec2->id,"identifier"=>"HIV_testing_area", "name" => "2.1 Is there a designated area for HIV testing?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_areaComment= Question::create(array("section_id" => $sec_Spisec2->id,"identifier"=>"comments12", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_clean= Question::create(array("section_id" => $sec_Spisec2->id, "identifier"=>"clean_testing_area","name" => "2.2 Is the testing area clean and organized for HIV rapid testing?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_cleanComment= Question::create(array("section_id" => $sec_Spisec2->id,"identifier"=>"comments13", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_lighting= Question::create(array("section_id" => $sec_Spisec2->id,"identifier"=>"lighting_available", "name" => "2.3 Is sufficient lighting available in the designated testing area?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_lightingComment= Question::create(array("section_id" => $sec_Spisec2->id,"identifier"=>"comments14", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_power= Question::create(array("section_id" => $sec_Spisec2->id,"identifier"=>"continous_power", "name" => "2.4 Is there continuous power supply available (If the kits are required to be stored in a refrigerator)?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_powerComment= Question::create(array("section_id" => $sec_Spisec2->id,"identifier"=>"comments15", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_storage= Question::create(array("section_id" => $sec_Spisec2->id,"identifier"=>"secure_storage", "name" => "2.5 Is there sufficient and secure storage space for test kits and other consumables?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_storageComment= Question::create(array("section_id" => $sec_Spisec2->id,"identifier"=>"comments16", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        //section3
        $question_sops= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"sops_inplace", "name" => "3.1 Are there SOPs and/or job aides in place to ensure that providers know to implement safety practices (apron, gloves, etc.)?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_sopsComment= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"comments17", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_dispose= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"sops_todispose", "name" => "3.2 Are there SOPs and/or job aides in place on how to dispose of infectious and non-infectious waste?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_disposeComment= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"comments18", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_spill= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"sops_tomanagespills", "name" => "3.3 Are there SOPs and/or job aides in place to manage spills of blood and other body fluids?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_spillComment= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"comments19", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_exposure= Question::create(array("section_id" => $sec_Spisec3->id, "identifier"=>"sops_address","name" => "3.4 Are there SOPs and/or job aides in place to address occupational exposure to potentially infectious body fluids through a needle stick injury, splash or other sharps injury?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_exposureComment= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"comments20", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_gear= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"safety_gears", "name" => "3.5 Are appropriate safety gears (i.e. gloves, lab coats or aprons) available for the providers?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_gearComment= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"comments21", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_gearuse= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"consistently_used", "name" => "3.6 Are appropriate safety gears (i.e. gloves, lab coats or aprons) consistently used by the providers?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_gearUseComment= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"comments22", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_water= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"hand_washing", "name" => "3.7 Are there clean water and soap available for hand washing?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_waterComment= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"comments23", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_disinfectant= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"disinfectant", "name" => "3.8 Is there an appropriate disinfectant (i.e. bleach, alcohol, etc.) available?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_disinfectantComment= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"comments25", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_handling= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"waste_handling", "name" => "3.9 Are sharps, infectious and non-infectious wastes handled properly?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_handlingComment= Question::create(array("section_id" => $sec_Spisec3->id,"identifier"=>"comments26", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
         //section4
        $question_guidelines= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"test_guidelines", "name" => "4.1 Are there national testing guidelines specific to the program available at the testing site?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_guidelinesComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments27", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_algoUse= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"national_testing", "name" => "4.2 Is the national testing algorithm being used?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_algoUseComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments28", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_jobAides= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"job_aides", "name" => "4.3 Are there SOPs and/or job aides in place for each HIV rapid test used in the testing algorithm?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_jobAidesComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments29", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_kits= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"HIV_rapidkits", "name" => "4.4 Are only MOH approved HIV rapid kits available for use?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_kitsComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments30", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_kitStorage= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"stored_according_toManu", "name" => "4.5 Are test kits stored according to manufacturer recommendations?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_kitStorageComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments31", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_inventory= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"inventory_management", "name" => "4.6 Is there a process in place for inventory management (receiving and monitoring supplies, handling expired kits, etc.)?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_inventoryComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments32", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_reagent= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"expirationdate", "name" => "4.7 Are reagents used within expiration date (First Expired, First Out principle)?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_reagentComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments33", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_label= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"labeledwithdate", "name" => "4.8 Are test kits labeled with date received, date opened, and initials?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_labelComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments34", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_alternative= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"place_alter", "name" => "4.9 Is there a process in place for alternative testing algorithm, in case of expired or shortage of test kit(s)?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_alternativeComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments35", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_specimen= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"specimencollection", "name" => "4.10 Are job aides on specimen collection available and posted at the facility?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_specimenComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments36", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_supplies= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"suffientsupplies", "name" => "4.11 Are there sufficient supplies available for specimen collection (i.e. lancets, gauze, alcohol swabs, plaster, etc.)?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_suppliesComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments37", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_disposal= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"shaprdisposal", "name" => "4.12 Are sharps (e.g., lancets and needles) disposed into appropriate containers after the blood collection procedure is performed?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_disposalComment= Question::create(array("section_id" => $sec_Spisec4->id,"identifier"=>"comments38", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
         //section5
        $question_testProcedure= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"TestingProcedures", "name" => "5.1 Are job aides on HIV testing procedures available and posted at the testing site?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_testProcedureComment= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"comments39", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_timers= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"routinely", "name" => "5.2 Are timers available and used routinely for HIV rapid testing?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_timersComment= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"comments40", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_devices= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"properlyLabeled", "name" => "5.3 Are test devices properly labeled with client ID during testing?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_devicesComment= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"comments41", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_sample= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"samplecollection", "name" => "5.4 Are sample collection devices (capillary tube, loop, disposable pipettes etc.) used accurately?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_sampleComment= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"comments42", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_procedures= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"proceduresfollowed", "name" => "5.5 Are testing procedures adequately followed (during observation)?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_proceduresComment= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"comments43", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_qcSpecimen= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"negativequality", "name" => "5.6 Are positive and negative quality control (QC) specimens routinely used (i.e. daily or weekly) according to country guidelines?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_qcSpecimenComment= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"comments44", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_qcResult= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"Qcresults", "name" => "5.7 Is QC results properly recorded?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_qcResultComment= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"comments45", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_stepDocumentation= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"stepsdocumented", "name" => "5.8 Are appropriate steps documented and taken when QC results are incorrect and/or invalid?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_stepDocumentationComment= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"comments46", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_qcRecordReview= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"qcrecords", "name" => "5.9 Are QC records reviewed by a supervisor routinely?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_qcRecordReviewComment= Question::create(array("section_id" => $sec_Spisec5->id,"identifier"=>"comments47", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
         //section6
        $question_testResult= Question::create(array("section_id" => $sec_Spisec6->id,"identifier"=>"results_recorded", "name" => "6.1 Are test results properly recorded in register/logbook?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_testResultComment= Question::create(array("section_id" => $sec_Spisec6->id,"identifier"=>"comments48", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_testDevice= Question::create(array("section_id" => $sec_Spisec6->id,"identifier"=>"divicesdisposed", "name" => "6.2 Are test devices disposed of properly after testing?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_testDeviceComment= Question::create(array("section_id" => $sec_Spisec6->id,"identifier"=>"comments49", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_testArea= Question::create(array("section_id" => $sec_Spisec6->id, "identifier"=>"areacleaned","name" => "6.3 Is testing area properly cleaned and disinfected?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_testAreaComment= Question::create(array("section_id" => $sec_Spisec6->id,"identifier"=>"comments50", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_container= Question::create(array("section_id" => $sec_Spisec6->id,"identifier"=>"wastecontainers", "name" => "6.4 Are waste containers emptied regularly?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_containerComment= Question::create(array("section_id" => $sec_Spisec6->id,"identifier"=>"comments51", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        //section7
        $question_htcUse= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"natioalstandard", "name" => "7.1 Is there a HTC Lab Register(MOH 362) available and in use at the site?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_htcUseComment= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"comments52", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_elements= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"HIVelements", "name" => "7.2 Are all the elements in the HIV rapid testing registers captured correctly (i.e., kit names, lot numbers, expiration dates, client demographics, tester name, individual and final HIV results, etc.)?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_elementsComment= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"comments53", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_invalid= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"resultsrecorded", "name" => "7.3 Are invalid test results recorded in the registers, and then repeated?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_invalidComment= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"comments54", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_end= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"eachpage", "name" => "7.4 Is the end of each page total summary for the registers complied accurately?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_endComment= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"comments55", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_secure= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"securelocation", "name" => "7.5 Are all registers and other documents kept in a secure location?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_secureComment= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"comments56", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_numbered= Question::create(array("section_id" => $sec_Spisec7->id, "identifier"=>"properly_labeled","name" => "7.6 Are registers properly labeled/numbered and archived when full?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_numberedComment= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"comments57", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_confidentiality= Question::create(array("section_id" => $sec_Spisec7->id, "identifier"=>"privateinformation","name" => "7.7 Does the testing site ensure confidentiality of client information throughout all phases of the testing process?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_confidentialityComment= Question::create(array("section_id" => $sec_Spisec7->id,"identifier"=>"comments58", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        //section8
        $question_ptEnrolled= Question::create(array("section_id" => $sec_Spisec8->id, "identifier"=>"siteenrolled", "name" => "8.1 (a) Is the testing site enrolled in the PT program?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_ptEnrolledComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments59", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_enrolled= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"providersenrolled", "name" => "8.1 (b) Are all providers enrolled in the PT program?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_areEnrolled= Question::create(array("section_id" => $sec_Spisec8->id, "identifier"=>"areenolled","name" => "If no, how many are enrolled", "description" => "If no, how many are enrolled","question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_enrolledComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments60", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_eqa= Question::create(array("section_id" => $sec_Spisec8->id, "identifier"=>"participateEQA","name" => "8.2 Do all providers at the testing site participate in the EQA/PT program?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_eqaComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments61", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_headReview= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"sitereview", "name" => "8.3 Does testing site incharge/QA officer review EQA/PT results before submission to NHRL?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_headReviewComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments62", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_report= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"feedbackreport", "name" => "8.4 Is EQA/PT feedback report received and reviewed?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_reportComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments63", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_siteCorrectiveAction= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"correctiveaction", "name" => "8.5 (a) Does the site implement corrective actions in case unsatisfactory", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_siteCorrectiveActionComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments64", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_unsatisfactory= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"correctiveactionproviders", "name" => "8.5 (b) Is corrective action implemented for all providers with unsatisfactory results?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_unsaManyPeople= Question::create(array("section_id" => $sec_Spisec8->id, "identifier"=>"manypeople","name" => "How many people implement corrective action?", "description" => "How many people implement corrective action?","question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_unsatisfactoryComment= Question::create(array("section_id" => $sec_Spisec8->id, "identifier"=>"comments65","name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_dbs= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"dbsapply", "name" => "Does DBS for external qualityquality assurance apply on this site?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_dbsComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_sampleRetest= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"samplesforresting", "name" => "8.6 Does the site collect samples for retesting (i.e. collection of DBS every 20th client)? *", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_sampleRetestComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments66", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_sampleCollect= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"samplescollected", "name" => "8.7 Are DBS samples collected properly (i.e., at least 3 complete circles) *", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_sampleCollectComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments67", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_sampleStore= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"samplesstored", "name" => "8.8 Are DBS samples stored properly (i.e., away from sun light, separated by glycine paper, desiccant, etc. *", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_sampleStoreComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments68", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_sampleSent= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"samplessent", "name" => "8.9 Are the IDs of DBS samples sent for retesting recorded? *", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_sampleSentComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments69", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_resultsReceipt= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"resultsreciept", "name" => "8.10 Are the DBS results upon receipt from NHRL recorded in the HIV register/logbook and used for corrective actions? *", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_resultsReceiptComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments70", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
              
        $question_periodicReview= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"siterecieve", "name" => "8.11 Does the site receive periodically supervisory team?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_periodicReviewComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments71", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_observation= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"directobservation", "name" => "8.12 Is a direct observation of client testing performed during site supervision?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_observationComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments72", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_providers= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"providersretained", "name" => "8.13 Are providers retrained during site supervision?", "description" => "","question_type" =>"0", "required" => "1", "info" => "", "score" => "1", "user_id" => "1"));
        $question_providersComment= Question::create(array("section_id" => $sec_Spisec8->id,"identifier"=>"comments73", "name" => "Comments", "description" => "Comments","question_type" =>"3", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
         //section9

        $question_testers= Question::create(array("section_id" => $sec_Spisec9->id,"identifier"=>"numbertesters", "name" => "Number of Testers", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_auditLength= Question::create(array("section_id" => $sec_Spisec9->id,"identifier"=>"auditlength", "name" => "Length of Audit", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_secNo= Question::create(array("section_id" => $sec_Spisec9->id,"identifier"=>"sectionno", "name" => "Section Number", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_secComment= Question::create(array("section_id" => $sec_Spisec9->id, "identifier"=>"comments1","name" => "Comments", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_supervisor= Question::create(array("section_id" => $sec_Spisec9->id,"identifier"=>"sitesuperviser", "name" => "Site Superviser Name", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_comment= Question::create(array("section_id" => $sec_Spisec9->id,"identifier"=>"comments3", "name" => "Comments", "description" => "","question_type" =>"2", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
        $question_date= Question::create(array("section_id" => $sec_Spisec9->id,"identifier"=>"datefinal", "name" => "Date", "description" => "","question_type" =>"1", "required" => "1", "info" => "", "score" => "0", "user_id" => "1"));
       
        $question_Spilat = Question::create(array("section_id" => $sec_Spiother->id,"identifier"=>"", "name" => "GPS Latitude", "description" => "", "question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        $question_Spilong = Question::create(array("section_id" => $sec_Spiother->id,"identifier"=>"", "name" => "GPS Longitude", "description" => "", "question_type" =>"2", "required" => "0", "info" => "", "score" => "0", "user_id" => "1"));
        
        /**
        *   PT Enrollment tool
        */

        /* Responses */
        $response_yes = Answer::create(array("name" => "Yes", "description" => "Yes(Y)", "score" => "1.0", "user_id" => "1"));
        $response_no = Answer::create(array("name" => "No", "description" => "No(N)", "score" => "0.0", "user_id" => "1"));
        $response_partial = Answer::create(array("name" => "Partial", "description" => "Partial(P)", "score" => "0.5", "user_id" => "1"));
        $response_doesNotExist = Answer::create(array("name" => "Does Not Exist", "description" => "", "score" => "0.0", "range_lower" => "0.00", "range_upper" => "25.00", "user_id" => "1"));
        $response_inDevelopment = Answer::create(array("name" => "In Development", "description" => "", "score" => "1.0", "range_lower" => "25.00", "range_upper" => "50.00", "user_id" => "1"));
        $response_beingImplemented= Answer::create(array("name" => "Being Implemented", "description" => "", "score" => "2.0", "range_lower" => "50.00", "range_upper" => "75.00", "user_id" => "1"));
        $response_completed = Answer::create(array("name" => "Completed", "description" => "", "score" => "3.0", "range_lower" => "75.00", "range_upper" => "100.00", "user_id" => "1"));
        //HTC lab register
        $response_provided = Answer::create(array("name" => "Provided", "description" => "Provided", "user_id" => "1"));
        $response_notProvided = Answer::create(array("name" => "Not Provided", "description" => "Not Provided", "user_id" => "1"));
        $response_available = Answer::create(array("name" => "Available", "description" => "Available", "user_id" => "1"));
        $response_notavailable = Answer::create(array("name" => "Not Available", "description" => "Not Available", "user_id" => "1"));

        $response_kits = Answer::create(array("name" => "Kits used are not in the national algorithm", "description" => "kits", "user_id" => "1"));
        $response_testkits = Answer::create(array("name" => "Wrong sequence of test kits", "description" => "testkits", "user_id" => "1"));        
        $response_confirmationtest1 = Answer::create(array("name" => "Confirmation of Test 1 reactive/postive not done", "description" => "confirmationtest1", "user_id" => "1"));
        $response_test2done = Answer::create(array("name" => "Test 2 done when Test 1 was negative/ non reactive", "description" => "test2done", "user_id" => "1"));
        $response_test3done = Answer::create(array("name" => "Test 3 done when Test 1 and Test 2 were postive ", "description" => "test3done", "user_id" => "1"));
        $response_test3negative = Answer::create(array("name" => "Test 3 done when Test 1 and Test 2 were negative", "description" => "test3negative", "user_id" => "1"));
        $response_tie = Answer::create(array("name" => "Tie breaking for discrepant results not done", "description" => "tie", "user_id" => "1"));

        $this->command->info('Answers table seeded');
        //M&E
        $response_baseline = AuditType::create(array("name" => "Baseline", "description" => "", "user_id" => "1"));
        $response_followUp = AuditType::create(array("name" => "Follow Up", "description" => "", "user_id" => "1"));
        $this->command->info('Audit type table seeded');

        $response_serial = Algorithm::create(array("name" => "Serial", "description" => "", "user_id" => "1"));
        $response_parallel = Algorithm::create(array("name" => "Parallel", "description" => "", "user_id" => "1"));
        $this->command->info('Algorithm table seeded');

        $response_KHB = HivTestKit::create(array("name" => "KHB", "description" => "", "user_id" => "1"));
        $response_firstResponse = HivTestKit::create(array("name" => "First Response", "description" => "", "user_id" => "1"));
        $response_unigold = HivTestKit::create(array("name" => "Unigold", "description" => "", "user_id" => "1"));
        $response_other = HivTestKit::create(array("name" => "Other", "description" => "", "user_id" => "1"));
        $this->command->info('HivTestKit table seeded');

        $response_govt = Affiliation::create(array("name" => "Government", "description" => "", "user_id" => "1"));
        $response_private = Affiliation::create(array("name" => "Private", "description" => "", "user_id" => "1"));
        $response_fbo = Affiliation::create(array("name" => "Faith Based Organization", "description" => "", "user_id" => "1"));
        $response_ngo = Affiliation::create(array("name" => "Non Governmental Organisation", "description" => "", "user_id" => "1"));
        $response_other = Affiliation::create(array("name" => "Other", "description" => "", "user_id" => "1"));
        $this->command->info('Affiliation table seeded');
       

        /* Question-Responses*/
                        
        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest1Status ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest1Status ->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test1lotNoStatus ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test1lotNoStatus->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test1expiryDateStatus ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test1expiryDateStatus ->id, "response_id" => $response_notProvided->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest2Status ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest2Status ->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test2lotNoStatus ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test2lotNoStatus ->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test2expiryDateStatus ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test2expiryDateStatus ->id, "response_id" => $response_notProvided->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest3Status ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_hivTest3Status ->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test3lotNoStatus ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test3lotNoStatus ->id, "response_id" => $response_notProvided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test3expiryDateStatus ->id, "response_id" => $response_provided->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_test3expiryDateStatus ->id, "response_id" => $response_notProvided->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_supervisorReview->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_supervisorReview->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmFollowed->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmFollowed->id, "response_id" => $response_no->id));

       //M & E
        DB::table('question_responses')->insert(
            array("question_id" => $question_resources->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_resources ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_resources ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_resources->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_supply->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_supply ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_supply ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_supply->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_curricula->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_curricula ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_curricula ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_curricula->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_training->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_training ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_training ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_training->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_competency->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_competency ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_competency ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_competency->id, "response_id" => $response_completed->id));
        
        DB::table('question_responses')->insert(
            array("question_id" => $question_certificate->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_certificate ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_certificate ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_certificate->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_certified->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_certified ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_certified ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_certified->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_performance->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_performance ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_performance ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_performance->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_criteria->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_criteria ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_criteria ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_criteria->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_siteCertification->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_siteCertification ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_siteCertification ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_siteCertification->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_display->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_display ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_display ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_display->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_action->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_action ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_action ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_action->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_control->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_control ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_control ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_control->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_pt->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_pt ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_pt ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_pt->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_feedback->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_feedback ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_feedback ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_feedback->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_ack->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_ack ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_ack ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_ack->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_issues->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_issues ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_issues ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_issues->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_correctiveAction->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_correctiveAction ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_correctiveAction ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_correctiveAction->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_standardRegister->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_standardRegister ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_standardRegister ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_standardRegister->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_registerTraining->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_registerTraining ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_registerTraining ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_registerTraining->id, "response_id" => $response_completed->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_reviewDataTraining->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reviewDataTraining ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reviewDataTraining ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reviewDataTraining->id, "response_id" => $response_completed->id));
       
       DB::table('question_responses')->insert(
            array("question_id" => $question_reportingStructure->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reportingStructure ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reportingStructure ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reportingStructure->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_analyze->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_analyze ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_analyze ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_analyze->id, "response_id" => $response_completed->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_reviewfeedback->id, "response_id" => $response_doesNotExist->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reviewfeedback ->id, "response_id" => $response_inDevelopment->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reviewfeedback ->id, "response_id" => $response_beingImplemented->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reviewfeedback->id, "response_id" => $response_completed->id));

        //SPI-RT checklist
        DB::table('question_responses')->insert(
            array("question_id" => $question_comprehensiveTraining ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_comprehensiveTraining ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_comprehensiveTraining ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_stdRegisterTraining ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_stdRegisterTraining ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_stdRegisterTraining ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_ptTraining ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_ptTraining ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_ptTraining ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_qc ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_qc ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_qc ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_safety ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_safety ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_safety ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_signedRecord ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_signedRecord ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_signedRecord ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_periodicTraining ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_periodicTraining ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_periodicTraining ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_evidence ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_evidence ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_evidence ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_certificationProgram ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_certificationProgram ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_certificationProgram ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_provider ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_provider ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_provider ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_reCertification ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reCertification ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reCertification ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_area ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_area ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_area ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_clean ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_clean ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_clean ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_lighting ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_lighting ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_lighting ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_power ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_power ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_power ->id, "response_id" => $response_partial->id));
        
         DB::table('question_responses')->insert(
            array("question_id" => $question_storage ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_storage ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_storage ->id, "response_id" => $response_partial->id));
        
         DB::table('question_responses')->insert(
            array("question_id" => $question_sops ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sops ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sops ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_dispose ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_dispose ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_dispose ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_testProcedure ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_testProcedure ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_testProcedure ->id, "response_id" => $response_partial->id));
        

        DB::table('question_responses')->insert(
            array("question_id" => $question_spill ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_spill ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_spill ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_exposure ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_exposure ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_exposure ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_gear ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_gear ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_gear ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_gearuse ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_gearuse ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_gearuse ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_water ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_water ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_water ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_disinfectant ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_disinfectant ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_disinfectant ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_handling ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_handling ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_handling ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_guidelines ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_guidelines ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_guidelines ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_algoUse ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algoUse ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algoUse ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_jobAides ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_jobAides ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_jobAides ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_kits ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_kits ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_kits ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_kitStorage ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_kitStorage ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_kitStorage ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_inventory ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_inventory ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_inventory ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_reagent ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reagent ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_reagent ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_label ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_label ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_label ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_alternative ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_alternative ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_alternative ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_specimen ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_specimen ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_specimen ->id, "response_id" => $response_partial->id));

          DB::table('question_responses')->insert(
            array("question_id" => $question_supplies ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_supplies ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_supplies ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_disposal ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_disposal ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_disposal ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_timers ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_timers ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_timers ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_devices ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_devices ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_devices ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_sample ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sample ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sample ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_procedures ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_procedures ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_procedures ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_qcSpecimen ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_qcSpecimen ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_qcSpecimen ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_qcResult ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_qcResult ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_qcResult ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_stepDocumentation ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_stepDocumentation ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_stepDocumentation ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_qcRecordReview ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_qcRecordReview ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_qcRecordReview ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_testResult ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_testResult ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_testResult ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_testDevice ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_testDevice ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_testDevice ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_testArea ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_testArea ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_testArea ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_container ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_container ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_container ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_htcUse ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_htcUse ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_htcUse ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_elements ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_elements ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_elements ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_invalid ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_invalid ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_invalid ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_end ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_end ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_end ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_secure ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_secure ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_secure ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_numbered ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_numbered ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_numbered ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_confidentiality ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_confidentiality ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_confidentiality ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_ptEnrolled ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_ptEnrolled ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_ptEnrolled ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_enrolled ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_enrolled ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_enrolled ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_eqa ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_eqa ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_eqa ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_headReview ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_headReview ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_headReview ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_report ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_report ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_report ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_siteCorrectiveAction ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_siteCorrectiveAction ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_siteCorrectiveAction ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_unsatisfactory ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_unsatisfactory ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_unsatisfactory ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_dbs ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_dbs ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_dbs ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_sampleRetest ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sampleRetest ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sampleRetest ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_sampleCollect ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sampleCollect ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sampleCollect ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_sampleStore ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sampleStore ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sampleStore ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_sampleSent ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sampleSent ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_sampleSent ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_resultsReceipt ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_resultsReceipt ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_resultsReceipt ->id, "response_id" => $response_partial->id));

         DB::table('question_responses')->insert(
            array("question_id" => $question_periodicReview ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_periodicReview ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_periodicReview ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_observation ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_observation ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_observation ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_providers ->id, "response_id" => $response_no->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_providers ->id, "response_id" => $response_yes->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_providers ->id, "response_id" => $response_partial->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_consumption_summary ->id, "response_id" => $response_available->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_consumption_summary ->id, "response_id" => $response_notavailable->id));

        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmNotFollowed ->id, "response_id" => $response_kits->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmNotFollowed ->id, "response_id" => $response_testkits->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmNotFollowed ->id, "response_id" => $response_confirmationtest1->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmNotFollowed ->id, "response_id" => $response_test2done->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmNotFollowed ->id, "response_id" => $response_test3done->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmNotFollowed ->id, "response_id" => $response_test3negative->id));
        DB::table('question_responses')->insert(
            array("question_id" => $question_algorithmNotFollowed ->id, "response_id" => $response_tie->id));
        
        $this->command->info('Question-responses table seeded');
        //  Levels table
        $level_0 = Level::create(array("name" => "Level 0", "description" => "", "range_lower" => "0", "range_upper" => "39", "user_id" => "1"));
        $level_1 = Level::create(array("name" => "Level 1", "description" => "", "range_lower" => "40", "range_upper" => "59", "user_id" => "1"));
        $level_2 = Level::create(array("name" => "Level 2", "description" => "", "range_lower" => "60", "range_upper" => "79", "user_id" => "1"));
        $level_3 = Level::create(array("name" => "Level 3", "description" => "", "range_lower" => "80", "range_upper" => "89", "user_id" => "1"));
        $level_4 = Level::create(array("name" => "Level 4", "description" => "", "range_lower" => "90", "range_upper" => "100", "user_id" => "1"));
        $this->command->info('Levels table seeded');    
    }
}