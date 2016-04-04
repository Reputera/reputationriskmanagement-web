<?php

namespace App\Console\Commands;

use App\Entities\Company;
use Illuminate\Database\Eloquent\Collection;

trait CompaniesFromInput
{
    /**
     * Gets the company from input, or all companies if no input is given.
     *
     * @return Collection|null
     */
    protected function companiesFromInput()
    {
        /** @var $companies Collection */
        if ($companyName = $this->option('company')) {
            $companies = app(Company::class)->whereName($companyName)->get();
        } else {
            $companies = app(Company::class)->all();
        }

        return $companies->isEmpty() ? null : $companies;
    }

    /**
     * Used to notify the command there are no companies.
     *
     * @return void
     */
    protected function reportNoCompanies()
    {
        $this->info('No company/companies found.');
    }
}
