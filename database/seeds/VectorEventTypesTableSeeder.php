<?php

use App\Entities\Vector;
use Illuminate\Database\Seeder;

class VectorEventTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vector = \DB::table('vectors')->where('name', 'Social Responsibility')->first();
        $dateTime = date('Y-m-d H:i:s');

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'EnvironmentalIssue', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVELegislation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'SecondaryIssuance', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'SourceLocation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Trial', 'created_at' => $dateTime, 'updated_at' => $dateTime],
        ]);

        $vector = \DB::table('vectors')->where('name', 'Influencers')->first();

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'CrimeAndViolence', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CriminalCourtProceeding', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'DiplomaticRelations', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Extinction', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'FamilyRelation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Hijacking', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'HostageRelease', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'HostageTakingKidnapping', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Indictment', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'ManMadeDisaster', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'NaturalDisaster', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PoliceOperation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEIedExplosion', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEPersonCareer', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEPersonCommunication', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEProtest', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'TerrorCommunication', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'TerrorFinancing', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'TrafficAccident', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Trafficking', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Vandalism', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Visit', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'VotingResult', 'created_at' => $dateTime, 'updated_at' => $dateTime],
        ]);

        $vector = \DB::table('vectors')->where('name', 'Social Intelligence')->first();

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'ArmedAssault', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'ArmedAttack', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Arrest', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Arson', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Conviction', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'LocatedEvent', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'LocationEvent', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'MaliciousMention', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'MovieRelease', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'MusicAlbumRelease', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PersonAttributes', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PersonCareer', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PersonCommunication', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PersonEducation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PersonEmailAddress', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PersonLocation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PersonMeeting', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PersonParty', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PersonRelation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PersonThreat', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PersonTravel', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PollResult', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PressRelease', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'ProductIssues', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'ProductRecall', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'ProductRelease', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Quotation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'QuotationText', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEArmedAssault', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Robbery', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Speech', 'created_at' => $dateTime, 'updated_at' => $dateTime],
        ]);

        $vector = \DB::table('vectors')->where('name', 'Cybersecurity')->first();

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'CredentialLeak', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CreditCardLeak', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CreditRating', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CyberAttack', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CyberExploit', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Cyberterrorism', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'MalwareAnalysis', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEMalwareThreat', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'ServiceDisruption', 'created_at' => $dateTime, 'updated_at' => $dateTime],
        ]);

        $vector = \DB::table('vectors')->where('name', 'Operational Risks')->first();

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'Acquisition', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Alliance', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'AnalystEarningsEstimate', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'AnalystRecommendation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Announcement', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Bankruptcy', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'BeneficialOwnershipFiling', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'BonusSharesIssuance', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'BusinessRelation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'BusinessTransaction', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'BusinessTransactionText', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Buybacks', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CivilCourtProceeding', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'ClinicalTrial', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CoEntityText', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CoOccurrence', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyAccountingChange', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyAffiliates', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyCompetitor', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyCustomer', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyEarningsAnnouncement', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyEarningsGuidance', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyEmployeesNumber', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyExpansion', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyForceMajeure', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyFounded', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyInvestment', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyLaborIssues', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyLayoffs', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyLegalIssues', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyListingChange', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyLocation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyMeeting', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyNameChange', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyProduct', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyReorganization', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyRestatement', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyTechnology', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyTicker', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyUsingProduct', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'ConferenceCall', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'DatedEvent', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'DebtFinancing', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'DelayedFiling', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Dividend', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'EmploymentChange', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'EmploymentRelation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'EntityOccurrence', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'EquityFinancing', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Event', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'ExtendedPatentFiling', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'FDAPhase', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'FinancialFiling', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'IPO', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'IndicesChanges', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'InsiderTransaction', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'JointVenture', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Merger', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'MnA', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PatentFiling', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PatentIssuance', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEAcquisition', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEMerger', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEOrganizationRelationship', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'StandardEvent', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'StatusEvent', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'StockSplit', 'created_at' => $dateTime, 'updated_at' => $dateTime],
        ]);

        $vector = \DB::table('vectors')->where('name', 'Geopolitics')->first();

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'ArmsPurchaseSale', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'BiologicalTerrorism', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Bombing', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'CandidatePosition', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Ceasefire', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'ChemicalTerrorism', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Coup', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'DiseaseOutbreak', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'EconomicEvent', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'Election', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'GeoPolitical', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'MilitaryAction', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'MilitaryManeuver', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'MilitaryOperation', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'MiscTerrorism', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'NuclearMaterialTransaction', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'NuclearTerrorism', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PoliticalEndorsement', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PoliticalEvent', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'PoliticalRelationship', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEPoliticalRelationship', 'created_at' => $dateTime, 'updated_at' => $dateTime],
            ['vector_id' => $vector->id, 'event_type' => 'RadiologicalMaterialTransaction', 'created_at' => $dateTime, 'updated_at' => $dateTime],
        ]);
    }
}
