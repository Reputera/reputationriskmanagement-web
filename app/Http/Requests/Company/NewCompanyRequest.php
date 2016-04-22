<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\ApiRequest;

class NewCompanyRequest extends ApiRequest
{
    const MIN_COMPANIES_TO_CREATE = 1;

    const MAX_COMPANIES_TO_CREATE = 10;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'companies.*.name' => 'bail|required|distinct|unique:companies,name|max:255',
            'companies.*.entity_id' => 'bail|required|distinct|unique:companies,entity_id|max:15',
            'companies.*.industry_id' => 'bail|required|exists:industries,id',
        ];
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                'companies.*.name.required' => 'The company name is required.',
                'companies.*.name.distinct' => 'The company is already in the list to be created.',
                'companies.*.name.unique' => 'The company name already exists in the system.',
                'companies.*.name.max' => 'The company name can not be longer than 255 characters.',
                'companies.*.entity_id.required' => 'A Recorded Future entity ID is required.',
                'companies.*.entity_id.distinct' => 'A company with this Recorded Future entity ID is already in the list to be created.',
                'companies.*.entity_id.max' => 'The Recorded Future entity ID can not be longer than 15 characters.',
                'companies.*.entity_id.unique' => 'The Recorded Future entity ID is already in the system.',
                'companies.*.industry_id.required' => 'An industry for a company is required.',
                'companies.*.industry_id.exists' => 'The industry must exist in the system.',
            ]
        );
    }

    public function validate()
    {
        $this->validateCompaniesHaveBeenGiven();
        $this->validateNumberOfCompanies($this->get('companies'));

        // Default rules check
        // This is also done later because we don't want to get a lot of error if there are no companies
        // given, as well as don't want to check the DB if there are a more than 10 companies to check
        // since each company name and company entity ID are checked against the DB.
        $instance = $this->getValidatorInstance();
        if (!$instance->passes()) {
            $this->failedValidation($instance);
        }
    }

    protected function validateCompaniesHaveBeenGiven()
    {
        /** @var \Illuminate\Validation\Validator $companiesValidator */
        $companiesValidator = $this->container
            ->make('Illuminate\Validation\Factory')
            ->make(
                $this->all(),
                [
                    'companies' => 'bail|required|array',
                ],
                [
                    'companies.required' => 'Please enter all the company(s) data.',
                    'companies.array' => 'Please enter all the company(s) data.',
                ]
            );

        if ($companiesValidator->fails()) {
            $this->failedValidation($companiesValidator);
        }
    }

    protected function validateNumberOfCompanies(array $data)
    {
        /** @var \Illuminate\Validation\Validator $numberOfCompaniesValidator */
        $numberOfCompaniesValidator = $this->container
            ->make('Illuminate\Validation\Factory')
            ->make(
                ['company_count' => count($data)],
                [
                    'company_count' => 'numeric|between:'.self::MIN_COMPANIES_TO_CREATE.','.self::MAX_COMPANIES_TO_CREATE
                ],
                [
                    'company_count.between' => 'Between '.self::MIN_COMPANIES_TO_CREATE.' and '.self::MAX_COMPANIES_TO_CREATE.' companies can be created at once.',
                ]
            );

        if ($numberOfCompaniesValidator->fails()) {
            $this->failedValidation($numberOfCompaniesValidator);
        }
    }
}
