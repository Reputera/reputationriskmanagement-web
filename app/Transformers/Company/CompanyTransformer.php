<?php

namespace App\Transformers\Company;

use App\Entities\Company;
use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract
{
    /**
     * @param Company $company
     * @return array
     */
    public function transform(Company $company)
    {
        return [
            'id' => $company->id,
            'name' => $company->name,
            'entity_id' => $company->entity_id,
        ];
    }
}
