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

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'EnvironmentalIssue'],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVELegislation'],
            ['vector_id' => $vector->id, 'event_type' => 'SecondaryIssuance'],
            ['vector_id' => $vector->id, 'event_type' => 'SourceLocation'],
            ['vector_id' => $vector->id, 'event_type' => 'Trial'],
        ]);

        $vector = \DB::table('vectors')->where('name', 'Influencers')->first();

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'CrimeAndViolence'],
            ['vector_id' => $vector->id, 'event_type' => 'CriminalCourtProceeding'],
            ['vector_id' => $vector->id, 'event_type' => 'DiplomaticRelations'],
            ['vector_id' => $vector->id, 'event_type' => 'Extinction'],
            ['vector_id' => $vector->id, 'event_type' => 'FamilyRelation'],
            ['vector_id' => $vector->id, 'event_type' => 'Hijacking'],
            ['vector_id' => $vector->id, 'event_type' => 'HostageRelease'],
            ['vector_id' => $vector->id, 'event_type' => 'HostageTakingKidnapping'],
            ['vector_id' => $vector->id, 'event_type' => 'Indictment'],
            ['vector_id' => $vector->id, 'event_type' => 'ManMadeDisaster'],
            ['vector_id' => $vector->id, 'event_type' => 'NaturalDisaster'],
            ['vector_id' => $vector->id, 'event_type' => 'PoliceOperation'],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEIedExplosion'],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEPersonCareer'],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEPersonCommunication'],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEProtest'],
            ['vector_id' => $vector->id, 'event_type' => 'TerrorCommunication'],
            ['vector_id' => $vector->id, 'event_type' => 'TerrorFinancing'],
            ['vector_id' => $vector->id, 'event_type' => 'TrafficAccident'],
            ['vector_id' => $vector->id, 'event_type' => 'Trafficking'],
            ['vector_id' => $vector->id, 'event_type' => 'Vandalism'],
            ['vector_id' => $vector->id, 'event_type' => 'Visit'],
            ['vector_id' => $vector->id, 'event_type' => 'VotingResult'],
        ]);

        $vector = \DB::table('vectors')->where('name', 'Social Intelligence')->first();

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'ArmedAssault'],
            ['vector_id' => $vector->id, 'event_type' => 'ArmedAttack'],
            ['vector_id' => $vector->id, 'event_type' => 'Arrest'],
            ['vector_id' => $vector->id, 'event_type' => 'Arson'],
            ['vector_id' => $vector->id, 'event_type' => 'Conviction'],
            ['vector_id' => $vector->id, 'event_type' => 'LocatedEvent'],
            ['vector_id' => $vector->id, 'event_type' => 'LocationEvent'],
            ['vector_id' => $vector->id, 'event_type' => 'MaliciousMention'],
            ['vector_id' => $vector->id, 'event_type' => 'MovieRelease'],
            ['vector_id' => $vector->id, 'event_type' => 'MusicAlbumRelease'],
            ['vector_id' => $vector->id, 'event_type' => 'PersonAttributes'],
            ['vector_id' => $vector->id, 'event_type' => 'PersonCareer'],
            ['vector_id' => $vector->id, 'event_type' => 'PersonCommunication'],
            ['vector_id' => $vector->id, 'event_type' => 'PersonEducation'],
            ['vector_id' => $vector->id, 'event_type' => 'PersonEmailAddress'],
            ['vector_id' => $vector->id, 'event_type' => 'PersonLocation'],
            ['vector_id' => $vector->id, 'event_type' => 'PersonMeeting'],
            ['vector_id' => $vector->id, 'event_type' => 'PersonParty'],
            ['vector_id' => $vector->id, 'event_type' => 'PersonRelation'],
            ['vector_id' => $vector->id, 'event_type' => 'PersonThreat'],
            ['vector_id' => $vector->id, 'event_type' => 'PersonTravel'],
            ['vector_id' => $vector->id, 'event_type' => 'PollResult'],
            ['vector_id' => $vector->id, 'event_type' => 'PressRelease'],
            ['vector_id' => $vector->id, 'event_type' => 'ProductIssues'],
            ['vector_id' => $vector->id, 'event_type' => 'ProductRecall'],
            ['vector_id' => $vector->id, 'event_type' => 'ProductRelease'],
            ['vector_id' => $vector->id, 'event_type' => 'Quotation'],
            ['vector_id' => $vector->id, 'event_type' => 'QuotationText'],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEArmedAssault'],
            ['vector_id' => $vector->id, 'event_type' => 'Robbery'],
            ['vector_id' => $vector->id, 'event_type' => 'Speech'],
        ]);

        $vector = \DB::table('vectors')->where('name', 'Cybersecurity')->first();

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'CredentialLeak'],
            ['vector_id' => $vector->id, 'event_type' => 'CreditCardLeak'],
            ['vector_id' => $vector->id, 'event_type' => 'CreditRating'],
            ['vector_id' => $vector->id, 'event_type' => 'CyberAttack'],
            ['vector_id' => $vector->id, 'event_type' => 'CyberExploit'],
            ['vector_id' => $vector->id, 'event_type' => 'Cyberterrorism'],
            ['vector_id' => $vector->id, 'event_type' => 'MalwareAnalysis'],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEMalwareThreat'],
            ['vector_id' => $vector->id, 'event_type' => 'ServiceDisruption'],
        ]);

        $vector = \DB::table('vectors')->where('name', 'Operational Risks')->first();

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'Acquisition'],
            ['vector_id' => $vector->id, 'event_type' => 'Alliance'],
            ['vector_id' => $vector->id, 'event_type' => 'AnalystEarningsEstimate'],
            ['vector_id' => $vector->id, 'event_type' => 'AnalystRecommendation'],
            ['vector_id' => $vector->id, 'event_type' => 'Announcement'],
            ['vector_id' => $vector->id, 'event_type' => 'Bankruptcy'],
            ['vector_id' => $vector->id, 'event_type' => 'BeneficialOwnershipFiling'],
            ['vector_id' => $vector->id, 'event_type' => 'BonusSharesIssuance'],
            ['vector_id' => $vector->id, 'event_type' => 'BusinessRelation'],
            ['vector_id' => $vector->id, 'event_type' => 'BusinessTransaction'],
            ['vector_id' => $vector->id, 'event_type' => 'BusinessTransactionText'],
            ['vector_id' => $vector->id, 'event_type' => 'Buybacks'],
            ['vector_id' => $vector->id, 'event_type' => 'CivilCourtProceeding'],
            ['vector_id' => $vector->id, 'event_type' => 'ClinicalTrial'],
            ['vector_id' => $vector->id, 'event_type' => 'CoEntityText'],
            ['vector_id' => $vector->id, 'event_type' => 'CoOccurrence'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyAccountingChange'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyAffiliates'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyCompetitor'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyCustomer'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyEarningsAnnouncement'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyEarningsGuidance'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyEmployeesNumber'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyExpansion'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyForceMajeure'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyFounded'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyInvestment'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyLaborIssues'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyLayoffs'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyLegalIssues'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyListingChange'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyLocation'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyMeeting'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyNameChange'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyProduct'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyReorganization'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyRestatement'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyTechnology'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyTicker'],
            ['vector_id' => $vector->id, 'event_type' => 'CompanyUsingProduct'],
            ['vector_id' => $vector->id, 'event_type' => 'ConferenceCall'],
            ['vector_id' => $vector->id, 'event_type' => 'DatedEvent'],
            ['vector_id' => $vector->id, 'event_type' => 'DebtFinancing'],
            ['vector_id' => $vector->id, 'event_type' => 'DelayedFiling'],
            ['vector_id' => $vector->id, 'event_type' => 'Dividend'],
            ['vector_id' => $vector->id, 'event_type' => 'EmploymentChange'],
            ['vector_id' => $vector->id, 'event_type' => 'EmploymentRelation'],
            ['vector_id' => $vector->id, 'event_type' => 'EntityOccurrence'],
            ['vector_id' => $vector->id, 'event_type' => 'EquityFinancing'],
            ['vector_id' => $vector->id, 'event_type' => 'Event'],
            ['vector_id' => $vector->id, 'event_type' => 'ExtendedPatentFiling'],
            ['vector_id' => $vector->id, 'event_type' => 'FDAPhase'],
            ['vector_id' => $vector->id, 'event_type' => 'FinancialFiling'],
            ['vector_id' => $vector->id, 'event_type' => 'IPO'],
            ['vector_id' => $vector->id, 'event_type' => 'IndicesChanges'],
            ['vector_id' => $vector->id, 'event_type' => 'InsiderTransaction'],
            ['vector_id' => $vector->id, 'event_type' => 'JointVenture'],
            ['vector_id' => $vector->id, 'event_type' => 'Merger'],
            ['vector_id' => $vector->id, 'event_type' => 'MnA'],
            ['vector_id' => $vector->id, 'event_type' => 'PatentFiling'],
            ['vector_id' => $vector->id, 'event_type' => 'PatentIssuance'],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEAcquisition'],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEMerger'],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEOrganizationRelationship'],
            ['vector_id' => $vector->id, 'event_type' => 'StandardEvent'],
            ['vector_id' => $vector->id, 'event_type' => 'StatusEvent'],
            ['vector_id' => $vector->id, 'event_type' => 'StockSplit'],
        ]);

        $vector = \DB::table('vectors')->where('name', 'Geopolitics')->first();

        \DB::table('vector_event_types')->insert([
            ['vector_id' => $vector->id, 'event_type' => 'ArmsPurchaseSale'],
            ['vector_id' => $vector->id, 'event_type' => 'BiologicalTerrorism'],
            ['vector_id' => $vector->id, 'event_type' => 'Bombing'],
            ['vector_id' => $vector->id, 'event_type' => 'CandidatePosition'],
            ['vector_id' => $vector->id, 'event_type' => 'Ceasefire'],
            ['vector_id' => $vector->id, 'event_type' => 'ChemicalTerrorism'],
            ['vector_id' => $vector->id, 'event_type' => 'Coup'],
            ['vector_id' => $vector->id, 'event_type' => 'DiseaseOutbreak'],
            ['vector_id' => $vector->id, 'event_type' => 'EconomicEvent'],
            ['vector_id' => $vector->id, 'event_type' => 'Election'],
            ['vector_id' => $vector->id, 'event_type' => 'GeoPolitical'],
            ['vector_id' => $vector->id, 'event_type' => 'MilitaryAction'],
            ['vector_id' => $vector->id, 'event_type' => 'MilitaryManeuver'],
            ['vector_id' => $vector->id, 'event_type' => 'MilitaryOperation'],
            ['vector_id' => $vector->id, 'event_type' => 'MiscTerrorism'],
            ['vector_id' => $vector->id, 'event_type' => 'NuclearMaterialTransaction'],
            ['vector_id' => $vector->id, 'event_type' => 'NuclearTerrorism'],
            ['vector_id' => $vector->id, 'event_type' => 'PoliticalEndorsement'],
            ['vector_id' => $vector->id, 'event_type' => 'PoliticalEvent'],
            ['vector_id' => $vector->id, 'event_type' => 'PoliticalRelationship'],
            ['vector_id' => $vector->id, 'event_type' => 'RFEVEPoliticalRelationship'],
            ['vector_id' => $vector->id, 'event_type' => 'RadiologicalMaterialTransaction'],
        ]);
    }
}
