<?php

namespace App\Server\Traits;

use App\Server\Server;
use Aws\Handler\GuzzleV6\GuzzleHandler;
use Aws\Sdk;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Storage;
use WyriHaximus\React\GuzzlePsr7\HttpClientAdapter;

trait AwsAsyncHelpers
{
    /**
     * Make React compatible AWS HTTP Handler.
     *
     * @return \Aws\Handler\GuzzleV6\GuzzleHandler
     */
    protected function awsHttpHandler()
    {
        $loop = Server::instance()->loop();
        $adapter = new HttpClientAdapter($loop);
        $handler = HandlerStack::create($adapter);
        $client = new GuzzleClient(['handler' => $handler]);

        return new GuzzleHandler($client);
    }

    /**
     * Create an AWS SDK compatible with React and Storage::cloud() defaults.
     *
     * @param \Aws\Handler\GuzzleV6\GuzzleHandler $http_handler
     *
     * @return \Aws\Sdk
     */
    protected function awsSdk($http_handler = null)
    {
        return new Sdk([
            'http_handler' => $http_handler ?: $this->awsHttpHandler(),
            'credentials'  => $this->awsS3Credentials(),
        ]);
    }

    /**
     * Create an AWS S3 client compatible with Storage::cloud() defaults.
     *
     * @param string $region
     *
     * @return \Aws\S3\S3Client
     */
    protected function awsS3($region = null)
    {
        return $this->awsSdk()
            ->createS3([
                'version' => 'latest',
                'region' => $region ?: $this->awsS3Region(),
            ]);
    }

    /**
     * Get the AWS S3 credentials compatible with Storage::cloud() defaults.
     *
     * @return array
     */
    protected function awsS3Credentials()
    {
        $key = $this->awsS3Config('key');
        $secret = $this->awsS3Config('secret');

        return compact('key', 'secret');
    }

    /**
     * Get the AWS S3 region compatible with Storage::cloud() defaults.
     *
     * @return string
     */
    protected function awsS3Region()
    {
        return $this->awsS3Config('region');
    }

    /**
     * Get the AWS S3 bucket compatible with Storage::cloud() defaults.
     *
     * @return string
     */
    protected function awsS3Bucket()
    {
        return $this->awsS3Config('bucket');
    }

    /**
     * Get the AWS S3 config key.
     *
     * @return string
     */
    protected function awsS3Config($key)
    {
        $driver = Storage::getDefaultCloudDriver();

        return config('filesystems.disks.'.$driver.'.'.$key);
    }

    /**
     * Run the Guzzle promise queue within the loop.
     */
    protected function awsRun()
    {
        Server::instance()->loop()
            ->nextTick(function () {
                \GuzzleHttp\Promise\queue()->run();
            });
    }
}
