<?php

namespace App\Entities;

use App\Services\Traits\Enumerable;

class VectorEventType
{
    use Enumerable;

    const COMPLIANCE = [
        'EnvironmentalIssue',
        'RFEVELegislation',
        'SecondaryIssuance',
        'SourceLocation',
        'Trial'
    ];

    const INFLUENCERS = [
        'CrimeAndViolence',
        'CriminalCourtProceeding',
        'DiplomaticRelations',
        'Extinction',
        'FamilyRelation',
        'Hijacking',
        'HostageRelease',
        'HostageTakingKidnapping',
        'Indictment',
        'ManMadeDisaster',
        'NaturalDisaster',
        'PoliceOperation',
        'RFEVEIedExplosion',
        'RFEVEPersonCareer',
        'RFEVEPersonCommunication',
        'RFEVEProtest',
        'TerrorCommunication',
        'TerrorFinancing',
        'TrafficAccident',
        'Trafficking',
        'Vandalism',
        'Visit',
        'VotingResult',
    ];

    const INFORMATAION = [
        'CredentialLeak',
        'CreditCardLeak',
        'CreditRating',
        'CyberAttack',
        'CyberExploit',
        'Cyberterrorism',
        'MalwareAnalysis',
        'RFEVEMalwareThreat',
        'ServiceDisruption',
    ];

    const MEDIA = [
        'ArmedAssault',
        'ArmedAttack',
        'Arrest',
        'Arson',
        'Conviction',
        'LocatedEvent',
        'LocationEvent',
        'MaliciousMention',
        'MovieRelease',
        'MusicAlbumRelease',
        'PersonAttributes',
        'PersonCareer',
        'PersonCommunication',
        'PersonEducation',
        'PersonEmailAddress',
        'PersonLocation',
        'PersonMeeting',
        'PersonParty',
        'PersonRelation',
        'PersonThreat',
        'PersonTravel',
        'PollResult',
        'PressRelease',
        'ProductIssues',
        'ProductRecall',
        'ProductRelease',
        'Quotation',
        'QuotationText',
        'RFEVEArmedAssault',
        'Robbery',
        'Speech',
    ];

    const OPERATIONS = [
        'Acquisition',
        'Alliance',
        'AnalystEarningsEstimate',
        'AnalystRecommendation',
        'Announcement',
        'Bankruptcy',
        'BeneficialOwnershipFiling',
        'BonusSharesIssuance',
        'BusinessRelation',
        'BusinessTransaction',
        'BusinessTransactionText',
        'Buybacks',
        'CivilCourtProceeding',
        'ClinicalTrial',
        'CoEntityText',
        'CoOccurrence',
        'CompanyAccountingChange',
        'CompanyAffiliates',
        'CompanyCompetitor',
        'CompanyCustomer',
        'CompanyEarningsAnnouncement',
        'CompanyEarningsGuidance',
        'CompanyEmployeesNumber',
        'CompanyExpansion',
        'CompanyForceMajeure',
        'CompanyFounded',
        'CompanyInvestment',
        'CompanyLaborIssues',
        'CompanyLayoffs',
        'CompanyLegalIssues',
        'CompanyListingChange',
        'CompanyLocation',
        'CompanyMeeting',
        'CompanyNameChange',
        'CompanyProduct',
        'CompanyReorganization',
        'CompanyRestatement',
        'CompanyTechnology',
        'CompanyTicker',
        'CompanyUsingProduct',
        'ConferenceCall',
        'DatedEvent',
        'DebtFinancing',
        'DelayedFiling',
        'Dividend',
        'EmploymentChange',
        'EmploymentRelation',
        'EntityOccurrence',
        'EquityFinancing',
        'Event',
        'ExtendedPatentFiling',
        'FDAPhase',
        'FinancialFiling',
        'IPO',
        'IndicesChanges',
        'InsiderTransaction',
        'JointVenture',
        'Merger',
        'MnA',
        'PatentFiling',
        'PatentIssuance',
        'RFEVEAcquisition',
        'RFEVEMerger',
        'RFEVEOrganizationRelationship',
        'StandardEvent',
        'StatusEvent',
        'StockSplit',
    ];

    const POLITICAL = [
        'ArmsPurchaseSale',
        'BiologicalTerrorism',
        'Bombing',
        'CandidatePosition',
        'Ceasefire',
        'ChemicalTerrorism',
        'Coup',
        'DiseaseOutbreak',
        'EconomicEvent',
        'Election',
        'GeoPolitical',
        'MilitaryAction',
        'MilitaryManeuver',
        'MilitaryOperation',
        'MiscTerrorism',
        'NuclearMaterialTransaction',
        'NuclearTerrorism',
        'PoliticalEndorsement',
        'PoliticalEvent',
        'PoliticalRelationship',
        'RFEVEPoliticalRelationship',
        'RadiologicalMaterialTransaction',
    ];

    /**
     * Retrieves the Vector name from a given event type.
     *
     * @param string $eventType
     * @return string|null
     */
    public static function getVectorNameFromEventType(string $eventType)
    {
        $eventType = strtolower($eventType);
        foreach (self::all() as $vectorName => $vectorEventTypes) {
            $vectorEventTypes = array_map(function ($value) {
                return  strtolower($value);
            }, $vectorEventTypes);

            if (in_array($eventType, $vectorEventTypes)) {
                return ucfirst(strtolower($vectorName));
            }
        };

        return null;
    }
}
