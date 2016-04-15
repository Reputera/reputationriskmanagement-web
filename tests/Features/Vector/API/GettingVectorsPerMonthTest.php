<?php

namespace Tests\Features\Vector\API;

use App\Entities\Instance;
use App\Entities\Vector;

class GettingVectorsPerMonthTest extends \TestCase
{
    public function testTwoMonthsOfRiskScoresAreBrokenIntoVectors()
    {
        $vectorsCollection = factory(Vector::class)->times(4)->create();
        $vectors = $vectorsCollection->toArray();
        $user = $this->beLoggedInAsUser();

        $datesToProcess[] = $this->setupVectorForFirstDate($user, $vectors);
        $datesToProcess[] = $this->setupVectorForSecondDate($user, $vectors);

        $this->apiCall('get', 'vector-risk-scores-by-month', ['dates' => $datesToProcess]);

        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertJsonResponseHasDataKeys($datesToProcess);

        $data = $this->response->getData(true)['data'];
        $this->assertCount(2, $data);

        // Make sure both dates have all the vectors.
        $this->assertEquals($vectorsCollection->pluck('name')->toArray(), array_keys($data[$datesToProcess[0]]));
        $this->assertEquals($vectorsCollection->pluck('name')->toArray(), array_keys($data[$datesToProcess[1]]));

        foreach ($vectors as $vector) {
            foreach ($datesToProcess as $date) {
                // Loops through each vector for each date and verifies the values set in the setup functions.
                // Also note, the "expectedOutput" is a "on-the-fly" field that is added in the setup functions.
                $this->assertEquals($vector[$date]['expectedOutput'], $data[$date][$vector['name']]);
            }
        }
    }

    protected function setupVectorForFirstDate($user, &$vectors)
    {
        $date = '2016-3'; // Done on purpose to make sure the no leading 0 works.
        $faker = \Faker\Factory::create();

        factory(Instance::class)->create([
            'company_id' => $user->company->id,
            'risk_score' => '20',
            'vector_id' => $vectors[0]['id'],
            'start' => $date.'-'.$faker->dateTime()->format('d H:i:s')
        ]);

        factory(Instance::class)->create([
            'company_id' => $user->company->id,
            'risk_score' => '-10',
            'vector_id' => $vectors[0]['id'],
            'start' => $date.'-'.$faker->dateTime()->format('d H:i:s')
        ]);
        // Score should be 20 + -10 = 10 / 2 = 5
        $vectors[0][$date]['expectedOutput'] = 5;

        // No entries = 0
        $vectors[1][$date]['expectedOutput'] = 0;

        factory(Instance::class)->create([
            'company_id' => $user->company->id,
            'risk_score' => '15',
            'vector_id' => $vectors[2]['id'],
            'start' => $date.'-'.$faker->dateTime()->format('d H:i:s')
        ]);

        factory(Instance::class)->create([
            'company_id' => $user->company->id,
            'risk_score' => '-10',
            'vector_id' => $vectors[2]['id'],
            'start' => $date.'-'.$faker->dateTime()->format('d H:i:s')
        ]);
        // Score should be 15 + -10 = 5 / 2 = 3 (round up)
        $vectors[2][$date]['expectedOutput'] = 3;

        // No entries = 0
        $vectors[3][$date]['expectedOutput'] = 0;

        return $date;
    }

    protected function setupVectorForSecondDate($user, &$vectors)
    {
        $date = '2016-07';
        $faker = \Faker\Factory::create();

        // No entries = 0
        $vectors[0][$date]['expectedOutput'] = 0;

        factory(Instance::class)->create([
            'company_id' => $user->company->id,
            'risk_score' => '75',
            'vector_id' => $vectors[1]['id'],
            'start' => $date.'-'.$faker->dateTime()->format('d H:i:s')
        ]);

        factory(Instance::class)->create([
            'company_id' => $user->company->id,
            'risk_score' => '-11',
            'vector_id' => $vectors[1]['id'],
            'start' => $date.'-'.$faker->dateTime()->format('d H:i:s')
        ]);
        // Score should be 75 + -11 = 64 / 2 = 32
        $vectors[1][$date]['expectedOutput'] = 32;

        factory(Instance::class)->create([
            'company_id' => $user->company->id,
            'risk_score' => '45',
            'vector_id' => $vectors[2]['id'],
            'start' => $date.'-'.$faker->dateTime()->format('d H:i:s')
        ]);

        // Score should be 45 / 1 = 45
        $vectors[2][$date]['expectedOutput'] = 45;

        factory(Instance::class)->create([
            'company_id' => $user->company->id,
            'risk_score' => '-12',
            'vector_id' => $vectors[3]['id'],
            'start' => $date.'-'.$faker->dateTime()->format('d H:i:s')
        ]);
        // Score should be 45 + -12 = 33 / 2 = 17 (round up)
        $vectors[3][$date]['expectedOutput'] = -12;

        return $date;
    }
}
