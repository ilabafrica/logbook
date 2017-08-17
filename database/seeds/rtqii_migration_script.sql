-- 1. Insert new records of tiers --
INSERT INTO tiers (name, user_id, created_at, updated_at) VALUES 
('IPD 1', 1, NOW(), NOW()),
('IPD 2', 1, NOW(), NOW()),
('IPD 5', 1, NOW(), NOW()),
('GENERAL WARD', 1, NOW(), NOW()),
('OPD 1', 1, NOW(), NOW()),
('OPD 2', 1, NOW(), NOW()),
('OPD 3', 1, NOW(), NOW()),
('ANC', 1, NOW(), NOW()),
('ANC/FP', 1, NOW(), NOW()),
('LABOUR&DELIVERY', 1, NOW(), NOW()),
('MATERNITY', 1, NOW(), NOW()),
('MNCH', 1, NOW(), NOW()),
('VCT 1', 1, NOW(), NOW()),
('VCT 2', 1, NOW(), NOW()),
('JUVENILE', 1, NOW(), NOW()),
('KAMITI MAXIMUM', 1, NOW(), NOW()),
('KAMITI MEDIUM', 1, NOW(), NOW()),
('MAIN HOSPITAL', 1, NOW(), NOW()),
('ROOM 5', 1, NOW(), NOW()),
('ROOM 10', 1, NOW(), NOW()),
('ROOM 11', 1, NOW(), NOW()),
('ROOM 16', 1, NOW(), NOW());
-- 2. Insert new records of sdp-tiers --
INSERT INTO sdp_tiers (sdp_id, tier_id, user_id, created_at, updated_at) VALUES 
(8, 1, 1, NOW(), NOW()),
(8, 2, 1, NOW(), NOW()),
(8, 3, 1, NOW(), NOW()),
(8, 4, 1, NOW(), NOW()),
(5, 5, 1, NOW(), NOW()),
(5, 6, 1, NOW(), NOW()),
(5, 7, 1, NOW(), NOW()),
(7, 8, 1, NOW(), NOW()),
(7, 9, 1, NOW(), NOW()),
(7, 10, 1, NOW(), NOW()),
(7, 11, 1, NOW(), NOW()),
(7, 12, 1, NOW(), NOW()),
(10, 13, 1, NOW(), NOW()),
(10, 14, 1, NOW(), NOW()),
(10, 15, 1, NOW(), NOW()),
(10, 16, 1, NOW(), NOW()),
(10, 17, 1, NOW(), NOW()),
(10, 18, 1, NOW(), NOW()),
(10, 19, 1, NOW(), NOW()),
(10, 20, 1, NOW(), NOW()),
(10, 21, 1, NOW(), NOW()),
(10, 22, 1, NOW(), NOW());
-- 3. Insert new records of facility-sdps --
INSERT INTO facility_sdps (facility_id, sdp_id, sdp_tier_id, user_id, created_at, updated_at) VALUES
(1, 5, NULL, 1, NOW(), NOW()),
(1, 12, NULL, 1, NOW(), NOW()),
(1, 8, 2, 1, NOW(), NOW()),
(1, 7, 12, 1, NOW(), NOW()),
(1, 9, NULL, 1, NOW(), NOW()),
(1, 11, NULL, 1, NOW(), NOW()),
(1, 8, 3, 1, NOW(), NOW()),
(2, 5, 5, 1, NOW(), NOW()),
(2, 9, NULL, 1, NOW(), NOW()),
(4, 5, 5, 1, NOW(), NOW()),
(5, 5, 5, 1, NOW(), NOW()),
(6, 1, NULL, 1, NOW(), NOW()),
(6, 12, NULL, 1, NOW(), NOW()),
(6, 5, 5, 1, NOW(), NOW()),
(6, 5, 6, 1, NOW(), NOW()),
(6, 15, NULL, 1, NOW(), NOW()),
(6, 7, 12, 1, NOW(), NOW()),
(7, 1, NULL, 1, NOW(), NOW()),
(7, 7, 8, 1, NOW(), NOW()),
(7, 8, 1, 1, NOW(), NOW()),
(7, 5, 5, 1, NOW(), NOW()),
(8, 11, NULL, 1, NOW(), NOW()),
(8, 5, 5, 1, NOW(), NOW()),
(8, 1, NULL, 1, NOW(), NOW()),
(8, 7, 8, 1, NOW(), NOW()),
(9, 9, NULL, 1, NOW(), NOW()),
(10, 5, 5, 1, NOW(), NOW()),
(10, 7, 8, 1, NOW(), NOW()),
(11, 5, 5, 1, NOW(), NOW()),
(11, 7, 8, 1, NOW(), NOW()),
(11, 1, NULL, 1, NOW(), NOW()),
(12, 5, 5, 1, NOW(), NOW()),
(12, 7, 8, 1, NOW(), NOW()),
(12, 8, 1, 1, NOW(), NOW()),
(13, 5, 5, 1, NOW(), NOW()),
(13, 5, 6, 1, NOW(), NOW()),
(13, 7, 8, 1, NOW(), NOW()),
(14, 10, NULL, 1, NOW(), NOW()),
(15, 1, NULL, 1, NOW(), NOW()),
(15, 15, NULL, 1, NOW(), NOW()),
(15, 11, NULL, 1, NOW(), NOW()),
(15, 7, 8, 1, NOW(), NOW()),
(15, 7, 11, 1, NOW(), NOW()),
(16, 11, NULL, 1, NOW(), NOW()),
(16, 7, 8, 1, NOW(), NOW()),
(16, 5, 5, 1, NOW(), NOW()),
(17, 5, 5, 1, NOW(), NOW()),
(17, 1, NULL, 1, NOW(), NOW()),
(17, 8, 1, 1, NOW(), NOW()),
(17, 7, 12, 1, NOW(), NOW()),
(17, 7, 8, 1, NOW(), NOW()),
(18, 5, NULL, 1, NOW(), NOW()),
(18, 7, 8, 1, NOW(), NOW()),
(19, 5, 5, 1, NOW(), NOW()),
(19, 7, 8, 1, NOW(), NOW()),
(20, 8, 4, 1, NOW(), NOW()),
(20, 10, NULL, 1, NOW(), NOW()),
(20, 5, 5, 1, NOW(), NOW()),
(20, 1, NULL, 1, NOW(), NOW()),
(20, 12, NULL, 1, NOW(), NOW()),
(20, 7, 8, 1, NOW(), NOW()),
(20, 7, 10, 1, NOW(), NOW()),
(20, 7, 11, 1, NOW(), NOW()),
(21, 10, NULL, 1, NOW(), NOW()),
(21, 7, 8, 1, NOW(), NOW()),
(21, 7, 11, 1, NOW(), NOW()),
(22, 10, NULL, 1, NOW(), NOW()),
(22, 7, 8, 1, NOW(), NOW()),
(23, 10, NULL, 1, NOW(), NOW()),
(23, 7, 8, 1, NOW(), NOW()),
(24, 10, NULL, 1, NOW(), NOW()),
(24, 7, 8, 1, NOW(), NOW()),
(25, 10, NULL, 1, NOW(), NOW()),
(25, 7, 8, 1, NOW(), NOW()),
(25, 1, NULL, 1, NOW(), NOW()),
(26, 10, NULL, 1, NOW(), NOW()),
(26, 7, 8, 1, NOW(), NOW()),
(27, 10, NULL, 1, NOW(), NOW()),
(27, 7, 8, 1, NOW(), NOW()),
(28, 10, NULL, 1, NOW(), NOW()),
(28, 5, 5, 1, NOW(), NOW()),
(28, 7, 8, 1, NOW(), NOW()),
(29, 7, 8, 1, NOW(), NOW()),
(29, 1, NULL, 1, NOW(), NOW()),
(29, 10, NULL, 1, NOW(), NOW()),
(30, 10, NULL, 1, NOW(), NOW()),
(30, 7, 8, 1, NOW(), NOW()),
(31, 7, 8, 1, NOW(), NOW()),
(31, 15, NULL, 1, NOW(), NOW()),
(31, 10, NULL, 1, NOW(), NOW()),
(32, 7, 8, 1, NOW(), NOW()),
(32, 10, NULL, 1, NOW(), NOW()),
(33, 7, 8, 1, NOW(), NOW()),
(33, 10, NULL, 1, NOW(), NOW()),
(33, 15, NULL, 1, NOW(), NOW()),
(33, 7, 11, 1, NOW(), NOW()),
(34, 12, NULL, 1, NOW(), NOW()),
(34, 10, NULL, 1, NOW(), NOW()),
(34, 7, 8, 1, NOW(), NOW()),
(35, 7, 8, 1, NOW(), NOW()),
(35, 10, NULL, 1, NOW(), NOW()),
(36, 10, NULL, 1, NOW(), NOW()),
(36, 7, 8, 1, NOW(), NOW()),
(37, 7, 8, 1, NOW(), NOW()),
(37, 10, NULL, 1, NOW(), NOW()),
(38, 1, NULL, 1, NOW(), NOW()),
(38, 7, 8, 1, NOW(), NOW()),
(39, 10, NULL, 1, NOW(), NOW()),
(40, 10, NULL, 1, NOW(), NOW()),
(40, 7, 8, 1, NOW(), NOW()),
(41, 10, NULL, 1, NOW(), NOW()),
(41, 11, NULL, 1, NOW(), NOW()),
(42, 10, NULL, 1, NOW(), NOW()),
(43, 7, 8, 1, NOW(), NOW()),
(43, 10, NULL, 1, NOW(), NOW()),
(44, 10, 18, 1, NOW(), NOW()),
(44, 10, 16, 1, NOW(), NOW()),
(44, 10, 17, 1, NOW(), NOW()),
(44, 10, 15, 1, NOW(), NOW()),
(44, 7, 8, 1, NOW(), NOW()),
(45, 7, 8, 1, NOW(), NOW()),
(45, 10, 13, 1, NOW(), NOW()),
(45, 10, 14, 1, NOW(), NOW()),
(46, 10, NULL, 1, NOW(), NOW()),
(46, 7, 8, 1, NOW(), NOW()),
(47, 10, NULL, 1, NOW(), NOW()),
(47, 7, 8, 1, NOW(), NOW()),
(48, 10, NULL, 1, NOW(), NOW()),
(48, 7, 8, 1, NOW(), NOW()),
(49, 10, NULL, 1, NOW(), NOW()),
(49, 7, 12, 1, NOW(), NOW()),
(50, 10, 19, 1, NOW(), NOW()),
(50, 10, 20, 1, NOW(), NOW()),
(50, 10, 21, 1, NOW(), NOW()),
(50, 10, 22, 1, NOW(), NOW()),
(51, 10, NULL, 1, NOW(), NOW()),
(51, 7, 8, 1, NOW(), NOW()),
(52, 10, NULL, 1, NOW(), NOW()),
(52, 7, 8, 1, NOW(), NOW()),
(53, 10, NULL, 1, NOW(), NOW()),
(53, 7, 12, 1, NOW(), NOW()),
(54, 10, NULL, 1, NOW(), NOW()),
(54, 7, 8, 1, NOW(), NOW()),
(54, 7, 11, 1, NOW(), NOW()),
(55, 10, NULL, 1, NOW(), NOW()),
(55, 7, 8, 1, NOW(), NOW()),
(56, 10, NULL, 1, NOW(), NOW()),
(56, 7, 8, 1, NOW(), NOW()),
(57, 10, NULL, 1, NOW(), NOW()),
(57, 7, 8, 1, NOW(), NOW()),
(58, 10, NULL, 1, NOW(), NOW()),
(58, 7, 8, 1, NOW(), NOW()),
(59, 7, 8, 1, NOW(), NOW()),
(59, 5, 6, 1, NOW(), NOW()),
(59, 5, 7, 1, NOW(), NOW()),
(59, 9, NULL, 1, NOW(), NOW()),
(60, 5, 5, 1, NOW(), NOW()),
(60, 7, 8, 1, NOW(), NOW()),
(61, 5, 5, 1, NOW(), NOW()),
(61, 7, 8, 1, NOW(), NOW()),
(62, 15, NULL, 1, NOW(), NOW()),
(62, 7, 8, 1, NOW(), NOW()),
(63, 5, 5, 1, NOW(), NOW()),
(63, 7, 8, 1, NOW(), NOW()),
(64, 1, NULL, 1, NOW(), NOW()),
(64, 7, 8, 1, NOW(), NOW()),
(65, 5, 5, 1, NOW(), NOW()),
(65, 7, 8, 1, NOW(), NOW()),
(66, 5, 5, 1, NOW(), NOW()),
(66, 7, 8, 1, NOW(), NOW()),
(66, 10, NULL, 1, NOW(), NOW()),
(66, 5, 6, 1, NOW(), NOW()),
(67, 5, 5, 1, NOW(), NOW()),
(68, 15, NULL, 1, NOW(), NOW()),
(68, 7, 8, 1, NOW(), NOW()),
(69, 5, 5, 1, NOW(), NOW()),
(69, 1, NULL, 1, NOW(), NOW()),
(70, 5, NULL, 1, NOW(), NOW()),
(70, 5, 6, 1, NOW(), NOW()),
(70, 7, 8, 1, NOW(), NOW()),
(71, 7, 8, 1, NOW(), NOW()),
(71, 5, NULL, 1, NOW(), NOW()),
(71, 1, NULL, 1, NOW(), NOW()),
(72, 5, 5, 1, NOW(), NOW()),
(72, 7, 8, 1, NOW(), NOW()),
(73, 7, 8, 1, NOW(), NOW()),
(73, 7, 11, 1, NOW(), NOW()),
(73, 5, 5, 1, NOW(), NOW()),
(73, 1, NULL, 1, NOW(), NOW()),
(74, 7, 9, 1, NOW(), NOW()),
(74, 15, NULL, 1, NOW(), NOW()),
(75, 5, 5, 1, NOW(), NOW()),
(75, 5, 6, 1, NOW(), NOW()),
(75, 7, 8, 1, NOW(), NOW()),
(75, 7, 12, 1, NOW(), NOW()),
(75, 7, 11, 1, NOW(), NOW()),
(76, 10, NULL, 1, NOW(), NOW()),
(76, 7, 8, 1, NOW(), NOW()),
(77, 1, NULL, 1, NOW(), NOW()),
(77, 15, NULL, 1, NOW(), NOW()),
(78, 7, 8, 1, NOW(), NOW()),
(78, 15, NULL, 1, NOW(), NOW()),
(79, 15, NULL, 1, NOW(), NOW()),
(79, 7, 8, 1, NOW(), NOW()),
(80, 10, NULL, 1, NOW(), NOW()),
(80, 7, 8, 1, NOW(), NOW()),
(81, 7, 8, 1, NOW(), NOW()),
(81, 7, 11, 1, NOW(), NOW()),
(81, 1, NULL, 1, NOW(), NOW()),
(81, 10, NULL, 1, NOW(), NOW()),
(82, 7, NULL, 1, NOW(), NOW()),
(82, 1, NULL, 1, NOW(), NOW()),
(82, 10, NULL, 1, NOW(), NOW());
-- 4. Drop unutilized tables --
DROP TABLE IF EXISTS survey_scores;
DROP TABLE IF EXISTS survey_spirt_comments;
DROP TABLE IF EXISTS survey_me_info;
DROP TABLE IF EXISTS site_test_kits;
DROP TABLE IF EXISTS sites;
DROP TABLE IF EXISTS test_kits;
DROP TABLE IF EXISTS site_types;
DROP TABLE IF EXISTS agencies;
-- 5. Create column in surveys
ALTER TABLE surveys ADD COLUMN facility_sdp_id INT(4) UNSIGNED AFTER facility_id;
ALTER TABLE surveys ADD CONSTRAINT fk_facility_sdp_id FOREIGN KEY (facility_sdp_id) REFERENCES facility_sdps(id);
--	Proceed to add survey_sdp_id column to surveys table --
ALTER TABLE surveys ADD COLUMN survey_sdp_id INT(4) UNSIGNED AFTER facility_sdp_id;
-- Now, replace survey_sdp_id in htc_survey_pages and survey_questions with survey_id --
ALTER TABLE htc_survey_pages ADD COLUMN survey_id INT(4) UNSIGNED AFTER survey_sdp_id;
ALTER TABLE survey_questions ADD COLUMN survey_id INT(4) UNSIGNED AFTER survey_sdp_id;
-- 6. Clean the data
UPDATE survey_sdps SET comment='IPD 1' WHERE comment='ipd1' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='IPD 2' WHERE comment='ipd2' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='IPD 5' WHERE comment='ipd5' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='OPD 1' WHERE comment='opd1' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='OPD 2' WHERE comment='opd2' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='OPD 3' WHERE comment='opd3' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='VCT 1' WHERE comment='vct1' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='VCT 2' WHERE comment='vct2' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='VCT 1' WHERE comment='VCT-1' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='VCT 2' WHERE comment='VCT-2' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='MATERNITY' WHERE comment='Maternity[labour and delivery]' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='MATERNITY' WHERE comment='Maternity[ANC]' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='LABOUR&DELIVERY' WHERE comment LIKE '%labour delivery%' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='KAMITI MEDIUM' WHERE comment='VCT Kamiti Medium' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='JUVENILE' WHERE comment='YCTC - juvenile' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='KAMITI MAXIMUM' WHERE comment='VCT Kamiti maximum' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='JUVENILE' WHERE comment='VCT YCTC/Juvenile.' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='MAIN HOSPITAL' WHERE comment='Kamiti main Hospital' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='JUVENILE' WHERE comment='Kamiti-Young Correction Trainig Centre' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='MAIN HOSPITAL' WHERE comment='Main' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ROOM 16' WHERE comment='ROOM 16' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ROOM 5' WHERE comment='ROOM 5' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ROOM 11' WHERE comment='ROOM 11' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ROOM 10' WHERE comment='ROOM 10' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='IPD 5' WHERE comment='Ward 5- paediatric' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ANC' WHERE comment='ANC/PMCTC' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ANC/FP' WHERE comment='ANC/FP/CWC' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ANC' WHERE comment='ANC/PMTCT' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ANC' WHERE comment='ANC register maternity' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='LABOUR&DELIVERY' WHERE comment LIKE '%Labour and delivery%' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='LABOUR&DELIVERY' WHERE comment LIKE '%LABOR AND DELIVERY%' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='LABOUR&DELIVERY' WHERE comment LIKE '%Labour & delivery%' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ROOM 16' WHERE comment='ROOM16' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ROOM 5' WHERE comment='ROOM5' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ROOM 11' WHERE comment='ROOM11' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ROOM 10' WHERE comment='ROOM10' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='ANC/FP' WHERE comment='anc fp' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='MATERNITY' WHERE comment='Maternity' AND deleted_at IS NULL;
UPDATE survey_sdps SET comment='MATERNITY' WHERE comment='materrnity' AND deleted_at IS NULL;
-- 5,7,8,10
UPDATE survey_sdps SET sdp_id=15 WHERE id=14;
UPDATE survey_sdps SET comment='MNCH' WHERE id=40;
UPDATE survey_sdps SET comment='ANC/FP' WHERE id=53;
UPDATE survey_sdps SET comment=NULL WHERE id=81;
UPDATE survey_sdps SET comment='VCT 1' WHERE id=126;
UPDATE survey_sdps SET comment=NULL WHERE id=298;
UPDATE survey_sdps SET comment='VCT 1' WHERE id=339;
UPDATE survey_sdps SET comment='ANC' WHERE id=440;
UPDATE survey_sdps SET comment='OPD 1' WHERE id=457;
UPDATE survey_sdps SET comment='OPD 1' WHERE id=491;
UPDATE survey_sdps SET comment=NULL WHERE id=540;
UPDATE survey_sdps SET comment='MNCH' WHERE id=588;
UPDATE survey_sdps SET comment='VCT 2' WHERE id=595;
UPDATE survey_sdps SET comment='VCT 1' WHERE id=596;
UPDATE survey_sdps SET sdp_id=5, comment='OPD 1' WHERE id=637;
UPDATE survey_sdps SET comment=NULL WHERE id=673;
UPDATE survey_sdps SET comment='MNCH' WHERE id=694;
UPDATE survey_sdps SET comment='VCT 2' WHERE id=738;
UPDATE survey_sdps SET comment='VCT 1' WHERE id=745;
UPDATE survey_sdps SET comment=NULL WHERE id=830;
UPDATE survey_sdps SET comment='MNCH' WHERE id=833;
UPDATE survey_sdps SET comment=NULL WHERE id=915;
UPDATE survey_sdps SET comment=NULL WHERE id=918;
UPDATE survey_sdps SET comment='ANC' WHERE id=951;
UPDATE survey_sdps SET comment='MNCH' WHERE id=955;
UPDATE survey_sdps SET comment='OPD 1' WHERE id=1082;
UPDATE survey_sdps SET comment='VCT 1' WHERE id=4230;
UPDATE survey_sdps SET comment='VCT 2' WHERE id=4270;
UPDATE survey_sdps SET comment='VCT 2' WHERE id=4271;
UPDATE survey_sdps SET comment='VCT 1' WHERE id=4358;
UPDATE survey_sdps SET comment='VCT 1' WHERE id=4417;
UPDATE survey_sdps SET comment=NULL WHERE id=4502;

--	Update survey_questions table --
UPDATE survey_questions SET survey_id = (SELECT id FROM surveys WHERE survey_questions.survey_sdp_id=surveys.survey_sdp_id);
UPDATE htc_survey_pages SET survey_id = (SELECT id FROM surveys WHERE htc_survey_pages.survey_sdp_id=surveys.survey_sdp_id);
UPDATE surveys SET deleted_at=NOW() WHERE facility_sdp_id IS NULL;
alter table htc_survey_pages drop foreign key htc_survey_pages_survey_sdp_id_foreign;
alter table htc_survey_pages drop column survey_sdp_id;

ALTER TABLE `htc_survey_pages`
  ADD CONSTRAINT `htc_survey_pages_survey_id_foreign` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`);
  

alter table survey_questions drop foreign key survey_questions_survey_sdp_id_foreign;
alter table survey_questions drop index survey_questions_survey_sdp_id_question_id_unique;
alter table survey_questions drop column survey_sdp_id;

ALTER TABLE `survey_questions`
  ADD CONSTRAINT `survey_questions_survey_id_foreign` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`);

alter table surveys drop foreign key surveys_facility_id_foreign;
alter table surveys drop column facility_id;

TRUNCATE TABLE revisions;

DROP TABLE survey_spirt_info;
DROP TABLE survey_sdps;
ALTER TABLE surveys DROP COLUMN survey_sdp_id;

-- UPDATE survey_sdps SET deleted_at=NOW() WHERE id=462;
-- UPDATE survey_sdps SET deleted_at=NOW() WHERE id=958;
-- UPDATE survey_sdps SET deleted_at=NOW() WHERE id=957;
-- UPDATE survey_sdps SET deleted_at=NOW() WHERE id=961;