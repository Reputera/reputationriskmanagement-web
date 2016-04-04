<?php

namespace Tests\Unit\Console\Commands\RecordedFuture\Instances;

use App\Entities\Company;
use Illuminate\Database\Eloquent\Collection;

trait MockedCompaniesCadence
{
    protected function setupMockedAllCompaniesForCadence($functionName, $days)
    {
        /** @var \Mockery\MockInterface $mockedModel */
        $mockedModel = \Mockery::mock(Company::class);

        $mockedModel->shouldReceive('whereName')
            ->never();

        $mockedModel->shouldReceive($functionName)
            ->with($days)
            ->times(3);

        $mockedModel->shouldReceive('all')
            ->once()
            ->andReturn(new Collection([$mockedModel, $mockedModel, $mockedModel]));

        app()->instance(Company::class, $mockedModel);
    }

    protected function setupMockedCompanyForCadence($functionName, $days, $name)
    {
        /** @var \Mockery\MockInterface $mockedModel */
        $mockedModel = \Mockery::mock(Company::class);

        $mockedModel->shouldReceive('whereName')
            ->with($name)
            ->once()
            ->andReturnSelf();

        $mockedModel->shouldReceive('get')
            ->withNoArgs()
            ->once()
            ->andReturn(new Collection([$mockedModel]));

        $mockedModel->shouldReceive($functionName)
            ->with($days)
            ->times(1);

        $mockedModel->shouldReceive('all')
            ->never();

        app()->instance(Company::class, $mockedModel);
    }
}
