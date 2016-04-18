<?php

namespace App\Transformers\Instance;

use App\Entities\Instance;
use League\Fractal\TransformerAbstract;

class InstanceTransformer extends TransformerAbstract
{
    /**
     * @apiDefine SingleInstances
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "id" => "123",
     *          "title" => "Some Title",
     *          "company" => "Company name",
     *          "vector" => "Vector", // Can be ''
     *          "type" => "Instance Type",
     *          "date" => "2016-04-12 12:23:23",
     *          "language" => "eng",
     *          "source" => "Some Source",
     *          "fragment" => "A string about the instance",
     *          "link" => "A URL to the instance",
     *          "regions" => "North America, Africa",
     *          "positive_risk_score" => "10",
     *          "negative_risk_score" => "0",
     *          "risk_score" => "10",
     *          "flagged" => false
     *      },
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    /**
     * @apiDefine MultipleInstances
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data": {[
     *          {
     *              "id" => "123",
     *              "title" => "Some Title",
     *              "company" => "Company name",
     *              "vector" => "Vector", // Can be ''
     *              "type" => "Instance Type",
     *              "date" => "2016-04-12 12:23:23",
     *              "language" => "eng",
     *              "source" => "Some Source",
     *              "fragment" => "A string about the instance",
     *              "link" => "A URL to the instance",
     *              "regions" => "North America, Africa",
     *              "positive_risk_score" => "10",
     *              "negative_risk_score" => "0",
     *              "risk_score" => "10",
     *              "flagged" => false
     *          },
     *          {
     *              "id" => "456",
     *              "title" => "Some Title",
     *              "company" => "Company name",
     *              "vector" => "Vector", // Can be ''
     *              "type" => "Instance Type",
     *              "date" => "2016-04-12 12:23:23",
     *              "language" => "eng",
     *              "source" => "Some Source",
     *              "fragment" => "A string about the instance",
     *              "link" => "A URL to the instance",
     *              "regions" => "Europe, South America",
     *              "positive_risk_score" => "20",
     *              "negative_risk_score" => "50",
     *              "risk_score" => "-30",
     *              "flagged" => true
     *          }
     *      ]},
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    /**
     * @param Instance $instance
     * @return array
     */
    public function transform(Instance $instance)
    {
        return [
            'id' => $instance->id,
            'title' => $instance->title,
            'company' => $instance->company->name,
            'vector' => $instance->vector->name ?? null,
            'type' => $instance->type,
            'date' => $instance->start->format('Y-m-d H:i:s'),
            'language' => $instance->language,
            'source' => $instance->source,
            'fragment' => $instance->fragment,
            'link' => $instance->link,
            'regions' => implode(', ', $instance->getRegions()),
            'positive_risk_score' => $instance->positive_risk_score,
            'negative_risk_score' => $instance->negative_risk_score,
            'risk_score' => $instance->risk_score,
            'flagged' => (bool)$instance->flagged
        ];
    }
}
