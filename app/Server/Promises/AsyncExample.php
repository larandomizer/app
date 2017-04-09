<?php

namespace App\Server\Promises;

use App\Server\Entities\Promise;
use App\Server\Traits\AwsAsyncHelpers;

class AsyncExample extends Promise
{
    use AwsAsyncHelpers;

    /**
     * Resolve the underlying promise with a value.
     *
     * @param mixed $result
     *
     * @return self
     */
    public function resolve($result = null)
    {
        $path = date('Y-m-d').'-AsyncExample.txt';

        $this->awsS3()
            ->uploadAsync($this->awsS3Bucket(), $path, (string) $result)
            ->then(function ($result) {
                return parent::resolve($result->get('VersionId'));
            })
            ->otherwise(function ($error) {
                return $this->reject($error);
            });

        $this->awsRun();

        return $this;
    }

    /**
     * Called when the promise is resolved.
     *
     * @param mixed $result
     *
     * @return mixed
     */
    public function resolved($result = null)
    {
        $this->dispatcher()
            ->broker()
            ->log($result);

        return $result;
    }
}
